<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

include "/inc/functions.php";

Session::checkLoginUser();
Session::checkRight("profile", "r");

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
<title>GLPI - <?php echo __('Charts','dashboard'). " " . __('por PATI','dashboard'); ?></title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />
<!--  <meta http-equiv="refresh" content= "120"/> -->

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />

<script type="text/javascript" src="../js/jquery.min.js"></script> 
<link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
<script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>

<script src="../js/highcharts.js"></script>
<script src="../js/modules/exporting.js"></script>
<script src="../js/modules/no-data-to-display.js"></script>

<script src="../js/bootstrap-datepicker.js"></script>
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">
<link href="../less/datepicker.less" rel="stylesheet" type="text/css">

<link href="../css/style-dash.css" rel="stylesheet" type="text/css" />

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>
<?php echo '<script src="../js/themes/'.$_SESSION['charts_colors'].'"></script>'; ?>

 <!-- odometer -->
<link href="../css/odometer.css" rel="stylesheet">
<script src="../js/odometer.js"></script> 

</head>
<body style="background-color: #e5e5e5; margin-left:0%;">

<?php

global $DB;

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


//select user entities
$entities = Profile_User::getUserEntities($_SESSION['glpiID'], true);
$ents = implode(",",$entities);

$sql_ent = "
SELECT id, name
FROM `glpi_entities`
WHERE id IN (".$ents.")
ORDER BY `name` ASC ";

$result_ent = $DB->query($sql_ent);
$ent = $DB->fetch_assoc($result_ent);


// lista de entidades
function dropdown( $name, array $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select style="width: 300px; height:27px;" autofocus onChange="javascript: document.form1.submit.focus()" name="'.$name.'" id="'.$name.'">'."\n";

    $selected = $selected;
    /*** loop over the options ***/
    foreach( $options as $key=>$option )
    {
        /*** assign a selected value ***/
        $select = $selected==$key ? ' selected' : null;

        /*** add each option to the dropdown ***/
        $dropdown .= '<option value="'.$key.'"'.$select.'>'.$option.'</option>'."\n";
    }

    /*** close the select ***/
    $dropdown .= '</select>'."\n";

    /*** and return the completed dropdown ***/
    return $dropdown;
}


$res_ent = $DB->query($sql_ent);
$arr_ent = array();
$arr_ent[0] = "-- ". __('Selecione o PATI','dashboard') . " --" ;

$DB->data_seek($result_ent, 0);

while ($row_result = $DB->fetch_assoc($result_ent))		
	{ 
	$v_row_result = $row_result['id'];
	$arr_ent[$v_row_result] = $row_result['name'] ;			
	} 	 

$name = 'sel_ent';
$options = $arr_ent;
$selected = "0";

?>

<div id='content' >
	<div id='container-fluid' style="margin: 0px 8% 0px 8%;"> 	
		<div id="charts" class="row-fluid chart"> 
			<div id="pad-wrapper" >
				<div id="head" class="row-fluid">		
					<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>
				
					<div id="titulo_graf" >			
					  <?php echo __('Tickets','dashboard') ." ". __('por PATI','dashboard'); ?> 
					<span style="color:#8b1a1a; font-size:35pt; font-weight:bold;"> </span> </div>
				
						<div id="datas-tec" class="span12 row-fluid" > 
						<form id="form1" name="form1" class="form2" method="post" action="?date1=<?php echo $data_ini ?>&date2=<?php echo $data_fin ?>&con=1" onsubmit="datai();dataf();"> 
						<table border="0" cellspacing="0" cellpadding="1" bgcolor="#efefef">
						
						<tr>
						<td>
						<?php 
							echo'
									<table>
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
						<?php
							//echo dropdown( $name, $options, $selected );
						?>
							<select style="width: 300px; height:27px;" autofocus onChange="javascript: document.form1.submit.focus()" name="sel_ent" id="sel_ent">
								<option value="0">-- Selecione um PATI --</option>
								<option value="29,30,37,39">PATI Ariquemes</option>
								<option value="27,31,46">PATI Cacoal</option>
								<option value="36">PATI Guajará-Mirim</option>
								<option value="38,41,43,28,47,34,40">PATI Ji-Paraná</option>
								<option value="35,42">PATI Pimenta Bueno</option>
								<option value="44,45">PATI Rolim de Moura</option>
								<option value="32,33,48">PATI Vilhena</option>							
							</select>
							
						</td>
						</tr>
						<tr><td height="15px"></td></tr>
						<tr>
							<td colspan="2" align="center" style="">
								<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?></button>
								<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='graf_pati.php'" > <i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button></td>
							</td>
						</tr>
							
						</table>
						<?php Html::closeForm(); ?>
						<!-- </form> -->
						</div>
				</div>
			<!-- DIV's -->
			
			<script type="text/javascript" >
			$(document).ready(function() { $("#sel_ent").select2(); });
			</script>
			
			<?php
			
			$con = $_GET['con'];
			
			if($con == "1") {
					
			
			if(!isset($_POST['date1']))
			{	
				$data_ini2 = $_GET['date1'];	
				$data_fin2 = $_GET['date2'];
			}
			
			else {	
				$data_ini2 = $_POST['date1'];	
				$data_fin2 = $_POST['date2'];	
			}  
			
			if(!isset($_POST["sel_ent"])) {
				$id_ent = $_GET["sel_ent"];	
			}
			
			else {
				$id_ent = $_POST["sel_ent"];
			}
			
			if($id_ent == " ") {
				echo '<script language="javascript"> alert(" ' . __('Select a entity','dashboard') . ' "); </script>';
				echo '<script language="javascript"> location.href="graf_pati.php"; </script>';
			}
			
			if($data_ini == $data_fin) {
				$datas = "LIKE '".$data_ini."%'";	
			}	
			
			else {
				$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
			}
			
/*			// nome da entidade
			$sql_nm = "
			SELECT id, name
			FROM `glpi_entities`
			WHERE id IN (".$id_ent.") ";
			
			$result_nm = $DB->query($sql_nm);
			$ent_name = $DB->fetch_assoc($result_nm);
*/

$ent_name = $_POST["sel_ent"];
//echo $ent_name;

if($ent_name === "29,30,37,39") {
	$ent_name = "PATI Ariquemes";
	}
	
if($ent_name === "27,31,46") {
	$ent_name = "PATI Cacoal";
	}
	
if($ent_name === "36") {
	$ent_name =  "PATI Guajará-Mirim";
	}
	
if($ent_name === "38,41,43,28,47,34,40") {
	$ent_name =  "PATI Ji-Paraná";
	}
	
if($ent_name === "35,42") {
	$ent_name =  "PATI Pimenta Bueno";
	}

if($ent_name === "44,45") {
	$ent_name =  "PATI Rolim de Moura";
	}

if($ent_name === "32,33,48") {
	$ent_name =  "PATI Vilhena";
	}
		
			
			//quant chamados
			$query2 = "
			SELECT COUNT(glpi_tickets.id) as total
			FROM glpi_tickets
			WHERE glpi_tickets.date ".$datas."
			AND glpi_tickets.is_deleted = 0     
			AND glpi_tickets.entities_id IN (".$id_ent.") ";
			
			$result2 = $DB->query($query2) or die('erro');
			$total = $DB->fetch_assoc($result2);
			
			
//count by status
$query_stat = "
SELECT 
SUM(case when glpi_tickets.status = 1 then 1 else 0 end) AS new,
SUM(case when glpi_tickets.status = 2 then 1 else 0 end) AS assig,
SUM(case when glpi_tickets.status = 3 then 1 else 0 end) AS plan,
SUM(case when glpi_tickets.status = 4 then 1 else 0 end) AS pend,
SUM(case when glpi_tickets.status = 5 then 1 else 0 end) AS solve,
SUM(case when glpi_tickets.status = 6 then 1 else 0 end) AS close
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas."
AND glpi_tickets.entities_id IN (".$id_ent.") ";

$result_stat = $DB->query($query_stat);

$new = $DB->result($result_stat,0,'new');
$assig = $DB->result($result_stat,0,'assig');
$plan = $DB->result($result_stat,0,'plan');
$pend = $DB->result($result_stat,0,'pend');
$solve = $DB->result($result_stat,0,'solve');
$close = $DB->result($result_stat,0,'close');        

echo '<div id="entidade2" class="span12 row-fluid" style="margin-bottom: 15px;">';
echo '<div id="name"  style="margin-top: 15px;"><span>'.$ent_name.'</span> - <span style = "color:#000;"> '.$total['total'].' '.__('Tickets','dashboard').'</span></div>
	
<div class="row" style="margin: 10px 0px 0 0;" >	
<div style="margin-top: 20px; height: 45px;">
							<!-- COLUMN 1 -->															
								  <div class="col-sm-3 col-md-3 stat" >
									 <div class="dashbox shad panel panel-default db-blue">
										<div class="panel-body_2">
										   <div class="panel-left red" style = "margin-top: -5px; margin-left: -5px;">
												<i class="fa fa-tags fa-3x fa2"></i>
										   </div>
										   <div class="panel-right">
										     <div id="odometer1" class="odometer" style="font-size: 20px; margin-top: 1px;">  </div><p></p>
                        				<span class="chamado">'. __('Tickets','dashboard').'</span><br>
                        				<span class="date" style="font-size: 16px;"><b>'. _x('status', 'New').' + '.__('Assigned').'</b></span>												
										   </div>
										</div>
									 </div>
								  </div>
								  
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox shad panel panel-default db-green">
										<div class="panel-body_2">
										   <div class="panel-left blue" style = "margin-top: -5px; margin-left: -5px;">
												<i class="fa fa-clock-o fa-3x fa2"></i>
										   </div>
										   <div class="panel-right">										 
											<div id="odometer2" class="odometer" style="font-size: 20px; margin-top: 1px;">   </div><p></p>
                        				<span class="chamado">'. __('Tickets','dashboard').'</span><br>
                        				<span class="date"><b>'. __('Pending').'</b></span>
										   </div>
										</div>
									 </div>
								  </div>																		
                     								
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox shad panel panel-default db-red">
										<div class="panel-body_2">
										   <div class="panel-left yellow" style = "margin-top: -5px; margin-left: -5px;">
												<i class="fa fa-check-square fa-3x fa2"></i>
										   </div>
										   <div class="panel-right">
												<div id="odometer3" class="odometer" style="font-size: 20px; margin-top: 1px;">   </div><p></p>
                        				<span class="chamado">'. __('Tickets','dashboard').'</span><br>
                        				<span class="date"><b>'. __('Solved','dashboard').'</b></span>
										   </div>										   
										</div>
									 </div>
								  </div>
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox shad panel panel-default db-orange">
										<div class="panel-body_2">
										   <div class="panel-left green" style = "margin-top: -5px; margin-left: -5px;">
												<i class="fa fa-times-circle fa-3x fa2"></i>
										   </div>
								   		<div class="panel-right">
												<div id="odometer4" class="odometer" style="font-size: 20px; margin-top: 1px;">   </div><p></p>
                        				<span class="chamado">'. __('Tickets','dashboard').'</span><br>
                        				<span class="date"><b>'. __('Closed','dashboard').'</b></span>
										   </div>
										</div>
									 </div>
								  </div>																	                          				                           							
						</div>   	
	
</div>
</div>';
?>

<script type="text/javascript" >
	window.odometerOptions = {
	   format: '( ddd).dd'
	};
	
	setTimeout(function(){
	    odometer1.innerHTML = <?php echo $new + $assig + $plan; ?>;
	    odometer2.innerHTML = <?php echo $pend; ?>;
	    odometer3.innerHTML = <?php echo $solve; ?>;
	    odometer4.innerHTML = <?php echo $close; ?>;
	}, 1000);
</script> 
			
			<div id="graf_linhas" class="span12" style="height: 450px; margin-top: 25px; margin-left: -5px;">
				<?php include ("./inc/graflinhas_pati.inc.php"); ?>
			</div>
						
			<div id="graf2" class="span6" >
				<?php include ("./inc/grafpie_stat_pati.inc.php"); ?>
			</div>
			
			<div id="graf_tipo" class="span6" style="margin-left: 2.5%;">
				<?php include ("./inc/grafpie_tipo_pati.inc.php");  ?>
			</div>

			<div>
				<?php include ("./inc/grafent_geral_mes.inc.php"); ?>
			</div>						
			
			<div id="graf4" class="span12" style="height: 450px; margin-left: -5px;">
				<?php include ("./inc/grafcat_pati.inc.php");  ?>
			</div>			
			
			<?php 
				include ("./inc/grafbar_pati.inc.php");			
			}
			?>
			
			<div id="graf_time1" class="span12" style="height: 450px; margin-top: 25px; margin-left: -5px;">
				<?php //include ("./inc/grafcol_time_grupo_pati.inc.php"); ?>
			</div>
			
			</div>
						
			</div>
		
		</div>
	</div>
</div>
</body>
</html>
