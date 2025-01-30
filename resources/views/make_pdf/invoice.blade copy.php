@php
    ini_set("pcre.backtrack_limit", "500000000");
    error_reporting(0);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ url('css/pdf.css') }}" rel="stylesheet" />

</head>
    <body>
        <div class="section">
            <div style="border:1px solid black">
                <p style="text-align: center;margin:0;padding:5px">
                    Reg No/{{$users['serviceno'] . ' ' . $users['rank'] . ' ' . $users['name'] . ', ' . $users['trade'] . ', ' . $users['baseorunit']}}
                </p>

                <table style="width: 100%;border:none" border="0">
                    <tr>
                        <th  id="header" style="text-align: left; background: #1cc88aa6;">1. Svc Info:</th>
                    </tr>
                </table>
                <table style="width: 100%;border:none" border="0" class='biodataTable'>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>a. Date of Birth:  </td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['dateofbirth'])?dMy($users['dateofbirth']):'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>b. Present Age:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{date_to_duration($users['dateofbirth'])}}</td>
                        <td rowspan='6' style='border:none;padding: 3px' width='140px'>
                            <img src="{{$personnel_image}}" style="width:90px !important; height: 145px;border-radius: 0px;border:1px solid black;" />
                        </td>
                    </tr>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>c. Date of Enrollment:  </td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['joiningdate'])?dMy($users['joiningdate']):'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>d. Total Svc:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{date_to_duration($users['joiningdate'])}}</td>
                    </tr>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>e. Base/Unit:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['baseorunit'])?$users['baseorunit']:'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>f. Working Place:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['workingplace'])?$users['workingplace']:'-'}}</td>
                    </tr>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>g. NID No:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['nidno'])?$users['nidno']:'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>h. Mobile No:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['mobileno'])?$users['mobileno']:'-'}}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div style="border:1px solid black">
                <table style="width: 100%;border:none" border="0">
                    <tr>
                        <th  id="header" style="text-align: left; background: #1cc88aa6;">2. Parents Information:</th>
                    </tr>
                </table>
                <table style="width: 100%;border:none" border="0" class='biodataTable'>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>a. Father's Name:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['fathername'])?$users['fathername']:'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>b. Father's NID:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['fathernidno'])?$users['fathernidno']:'-'}}</td>
                    </tr>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>c. Mother's Name:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['mothername'])?$users['mothername']:'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>d. Mother's NID:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['mothernidno'])?$users['mothernidno']:'-'}}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div style="border:1px solid black">
                <table style="width: 100%;border:none" border="0">
                    <tr>
                        <th  id="header" style="text-align: left; background: #1cc88aa6;">3. Address (Permanent & Present):</th>
                    </tr>
                </table>
                <table style="width: 100%;border:none" border="0" class='biodataTable'>
                    <tr>
                        <td style='text-align:left;border: none;padding: 6px'> i) Permanent Address</td>
                    </tr>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>a. Village/House No:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['per_add'])?$users['per_add']:'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>b. Post office(Code):</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['per_po'])?$users['per_po']:'-'}} ({{isset($users['per_pc'])?$users['per_pc']:'-'}})</td>
                    </tr>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>c. Union:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['per_union'])?$users['per_union']:'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>d. PS:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['per_ps'])?$users['per_ps']:'-'}}</td>
                    </tr>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>e. District:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['per_dis'])?$users['per_dis']:'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>f. Division:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['per_div'])?$users['per_div']:'-'}}</td>
                    </tr>

                    <tr>
                        <td style='text-align:left;border: none;padding: 6px'> ii) Present Address</td>
                    </tr>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>a. Village/House No:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['pre_add'])?$users['pre_add']:'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>b. Post office(Code):</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['pre_po'])?$users['pre_po']:'-'}} ({{isset($users['pre_pc'])?$users['pre_pc']:'-'}})</td>
                    </tr>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>c. Union:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['pre_union'])?$users['pre_union']:'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>d. PS:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['pre_ps'])?$users['pre_ps']:'-'}}</td>
                    </tr>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>e. District:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['pre_dis'])?$users['pre_dis']:'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>f. Division:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['pre_div'])?$users['pre_div']:'-'}}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div style="border:1px solid black">
                <table style="width: 100%;border:none" border="0">
                    <tr>
                        <th  id="header" style="text-align: left; background: #1cc88aa6;">4. Expertise (If any):</th>
                    </tr>
                </table>
                <table style="width: 100%;border:none" border="0" class='biodataTable'>
                    <tr>
                        <td style='text-align:left;border: none;padding: 3px' width='160px'>a. Expertise:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['expertise'])?$users['expertise']:'-'}}</td>
                        <td style='text-align:left;border: none;padding: 3px' width='170px'>b. Remarks:</td>
                        <td style='text-align:left;border: none;padding: 3px' width='180px'>{{isset($users['remarks'])?$users['remarks']:'-'}}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div style="border:1px solid black;">
                <table style="width: 100%;border:none" border="0">
                    <tr>
                        <th  id="header" style="text-align: left; background: #1cc88aa6;">5. Service all Information (If any):</th>
                    </tr>
                </table>

                <div style="padding: 5px 3px">
                    <table style="width: 100%;border:none" border="0">
                        <tr>
                            <th>S/N</th>
                            <th>Name Of Place</th>
                            <th>Type of work</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Remarks</th>
                        </tr>
                        @forelse ($jobsdata as $jobdata)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $jobdata['job_place_name'] }}</td>
                                <td>{{ $jobdata['job_type'] }}</td>
                                <td>{{ ymdhistoJMY($jobdata['from_date']) }}</td>
                                <td>{{ ($jobdata['to_date'] == 'Continue') ? $jobdata['to_date'] : ymdhistoJMY($jobdata['to_date']) }}</td>
                                <td>{{ $jobdata['remarks'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No job data available.</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>

        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>


        <div class="section">
            <div style="border:1px solid black;">
                <table style="width: 100%;border:none" border="0">
                    <tr>
                        <th id="header" style="text-align: left; background: #1cc88aa6;">6. All Documents:</th>
                    </tr>
                </table>

                <div style="padding: 10px">
                    <table style="width: 100%;border:none" border="0">
                        <tr>
                            <th id="header" style="text-align: left; background: #cc8888a6;"> (i) NID Card:</th>
                        </tr>
                    </table>
                    <table style="width: 100%;border:none" border="0">
                        <tr>
                            {{-- <td colspan="6">{{isset($users['remarks'])?$users['remarks']:'-'}}</td> --}}
                            <td colspan="6">
                                <img src="{{file_exists('images\document_images\NID Card-'.$users['serviceno'].'.gif')?'images\document_images\NID Card-'.$users['serviceno'].'.gif':'NID Card Not Found'}}" style="padding: 3px; width: 100%; height: 100px" alt="">
                            </td>
                        </tr>
                    </table>
                </div>

                <br>
                <br>
                <br>
                <br>

                <div style="padding: 5px 3px">
                    <table style="width: 100%;border:none" border="0">
                        <tr>
                            <th id="header" style="text-align: left; background: #cc8888a6;"> (ii) Birth Certificate:</th>
                        </tr>
                    </table>
                    <table style="width: 100%;border:none" border="0">
                        <tr>
                            {{-- <td colspan="6">{{isset($users['remarks'])?$users['remarks']:'-'}}</td> --}}
                            <td colspan="6">
                                <img src="{{file_exists('images\document_images\Birth Certificate-'.$users['serviceno'].'.gif')?'images\document_images\Birth Certificate-'.$users['serviceno'].'.gif':'Birth Certificate Not Found'}}" alt="">
                            </td>
                        </tr>
                    </table>
                </div>

                <br>
                <br>
                <br>
                <br>

                <div style="padding: 5px 3px">
                    <table style="width: 100%;border:none" border="0">
                        <tr>
                            <th id="header" style="text-align: left; background: #cc8888a6;"> (iii) Salary Certificate:</th>
                        </tr>
                    </table>
                    <table style="width: 100%;border:none" border="0">
                        <tr>
                            {{-- <td colspan="6">{{isset($users['remarks'])?$users['remarks']:'-'}}</td> --}}
                            <td colspan="6">
                                <img src="{{file_exists('images\document_images\Salary Certificate-'.$users['serviceno'].'.gif')?'images\document_images\Salary Certificate-'.$users['serviceno'].'.gif':'Salary Certificate Not Found'}}" alt="">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </body>
</html>




@php

    $content    =   ob_get_clean();

    $conf       =   [
        'mode'          =>  'utf-8',
        'format'        =>  [224, 286],
        'tempDir'       =>  storage_path('temp'),
        'orientation'   => 'portrait',
        'margin_left' => 6,
        'margin_right' => 6,
        // 'orientation'   => 'L'
    ];

    $mpdf = new \Mpdf\Mpdf($conf);
    $dateTime = date("F d, Y  h:i A", time());
    $html = "<div style='width:100%;'>
                <div style='width:20%; float: left;'>
                    <img src='images/other_images/baf_logo.jpg' width='65' height='60' />
                </div>
                <div style='float: left;font-size:18px;width:50%;text-align:center'><strong>BIO-DATA & DOCU : BAF TY CIV</strong></div>
                <div style='width:20%; float: right; text-align:right'>
                    <img src='images/other_images/sign_logo.jpg' width='65' height='60' />
                </div>
                <p  style='padding-top:-25px;margin-left:5rem;float: left;font-size:12px'><i><span>Report run on: $dateTime<span></i></p>
            </div>";

        $mpdf->SetHTMLHeader($html);
        $mpdf->SetTopMargin(30);

    $mpdf->SetHTMLFooter('
        <div style="text-align:right; margin-top: 1rem;padding-right:50px">
        Page {PAGENO} of {nbpg}
        </div>
    ');

    $stylesheet = file_get_contents('css/pdf.css');

    $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($content, \Mpdf\HTMLParserMode::HTML_BODY);
    $mpdf->SetDisplayMode('fullpage');
    //$mpdf->Output("{$reportName}.pdf");
    $mpdf->Output();
@endphp
