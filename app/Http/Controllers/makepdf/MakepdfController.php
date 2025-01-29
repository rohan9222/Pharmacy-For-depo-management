<?php

namespace App\Http\Controllers\makepdf;

use App\Models\PersonnelInfo;
use App\Models\JobInfo;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect,Response;
use PDF;

class MakepdfController extends Controller
{


        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */


    // personnel biolink pdf creator
    public function biolinkPDF(Request $request){
        // \dd($request->reg_no);
        $user_data = PersonnelInfo::where('reg_no',$request->reg_no)->first();

        $job_data = JobInfo::where('reg_no',$request->reg_no)->get();
        $data = [
            'title' => 'BIO-DATA & OTHER DOCUMENTS: BAF TEMPORARY PERSON',
            'date' => date('m/d/y'),
            'personnel_image' => url('images/personnel_images/'.$request->reg_no.'.gif'),
            'pdffile' => url('images/stratrgic roadmap.pdf'),
            'baf_logo' => url('images/other_images/baf.png'),
            'users' => $user_data,
            'jobsdata' => $job_data
        ];


        $pdf = PDF::loadview('mypdf', $data);
        // $pdf->setPaper('a4');
        // $mpdf->WriteHTML('This copy is XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
        // $stylesheet = file_get_contents('css/bootstrap.min.css');
        return $pdf->stream('bio-data for '.$users->name.'('.$users->serviceno.')('.date('m/D/y').').pdf');
    }
}
