<?php
include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");
include "../inc/functions.php";

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if(!empty($_POST['submit']))
{
	$data_ini =  $_REQUEST['date1'];
	$data_fin = $_REQUEST['date2'];
}

else {
	$data_ini = date("Y-01-01");
	$data_fin = date("Y-m-d");
}

# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

//select entity
if($sel_ent == '' || $sel_ent == -1) {

	$entities = $_SESSION['glpiactiveentities'];	
	$ent = implode(",",$entities);
	$entidade = "AND glpi_tickets.entities_id IN (".$ent.") ";
}

else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.") ";
}

?>

<html>
<head>
<title> GLPI - <?php echo __('Tickets', 'dashboard') .'  '. __('by Location', 'dashboard') ?> </title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />
<meta charset="utf-8">

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />

<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />

<script language="javascript" src="../js/jquery.min.js"></script>
<link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
<script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>
<script src="../js/bootstrap-datepicker.js"></script>
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">

<script src="../js/media/js/jquery.dataTables.min.js"></script>
<link href="../js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />
<script src="../js/media/js/dataTables.bootstrap.js"></script>

<script src="../js/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.html5.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="../js/extensions/Buttons/js/buttons.print.min.js"></script>
<script src="../js/media/pdfmake.min.js"></script>
<script src="../js/media/vfs_fonts.js"></script>
<script src="../js/media/jszip.min.js"></script>

<script src="../js/extensions/Select/js/dataTables.select.min.js"></script>
<link href="../js/extensions/Select/css/select.bootstrap.css" type="text/css" rel="stylesheet" />

<style type="text/css">
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
   a:hover { color: #000099;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>

</head>

<body style="background-color: #e5e5e5;">

<div id='content' >
	<div id='container-fluid' style="margin:  <?php echo margins(); ?> ;">
		<div id="charts" class="fluid chart">
		<div id="pad-wrapper" >
		<div id="head-rel" class="fluid">
		<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

	<div id="titulo_rel"> <?php echo __('Tickets', 'dashboard') .'  '. __('by Location', 'dashboard') ?> </div>
	<div id="datas-tec" class="col-md-12 col-sm-12 fluid" >

	<form id="form1" name="form1" class="form_rel" method="post" action="rel_localidades.php?con=1" style="margin-left: 37%;">
	<table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef" >
		<tr>
			<td style="width: 310px;">
			<?php
			$url = $_SERVER['REQUEST_URI'];
			$arr_url = explode("?", $url);
			$url2 = $arr_url[0];
		
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
	
			</td>
		</tr>
		<tr><td height="15px"></td></tr>
		<tr>
			<td colspan="2" align="center">
				<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?> </button>
				<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" ><i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button>
			</td>
		</tr>
	</table>
<?php Html::closeForm(); ?>
<!-- </form> -->
		</div>
	</div>

<?php

//localidades
if(isset($_GET['con'])){$con = $_GET['con'];}
else {$con = '';}

if($con == "1") {

if(!isset($_POST['date1']))
{
	$data_ini2 = $_REQUEST['date1'];
	$data_fin2 = $_REQUEST['date2'];
}

else {
	$data_ini2 = $_POST['date1'];
	$data_fin2 = $_POST['date2'];
}

if($data_ini2 == $data_fin2) {
	$datas2 = "LIKE '".$data_ini2."%'";
}

else {
	$datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";
}

//status
$status = "";
$status_open = "('2','1','3','4')";
$status_close = "('5','6')";
$status_all = "('2','1','3','4','5','6')";

if(isset($_GET['stat'])) {

	if($_GET['stat'] == "open") {
		$status = $status_open;
	}
	elseif($_GET['stat'] == "close") {
		$status = $status_close;
	}
	else {
		$status = $status_all;
	}
}

else {
	$status = $status_all;
	}

//select location with tickets
$sql_loc = 
"SELECT count(glpi_tickets.id) AS total, glpi_locations.name AS name, glpi_locations.id AS id
FROM glpi_tickets, glpi_locations
WHERE glpi_locations.id = glpi_tickets.locations_id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
".$entidade."
GROUP BY name
ORDER BY total DESC ";			

$result_loc = $DB->query($sql_loc);	
$conta_cons = $DB->numrows($result_loc);
//$chamados = $data_cham['total'];	


//listar chamados
echo "<div class='well info_box fluid col-md-12 report' style='margin-left: -1px;'>";
echo "
	<table id='local' class='display' style='font-size: 13px; font-weight:bold;' cellpadding = 2px >
		<thead>
			<tr>
				<th style='text-align:center; cursor:pointer;'> ". _n('Location','Locations',2) ." </th>
				<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Tickets')." </th>
				<th style='text-align:center; cursor:pointer;'> ". __('Opened','dashboard') ."</th>
				<th style='text-align:center; cursor:pointer;'> ". __('Solved','dashboard') ."</th>	
				<th style='text-align:center; cursor:pointer;'> ". __('Closed','dashboard') ."</th>									
				<th style='text-align:center; '> % ". __('Closed','dashboard') ."</th> 
			</tr>
		</thead>
	<tbody>";

while($id_loc = $DB->fetch_assoc($result_loc)){
	
//tickets
$sql_cham = "SELECT count( glpi_tickets.id ) AS total, glpi_locations.id AS id
FROM glpi_locations, glpi_tickets
WHERE glpi_tickets.locations_id = glpi_locations.id
AND glpi_tickets.is_deleted = 0
AND glpi_locations.id = ".$id_loc['id']."
AND glpi_tickets.date ".$datas2."
". $entidade ."  ";

$result_cham = $DB->query($sql_cham) or die ("erro_cham");
$data_cham = $DB->fetch_assoc($result_cham);

$chamados = $data_cham['total'];


//chamados abertos
$sql_ab = "SELECT count( glpi_tickets.id ) AS total, glpi_locations.id AS id
FROM glpi_locations, glpi_tickets
WHERE glpi_tickets.locations_id = glpi_locations.id
AND glpi_tickets.is_deleted = 0
AND glpi_locations.id = ".$id_loc['id']."
AND glpi_tickets.status NOT IN ".$status_close."
AND glpi_tickets.date ".$datas2."
". $entidade ."  ";

$result_ab = $DB->query($sql_ab) or die ("erro_ab");
$data_ab = $DB->fetch_assoc($result_ab);

$abertos = $data_ab['total'];


//chamados solucionados
$sql_sol = "SELECT count( glpi_tickets.id ) AS total, glpi_locations.id AS id
FROM glpi_locations, glpi_tickets
WHERE glpi_tickets.locations_id = glpi_locations.id
AND glpi_tickets.is_deleted = 0
AND glpi_locations.id = ".$id_loc['id']."
AND glpi_tickets.status = 5
AND glpi_tickets.date ".$datas2."
". $entidade ."  ";

$result_sol = $DB->query($sql_sol) or die ("erro_ab");
$data_sol = $DB->fetch_assoc($result_sol);

$solucionados = $data_sol['total'];


//chamados fechados
$sql_clo = "SELECT count( glpi_tickets.id ) AS total, glpi_locations.id AS id
FROM glpi_locations, glpi_tickets
WHERE glpi_tickets.locations_id = glpi_locations.id
AND glpi_tickets.is_deleted = 0
AND glpi_locations.id = ".$id_loc['id']."
AND glpi_tickets.status = 6
AND glpi_tickets.date ".$datas2."
". $entidade ."  ";

$result_clo = $DB->query($sql_clo) or die ("erro_ab");
$data_clo = $DB->fetch_assoc($result_clo);

$fechados = $data_clo['total'];

//barra de porcentagem
if($conta_cons > 0) {

if($status == $status_close ) {
    $barra = 100;
    $cor = "progress-bar-success";
	}

else {

	//porcentagem
	$perc = round(($abertos*100)/$chamados,1);
	$barra = 100 - $perc;
	$cor = '';

	// cor barra
	if($barra == 100) { $cor = "progress-bar-success"; }
	if($barra >= 80 and $barra < 100) { $cor = "progress-bar-default"; }
	if($barra > 51 and $barra < 80) { $cor = "progress-bar-warning"; }
	if($barra > 0 and $barra <= 50) { $cor = "progress-bar-danger"; }
	if($barra < 0) { $cor = "progress-bar-danger"; $barra = 0; }

	}
}

else { 
	$barra = 0;
	$cor = '';
}

		echo "
		<tr>
			<td style='vertical-align:middle; text-align:left;'><a href='rel_localidade.php?con=1&sel_loc=". $id_loc['id'] ."&date1=".$data_ini."&date2=".$data_fin."' target='_blank' >" . $id_loc['name'].' ('.$id_loc['id'].")</a></td>
			<td style='vertical-align:middle; text-align:center;'> ". $chamados ." </td>
			<td style='vertical-align:middle; text-align:center;'> ". $abertos ." </td>
			<td style='vertical-align:middle; text-align:center;'> ". $solucionados ." </td>
			<td style='vertical-align:middle; text-align:center;'> ". $fechados ." </td>			
			<td style='vertical-align:middle; text-align:center;'> 
				<div class='progress' style='margin-top: 5px; margin-bottom: 5px;'>
					<div class='progress-bar ". $cor ." ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
			 			".$barra." % 	
			 		</div>		
				</div>			
		   </td>";	
				
	echo "</tr>";
		
//fim while1
}

echo "</tbody>
		</table>
		</div>"; 
?>

	<script type="text/javascript" charset="utf-8">
	
	$('#local')
		.removeClass('display')
		.addClass('table table-striped table-bordered table-hover dataTable');
	
	$(document).ready(function() {
	    $('#local').DataTable( {    	
	
			select: false,	    	    	
	        dom: 'Blfrtip',
	        filter: false,        
	        pagingType: "full_numbers",
	        sorting: [[1,'desc'],[0,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc']],
			  displayLength: 25,
	        lengthMenu: [[25, 50, 75, 100], [25, 50, 75, 100]],        
	        buttons: [
	        	    {
	                 extend: "copyHtml5",
	                 text: "<?php echo __('Copy'); ?>"
	             },
	             {
	             	  extend: "collection",
	                 text: "<?php echo __('Print','dashboard'); ?>",
							  buttons:[ 
							  	{               
			                 extend: "print",
			                 autoPrint: true,
			                 text: "<?php echo __('All','dashboard'); ?>",
			                 //message: "<div id='print' class='info_box fluid' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php //echo __('Location'); ?> : </span><?php //echo $ent_name['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php //echo  __('Tickets','dashboard'); ?> : </span><?php //echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",		     
			                }, 
								  {               
			                 extend: "print",
			                 autoPrint: true,
			                 text: "<?php echo __('Selected','dashboard'); ?>",
			                 //message: "<div id='print' class='info_box fluid' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php //echo __('Location'); ?> : </span><?php //echo $ent_name['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php //echo  __('Tickets','dashboard'); ?> : </span><?php //echo $/ ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
			                 exportOptions: {
			                    modifier: {
			                        selected: true
			                    }
			                	}
			                }
		                ]
	             },
	             {
	                 extend: "collection",
	                 text: "<?php echo _x('button', 'Export'); ?>",
	                 buttons: [ "csvHtml5", "excelHtml5",
	                  {
	                 		extend: "pdfHtml5",
	                 		orientation: "landscape",
	                 		//message: "<?php echo  __('Period','dashboard'); ?> : <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?>",
	                  }]
	             }
	        ]
	        
	    });
	});
	
	</script>

<?php
echo "</div><br>\n";
}

else {

	echo "
	<div id='nada_rel' class='well info_box fluid col-md-12'>
		<table class='table' style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
			<tr><td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
			<tr></tr>
		</table>
	</div>\n";
}
?>
	
	<script type="text/javascript" >
		$(document).ready(function() { $("#sel1").select2({dropdownAutoWidth : true}); });
	</script>
			</div>
		</div>

	</div>
</div>

</body>
</html>
