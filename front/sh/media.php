<?php
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/inc/config.php");

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

//$ver = explode(" ",implode(" ",plugin_version_dashboard()));


// count years	
$query_y = "SELECT DISTINCT DATE_FORMAT( date, '%Y' ) AS year
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND date IS NOT NULL
ORDER BY year DESC ";
	
$result_y = $DB->query($query_y);
$num_years = $DB->numrows($result_y);


// count months	
$query_m = "SELECT DISTINCT DATE_FORMAT( date, '%Y-%m' ) AS month
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND date IS NOT NULL
ORDER BY month DESC ";
	
$result_m = $DB->query($query_m);
$num_months = $DB->numrows($result_m);


//count tickets
$query_tick = "SELECT count(*) AS tickets
FROM glpi_tickets
WHERE is_deleted = 0";

$result_tick = $DB->query($query_tick);
$num_tickets = $DB->result($result_tick,0,'tickets');


$media_ano = round(($num_tickets/$num_years),0);

$media_mes = round(($num_tickets/$num_months),0);
              						                         	            
?>

<html>
  <head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>GLPI - Dashboard - Médias</title>
  <link rel="icon" href="img/dash.ico" type="image/x-icon" />
  <link rel="shortcut icon" href="img/dash.ico" type="image/x-icon" />
  <link href="css/styles.css" rel="stylesheet" type="text/css" />
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
  <link href="css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />         
    
  </head>
<body style="background-color: #fff;">

<?php //echo $num_months; ?>
<br>
<?php echo "Total de Tickets: ".$num_tickets; ?>
<br>
<?php echo "Chamados - Média anual: ".$media_ano; ?>
<br>
<?php echo "Chamados - Média mensal: ".$media_mes; ?>
<br>

<?php

while($row = $DB->fetch_assoc($result_m)) {

$query =  "SELECT DISTINCT DATE_FORMAT( date, '%Y-%m' ) AS data, count(*) AS conta
FROM glpi_tickets
WHERE is_deleted = 0
AND date LIKE '%".$row['month']."%' ";

$result = $DB->query($query);
$row_mes = $DB->fetch_assoc($result);

echo $row_mes['data']." - ".$row_mes['conta']."<br />";
	
}	

?>

</body>
</html>
