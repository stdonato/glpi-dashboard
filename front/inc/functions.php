<?php

function conv_data($data) {
    if($data != "") {
        $source = $data;
        $date = new DateTime($source);
        
	 switch ($_SESSION['glpidate_format']) {
    case "0": $dataf = $date->format('Y-m-d'); break;
    case "1": $dataf = $date->format('d-m-Y'); break;
    case "2": $dataf = $date->format('m-d-Y'); break;    
    }        
        
        //return $date->format('d-m-Y');}
        return $dataf;}
    else {
        return "";
    }
}


function conv_data_hora($data) {
    if($data != "") {
        $source = $data;
        $date = new DateTime($source);

    switch ($_SESSION['glpidate_format']) {
    case "0": $dataf = $date->format('Y-m-d H:i'); break;
    case "1": $dataf = $date->format('d-m-Y H:i'); break;
    case "2": $dataf = $date->format('m-d-Y H:i'); break;    
    }                 
        
        //return $date->format('d-m-Y H:i');}
        return $dataf;}
    else {
        return "";
    }
}




function time_ext($solvedate)
{

$time = $solvedate; // time duration in seconds

 if ($time == 0){
        return '';
    }

	$days = floor($time / (60 * 60 * 24));
	$time -= $days * (60 * 60 * 24);
	
	$hours = floor($time / (60 * 60));
	$time -= $hours * (60 * 60);
	
	$minutes = floor($time / 60);
	$time -= $minutes * 60;
	
	$seconds = floor($time);
	$time -= $seconds;
	
	$return = "{$days}d {$hours}h {$minutes}m {$seconds}s"; // 1d 6h 50m 31s
	
	return $return;
}


function time_hrs($time)
{

 if ($time == 0){
        return '';
    }

  // $days = floor($time / 86400); // 60*60*24
  // $time -= $days * 86400;
	
	$hours = floor($time / (60 * 60));
	$time -= $hours * (60 * 60);
	
	$minutes = floor($time / 60);
	$time -= $minutes * 60;
	
	$seconds = floor($time);
	$time -= $seconds;
	
	$return = "{$hours}h {$minutes}m {$seconds}s"; // 1d 6h 50m 31s
	
	return $return;
}


function time_hrs2($time)
{

 if ($time == 0){
        return '';
    }

  // $days = floor($time / 86400); // 60*60*24
  // $time -= $days * 86400;
	
	$hours = floor($time / (60 * 60));
	$time -= $hours * (60 * 60);
	
	$minutes = floor($time / 60);
	$time -= $minutes * 60;
	
	$seconds = floor($time);
	$time -= $seconds;
	
	$return = $hours; // 1d 6h 50m 31s
	
	return $return;
}



function dropdown( $name, array $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select id="sel1" style="width: 300px;" autofocus onChange="javascript: document.form1.submit.focus()" name="'.$name.'" id="'.$name.'">'."\n";

    $selected = $selected;
    /*** loop over the options ***/
    foreach( $options as $key=>$option )
    {
        /*** assign a selected value ***/
        $select = $selected==$key ? ' selected' : null;

        /*** add each option to the dropdown ***/
        $dropdown .= '<option value="'.$key.'"'.$select.'>'.$option.'</option>'."\n";
    }

    /*** close the select ***/
    $dropdown .= '</select>'."\n";

    /*** and return the completed dropdown ***/
    return $dropdown;
}


function dropdown2( $name, array $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select style="width: 300px; height: 27px;" autofocus name="'.$name.'" id="'.$name.'">'."\n";

    $selected = $selected;
    /*** loop over the options ***/
    foreach( $options as $key=>$option )
    {
        /*** assign a selected value ***/
        $select = $selected==$key ? ' selected' : null;
        /*** add each option to the dropdown ***/
        $dropdown .= '<option value="'.$key.'"'.$select.'>'.$option.'</option>'."\n";
    }
    /*** close the select ***/
    $dropdown .= '</select>'."\n";

    /*** and return the completed dropdown ***/
    return $dropdown;
}

//segundos para h:m:s
/*
$segundos = 15058084;
//$converter = date('H:i:s',mktime(0,0,$segundos,15,03,2013));//Converter os segundos em no formato mm:ss
$converter = date('H:i:s',mktime(0,0,$segundos));//Converter os segundos em no formato mm:ss
echo $converter;//no exemplo ira retornar 02:15	
*/

?>




