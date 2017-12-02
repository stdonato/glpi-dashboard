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
<title>GLPI - <?php echo __('Charts','dashboard'). " " . __('by Category','dashboard'); ?></title>
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

$ano = date("Y");
$month = date("Y-m");
$datahoje = date("Y-m-d");

//cat
if(!isset($_POST["sel_cat"])) {
	$id_cat = $_REQUEST["sel_cat"];
}

else {
	$id_cat = $_POST["sel_cat"];
}

# entity
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
	$ent = implode(",",$entities);

	$entidade = "AND glpi_tickets.entities_id IN (".$ent.")";
	$entidade_cw = "WHERE (entities_id IN (".$ent.") OR is_recursive = 1)";	
}
else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
	$entidade_cw = "WHERE (entities_id IN (".$sel_ent.") OR is_recursive = 1)";	
}

// lista
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

// lista de categorias
$sql_cat = "
SELECT id, completename AS name
FROM `glpi_itilcategories`
". $entidade_cw ."
ORDER BY `name` ASC ";

$result_cat = $DB->query($sql_cat);

$arr_cat = array();
$arr_cat[0] = "-- ". __('Select a category', 'dashboard') . " --" ;

while ($row_result = $DB->fetch_assoc($result_cat))
{
	$v_row_result = $row_result['id'];
	$arr_cat[$v_row_result] = $row_result['name'] ;
}

$name = 'sel_cat';
$options = $arr_cat;
$selected = $id_cat;

?>
<div id='content' >
<div id='container-fluid' style="margin: 0px 5% 0px 5%;">
<div id="pad-wrapper" >

<div id="charts" class="fluid chart">
	<div id="head" class="fluid">

		<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>
		<div id="titulo_graf" >
		   <?php echo __('Tickets','dashboard') ." ". __('by Category','dashboard'); ?>
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

					<script language="Javascript">

						$('#dp1').datepicker('update');
						$('#dp2').datepicker('update');

					</script>
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
							<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='graf_categoria.php'" > <i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button></td>
						</td>
					</tr>

				</table>
			<?php Html::closeForm(); ?>
			<!-- </form> -->
		</div>
	</div>
<!-- DIV's -->
<script type="text/javascript" >
	$(document).ready(function() { $("#sel_cat").select2({dropdownAutoWidth : true}); });
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

if($id_cat == " ") {
	echo '<script language="javascript"> alert(" ' . __('Select a category','dashboard') . ' "); </script>';
	echo '<script language="javascript"> location.href="graf_categoria.php"; </script>';
}

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}

// nome da categoria
$sql_nm = "
SELECT id, completename AS cname, name
FROM glpi_itilcategories
WHERE id = ".$id_cat." ";

$result_nm = $DB->query($sql_nm);
$ent_name = $DB->fetch_assoc($result_nm);

//quant chamados
$query2 = "
SELECT COUNT(glpi_tickets.id) as total
FROM glpi_tickets
WHERE glpi_tickets.date ".$datas."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.itilcategories_id = ".$id_cat."
". $entidade ."  ";

$result2 = $DB->query($query2) or die('erro1');
$total = $DB->fetch_assoc($result2);

echo '<div id="entidade" class="col-md-12 fluid">';
echo $ent_name['name']." - <span> ".$total['total']." ".__('Tickets','dashboard')."</span>
</div>";
 ?>

<div id="graf_linhas" class="col-md-12 col-sm-12" style="height: 450px; margin-left: -5px;">
	<?php include ("./inc/graflinhas_cat.inc.php"); ?>
</div>

<div id="graf2" class="col-md-6 col-sm-6" >
	<?php include ("./inc/grafpie_stat_cat.inc.php"); ?>
</div>

<div id="graf_tipo" class="col-md-6 col-sm-6" style="margin-left: 0%;">
	<?php include ("./inc/grafpie_tipo_cat.inc.php");  ?>
</div>

<div id="graf3" class="col-md-12 col-sm-12" >
	<?php  include ("./inc/grafbar_cat_user.inc.php");  ?>
</div>

<div id="grafcat_tec" class="col-md-12 col-sm-12" style="height: 450px; margin-top: 240px; margin-left: 0px;">
	<?php  include ("./inc/grafbar_cat_tec.inc.php");
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
