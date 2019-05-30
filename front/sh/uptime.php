<?php

Session::checkLoginUser();
Session::checkRight("profile", READ);

$uptime = shell_exec('uptime |cut -d" " -f4-8');

echo $uptime;

/*$totalSeconds1 = shell_exec("/usr/bin/cut -d'.' -f1 /proc/uptime");
$totalSeconds = strtotime($totalSeconds1);
$totalMin   = $totalSeconds / 60;
$totalHours = $totalMin / 60;

$days  = floor($totalHours / 24);
$hours = floor($totalHours - ($days * 24));
$min   = floor($totalMin - ($days * 60 * 24) - ($hours * 60));

$formatUptime = '';

if ($days != 0) {
    $formatUptime .= "$days d ";
}

if ($hours != 0) {
    $formatUptime .= "$hours h ";
}

if ($min != 0) {
    $formatUptime .= "$min m";
}

echo ($formatUptime);*/

?>