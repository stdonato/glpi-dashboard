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
<title>GLPI - <?php echo __('Charts','dashboard'). " " . __('by Group','dashboard'); ?></title>
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
if(!isset($_POST["sel_grp"])) {
	$id_grp = $_GET["grp"];	
}

else {
	$id_grp = $_POST["sel_grp"];
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

	$entidade = "WHERE entities_id IN (".$ent.") OR is_recursive = 1 ";
	$entidade_and = "AND glpi_tickets.entities_id IN (".$ent.") ";
	$entidade_pro = "AND glpi_problems.entities_id IN (".$ent.") ";
	$entidade_age = "AND glpi_tickets.entities_id IN (".$ent.")";
	$entidade1 = "";	
}

else {
	$entidade = "WHERE entities_id IN (".$sel_ent.") OR is_recursive = 1 ";
	$entidade_and = "AND glpi_tickets.entities_id IN (".$sel_ent.") ";
	$entidade_pro = "AND glpi_problems.entities_id IN (".$sel_ent.") ";
	$entidade_age = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
}

//seleciona grupo
$sql_grp = "
SELECT id, name
FROM `glpi_groups`
".$entidade."
ORDER BY `name` ASC ";

$result_grp = $DB->query($sql_grp);
$grp = $DB->fetch_assoc($result_grp);


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


$res_grp = $DB->query($sql_grp);
$arr_grp = array();
$arr_grp[0] = "-- ". __('Select a group','dashboard') . " --" ;

$DB->data_seek($result_grp, 0);

while ($row_result = $DB->fetch_assoc($result_grp))		
{ 
	$v_row_result = $row_result['id'];
	$arr_grp[$v_row_result] = $row_result['name'] ;			
} 	 

$name = 'sel_grp';
$options = $arr_grp;
$selected = $id_grp;

?>
<div id='content' >
<div id='container-fluid' style="margin: 0px 5% 0px 5%;"> 
<div id="pad-wrapper" >
<div id="charts" class="fluid chart"> 
<div id="head" class="fluid">


<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>
	
<div id="titulo_graf">
   <?php echo __('Tickets','dashboard') ." ". __('by Group','dashboard'); ?> 
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
			echo dropdown( $name, $options, $selected );
			?>
			</td>
		</tr>
		<tr><td height="15px"></td></tr>
		<tr>
			<td colspan="2" align="center" style="">
				<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?></button>
				<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='graf_grupo.php'" > <i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button></td>
			</td>
		</tr>	
	</table>		
	<?php Html::closeForm(); ?>
	<!-- </form> -->

</div>
</div>

<!-- DIV's -->

<script type="text/javascript" >
	$(document).ready(function() { $("#sel_grp").select2({dropdownAutoWidth : true});});
				
	$('#dp1').datepicker('update');
	$('#dp2').datepicker('update');
</script>

<?php

if(isset($_REQUEST['con'])) {
	$con = $_REQUEST['con'];
}
else { $con = ''; }

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


if($id_grp == "0") {
	echo '<script language="javascript"> alert(" ' . __('Select a group','dashboard') . ' "); </script>';
	echo '<script language="javascript"> location.href="graf_grupo.php"; </script>';
}


if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";	
}	

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}

// nome do grupo
$sql_nm = "
SELECT id, name
FROM `glpi_groups`
WHERE id = ".$id_grp." ";

$result_nm = $DB->query($sql_nm);
$grp_name = $DB->fetch_assoc($result_nm);

//quant de chamados
$query_quant = "
SELECT count(*) AS total
FROM `glpi_groups_tickets` , glpi_tickets, glpi_groups
WHERE glpi_groups_tickets.`groups_id` = ".$id_grp."
AND glpi_groups_tickets.`groups_id` = glpi_groups.id
AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas."
". $entidade_age ." ";

$result_quant = $DB->query($query_quant);
$total = $DB->fetch_assoc($result_quant);

echo '<div id="entidade" class="col-md-12 col-sm-12 fluid" >';
echo $grp_name['name']." - <span> ".$total['total']." ".__('Tickets','dashboard')."</span>";
echo "</div>";
 ?>


<div id="graf_linhas" class="col-md-12" style="height: 450px; margin-left: 0px;">
	<?php include ("./inc/graflinhas_grupo.inc.php"); ?>
</div>

<div id="graf2" class="col-md-6" >
	<?php  include ("./inc/grafpie_stat_grupo.inc.php"); ?>
</div>

<div id="graf_tipo" class="col-md-6" style="margin-left: 0%;">
	<?php include ("./inc/grafpie_tipo_grupo.inc.php");  ?>
</div>	

<div id="graf4" class="col-md-12" style="height: 450px; margin-left: 0px;">
	<?php include ("./inc/grafcat_grupo.inc.php"); ?>
</div>

<div id="graf_time" class="col-md-6">
	<?php include ("./inc/grafbar_age_group.inc.php");  ?>
</div>

<div id="graf_prio" class="col-md-6" style="margin-left: 0%;">
	<?php include ("./inc/grafpie_prio_group.inc.php");  ?>
</div>

<div id="graf_user" class="col-md-12" style="height: 450px; margin-top:30px; margin-bottom:120px; margin-left: 0px;">
	<?php  include ("./inc/grafbar_user_grupo.inc.php"); ?>
</div>

<div id="graf_time1" class="col-md-12" style="height: 450px; margin-top:20px; margin-left: 0px;">
	<?php  include ("./inc/grafpie_time_grupo.inc.php"); ?>
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
