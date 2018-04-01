<?php
include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");
include "../inc/functions.php";

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if(!empty($_POST['submit']))
{
	$data_ini = $_REQUEST['date1'];
	$data_fin = $_REQUEST['date2'];
}

else {
	$data_ini = date("Y-m-01");
	$data_fin = date("Y-m-d");
}

if(!isset($_POST["sel_loc"])) {
	$id_loc = $_REQUEST["sel_loc"];
}

else {
	$id_loc = $_POST["sel_loc"];
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
	$entidade_l = "AND glpi_locations.entities_id IN (".$ent.") ";
	$entidade_lw = "WHERE glpi_locations.entities_id IN (".$ent.") OR glpi_locations.is_recursive = 1";
	$entidade1 = "";

}
else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.") ";
	$entidade_l = "AND glpi_locations.entities_id IN (".$sel_ent.") ";
	$entidade_lw = "WHERE glpi_locations.entities_id IN (".$sel_ent.") OR glpi_locations.is_recursive = 1";
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

<body style="background-color: #e5e5e5; margin-left:0%;">

<div id='content' >
	<div id='container-fluid' style="margin: <?php echo margins(); ?> ;">
	<div id="charts" class="fluid chart">
	<div id="pad-wrapper" >
	<div id="head-rel" class="fluid">
	<a href="../index.php"><i class="fa fa-home home-rel" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

	<div id="titulo_rel"> <?php echo __('Tickets', 'dashboard') .'  '. __('by Location', 'dashboard') ?> </div>
	<div id="datas-tec" class="col-md-12 col-sm-12 fluid" >
	<form id="form1" name="form1" class="form_rel" method="post" action="rel_localidade.php?con=1">
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
				</td>
				<td style="margin-top:2px;">		
					<?php
					
					// lista de localidades
					$sql_loc = "
					SELECT id, completename AS name, entities_id AS ent
					FROM glpi_locations
					".$entidade_lw."
					ORDER BY `name` ASC ";
					
					$result_loc = $DB->query($sql_loc);
					
					$arr_loc = array();
					$arr_loc[0] = "-- ". __('Select a location', 'dashboard') . " --" ;
					
					
					while ($row_result = $DB->fetch_assoc($result_loc))
					{
					
						$sql_ent = "
						SELECT name
						FROM glpi_entities
						WHERE id = ".$row_result['ent']." ";
					
						$result_ent = $DB->query($sql_ent);
						$ent_loc = $DB->result($result_ent,0,'name');
					
						$v_row_result = $row_result['id'];
						$arr_loc[$v_row_result] = $ent_loc ." > ".$row_result['name'] ;
					}
					
					$name = 'sel_loc';
					$options = $arr_loc;
					$selected = $id_loc;
					
					echo dropdown( $name, $options, $selected );
					
					?>
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
	
			
<script language="Javascript">
	$('#dp1').datepicker('update');
	$('#dp2').datepicker('update');
</script>	

<?php

//localidades
if(isset($_GET['con'])){$con = $_GET['con'];}
else {$con = '';}

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

if(!isset($_POST["sel_loc"])) {
	$id_loc = $_REQUEST["sel_loc"];
}

else {
	$id_loc = $_POST["sel_loc"];
}

if($id_loc == " " || $id_loc == 0) {
	echo '<script language="javascript"> alert(" ' . __('Select a location', 'dashboard') . ' "); </script>';
	echo '<script language="javascript"> location.href="rel_localidade.php"; </script>';
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

// Chamados
$sql_cham =
"SELECT glpi_tickets.id AS id, glpi_tickets.name AS descr, glpi_tickets.date AS date, glpi_tickets.solvedate as solvedate, glpi_tickets.status
FROM glpi_tickets
WHERE glpi_tickets.locations_id = ".$id_loc."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status IN ".$status."
".$entidade."
ORDER BY id DESC ";

$result_cham = $DB->query($sql_cham);


$consulta1 =
"SELECT glpi_tickets.id AS total
FROM glpi_tickets
WHERE glpi_tickets.locations_id = ".$id_loc."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status IN ".$status."
".$entidade." ";

$result_cons1 = $DB->query($consulta1);
$conta_cons = $DB->numrows($result_cons1);

$consulta = $conta_cons;


if($consulta > 0) {

//montar barra
$sql_ab = "SELECT glpi_tickets.id AS total
FROM glpi_tickets
WHERE glpi_tickets.locations_id = ".$id_loc."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status IN ".$status_open."
".$entidade." ";

$result_ab = $DB->query($sql_ab) or die ("erro_ab");
$data_ab = $DB->numrows($result_ab);

$abertos = $data_ab;

if($conta_cons > 0) {

//barra de porcentagem
if($status == $status_close ) {
    $barra = 100;
    $cor = "progress-bar-success";
}

else {

	//porcentagem
	$perc = round(($abertos*100)/$conta_cons,1);
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

// nome da localidade
$sql_nm = "
SELECT id , completename AS name
FROM glpi_locations
WHERE id=".$id_loc." ";

$result_nm = $DB->query($sql_nm);
$ent_name = $DB->fetch_assoc($result_nm);


//listar chamados
echo "
<div class='well info_box fluid col-md-12 col-sm-12 report' style='margin-left: -1px;'>
<table class='fluid'  style='font-size: 18px; font-weight:bold; width:100%;' cellpadding='1px' border='0' >
<tr>
	<td style='font-size: 16px; font-weight:bold; vertical-align:middle; width:55%;'><span style='color:#000;'> ".__('Location').": </span>".$ent_name['name']." </td>
</tr>
<tr>
		<td style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Tickets', 'dashboard').": </span>".$consulta." </td>
		<td style='font-size: 16px; font-weight:bold; vertical-align:middle; width:230px;'><span style='color:#000;'>".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)."</td>

		<td style='vertical-align:middle; width: 190px; '>
		<div class='progress'>
			<div class='progress-bar ". $cor ." ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
    			".$barra." % ".__('Closed', 'dashboard') ."
    		</div>
		</div>
		</td>
</tr>
</table>

<table align='right' style='margin-bottom:3px;'>
		<tr>
			<td colspan=3>
				<button class='btn btn-primary btn-sm' type='button' name='abertos' value='Abertos' onclick='location.href=\"rel_localidade.php?con=1&stat=open&sel_loc=".$id_loc."&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('Opened', 'dashboard') ." </button>
				<button class='btn btn-primary btn-sm' type='button' name='fechados' value='Fechados' onclick='location.href=\"rel_localidade.php?con=1&stat=close&sel_loc=".$id_loc."&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('Closed', 'dashboard')." </button>
				<button class='btn btn-primary btn-sm' type='button' name='todos' value='Todos' onclick='location.href=\"rel_localidade.php?con=1&stat=all&sel_loc=".$id_loc."&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('All', 'dashboard')." </button>
			</td>
		</tr>
</table>

<table>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
</table>

<table id='local' class='display'  style='font-size: 12px; font-weight:bold;' cellpadding = 2px>
	<thead>
		<tr>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Tickets', 'dashboard')." </th>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Status')." </th>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Title')." </th>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Requester')." </th>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Technician')." </th>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Opened','dashboard')."</th>
			<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Closed')." </th>
		</tr>
	</thead>
<tbody>
";

while($row = $DB->fetch_assoc($result_cham)){

	$status1 = $row['status'];

	if($status1 == "1" ) { $status1 = "new";}
	if($status1 == "2" ) { $status1 = "assign";}
	if($status1 == "3" ) { $status1 = "plan";}
	if($status1 == "4" ) { $status1 = "waiting";}
	if($status1 == "5" ) { $status1 = "solved";}
	if($status1 == "6" ) { $status1 = "closed";}

	//requerente

		$sql_user = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
	FROM `glpi_tickets_users` , glpi_tickets, glpi_users
	WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
	AND glpi_tickets.id = ". $row['id'] ."
	AND glpi_tickets_users.`users_id` = glpi_users.id
	AND glpi_tickets_users.type = 1
	".$entidade."	";
	$result_user = $DB->query($sql_user);

		$row_user = $DB->fetch_assoc($result_user);

	//tecnico

		$sql_tec = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
	FROM `glpi_tickets_users` , glpi_tickets, glpi_users
	WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
	AND glpi_tickets.id = ". $row['id'] ."
	AND glpi_tickets_users.`users_id` = glpi_users.id
	AND glpi_tickets_users.type = 2
  ".$entidade."	";
	$result_tec = $DB->query($sql_tec);

	$row_tec = $DB->fetch_assoc($result_tec);

	echo "

	<tr style='font-weight:normal;'>
		<td style='vertical-align:middle; text-align:center; font-weight:bold;'><a href=".$CFG_GLPI['url_base']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
		<td style='vertical-align:middle;' align='left' ><img src=".$CFG_GLPI['url_base']."/pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/>&nbsp; ".Ticket::getStatus($row['status'])."  </td>
		<td style='vertical-align:middle;'> ". substr($row['descr'],0,55) ." </td>
		<td style='vertical-align:middle;'> ". $row_user['name'] ." ".$row_user['sname'] ." </td>
		<td style='vertical-align:middle;'> ". $row_tec['name'] ." ".$row_tec['sname'] ." </td>
		<td style='vertical-align:middle;'> ". conv_data_hora($row['date']) ." </td>
		<td style='vertical-align:middle;'> ". conv_data_hora($row['solvedate']) ." </td>
	</tr>";
}

echo "</tbody>
		</table>
		</div>"; ?>

<script type="text/javascript" charset="utf-8">

$('#local')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover dataTable');

$(document).ready(function() {
    $('#local').DataTable( {    	

		select: true,	    	    	
        dom: 'Blfrtip',
        filter: false,        
        pagingType: "full_numbers",
        sorting: [[0,'desc'],[1,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc'],[6,'desc']],
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
		                 message: "<div id='print' class='info_box fluid' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Location'); ?> : </span><?php echo $ent_name['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",		     
		                }, 
							{               
		                 extend: "print",
		                 autoPrint: true,
		                 text: "<?php echo __('Selected','dashboard'); ?>",
		                 message: "<div id='print' class='info_box fluid' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Location'); ?> : </span><?php echo $ent_name['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
		                 exportOptions: {
		                    modifier: {
		                        selected: true
		                    }
								}
		                }
	                ]
             },
             {
                 extend:  "collection",
                 text: "<?php echo _x('button', 'Export'); ?>",
                 buttons: [ "csvHtml5", "excelHtml5",
                  {
                 		extend: "pdfHtml5",
                 		orientation: "landscape",
                 		message: "<?php echo __('Location'); ?> : <?php echo $ent_name['name'] .'  -  '; ?>  <?php echo  __('Tickets','dashboard'); ?> : <?php echo $consulta . '  -  '; ?> <?php echo  __('Period','dashboard'); ?> : <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?>",
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
			<tr>
				<td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
			<tr></tr>
	</table></div>\n";
}

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
