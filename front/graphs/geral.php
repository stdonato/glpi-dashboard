<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");

Session::checkLoginUser();
Session::checkRight("profile", READ);

global $DB;
   
    switch (date("m")) {
    case "01": $mes = __('January','dashboard'); break;
    case "02": $mes = __('February','dashboard'); break;
    case "03": $mes = __('March','dashboard'); break;
    case "04": $mes = __('April','dashboard'); break;
    case "05": $mes = __('May','dashboard'); break;
    case "06": $mes = __('June','dashboard'); break;
    case "07": $mes = __('July','dashboard'); break;
    case "08": $mes = __('August','dashboard'); break;
    case "09": $mes = __('September','dashboard'); break;
    case "10": $mes = __('October','dashboard'); break;
    case "11": $mes = __('November','dashboard'); break;
    case "12": $mes = __('December','dashboard'); break;
    }
?>

<html> 
<head>
<title>GLPI - <?php echo __('Charts','dashboard')." - ".__('Overall','dashboard'); ?></title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="../js/jquery.min.js"></script> 

<script src="../js/highcharts.js"></script>
<script src="../js/highcharts-3d.js"></script>
<script src="../js/modules/boost.js"></script>
<script src="../js/modules/exporting.js"></script>
<script src="../js/modules/no-data-to-display.js"></script>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>
<?php echo '<script src="../js/themes/'.$_SESSION['charts_colors'].'"></script>'; ?>

</head>

<body style="background-color:#e5e5e5; margin-left:0%;">
<?php

$ano = date("Y");
$month = date("Y-m");
$datahoje = date("Y-m-d");

# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');


if($sel_ent != '') {			
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
	$problem =  "AND glpi_problems.entities_id IN (".$sel_ent.")";
}

if($sel_ent == '') {
	
	$entities = $_SESSION['glpiactiveentities'];
	$ent = implode(",",$entities);
	$entidade = "AND glpi_tickets.entities_id IN (".$ent.")";
	$problem =  "AND glpi_problems.entities_id IN (".$ent.")";
}

//total de chamados
$sql =	"SELECT COUNT(glpi_tickets.id) as total        
      FROM glpi_tickets
      LEFT JOIN glpi_entities ON glpi_tickets.entities_id = glpi_entities.id
      WHERE glpi_tickets.is_deleted = '0'
      ".$entidade." ";

$result = $DB->query($sql) or die ("erro");
$total_mes = $DB->fetch_assoc($result);

?>
<div id='content' >
	<div id='container-fluid' style="margin: 0px 5% 0px 5%;"> 

	<div id="pad-wrapper" >
		<div id="charts" class="fluid chart"> 
			<div id="head" class="fluid" style="min-height:120px !important;">			
				<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:5%;"></i><span></span></a>				
				<div id="titulo_graf" >				
					<?php echo __('Tickets Total','dashboard'); ?>: <?php //echo $ano .":" ; ?> 
					<span class="quant"> <?php echo " ".$total_mes['total'] ; ?> </span> 
				</div>
			</div>

			<!-- DIV's -->
			
			<div id="graf_linhas" class="col-md-12 col-sm-12 geral_linhas" style="margin-left: 0px;" >
				<?php include ("./inc/graflinhas_sat_geral.inc.php"); ?>
			</div>
			
			<div id="graf2" class="col-md-6 col-sm-6" style="margin-top:45px;" >
			 <?php include ("./inc/grafpie_stat_geral.inc.php"); ?>
			</div>
			
			<div id="graf4" class="col-md-6 col-sm-6" style="margin-top:45px;">
			 <?php include ("./inc/grafpie_origem.inc.php");  ?>
			</div>
			
			<div id="graf_tipo" class="col-md-12 col-sm-12 fluid" style="margin-top: 35px;">
			 <?php include ("./inc/grafcol_tipo_geral.inc.php");  ?>
			</div>
			
			<div>
			 <?php include ("./inc/grafent_geral.inc.php");  ?>
			</div>
			
			<div id="graftime" class="col-md-6 col-sm-6" style="height:450px; margin-top:35px; margin-left: 0px;">
			 <?php include ("./inc/grafpie_time_geral.inc.php");?>
			</div>
			
			<div id="grafgrp" class="col-md-6 col-sm-6 fluid" style="height: 450px; margin-top:35px; margin-left: 0px;">
			 <?php include ("./inc/grafbar_grupo_geral.inc.php");?>
			</div>			
						
			<div id="grafcat"  class="col-md-12 col-sm-12 fluid" style="margin-top:35px; margin-left: 0px;">
			 <?php include ("./inc/grafcat_geral.inc.php"); ?>
			</div>
			
			
			</div>
		</div>
</div>

<!-- Highcharts export xls, csv -->
<script src="../js/export-csv.js"></script>

</body>
</html>
