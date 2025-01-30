<?php

namespace App\Http\Controllers\makepdf;

use App\Models\User;
use App\Models\SiteSetting;
use App\Models\Invoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect,Response;
use PDF;

class MakepdfController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */


    // personnel biolink pdf creator
    public function invoicePDF(Request $request){
        $invoice_data = Invoice::where('invoice_no',$request->invoice)->first();

        $site_data = SiteSetting::first();
        $data = [
            'date' => date('m/d/y'),
            'pdf_title' => $site_data->site_name,
            'pdf_logo' => url($site_data->site_logo),
            'users' => $invoice_data,
            'sitedata' => $site_data
        ];


        $pdf = PDF::loadview('make_pdf.invoice', $data);
        // $pdf->setPaper('a4');
        // $mpdf->WriteHTML('This copy is XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
        // $stylesheet = file_get_contents('css/bootstrap.min.css');
        return $pdf->stream($site_data->site_invoice_prefix.'-'.$$request->invoice.'('.date('m/D/y').').pdf');
    }
}
