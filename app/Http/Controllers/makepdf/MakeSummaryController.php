<?php

namespace App\Http\Controllers\makepdf;

use App\Models\User;
use App\Models\SiteSetting;
use App\Models\Invoice;
use Redirect,Response;
use PDF;
// use Illuminate\Support\Number;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MakeSummaryController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function summaryPDF(Request $request){
        $invoice_data = Invoice::where('summary_id', $request->id)
            ->with('salesMedicines', 'customer', 'fieldOfficer', 'salesManager', 'manager','deliveredBy')
            ->get();

        $site_data = SiteSetting::first();
        $data = [
            'date' => date('m/d/Y'), // Fixed date format
            'pdf_title' => $site_data->site_name,
            'pdf_logo' => url($site_data->site_logo),
            'invoice_data' => $invoice_data,
            'site_data' => $site_data
        ];

        $pdf = PDF::loadView('make_pdf.summary', $data);
        $pdf->setPaper('a4');
        $pdf->AutoPrint(true);
        return $pdf->stream($site_data->site_invoice_prefix . '-' . $request->invoice . '(' . date('m-d-Y') . ').pdf'); // Fixed string interpolation
    }
}
