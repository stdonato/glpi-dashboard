<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");

Session::checkLoginUser();
Session::checkRight("profile", READ);

$ent_id =	$_POST["id"];

if(isset($_POST["lng"]) && isset($_POST["lat"])) {

	$lng = 	$_POST["lng"]; 
	$lat =	$_POST["lat"];
		
	$query = "SELECT name FROM glpi_entities WHERE id = ".$ent_id;
	$result = $DB->query($query) or die ("error insert");
	
	$location = $DB->result($result,0,'name');
	
	$insert = "
		INSERT INTO glpi_plugin_dashboard_map (entities_id, location, lat, lng) 
		VALUES ('$ent_id', '$location', '$lat', '$lng') 
		ON DUPLICATE KEY UPDATE lat='$lat', lng='$lng'";			 
	
	$DB->query($insert) or die ("error inserting coordinates");
	
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=".$CFG_GLPI['root_doc']."/front/entity.form.php?id=".$ent_id."'>";
}

if($_POST["lng"] == "" && $_POST["lat"] == "") {
	
	$query = "DELETE FROM glpi_plugin_dashboard_map WHERE entities_id = ".$_POST["id"];
	$DB->query($query) or die ("error removing coordinates");
	
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=".$CFG_GLPI['root_doc']."/front/entity.form.php?id=".$ent_id."'>";	
}			

?>

