<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/inc/config.php");

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if(isset($_REQUEST['usr']) && $_REQUEST['type'] == 0 ) {

$passo = $_REQUEST['ab'] - ($_REQUEST['dif'] - 1);

	//update tickets count
	$query_up = "UPDATE glpi_plugin_dashboard_notify 
	SET quant=". $passo ."
	WHERE users_id = ". $_REQUEST['usr'] ." 
	AND type = 0";
	
	$result_up = $DB->query($query_up);
	
echo "<script>location.href='../index.php';</script>"; 

}


if(isset($_REQUEST['usr']) && $_REQUEST['type'] == 1 ) {

$passo = $_REQUEST['ab'] - ($_REQUEST['dif'] - 1);

	//update tickets count	
	$query_up = "UPDATE glpi_plugin_dashboard_notify 
	SET quant=". $passo ."
	WHERE users_id = ". $_REQUEST['usr'] ." 
	AND type = 1";
	
	$result_up = $DB->query($query_up);
	
echo "<script>location.href='../index.php';</script>"; 

}


?>
