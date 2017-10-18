<?php

include ("../../../inc/includes.php");
include ("../../../inc/config.php");

global $DB;

Session::checkLoginUser();


$query_lay = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'layout' AND users_id = ".$_SESSION['glpiID']." ";																
					$result_lay = $DB->query($query_lay);					
					$layout = $DB->result($result_lay,0,'value');
					
//redirect to index
if($layout == '0')
	{
		// sidebar
		$redir = '<meta http-equiv="refresh" content="0; url=index2.php" />';
	}

if($layout == 1 || $layout == '' )
	{
		//top menu
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
