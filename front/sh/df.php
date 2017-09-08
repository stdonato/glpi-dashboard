<?php

$disk = exec('/bin/df -hm |grep sd | awk \'{print $1","$2","$3","$4","$5","$6}\'', $result);

$count = exec('/bin/df -h |grep sd | awk \'{print $1","$2","$3","$4","$5","$6}\' |wc -l');


if($count == 1) {

	$size = explode(',',$disk);
	
	if($size[2] >= 1024) {
		
		echo round($size[2] / '1024',2)." / ". round($size[1] / '1024',2)." GB";
		$percd = round(($size[2]*100)/$size[1] ,1);
		$udisk = $percd;
		$dname = $size[5];
		$usedd = round($size[2] / '1024',1);
		$totald = round($size[1] / '1024',1);
		$titled = "DISK - $totald GB";
	}
	
	else {
	
		echo $size[2] . " / " . $size[1] . " MB";	
		$percd = round(($size[2]*100)/$size[1] ,1);
		$udisk = $percd;
		$dname = $size[5];
		$usedd = $size[2] ;
		$totald = $size[1] ;
		$titled = "DISK - $totald MB";
		}

}


// more than 1 disk or partition

if($count > 1) {

	foreach($result as $a) {
		
		$a1 = explode(',', $a);
		
		$size1+=$a1[1];
		$size2+=$a1[2];
			
	}
	
	if($size2 >= 1024) {
		
		echo round($size2 / '1024',2)." / ". round($size1 / '1024',2)." GB";
		$percd = round(($size2*100)/$size1 ,1);
		$udisk = $percd;
		
		$usedd = round($size2 / '1024',1);
		$totald = round($size1 / '1024',1);
	#	$titled = "DISK - GB";
		$titled = $totald;
	}
	
	else {
	
		echo $size2 . " / " . $size1 . " MB";
		$percd = round(($size2*100)/$size1 ,1);
		$udisk = $percd;	
		
		$usedd = $size2;
		$totald = $size1 ;
		$titled = "DISK - MB";
	}

}

if($udisk > 90) { $cord = "progress-bar-danger"; }

if($udisk >= 60 and $udisk <= 90) { $cord = "progress-bar-warning"; } 

if($udisk > 51 and $udisk < 60) { $cord = "progress-bar"; }

if($udisk > 0 and $udisk <= 50) { $cord = "progress-bar-success"; }

 
