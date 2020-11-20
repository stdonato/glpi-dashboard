<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");

Session::checkLoginUser();
Session::checkRight("profile", READ);

$mydate = isset($_POST["date1"]) ? $_POST["date1"] : "";
?>

<html>
<head>
<title>GLPI - <?php echo __('Tickets') .'  '. __('by Assets','dashboard').'s' ?></title>
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
<script src="../js/modules/exporting.js"></script>
<script src="../js/modules/no-data-to-display.js"></script>

<script src="../js/bootstrap-datepicker.js"></script>
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">

<link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
<script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>
<?php echo '<script src="../js/themes/'.$_SESSION['charts_colors'].'"></script>'; ?>

<style type="text/css">
	#select2-chosen-1 { color: #555; }
	.select2-chosen { color: #555; }
</style>

</head>

<?php

if(!empty($_POST['submit']))
{
	$data_ini =  $_POST['date1'];
	$data_fin = $_POST['date2'];
}

else {
	$data_ini = date("Y-m-01");
	$data_fin = date("Y-m-d");
}

$month = date("Y-m");
$datahoje = date("Y-m-d");
?>

<body style="background-color: #e5e5e5; margin-left:0%;">
<div id='content' >
<div id='container-fluid' style="margin: 0px 5% 0px 5%;">
<div id="pad-wrapper" >
<div id="charts" class="fluid chart">
<div id="head" class="fluid">

	<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

	<div id="titulo_graf"> <?php echo __('Tickets') .'  '. __('by Assets','dashboard');  ?></div>
		<div id="datas-tec" class="col-md-12 fluid" >
			<form id="form1" name="form1" class="form2" method="post" action="?con=1&date1=<?php echo $data_ini ?>&date2=<?php echo $data_fin ?>">
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
					<?php echo "
					<select id='sel_item' name='sel_item' style='color:#000; width: 280px; height: 27px;' autofocus onChange='javascript: document.form1.submit.focus()' >
						<option value='0'> -- ".__('Select a asset','dashboard')." -- </option>
						<option value='1'>".__('Computer')."</option>
						<option value='2'>".__('Monitor')."</option>
						<option value='3'>".__('Software')."</option>
						<option value='4'>".__('Network')."</option>
						<option value='5'>".__('Device')."</option>
						<option value='6'>".__('Printer')."</option>
						<option value='7'>".__('Phone')."</option>
					</select> ";
					?>
				</td>
				</tr>
				<tr><td height="15px"></td></tr>
				<tr>
					<td colspan="2" align="center">
						<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?> </button>
						<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='ativos.php'" ><i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button>
					</td>
				</tr>
				</table>
			<?php Html::closeForm(); ?>
	</div>
</div>

<script language="Javascript">
	$('#dp1').datepicker('update');
	$('#dp2').datepicker('update');
</script>

<div id="graf1" class="fluid">
<?php

if(isset($_REQUEST['con']) && $_REQUEST['con'] == 1 ) {

	if(isset($_REQUEST['sel_item']) && $_REQUEST['sel_item'] == '0' ) {
		//$type = $_REQUEST['itemtype'];
		echo '<script language="javascript"> alert(" ' . __('Select a asset','dashboard') . ' "); </script>';

		}

	else {

		$itemtype = $_REQUEST['sel_item'];

		switch ($itemtype) {
	    case "1": $type = 'computer'; break;
	    case "2": $type = 'monitor'; break;
	    case "3": $type = 'software'; break;
	    case "4": $type = 'networkequipment'; break;
	    case "5": $type = 'peripheral'; break;
	    case "6": $type = 'printer'; break;
	    case "7": $type = 'phone'; break;
	}
}


include ("./inc/grafbar_ativo_mes.inc.php");

}
?>
</div>

</div>

<script type="text/javascript" >
	$(document).ready(function() { $("#sel_item").select2({dropdownAutoWidth : true}); });
</script>

</div>
</div>
</div>

<!-- Highcharts export xls, csv -->
<script src="../js/export-csv.js"></script>

</body>
</html>
