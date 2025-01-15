<?php

/**
 * Created by Md. Jahangir Alam Rohan.
 * User: Md. Jahangir Alam Rohan.
 * Date: 25-Jun-2024
 * Time: 03.01 PM
 */



if (! function_exists('date_to_duration')) {
    /**
     * Calculate the full duration between a given date and the current date.
     *
     * @param string $date The date in 'Y-m-d' format.
     * @return string The calculated duration in the format "X years, Y months, Z days".
     */
    function date_to_duration($date) {
        $startDate = new DateTime($date);
        $endDate = new DateTime();
        $endDate->modify('+1 day'); // Add one day to the end date to include it in the duration

        $interval = $startDate->diff($endDate);

        return "{$interval->y}Y {$interval->m}M {$interval->d}D";
    }
}
// j-M-Y to Y-m-d H:i:s
function formatDMYTOYMD($date){
    $dt =date_create_from_format("j-M-Y",$date, new DateTimeZone('Asia/Dhaka'));
    if($date){
        return date_format($dt,"Y-m-d H:i:s");
    }
    return ;
}
// mm/dd/yy to Y-m-d H:i:s
function formatddmmYTOYMD($date){
    $dt =date_create_from_format("d/m/Y",$date, new DateTimeZone('Asia/Dhaka'));
    if($date){
        return date_format($dt,"Y-m-d H:i:s");
    }
    return ;
}
// Y-m-d H:i:s to  j-M-Y
function ymdhistoJMY($date){
    $dt =date_create_from_format("Y-m-d H:i:s",$date, new DateTimeZone('Asia/Dhaka'));
    if($date){
        return date_format($dt,"j-M-Y");
    }
    return ;
}

/**
 * This is end session code.
 * This code created by Md. Jahangir Alam Rohan.
 */



function bafDateFormat($date){
    return date('d-M-Y', strtotime($date));
}


function bafDateFormat3($date){
    $date_arr = explode('-',$date);
    $day = ltrim($date_arr[0],'0');

    $word_month =   ['Jan','Feb','Mar','Apr','May','Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'];
    $number_month = ['01','02','03','04','05','06','07','08','09','10','11','12'];
    $month = str_replace($number_month,$word_month,$date_arr[1]);

    $year = preg_split("/[\s]/",$date_arr[2],0, PREG_SPLIT_NO_EMPTY);

     $valid_date = $day.'-'.$month.'-'.$year[0];
     return $valid_date;

}

function entryType(){
     $entry_types = [
        '1st TIME'=>'1st TIME',
        '2nd TIME'=>'2nd TIME',
        'BLACK'=>'BLACK',
        'Letter of Displeasure'=>'Letter of Displeasure',
        'RED'=>'RED'
    ];

    return $entry_types;
}
function reasonOfMoralTurpitudes(){
    return [
        'Rape'=>'Rape',
        'Illegal Money Transaction'=>'Illegal Money Transaction',
        'Illegal relation with a lady'=>'Illegal relation with a lady',
        'Drug addicted'=>'Drug addicted',
        'Assault'=>'Assault',
        'Misappropriate of govt property'=>'Misappropriate of govt property',
        'Mis Conduct'=>'Mis Conduct',
        'Adopting Unafair Means'=>'Adopting Unafair Means',
    ];

}
function moralTurpitudes(){
    return [
        'yes'=>'yes',
        'no'=>'no'

    ];

}

function reported(){
    return [
        'YES'=>'YES',
        'NO'=>'NO'

    ];

}




function bafDateFormat2($date){
    return date('d-m-Y', strtotime($date));
}


function bafDateFormat4($date){
    return date('d-m-Y', strtotime($date));
}

function dMy($date){
    return date('d M Y', strtotime($date));
}

function shortDateFormat($date){
    return date('d.m.y', strtotime($date));
}

function mdY($date){
    $data = explode("-",$date);
    return $data[1]."/". $data[2]."/".$data[0];
}

function dateArr($date)
{
   $date = explode("-",$date);
   return [
    'day'=>$date[2],
    'month'=>$date[1],
    'year'=>$date[0],
   ];
}
function convertDMYTOYMD($date){
    return date("Y-m-d H:i:s", strtotime($date));
}

function bafDateFormatWithTime($date){
    return date('M d, Y H:m a', strtotime($date));
}

function revertString($string){
    return  str_replace('-', '/', $string);
}

function revertSpace($string){
    return  str_replace('-', ' ', $string);
}

function convertStringToDate($date){
    return date("Y-m-d",strtotime($date));
}

function rankShortForm($rank){
    if(isset( explode("(", $rank)[1])){
        return explode(')', explode("(", $rank)[1])[0];
    }else{
        return $rank;
    }
}

function baseOrUnitShortForm($baseorunit){
    if(isset( explode("(", $baseorunit)[1])){
        return explode(')', explode("(", $baseorunit)[1])[0];
    }else{
        return $baseorunit;
    }
}

function format_amount($amount){
    return number_format((float)$amount, 2, '.', ',');
}

function convertNumberToWord($num = false){
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    );
    $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
    $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundred = (int) ($num_levels[$i] / 100);
        $hundred = ($hundred ? ' ' . $list1[$hundred] . ' hundred' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundred . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    return title_case(implode(' ', $words));
}

// function calculateDuration($from, $to = null){
//     $to = $to == null?date('Y-m-d'):$to;
//     $diff = abs(strtotime($from) - strtotime($to));
//     $years = floor($diff / (365*60*60*24));
//     $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
//     $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
//     return ['year'=>(int) $years, 'month'=>(int) $months, 'day'=>(int) $days];
// }


function calculateDuration($from, $to = null){
    $to = $to == null?date('Y-m-d'):$to;
    $diff = abs(strtotime($from) - strtotime($to));
    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30.5*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30.5*60*60*24)/ (60*60*24));
    if($months == 12.0){$months=0;$years=$years+1;}
    return ['year'=>(int) $years, 'month'=>(int) $months, 'day'=>(int) $days];
}


function formatAmount($amount){
    return number_format($amount, 2, ".", ",");
}

function currentFinancialYear(){
    if (date('m') <= 6) {
        $financial_year = (date('Y')-1) . '-' . date('Y');
    } else {
        $financial_year = date('Y') . '-' . (date('Y') + 1);
    }
    return $financial_year;
}

function daysBetweenWithYearsMonthsDays($end, $start) {
    $date1= new DateTime($start);
    $date2= new DateTime($end);
    $interval = $date1->diff($date2);
    return "$interval->y"."Y"." $interval->m"."M"." $interval->d"."D";
}


function remainingSVC($len, $svc) {
    $year = ((int)substr("$svc",0,1))+$len;
    return ($year."".substr($svc,1));
}

function daysBetween($end, $start) {
    return date_diff(
        date_create($end),
        date_create($start)
    )->format('%a');
}

function dateOfExpireSVCLength($rank)
{
    $q = \App\Rank::selectRaw('SVC_LENGTH')
        ->where('rankname',$rank)
        ->first();
    return $q->svc_length;
}



function getAccumulatedTotalDays($serialNo, $date)
{
    $q = \App\LeaveRegister::selectRaw('sum(noofdays) as noofdays, count(*) as total_rows')
        ->where('fserialno',$serialNo)
        ->where('startdate','like',$date.'%')
        ->orderBy('startdate','ASC')
        ->first();
        return $q;
}

function getAccumulatedTotalDays2($serialNo, $date)
{
    $q = \App\LeaveRegister::selectRaw('sum(noofdays) as noofdays, count(*) as total_rows')
        ->where('fserialno',$serialNo)
        ->where('startdate','like',$date.'%')
        ->orderBy('startdate','DESC')
        ->first();
        return $q;
}



function loadMovements($serialNo,$dateofeffect)
{
    $q = \App\MovementRecords::selectRaw('MAX(dateofeffect) as dateofeffect')
        ->where("dateofeffect","<",$dateofeffect)
        ->where('fserialno',$serialNo)
        ->first();
    return $q;
}

function loadMovementsTotalDates($serialNo,$area)
{
    $q = \App\MovementRecords::selectRaw('MIN(dateofeffect) as dateofeffect')
        ->where("AREA",$area)
        ->where('fserialno',$serialNo)
        ->where('MOVEMENTTYPE',"Posting")
        ->first();
    return $q;
}


function getLastPromotion($serialNo)
{
    $q = \App\PromotionRecords::selectRaw('MAX(dateofeffect) as dateofeffect')
        ->whereNotNull("dateofeffect")
        ->where('fserialno',$serialNo)
        ->where('OCCURRENCETYPE',"Promotion")
        ->orderByDesc('dateofeffect')
        ->first();
    return $q;
}

function lastLocation($fserialno)
{
    return \App\ResidentialStatus::orderBy('accommodationdate','DESC')->where ('fserialno',$fserialno)->pluck('remarks')->first();
}

function ranksByPersonnelType(string $personnelType) {
    $ranks = [
        'Airman' => [
            "Master Warrant Officer (MWO)" => "MWO",
            "Senior Warrant Officer (SWO)" => "SWO",
            "Warrant Officer (WO)" => "WO",
            "Sergeant (SGT)" => "SGT",
            "Corporal (CPL)" => "CPL",
            "Leading Aircraft Man (LAC)" => "LAC",
            "Aircraftman-1 (AC-1)" => "AC-1",
            "Aircraft Man - Trainee (AC2)" => "AC2"
        ],
        'MODC' => [
            "Master Warrant Officer (MWO - MODC)" => "MWO-MODC",
            "Senior Warrant Officer (SWO - MODC)" => "SWO-MODC",
            "Warrant Officer (WO - MODC)" => "WO-MODC",
            "Sergeant (SGT - MODC)" => "SGT-MODC",
            "Corporal (CPL - MODC)" => "CPL-MODC",
            "Lance Corporal (L/CPL -MODC)" => "L/CPL-MODC",
            "Sainik (SNK - MODC)" => "SNK-MODC",
        ]
    ];

    $personnelType = getActualPersonnelType($personnelType);

    if(in_array($personnelType, ['Airman (On-LPR)', 'Ex-Airman', 'Airman'])) {
        $personnelType = 'Airman';
    }

    if(in_array($personnelType, ['MODC (On-LPR)', 'Ex-MODC', 'MODC'])) {
        $personnelType = 'MODC';
    }

    return $ranks[$personnelType]??[];
}

function getActualPersonnelType(string $personnelType) {
    $personnelTypesMap = [
        'Airman' => 'Airman',
        'Ex-Airman' => 'Ex-Airman',
        'Airman(F)' => 'Airman',
        'Ex-Airman(F)' => 'Airman',
        'Airman(M)' => 'Airman',
        'Ex-Airman(M)' => 'Airman',
        'Airman (On-LPR)' => 'Airman (On-LPR)',
        'Airman-(On-LPR)' => 'Airman (On-LPR)',
        'MODC' => 'MODC',
        'Ex-MODC' => 'Ex-MODC',
        'MODC (On-LPR)' => 'MODC (On-LPR)',
        'MODC-(On-LPR)' => 'MODC (On-LPR)'
    ];

    return $personnelTypesMap[$personnelType]??null;
}

function shortRanks()
{
    return [
    "MWO"=>"MWO",
    "SWO"=>"SWO",
    "WO"=>"WO",
    "SGT"=>"SGT",
    "CPL"=>"CPL",
    "LAC"=>"LAC",
    "AC-1"=>"AC-1",
    "AC2"=>"AC2",
   ];
}

function branchOrTrades()
{
    return [
        "Ac Fitt" => "Ac Fitt",
        "Afr Fitt" => "Afr Fitt",
        "Eng Fitt" => "Eng Fitt",
        "E&I Fitt"=>"E&I Fitt",
        "Radio Fitt"=>"Radio Fitt",
        "PF&DI"=>"PF&DI",
        "Sec Asst (A)"=>"Sec Asst (A)",
        "Sec Asst (GD)"=>"Sec Asst (GD)",
        "Flt Engr"=>"Flt Engr",
        "LM"=>"LM",
        "GS"=>"GS",
        "GC"=>"GC",
        "Armt Fitt"=>"Armt Fitt",
        "ATCA"=>"ATCA",
        "Log Asst"=>"Log Asst",
        "MTOF"=>"MTOF",
        "Med Asst"=>"Med Asst",
        "Gen Engg"=>"Gen Engg",
        "IT Asst"=>"IT Asst",
        "Met Asst"=>"Met Asst",
        "Radar Optr"=>"Radar Optr",
        "Air Gunner"=>"Air Gunner",
        "Cy Asst"=>"Cy Asst",
        "Admin Asst"=>"Admin Asst",
        "Edn Instr"=>"Edn Instr",
        "Music"=>"Music",
        "Provost"=>"Provost",
        "Air Std"=>"Air Std",
    ];
}

function fullRanks()
{
    return [
        "Master Warrant Officer (MWO)" => "MWO",
        "Senior Warrant Officer (SWO)" => "SWO",
        "Warrant Officer (WO)" => "WO",
        "Sergeant (SGT)" => "SGT",
        "Corporal (CPL)" => "CPL",
        "Leading Aircraft Man (LAC)" => "LAC",
        "Aircraftman-1 (AC-1)" => "AC-1",
        "Aircraft Man - Trainee (AC2)" => "AC2"
   ];
}

//
//function shortModcRanks()
//{
//    return [
//    "MWO"=>"MWO",
//    "SWO"=>"SWO",
//    "WO"=>"WO",
//    "SGT"=>"SGT",
//    "CPL"=>"CPL",
//    "L/CPL"=>"L/CPL",
//    "SNK"=>"SNK",
//   ];
//}

function shortRankWithModc()
{
    return [
    "MWO-MODC"=>"MWO-MODC",
    "SWO-MODC"=>"SWO-MODC",
    "WO-MODC"=>"WO-MODC",
    "SGT-MODC"=>"SGT-MODC",
    "CPL-MODC"=>"CPL-MODC",
    "L/CPL-MODC"=>"L/CPL-MODC",
    "SNK-MODC"=>"SNK-MODC",
   ];
}

function fullModcRanks()
{
    return [
        "Master Warrant Officer (MWO - MODC)" => "MWO-MODC",
        "Senior Warrant Officer (SWO - MODC)" => "SWO-MODC",
        "Warrant Officer (WO - MODC)" => "WO-MODC",
        "Sergeant (SGT - MODC)" => "SGT-MODC",
        "Corporal (CPL - MODC)" => "CPL-MODC",
        "Lance Corporal (L/CPL -MODC)" => "L/CPL-MODC",
        "Sainik (SNK - MODC)" => "SNK-MODC",
   ];
}

function findPtOrRankOrTradeOrEntry($value){
    $personnelTypes = getActualPersonnelType($value);
    if($personnelTypes)
        return 'Personnel Type';

    $ranks = fullRanks() + fullModcRanks();
    if(in_array($value, $ranks))
        return 'Rank';

    $trades = branchOrTrades();
    if(isset($trades[$value]))
        return 'Trade';

    if(in_array($value, entrynumbers()))
        return  'Entry';

    return 'Not Found';
}

function percentage($value, $percentage)
{ if($percentage == 0){
        return number_format(($value), 2);
    }
    return number_format(100-(($value/$percentage)*100),2);
}



function trades(){
    return \App\Trade::OrderBy('tradeserial','asc')->pluck('tradename')->toArray();

    // return \App\PersonnelInfo::OrderBy('branchortrade','asc')->whereIn('personneltype',['Airman','MODC'])->whereNotNull('basicbranchortrade')->distinct('branchortrade')->pluck('branchortrade')->toArray();

}

//function tradesByPersonnelType(string $personnelType): array {
//    $personnelType = getActualPersonnelType($personnelType);
//    return \App\PersonnelInfo::where('personneltype', $personnelType)
//        ->whereNotNull('basicbranchortrade')
//        ->orderBy('branchortrade','asc')
//        ->distinct('branchortrade')
//        ->pluck('branchortrade')
//        ->toArray();
//}



function tradesByPersonnelType(string $personnelType): array {
    $personnelType = getActualPersonnelType($personnelType);
    return \App\PersonnelInfo::where('personneltype', $personnelType)
    ->orderby('branchortrade','asc')
        ->distinct('branchortrade')
        ->pluck('branchortrade')
        ->toArray();
}


// function getBasicTradeByTrade($trade)
//     {
//         $basicTrades = Personnelinfo::distinct('basicbranchortrade')->where('branchortrade',$trade)->pluck('basicbranchortrade')->toArray();
//         return response()->json($basicTrades);
//     }


function basictrades()
{
    return \App\PersonnelInfo::OrderBy('basicbranchortrade','asc')
        ->whereIn('personneltype',['Airman','MODC'])
        ->whereNotNull('basicbranchortrade')
        ->distinct('basicbranchortrade')
        ->pluck('basicbranchortrade')
        ->toArray();
}

//function basicTradesByPersonnelType(string $personnelType): array {
//    $personnelType = getActualPersonnelType($personnelType);
//    return \App\PersonnelInfo::where('personneltype', $personnelType)
//        ->whereNotNull('basicbranchortrade')
//        ->orderBy('basicbranchortrade','asc')
//        ->distinct('basicbranchortrade')
//        ->pluck('basicbranchortrade')
//        ->toArray();
//}





function basicTradesByPersonnelType(string $personnelType): array {
    $personnelType = getActualPersonnelType($personnelType);
    return \App\BasicTrade::where('personneltype', $personnelType)
        ->orderBy('tradeserial','asc')
        ->pluck('basictradename')
        ->toArray();
}


function baseorunits(){
    return [
        'Air HQ',
        'BSR',
        'BBD',
        'MTR',
        'ZHR',
        'PKP',
        'BAF CXB'
    ];
}

function entrynumbers(){
    return range(18, 53);
   $entry_numbers = [];
   for($start = 18; $start <= 53; $start ++){
    $entry_numbers[$start] = $start;
   }
   return $entry_numbers;
}

function missionyears(){
    $presentYear = date('Y');
    $missionears = [];
    for($start = 1980; $start <= $presentYear+3; $start ++){
     $missionears[$start] = $start;
    }
    return $missionears;
 }

function bloodgroups(){
   return [
    "A_PLUS"=>"A+",
    "O_PLUS"=>"O+",
    "B_PLUS"=>"B+",
    "AB_PLUS"=>"AB+",
    "A_MINUS"  => "A-",
    "B_MINUS"  => "B-",
    "AB_MINUS"  => "AB-",
    "O_MINUS"  => "O-",
   ];
}

function maritalstatuses(){
   return [
    'Married',
    'Unmarried'
   ];
}

function findPtByValue($ptOrRankOrTrade){

    $ptOrRankOrTrade = revertAnd(revertSlash($ptOrRankOrTrade));

    if(findPtOrRankOrTradeOrEntry($ptOrRankOrTrade) == "Personnel Type"){
        $personneltype = $ptOrRankOrTrade;

        if($personneltype == 'Airman(F)') {
            $personneltype = 'Airman';
        }

        if($personneltype == 'Airman(M)') {
            $personneltype = 'Airman';
        }

        return getActualPersonnelType($personneltype);
    }

    if(findPtOrRankOrTradeOrEntry($ptOrRankOrTrade) == "Rank"){
        return substr($ptOrRankOrTrade, -4) == 'MODC' ? 'MODC' : 'Airman';
    }

    if(findPtOrRankOrTradeOrEntry($ptOrRankOrTrade) == "Trade"){
        $personneltype = 'Airman';
    }

    if(findPtOrRankOrTradeOrEntry($ptOrRankOrTrade) == "Entry"){
        $personneltype = 'Airman';
    }

    return $personneltype;
}

function revertSlash($value) {
    return str_replace('_slash_', '/', $value);
}

function revertAnd($value) {
    return str_replace('_and_', '&', $value);
}

function promotionRanks()
{
    return [
        'Sergeant (SGT)',
        'Warrant Officer (WO)',
        'Senior Warrant Officer (SWO)',
        'Master Warrant Officer (MWO)'
    ];
}

function zeroToNine()
{
    return [
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
    ];
}

function isValidateServiceNumber($servicenumber)
{
    $bd = str_split($servicenumber);
    $isValidate = true;

    foreach($bd as $ltr){
      if(!in_array($ltr, zeroToNine())){
        $isValidate = false;
      }
    }

    if (5 <= strlen($servicenumber) && 6 >= strlen($servicenumber));
    return $isValidate;

}

function isValidateMobileNumber($servicenumber)
{

    $bd = str_split($servicenumber);
    $isValidate = true;

    foreach($bd as $ltr){
      if(!in_array($ltr, zeroToNine())){
        $isValidate = false;
      }
    }

    if (strlen($servicenumber) > 11 || strlen($servicenumber) < 11) return;
    return $isValidate;

}

function dhakaArea()
{
    return [
        'Air HQ',
        'BSR',
        'BBD'
    ];
}

function outSideDhakaArea()
{
    return [
        'MTR',
        'ZHR',
        'PKP',
        'BAF CXB'
    ];
}

function getRankBySerialNo($serialNo){
    return \App\PersonnelInfo::where('serialno',$serialNo)->pluck('rank')->first();
}

function getMissionTypeByMissionTypeId($missiontypeid){
    return \App\MissionDeputationMiscType::where('missiontypeid',$missiontypeid)->pluck('missiontype')->first();
}







// PROMOTIONRECORDS // OCCURRENCETYPE //  DATEOFEFFECT //






