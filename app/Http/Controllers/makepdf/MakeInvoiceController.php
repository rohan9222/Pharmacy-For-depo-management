<?php

namespace App\Http\Controllers\makepdf;

use App\Models\User;
use App\Models\SiteSetting;
use App\Models\Invoice;
use Redirect,Response;
use PDF;
use Illuminate\Support\Number;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MakeInvoiceController extends Controller
{
      /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function invoicePDF(Request $request){
        $invoice_data = Invoice::where('invoice_no', $request->invoice)
            ->with('salesMedicines', 'customer', 'fieldOfficer', 'salesManager', 'manager')
            ->first();

        $site_data = SiteSetting::first();
        $data = [
            'date' => date('m/d/Y'), // Fixed date format
            'pdf_title' => $site_data->site_name,
            'pdf_logo' => url($site_data->site_logo),
            'invoice_data' => $invoice_data,
            'grand_total_words' =>  Number::spell($invoice_data->grand_total, locale: 'en'), // Corrected
            'site_data' => $site_data
        ];

        $pdf = PDF::loadView('make_pdf.invoice', $data);
        $pdf->setPaper('a4');
        $pdf->AutoPrint(true);
        return $pdf->stream($site_data->site_invoice_prefix . '-' . $request->invoice . '(' . date('m-d-Y') . ').pdf'); // Fixed string interpolation
    }

    public function invoicePrint(Request $request){
        $invoice_data = Invoice::where('invoice_no', $request->invoice)
            ->with('salesMedicines', 'customer', 'fieldOfficer', 'salesManager', 'manager')
            ->first();

        $site_data = SiteSetting::first();
        $data = [
            'date' => date('m/d/Y'), // Fixed date format
            'pdf_title' => $site_data->site_name,
            'pdf_logo' => url($site_data->site_logo),
            'invoice_data' => $invoice_data,
            'grand_total_words' =>  Number::spell($invoice_data->grand_total, locale: 'en'), // Corrected
            'site_data' => $site_data,
            'dateTime' => date("d/m/Y,  h:i A", time())
        ];
        return view('make_pdf.invoice-print', $data);
    }

    public function invoiceReturnPrint(Request $request){
        $invoice_data = Invoice::where('invoice_no', $request->invoice)
            ->with('salesMedicines', 'customer', 'fieldOfficer', 'salesManager', 'manager','salesReturnMedicines')
            ->first();


        $site_data = SiteSetting::first();
        $data = [
            'date' => date('m/d/Y'), // Fixed date format
            'pdf_title' => $site_data->site_name,
            'pdf_logo' => url($site_data->site_logo),
            'invoice_data' => $invoice_data,
            'grand_total_words' =>  Number::spell($invoice_data->grand_total, locale: 'en'), // Corrected
            'site_data' => $site_data,
            'dateTime' => date("d/m/Y,  h:i A", time())
        ];
        return view('make_pdf.invoice-return-print', $data);
    }
}
