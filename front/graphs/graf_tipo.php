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
<title>GLPI - <?php echo __('Charts','dashboard'). " " . __('by Type','dashboard'); ?></title>
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
<link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
<script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>

<script src="../js/highcharts.js"></script>
<script src="../js/modules/exporting.js"></script>
<script src="../js/modules/no-data-to-display.js"></script>

<script src="../js/bootstrap-datepicker.js"></script>    
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>
<?php echo '<script src="../js/themes/'.$_SESSION['charts_colors'].'"></script>'; ?>

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
	$data_ini = date("Y-m-01");
	$data_fin = date("Y-m-d");
} 

//group
if(!isset($_POST["sel_type"])) {
	$id_tip = $_GET["sel_type"];	
}

else {
	$id_tip = $_POST["sel_type"];
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

	$query_ent1 = "
	SELECT entities_id
	FROM glpi_users
	WHERE id = ".$_SESSION['glpiID']." ";
	
	$res_ent1 = $DB->query($query_ent1);
	$user_ent = $DB->result($res_ent1,0,'entities_id');

	//get all user entities
	$entities = $_SESSION['glpiactiveentities'];
	$entities[] = $user_ent;
	$ent = implode(",",$entities);

	$entidade = "WHERE entities_id IN (".$ent.")  ";
	$entidade_a = "AND glpi_tickets.entities_id IN (".$ent.")";
	$entidade_age = "AND glpi_tickets.entities_id IN (".$ent.")";
	$entidade1 = "";
	
}

else {
	$entidade = "WHERE entities_id IN (".$sel_ent.") ";
	$entidade_a = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
	$entidade_age = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
}

// lista de grupos

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

?>
<div id='content' >
<div id='container-fluid' style="margin: 0px 5% 0px 5%;"> 
<div id="pad-wrapper" >
<div id="charts" class="fluid chart"> 
<div id="head" class="fluid">

<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>
	
<div id="titulo_graf">
   <?php echo __('Tickets','dashboard') ." ". __('by Type','dashboard'); ?> 
	<span style="color:#8b1a1a; font-size:35pt; font-weight:bold;"> </span> 
</div>
<div id="datas-tec" class="col-md-12 col-sm-12 fluid" > 
	<form id="form1" name="form1" class="form2" method="post" action="?date1=<?php echo $data_ini ?>&date2=<?php echo $data_fin ?>&con=1"> 
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
			</td>
			<td style="margin-top:2px;">
			<?php
					
			// lista de tipos		
			$arr_tip = array();
			$arr_tip[0] = "-- ". __('Select a type','dashboard') . " --" ;
			$arr_tip[1] = __('Incident') ;
			$arr_tip[2] = __('Request');	
			//$arr_tip[3] = __('All');		
			$name = 'sel_type';
			$options = $arr_tip;
			$selected = $id_tip;
			
			echo dropdown( $name, $options, $selected );
			
			?>
			</td>
		</tr>
		<tr><td height="15px"></td></tr>
		<tr>
			<td colspan="2" align="center" style="">
				<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?></button>
				<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='graf_tipo.php'" > <i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button></td>
			</td>
		</tr>	
	</table>		
	<?php Html::closeForm(); ?>
	<!-- </form> -->

</div>
</div>

<!-- DIV's -->

<script type="text/javascript" >
	$(document).ready(function() { $("#sel_type").select2({dropdownAutoWidth : true});});				
	$('#dp1').datepicker('update');
	$('#dp2').datepicker('update');
</script>

<?php

if(isset($_REQUEST['con'])) {
	$con = $_REQUEST['con'];
}
else { $con = ''; }
if($con == "1") {
	
if(!isset($_POST["sel_type"])) {
	$id_tip = $_GET["sel_type"];	
}

else {
	$id_tip = $_POST["sel_type"];
}	

if($id_tip == "0") {
	echo '<script language="javascript"> alert(" ' . __('Select a type','dashboard') . ' "); </script>';
	echo '<script language="javascript"> location.href="graf_tipo.php"; </script>';
}

if(!isset($_POST['date1']))
{	
	$data_ini2 = $_GET['date1'];	
	$data_fin2 = $_GET['date2'];
}

else {	
	$data_ini2 = $_POST['date1'];	
	$data_fin2 = $_POST['date2'];	
}  


if($data_ini2 == $data_fin2) {
	$datas = "LIKE '".$data_ini2."%'";	
}	

else {
	$datas = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";	
}

//type
if($id_tip == 1) { 
	$type = __('Incident');	 
}
else {
	$type = __('Request'); 
}


//quant de chamados
$query_quant = "
SELECT count(glpi_tickets.id) AS total
FROM glpi_tickets	
".$entidade."
AND glpi_tickets.date ".$datas."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.type =".$id_tip." ";

$result_quant = $DB->query($query_quant);
$total = $DB->fetch_assoc($result_quant);

echo '<div id="entidade" class="col-md-12 col-sm-12 fluid" >';
echo $type." - <span> ".$total['total']." ".__('Tickets','dashboard')."</span>";
echo "</div>";

?>

<div id="graf_linhas" class="col-md-12 col-sm-12" style="height: 450px; margin-left: 0px;">
	<?php include ("./inc/graflinhas_tipo.inc.php"); ?>
</div>

<div id="graf2" class="col-md-6 col-sm-6" style="height: 450px; margin-left: 0px;">
	<?php  include ("./inc/grafpie_stat_tipo.inc.php"); ?>
</div>

<div id="graf_time1" class="col-md-6 col-sm-6" style="height: 450px; margin-top:30px; margin-left: 0px;">
	<?php  include ("./inc/grafpie_time_tipo.inc.php"); ?>
</div>

<div id="graf4" class="col-md-12 col-sm-12" style="height: 450px; margin-left: 0px;">
	<?php include ("./inc/grafcat_tipo.inc.php"); ?>
</div>

<div id="graf_time" class="col-md-6 col-sm-6">
	<?php include ("./inc/grafbar_age_tipo.inc.php");  ?>
</div>

<div id="graf_prio" class="col-md-6 col-sm-6" style="margin-left: 0%;">
	<?php include ("./inc/grafpie_prio_tipo.inc.php");  ?>
</div>

<div id="graf_user" class="col-md-12 col-sm-12" style="height: 450px; margin-top:30px; margin-bottom:120px; margin-left: 0px;">
	<?php  include ("./inc/grafbar_user_tipo.inc.php"); ?>
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
