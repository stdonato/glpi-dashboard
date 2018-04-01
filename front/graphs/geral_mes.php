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
<title>GLPI - <?php echo __('Tickets','dashboard'). " " .__('by Date','dashboard'); ?></title>
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
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../js/jquery.min.js"></script> 
<script src="../js/highcharts.js"></script>
<script src="../js/modules/exporting.js"></script>
<script src="../js/modules/no-data-to-display.js"></script>
<script src="../js/bootstrap-datepicker.js"></script>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>
<?php echo '<script src="../js/themes/'.$_SESSION['charts_colors'].'"></script>'; ?>

</head>

<body style="background-color:#e5e5e5; margin-left:0%;">

<?php

if(!empty($_POST['submit']))
{	
	$data_ini =  $_POST['date1'];	
	$data_fin = $_POST['date2'];
}

else {
	$data_ini = date("Y-01-01");
	$data_fin = date("Y-m-d");
} 

$ano = date("Y");
$month = date("Y-m");
$datahoje = date("Y-m-d");

# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '') {
	//get user entities
	//$entities = Profile_User::getUserEntities($_SESSION['glpiID'], true);
	$entities = $_SESSION['glpiactiveentities'];
	$ent = implode(",",$entities);

	$entidade = "AND glpi_tickets.entities_id IN (".$ent.")";	
	$problem =  "AND glpi_problems.entities_id IN (".$ent.")";
}
else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
	$problem =  "AND glpi_problems.entities_id IN (".$sel_ent.")";
}
?>

<div id='content' >
	<div id='container-fluid' style="margin: 0px 5% 0px 5%;"> 
	
		<div id="pad-wrapper" >
			<div id="charts" class="fluid chart"> 
				<div id="head" class="fluid">
				
				<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:5%;"></i><span></span></a>
				
				<div id="titulo" style="margin-bottom: 2px;">
				
				<?php echo __('Tickets','dashboard') ." ". __('by Date','dashboard');  ?> 
				
				<div id="datas" class="col-md-12 col-sm-12 fluid" > 
				<form id="form1" name="form1" class="form1" method="post" action="?date1=<?php echo $data_ini ?>&date2=<?php echo $data_fin ?>"> 
					<table border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td style="width: 300px;">			
							<?php			    
							echo'
							<table style="margin-top:6px;" border=0>
								<tr>
									<td>
									   <div class="input-group date" id="dp1" data-date="'.$data_ini.'" data-date-format="yyyy-mm-dd">
									    	<input class="col-md-9 form-control" size="13" type="text" name="date1" value="'.$data_ini.'" >		    	
									    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>	    	
								    	</div>
									</td>
									<td>&nbsp;</td>
									<td>
								   	<div class="input-group date" id="dp2" data-date="'.$data_fin.'" data-date-format="yyyy-mm-dd">
									    	<input class="col-md-9 form-control" size="13" type="text" name="date2" value="'.$data_fin.'" >		    	
									    	<span class="input-group-addon add-on"><i class="fa fa-calendar"></i></span>	    	
								    	</div>
									</td>
									<td>&nbsp;</td>
								</tr>
							</table> ';
							?>
							
							<script language="Javascript">			
								$('#dp1').datepicker('update');
								$('#dp2').datepicker('update');			
							</script>
							</td>
						
						<td style="margin-top:2px;">
						</tr>
						<tr height="12px" ><td></td></tr>
						<tr align="center">			
							<td>
								<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?> </button>
								<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='geral_mes.php" ><i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button>
							</td>
						</tr>
					</table>
				<p>
				</p>
				<?php Html::closeForm(); ?>
				<!-- </form> -->
				</div>
				
				</div>
				<!-- DIV's -->
				
				 </div>
				 
				<?php
				
				if($data_ini == $data_fin) {
				$datas = "LIKE '".$data_ini."%'";	
				}	
				
				$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
				
					$query_total = "
					SELECT COUNT(id) AS total
					FROM glpi_tickets
					WHERE glpi_tickets.is_deleted = '0'
					AND date ".$datas."
					".$entidade." ";
					
					$result_total = $DB->query($query_total) or die('erro');
					$total = $DB->fetch_assoc($result_total);
					
				 
				echo '<div id="entidade" class="col-md-12 col-sm-12 fluid" style="margin-left:-5px;">';
				echo "<span class='tit-ent'> ".$total['total']." ".__('Tickets','dashboard')."</span>";
				echo "</div>";
				?>
				
				<div id="graf_linhas" class="col-md-12 col-sm-12" style="height: 450px;">
					<?php  include ("./inc/graflinhas_sat_geral_mes.inc.php"); ?>
				</div>
				
				<div id="graf2" class="col-md-6 col-sm-6" style="height:450px; margin-top:35px;">
					<?php include ("./inc/grafpie_stat_geral_mes.inc.php"); ?>
				</div>
				
				<div id="graf4" class="col-md-6 col-sm-6" style="height:450px; margin-top:35px;">
					<?php  include ("./inc/grafpie_origem_mes.inc.php");  ?>
				</div>
				
				<div id="graf_tipo" class="col-md-12 col-sm-12" style="margin-top: 35px;">
					<?php include ("./inc/grafcol_tipo_geral_mes.inc.php");  ?>
				</div>
				
				<div id="grafent" class="col-md-12 col-sm-12" style="height: 450px; margin-top:40px;">
					<?php include ("./inc/grafent_geral_mes.inc.php"); ?>
				</div>
				
				<div id="graftime" class="col-md-6 col-sm-6" style="height:450px; margin-top:35px;">
				<?php include ("./inc/grafpie_time_geral_mes.inc.php");?>
				</div>
				
				<div id="grafgrp" class="col-md-6 col-sm-6 fluid" style="height:450px; margin-top:35px; ">
					<?php  include ("./inc/grafbar_grupo_geral_mes.inc.php"); ?>
				</div>
				
				<div id="grafcat"  class="col-md-12 col-sm-12 fluid" style="margin-top:35px;">
					<?php include ("./inc/grafcat_geral_mes.inc.php"); ?>
				</div>
			
			</div>
		</div>
	</div>

<!-- Highcharts export xls, csv -->
<script src="../js/export-csv.js"></script>

<!-- This module, after the dependencies 
<script src="../js/highcharts-export-clientside.js"></script>
-->

</body>
</html>
