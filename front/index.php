<?php

include ("../../../inc/includes.php");
include ("../../../config/config.php");

global $DB;

Session::checkLoginUser();
/*
function find_SQL_Version() {
   $output = shell_exec('mysql -V');
   preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
   return $version[0][0].".".$version[0][2];
}

//echo 'Your SQL version is ' . find_SQL_Version(); 
if(find_SQL_Version() >= 5.7) {
*/	
//	$mode = "SET GLOBAL sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';";
//	$DB->query($mode);
//}

$query_lay = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'layout' AND users_id = ".$_SESSION['glpiID']." ";																
					$result_lay = $DB->query($query_lay);
					
					$layout = $DB->result($result_lay,0,'value');
					
//redirect to index
if($layout == '0')
	{
		$redir = '<meta http-equiv="refresh" content="0; url=index2.php" />';
	}

if($layout == 1 || $layout == '' )
	{
		$redir = '<meta http-equiv="refresh" content="0; url=index1.php" />';
	}
						
?>

<!DOCTYPE html>
<html>
<head>
    <title>GLPI - Dashboard - Home</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <meta http-equiv="Pragma" content="public">
    <?php echo $redir; ?>        
      	 
</head>
<body style='background-color: #FFF;'>
</body>
</html>
