<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");

Session::checkLoginUser();

$mydate = isset($_POST["date1"]) ? $_POST["date1"] : "";

?>

<html> 
<head>
<title>GLPI - <?php echo __('Tickets','dashboard') .'  '. __('by Technician','dashboard').'s'  ?></title>
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
<script src="../js/modules/exporting.js"></script>

<script src="../js/jquery-ui.min.js"></script>
<script src="../js/bootstrap-datepicker.js"></script>
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>
<?php echo '<script src="../js/themes/'.$_SESSION['charts_colors'].'"></script>'; ?>

</head>

<body style="background-color: #e5e5e5; margin-left:0%;">

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
<div id='content' >
<div id='container-fluid' style="margin: 0px 5% 0px 5%;"> 
<div id="charts" class="fluid chart"> 
<div id="head" class="fluid">
	<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

<div id="titulo_graf" style="margin-bottom:45px;"> <?php echo __('Tickets','dashboard') .'  '. __('by Technician','dashboard').'s'  ?>  
<div id="datas" class="col-md-12" > 
<form id="form1" name="form1" class="form1" method="post" action="?date1=<?php echo $data_ini ?>&date2=<?php echo $data_fin ?>"> 

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
			<td style="width: 300px;">		
			<?php	
			$url = $_SERVER['REQUEST_URI'];
			$arr_url = explode("?", $url);
			$url2 = $arr_url[0];		    
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
	<tr height="12px" ><td></td></tr>
	<tr align="center">
		<td>
			<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?> </button>
			<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" ><i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button>
		</td>
	</tr>
</table>	
<p>
</p>
<?php Html::closeForm(); ?>
<!-- </form> -->
</div>
</div>
</div>

<div id="graf1">
	<div id="graf1" class="row-fluid">
		<?php include ("./inc/grafbar_tec_mes.inc.php"); ?>
	</div>
</div>
</div>
</div>

<!-- Highcharts export xls, csv -->
<script src="../js/export-csv.js"></script>

</body>
</html>
