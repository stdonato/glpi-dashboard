<?php

// /bin/cat /proc/loadavg | /usr/bin/awk '{print $1","$2","$3}'

$cores = exec('/bin/grep -c ^processor /proc/cpuinfo');

$loadavg = exec('/bin/cat /proc/loadavg | /usr/bin/awk \'{print $2}\'');

$load = round(($loadavg*100)/$cores ,1);

//$load = $perc;	

if($cores == 1) {
	$ncores = '1 core'; }
else {
	$ncores = $cores.' cores'; }	

//echo $load."% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(5 min)";

echo $load."% &nbsp;&nbsp;&nbsp;&nbsp;(".$ncores.")";	
	
if($load > 90) { $corl = "progress-bar-danger"; }

if($load >= 60 and $load <= 90) { $corl = "progress-bar-warning"; } 

if($load > 51 and $load < 60) { $corl = "progress-bar"; }

if($load > 0 and $load <= 50) { $corl = "progress-bar-success"; }	


?>