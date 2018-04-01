<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");

Session::checkLoginUser();

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
<title>GLPI - <?php echo __('My Dashboard','dashboard'); ?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />

<script language="javascript" src="../js/jquery.min.js"></script>
<link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
<script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>

<script src="../js/highcharts.js"></script>
<script src="../js/modules/exporting.js"></script>
<script src="../js/modules/no-data-to-display.js"></script>
<script src="../js/bootstrap-datepicker.js"></script>

<link href="../css/datepicker.css" rel="stylesheet" type="text/css">

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

#entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

//select entity
if($sel_ent == '' || $sel_ent == -1) {

	//get all user entities
	$entities = $_SESSION['glpiactiveentities'];
	//$entities[] = $user_ent;
	$ent = implode(",",$entities);

	$entidade = "AND glpi_users.entities_id IN (".$ent.")";
	$entidade_age = "AND glpi_tickets.entities_id IN (".$ent.")";
	$entidade1 = "";

}
else {
	$entidade = "AND glpi_users.entities_id IN (".$sel_ent.")";
	$entidade_age = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
}

//seleciona técnico
$sql_tec = "
SELECT DISTINCT glpi_users.`id` AS id , glpi_users.`firstname` AS name, glpi_users.`realname` AS sname
FROM `glpi_users` , glpi_tickets_users
WHERE glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets_users.type = 2
AND glpi_users.id = ".$_SESSION['glpiID']."
".$entidade."
ORDER BY `glpi_users`.`firstname` ASC ";

$result_tec = $DB->query($sql_tec);
$tec = $DB->fetch_assoc($result_tec);


// lista de tecnicos
function dropdown( $name, array $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select style="width: 300px; height: 27px;" autofocus onChange="javascript: document.form1.submit.focus()" name="'.$name.'" id="'.$name.'">'."\n";

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

$DB->data_seek($result_tec, 0) ;

while ($row_result = $DB->fetch_assoc($result_tec))
{
	$v_row_result = $row_result['id'];
	$arr_tec[$v_row_result] = $row_result['name']." ".$row_result['sname'] ;
}

?>

<div id='content' >
<div id='container-fluid' style="margin: 0px 5% 0px 5%;">

<div id="pad-wrapper" >
<div id="charts" class="fluid chart">
<div id="head" class="fluid">

<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:8%;"></i><span></span></a>

<div id="titulo_graf" >

<?php echo __('My Dashboard','dashboard') ; ?>
<span style="color:#8b1a1a; font-size:35pt; font-weight:bold;">  </span> </div>

<?php

// lista de técnicos
$res_tec = $DB->query($sql_tec);
$arr_tec = array();
$arr_tec[0] = "-- ". __('Select a technician','dashboard') . " --" ;

$DB->data_seek($result_tec, 0);

while ($row_result = $DB->fetch_assoc($result_tec))
{
	$v_row_result = $row_result['id'];
	$arr_tec[$v_row_result] = $row_result['name']." ".$row_result['sname'] ;
}

$name = 'sel_tec';
$options = $arr_tec;
$selected = 0;
?>

<div id="datas-tec" class="fluid" >
<form id="form1" name="form1" class="form1" method="post" action="?date1=<?php echo $data_ini ?>&date2=<?php echo $data_fin ?>&con=1">

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
			<td style="margin-top:2px;"></td>
		</tr>
		<tr><td height="15px"></td></tr>
		<tr>
			<td colspan="2" align="center" style="">
			<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?></button>
			<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='graf_tech.php'" > <i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button></td>
			</td>
		</tr>
	</table>
<?php Html::closeForm(); ?>

</div>
</div>

<!-- DIV's -->
<script type="text/javascript" >
	$(document).ready(function() { $("#sel_tec").select2({dropdownAutoWidth : true}); });
</script>

<?php

$con = $_GET['con'];

if($con == "1") {

if(!isset($_POST['date1']))
{
	//$data_ini2 = $_GET['date1'];
	//$data_fin2 = $_GET['date2'];
	$data_ini2 = date("Y-01-01");
	$data_fin2 = date("Y-m-d");
}

else {
	$data_ini2 = $_POST['date1'];
	$data_fin2 = $_POST['date2'];
}

if(!isset($_POST["sel_tec"])) {
	$id_tec = $_SESSION['glpiID'];
}

else {
	$id_tec = $_SESSION['glpiID'];
}

if($id_tec == 0) {
 echo '<script language="javascript"> alert(" ' . __('Select a technician','dashboard') . ' "); </script>';
 echo '<script language="javascript"> location.href="graf_tech.php"; </script>';
}

// nome do tecnico
$sql_nm = "
SELECT DISTINCT glpi_users.`id` AS id , glpi_users.`firstname` AS name, glpi_users.`realname` AS sname, glpi_users.picture 
FROM `glpi_users` , glpi_tickets_users
WHERE glpi_tickets_users.users_id = glpi_users.id
AND glpi_users.id = ".$id_tec."
AND glpi_tickets_users.type = 2
ORDER BY `glpi_users`.`firstname` ASC ";

$result_nm = $DB->query($sql_nm);
$tec_name = $DB->fetch_assoc($result_nm);


if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}

//total tickets
$query_total = "SELECT COUNT(glpi_tickets.id) AS total
FROM glpi_tickets_users, glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND (glpi_tickets.date ".$datas." OR glpi_tickets.closedate ".$datas." )
AND glpi_tickets_users.users_id = ".$id_tec."
AND glpi_tickets_users.type = 2
".$entidade_age."
AND glpi_tickets_users.tickets_id = glpi_tickets.id ";

$result_total = $DB->query($query_total);
$total_cham = $DB->fetch_assoc($result_total);

//count by status
$query_stat = "
SELECT
SUM(case when glpi_tickets.status = 1 then 1 else 0 end) AS new,
SUM(case when glpi_tickets.status = 2 then 1 else 0 end) AS assig,
SUM(case when glpi_tickets.status = 3 then 1 else 0 end) AS plan,
SUM(case when glpi_tickets.status = 4 then 1 else 0 end) AS pend,
SUM(case when glpi_tickets.status = 5 then 1 else 0 end) AS solve,
SUM(case when glpi_tickets.status = 6 then 1 else 0 end) AS close
FROM glpi_tickets_users, glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND (glpi_tickets.date ".$datas." OR glpi_tickets.closedate ".$datas." )
AND glpi_tickets_users.users_id = ".$id_tec."
AND glpi_tickets_users.type = 2
".$entidade_age."
AND glpi_tickets_users.tickets_id = glpi_tickets.id ";

$result_stat = $DB->query($query_stat);

$new = $DB->result($result_stat,0,'new') + 0;
$assig = $DB->result($result_stat,0,'assig') + 0;
$plan = $DB->result($result_stat,0,'plan') + 0;
$pend = $DB->result($result_stat,0,'pend') + 0;
$solve = $DB->result($result_stat,0,'solve') + 0;
$close = $DB->result($result_stat,0,'close') + 0;

echo '<div id="entidade2" class="col-md-12 fluid" style="margin-bottom: 15px;">';
echo '<div id="name"  style="margin-top: 15px;">
<span><img class="avatar2" width="43px" height="45px" src="'.User::getURLForPicture($tec_name["picture"]).'"></img>&nbsp;&nbsp;</span>
<span>'.$tec_name['name'].' '.$tec_name['sname'].'</span> - <span class="total_tech"> '.$total_cham['total'].' '.__('Tickets','dashboard').'</span></div>
<div class="row" style="margin: 10px 0px 0 0;" >
<div style="margin-top: 20px; height: 45px;">
		<!-- COLUMN 1 -->															
		  <div class="col-sm-3 col-md-3 stat" >
			 <div class="dashbox shad panel panel-default db-blue">
				<div class="panel-body_2">
				   <div class="panel-left red bluebg" style = "margin-top: -5px; margin-left: -5px;">
						<i class="fa fa-tags fa-3x fa2"></i>
				   </div>
				   <div class="panel-right">
				     <div id="odometer1" class="odometer" style="font-size: 22px; margin-top: 1px;">  </div><p></p>
      				<span class="chamado">'. __('Tickets','dashboard').'</span><br>
      				<span class="date" style="font-size: 16px;"><b>'. _x('status', 'New').' + '.__('Assigned').'</b></span>												
				   </div>
				</div>
			 </div>
		  </div>
		  
		  <div class="col-sm-3 col-md-3">
			 <div class="dashbox shad panel panel-default db-orange">
				<div class="panel-body_2">
				   <div class="panel-left orange orangebg " style = "margin-top: -5px; margin-left: -5px;">
						<i class="fa fa-clock-o fa-3x fa2"></i>
				   </div>
				   <div class="panel-right">										 
					<div id="odometer2" class="odometer" style="font-size: 22px; margin-top: 1px;">   </div><p></p>
      				<span class="chamado">'. __('Tickets','dashboard').'</span><br>
      				<span class="date"><b>'. __('Pending').'</b></span>
				   </div>
				</div>
			 </div>
		  </div>																		
   								
		  <div class="col-sm-3 col-md-3">
			 <div class="dashbox shad panel panel-default db-red">
				<div class="panel-body_2">
				   <div class="panel-left yellow redbg" style = "margin-top: -5px; margin-left: -5px;">
						<i class="fa fa-check-square fa-3x fa2"></i>
				   </div>
				   <div class="panel-right">
						<div id="odometer3" class="odometer" style="font-size: 22px; margin-top: 1px;">   </div><p></p>
      				<span class="chamado">'. __('Tickets','dashboard').'</span><br>
      				<span class="date"><b>'. __('Solved','dashboard').'</b></span>
				   </div>										   
				</div>
			 </div>
		  </div>
		  <div class="col-sm-3 col-md-3">
			 <div class="dashbox shad panel panel-default db-yellow">
				<div class="panel-body_2">
				   <div class="panel-left yellow yellowbg" style = "margin-top: -5px; margin-left: -5px;">
						<i class="fa fa-times-circle fa-3x fa2"></i>
				   </div>
		   		<div class="panel-right">
						<div id="odometer4" class="odometer" style="font-size: 22px; margin-top: 1px;">   </div><p></p>
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
				
					<div id="graf_linhas" class="col-md-12 col-sm-12" style="height: 450px; margin-top: 20px !important; margin-left: 0px;">
						<?php  include ("./inc/graflinhas_tec.inc.php"); ?>
					</div>
				
					<div id="graf2" class="col-md-6 col-sm-6" >
						<?php  include ("./inc/grafpie_stat_tec.inc.php");  ?>
					</div>
				
					<div id="graf_tipo" class="col-md-6 col-sm-6" style="margin-left: 0%;">
						<?php include ("./inc/grafpie_tipo_tec.inc.php");  ?>
					</div>
				
					<div id="graf4" class="col-md-12 col-sm-12" style="height: 450px; margin-left: 0px;">
						<?php include ("./inc/grafcat_tec.inc.php"); ?>
					</div>
				
					<div id="graf_time" class="col-md-6 col-sm-6">
						<?php include ("./inc/grafbar_age_tech.inc.php");  ?>
					</div>
				
					<div id="graf_prio" class="col-md-6 col-sm-6" style="margin-left: 0%;">
						<?php include ("./inc/grafpie_prio_tech.inc.php");  ?>
					</div>
				
					<div id="graf_time1" class="col-md-6 col-sm-6 col-sm-6" style="height: 450px; margin-top:30px; margin-bottom:0px; margin-left: 0px;">			
						<?php  include ("./inc/grafpie_time_tech.inc.php"); ?>
					</div>
		
					<div id="graf_source" class="col-md-6 col-sm-6 col-sm-6" style="height: 450px; margin-top:30px; margin-bottom:0px; margin-left: 0px;">						
						<?php  include ("./inc/grafpie_origem_tech.inc.php"); ?>
					</div>	
				
					<div id="graf_sat" class="col-md-12 col-sm-12" style="height: 450px; margin-top:20px !important; margin-bottom:30px; margin-left: 0px;">
						<?php include ("./inc/grafcol_sat_tec.inc.php"); ?>
					</div>
				
					<?php				
						}
					?>

				</div>
			</div>
		</div>
	</div>
</div>

<!-- Highcharts export xls, csv -->
<script src="../js/export-csv.js"></script>

</body>
</html>
