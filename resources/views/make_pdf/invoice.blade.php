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
