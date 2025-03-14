<?php

namespace App\Http\Controllers\makepdf;

use App\Models\User;
use App\Models\SiteSetting;
use App\Models\Invoice;
use App\Models\StockInvoice;
use App\Models\Medicine;
use App\Models\TargetReport;
use Redirect,Response;
use PDF;
use DNS1D; // For 1D barcodes
use DNS2D; // For 2D barcodes (QR codes)
use Illuminate\Support\Number;
use Carbon\Carbon;  

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MakeReportController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct(){
        if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view-report')) {
            return true;
        }else{
            abort(403, 'Unauthorized action.'); 
        }
    }

    public function index()
    {   
        $user_lists = User::whereIn('role', ['Manager', 'Zonal Sales Executive', 'Territory Sales Executive'])->get();
        $managers = User::where('role', 'Manager')->get();
        return view('make_pdf.report.index', compact('managers', 'user_lists'));
    }

    public function printQrCode(){
        $medicines = Medicine::all();

        foreach ($medicines as $medicine) {
            $medicine->barcode_image = 'data:image/png;base64,' . DNS1D::getBarcodePNG($medicine->barcode, 'PHARMA', 2, 50);
        }
        
        return view('make_pdf.print.qr-code', compact('medicines'));
    }

    public function dailySalesCollection(Request $request){
        if(!$request->manager_id){        
            $user_data_all = User::where('role', 'Manager')->get();
        }
        if($request->manager_id){
            $user_data_all = User::where('role', 'Zonal Sales Executive')->where('manager_id', $request->manager_id)->get();
            $manager_data = User::where('id', $request->manager_id)->first();
        }

        $site_data = SiteSetting::first();
        $data = [
            'date' => date('m/d/Y'), // Fixed date format
            'pdf_title' => 'Impex Pharma ',
            'pdf_logo' => url($site_data->site_logo),
            'user_data_all' => $user_data_all,
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date),
            'manager_data' => $manager_data ?? ''
        ];

        $pdf = PDF::loadView('make_pdf.report.daily_collection', $data);
        $pdf->setPaper('a4');
        $pdf->AutoPrint(true);
        return $pdf->stream($site_data->site_invoice_prefix . '-' . $request->invoice . '(' . date('m-d-Y') . ').pdf'); // Fixed string interpolation
    }

    // public function productSalesReport(Request $request){
    //     if(!$request->manager_id){        
    //         $user_data_all = User::where('role', 'Manager')->get();
    //     }
    //     if($request->manager_id){
    //         $user_data_all = User::where('role', 'Zonal Sales Executive')->where('manager_id', $request->manager_id)->get();
    //         $manager_data = User::where('id', $request->manager_id)->first();
    //     }

    //     $site_data = SiteSetting::first();
    //     $data = [
    //         'date' => date('m/d/Y'), // Fixed date format
    //         'pdf_title' => 'Impex Pharma ',
    //         'pdf_logo' => url($site_data->site_logo),
    //         'user_data_all' => $user_data_all,
    //         'start_date' => Carbon::parse($request->start_date),
    //         'end_date' => Carbon::parse($request->end_date),
    //         'manager_data' => $manager_data ?? ''
    //     ];

    //     $pdf = PDF::loadView('make_pdf.report.product_sales_report', $data);
    //     $pdf->setPaper('a4');
    //     $pdf->AutoPrint(true);
    //     return $pdf->stream($site_data->site_invoice_prefix . '-' . $request->invoice . '(' . date('m-d-Y') . ').pdf'); // Fixed string interpolation
    // }
    
    public function productSalesReport(Request $request){
        // dd($request->all());
        if(!$request->user_id){        
            return redirect()->back()->with('error', 'Please select a user');
        }
        // dd($request->user_id);
        if($request->user_id){
            $user_data_all = User::find($request->user_id);
            $sales_target_total = TargetReport::where('user_id', $user_data_all->id)
                        ->where('target_month', Carbon::parse($request->start_date)->format('F'))
                        ->where('target_year', Carbon::parse($request->start_date)->format('Y'))
                        ->first();
        }
        $site_data = SiteSetting::first();
        $data = [
            'date' => date('m/d/Y'), // Fixed date format
            'pdf_title' => 'Impex Pharma ',
            'pdf_logo' => url($site_data->site_logo),
            'user_data_all' => $user_data_all,
            'sales_target_total' => $sales_target_total,
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date),
        ];

        $pdf = PDF::loadView('make_pdf.report.product_sales_report', $data);
        $pdf->setPaper('a4');
        $pdf->AutoPrint(true);
        return $pdf->stream($site_data->site_invoice_prefix . '-' . $request->invoice . '(' . date('m-d-Y') . ').pdf'); // Fixed string interpolation
    }

    public function stockStatement(Request $request){
        $start_date = Carbon::parse($request->reportdate)->startOfMonth()->format('Y-m-d');
        $end_date = Carbon::parse($request->reportdate)->format('Y-m-d');
        $report_date = Carbon::parse($request->reportdate);
        $medicines = Medicine::all();
        $invoices = Invoice::with('salesMedicines')->whereBetween('invoice_date', [$start_date, $end_date])->get();
        $stock_invoices = StockInvoice::with('stockLists')->whereBetween('invoice_date', [$start_date, $end_date])->get();
        $site_data = SiteSetting::first();
        $data = [
            'date' => date('m/d/Y'), // Fixed date format
            'pdf_title' => 'Impex Pharma ',
            'pdf_logo' => url($site_data->site_logo),
            'medicines' => $medicines,
            'invoices' => $invoices,
            'stock_invoices' => $stock_invoices,
            'report_date' => $report_date,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'manager_data' => $manager_data ?? ''
        ];

return view('make_pdf.report.stock-statement', $data);
        // $pdf = PDF::loadView('make_pdf.report.stock-statement', $data);
        // $pdf->setPaper('a4');
        // $pdf->AutoPrint(true);
        // return $pdf->stream($site_data->site_invoice_prefix . '-' . $request->invoice . '(' . date('m-d-Y') . ').pdf'); // Fixed string interpolation
    }

}
