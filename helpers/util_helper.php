<?php
function formatInput($txt) {
    $txt = addslashes(trim($txt));
    $txt = str_replace('\'', '&#039;', $txt);
    $txt = str_replace('"', '&quot;', $txt);
    $txt = str_replace('\\', '', $txt);
    return $txt;
}
function formatOutput($txt) {
    $txt = stripslashes($txt);
    $txt = str_replace('&#039;', '\'', $txt);
    $txt = str_replace('&quot;', '"', $txt);
    return $txt;
}
function encryptID($id) {
    $encry = md5($id);
    $start = substr($encry, 0, 4);
    $end = substr($encry, 5);
    return $start.$id.$end;
}
function encryptIDRandom($id) {
    $encry = md5(rand(1, 99));
    $start = substr($encry, 0, 4);
    $end = substr($encry, 5);
    return $start.$id.$end;
}
function decryptID($id) {
    return substr_replace(substr_replace($id, '', 0, 4), '', -27);
}
function getFileExtension($fileName) { 
    $i = strrpos($fileName, "."); 
    if (!$i) return "";
    $l = strlen($fileName) - $i; 
    $ext = substr($fileName, $i + 1, $l);
    return $ext; 
}
function removeExtension($fileName) { 
    $i = strrpos($fileName, "."); 
    if (!$i) return ""; 
    $name = substr($fileName, 0, $i); 
    return $name; 
}
function plusFile($fileName, $plus) { 
    $name = removeExtension($fileName);
    $exten = getFileExtension($fileName); 
    return $name . $plus . "." . $exten;
}
function formatDate($dateinput, $formatdate, $stamp=FALSE) {
    if (!$dateinput) return 'None';
    $tzoffset = 0;
    $date = $dateinput;
    $hour = 0;
    $minute = 0;
    $second = 0;
    if ($stamp) {
        list($date, $time) = explode(" ", $dateinput);
        list($hour, $minute, $second) = explode(":", $time);
        $hour = $hour + $tzoffset;
    }
    list($year, $month, $day) = explode("-", $date);

    $year_org = $year;
    if($year <= 1971) $year = 1971;

    $tstamp = mktime($hour, $minute, $second, $month, $day, $year);
    $sDate = date($formatdate, $tstamp);
    $sDate =  str_replace("1971", $year_org , $sDate);
    return $sDate;
}
function convertDate($datestamp, $dateconvert) {
    $tzoffset = 0;
    if ($datestamp == "00000000") 
    {
        $datestamp = "00000000000000";
    }
    $date   = substr($datestamp, 0, 8);
    $year   = substr($date, 0, 4);
    $month  = substr($date, 4, 2);
    $day    = substr($date, 6);
    $time   = substr($datestamp, 8);
    $hour   = substr($time, 0, 2);
    $hour   = $hour + $tzoffset;
    $minute = substr($time, 2, 2);
    $second = substr($time, 4);
    $tstamp = mktime($hour,$minute,$second,$month,$day,$year);
    $sDate	= date($dateconvert,$tstamp);
    return $sDate;
}
function datetimeInput($inputdatetime) {
    list($strdate, $strhour) = explode(" ", $inputdatetime);
    list($date, $month, $year) = explode("/", $strdate);
    return ($year . "-" . $month . "-" . $date." ".$strhour.":00");
}
function datetimeOutput($inputdatetime) {
    if(!$inputdatetime) return;
    list($strdate, $strhour) = explode(" ", $inputdatetime);
    list($year, $month, $date) = explode("-", $strdate);
    list($hour, $minute) = explode(":", $strhour);
    return ($date . "/" . $month . "/" . $year." ".$hour.":".$minute);
}
function dateInput($inputdate) {
    if(strpos($inputdate, "/") > 0) {
        list($date, $month, $year) = explode("/", $inputdate);
        return ($year . "-" . $month . "-" . $date);		
    }
    if(strpos($inputdate, "-") > 0) {
        list($date, $month, $year) = explode("-", $inputdate);
        return ($year . "-" . $month . "-" . $date);		
    }
    return $inputdate;
}
function dateOutput($inputdate) {
    if(!$inputdate) return;
    list($year, $month, $date) = explode("-", $inputdate);
    return ($date . "-" . $month . "-" . $year);
}
//Just add month(s) on the orginal date. 
function add_date($orgDate, $mth) {
    $cd = strtotime($orgDate);
    $retDAY = date('Y-m-d', mktime(0,0,0,date('m',$cd)+$mth,date('d',$cd),date('Y',$cd)));
    return $retDAY;
}
function rangeMonth($datestr) {
    date_default_timezone_set(date_default_timezone_get());
    $dt = strtotime($datestr);
    $res['start'] = date('Y-m-d', strtotime('first day of this month', $dt));
    $res['end'] = date('Y-m-d', strtotime('last day of this month', $dt));
    return $res;
}
function current_week() {
    $curDay = date('w', strtotime( "today" ));
    $week = array();
    if ($curDay == 0 OR $curDay == 6) {
        $week['week'] = intval(date("W", strtotime( "next monday" ))) + 1;
        $week['THỨ 2'] = strtotime('next monday');
        $week['THỨ 3'] = strtotime('next tuesday');
        $week['THỨ 4'] = strtotime('next wednesday');
        $week['THỨ 5'] = strtotime('next thursday');
        $week['THỨ 6'] = strtotime('next friday');
        //$week['THỨ 7'] = strtotime('next saturday this week');
    }
    else {
        $week['week'] = intval(date("W", strtotime( "today" ))) + 1;
        $week['THỨ 2'] = strtotime('monday this week');
        $week['THỨ 3'] = strtotime('tuesday this week');
        $week['THỨ 4'] = strtotime('wednesday this week');
        $week['THỨ 5'] = strtotime('thursday this week');
        $week['THỨ 6'] = strtotime('friday this week');
        //$week['THỨ 7'] = strtotime('saturday this week');
    }
    return $week;
}
//Lay tu ngay thu 2 den ngay thu 7 trong tuan
function rangeWeek($datestr) {
    date_default_timezone_set(date_default_timezone_get());
    $dt = strtotime($datestr);
    $res['start'] = date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
    $res['end'] = date('N', $dt)==6 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next saturday', $dt));
    return $res;
}
//Lay range tu so tuan trong nam (thu 2 den thu 7)
function rangeWeek2($week, $year) {
    $return = array();
    $return['start'] = date("Y-m-d", strtotime("{$year}-W{$week}-1"));
    $return['end'] = date("Y-m-d", strtotime("{$year}-W{$week}-6"));
    return $return;
}
function getMondays($year, $month) {
    $mondays = array();
    # First weekday in specified month: 1 = monday, 7 = sunday
    $firstDay = date('N', mktime(0, 0, 0, $month, 1, $year));
    /* Add 0 days if monday ... 6 days if tuesday, 1 day if sunday to get the first monday in month */
    $addDays = (8 - $firstDay) % 7;
    $mondays[] = date('Y-m-d', mktime(0, 0, 0, $month, 1 + $addDays, $year));
    
    $nextMonth = mktime(0, 0, 0, $month + 1, 1, $year);
    # Just add 7 days per iteration to get the date of the subsequent week
    for ($week = 1, $time = mktime(0, 0, 0, $month, 1 + $addDays + $week * 7, $year);
        $time < $nextMonth;
        ++$week, $time = mktime(0, 0, 0, $month, 1 + $addDays + $week * 7, $year))
    {
        $mondays[] = date('Y-m-d', $time);
    }
    return $mondays;
} 
function dayofyear2date($tDay, $tYear, $tFormat = 'Y-m-d') {
    $day = intval($tDay);
    $day = ($day == 0) ? $day : $day - 1;
    $offset = intval(intval($tDay) * 86400);
    $str = date($tFormat, strtotime('Jan 1, '.$tYear) + $offset);
    return $str;
}
function getRandom($length) {
    $seed = str_split('abcdefghijklmnpqrstuvwxyz0123456789'); // and any other characters
    shuffle($seed); // probably optional since array_is randomized; this may be redundant
    $rand = '';
    foreach (array_rand($seed, $length) as $k) $rand .= $seed[$k];
    return $rand;
}
function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') == $date;
}
// Function to get the client IP address
// getenv() is used to get the value of an environment variable in PHP.
function get_ip_getenv() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
// Function to get the client IP address
// $_SERVER is an array that contains server variables created by the web server.
function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
function isMethod() {
    $CI =& get_instance();
    $data = array();
    if ($CI->input->server('REQUEST_METHOD') == 'GET') {
        $data['method'] = 'GET';
        $data['data'] = $CI->input->get();
    }
    else if ($CI->input->server('REQUEST_METHOD') == 'POST') {
        $data['method'] = 'POST';
        $data['data'] = $CI->input->post();
    }
    return $data;
}
//* End of file */