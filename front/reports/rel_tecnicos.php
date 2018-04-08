<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");

global $DB, $con;

Session::checkLoginUser();
Session::checkRight("profile", READ);

function dropdown( $name, array $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select id="sel_group" style="width: 300px;" autofocus onChange="javascript: document.form1.submit.focus()" name="'.$name.'" id="'.$name.'">'."\n";

    $selected = $selected;
    /*** loop over the options ***/

	$dropdown .= '<option value="-1">'. __('Select a group', 'dashboard') .'</option>'."\n";

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


function margins() {
	
	global $DB;
	$query_lay = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'layout' AND users_id = ".$_SESSION['glpiID']." ";																
						$result_lay = $DB->query($query_lay);					
						$layout = $DB->result($result_lay,0,'value');
						
	//redirect to index
	// sidebar
	if($layout == '0') {$margin = '0px 2% 0px 4%';}
	
	//top menu
	if($layout == 1 || $layout == '' ) {$margin = '0px 1% 0px 1%';}
		
	return $margin;	
}


//if(!empty($_POST['submit'])){
if(!empty($_REQUEST['date1'])) {	
  	$data_ini = $_REQUEST['date1'];
  	$data_fin = $_REQUEST['date2'];
  	$sel_group = $_REQUEST['sel_group'];
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
	$entidade_u = "AND glpi_profiles_users.entities_id IN (".$ent.") ";
	$entidade_g = "WHERE entities_id IN (".$ent.") OR is_recursive = 1";
	$entidade1 = "";

}
else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.") ";	
	$entidade_u = "AND glpi_profiles_users.entities_id IN (".$sel_ent.") ";
	$entidade_g = "WHERE entities_id IN (".$sel_ent.") OR is_recursive = 1";
}
?>

<html>
<head>
<title> GLPI - <?php echo __('Tickets','dashboard') .'  '. __('Technician group') ?> </title>
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
<link href="../js/extensions/Select/css/select.dataTables.min.css" type="text/css" rel="stylesheet" />
<link href="../js/extensions/Select/css/select.bootstrap.css" type="text/css" rel="stylesheet" />

<script src="../js/extensions/FixedHeader/js/dataTables.fixedHeader.min.js"></script>
<link href="../js/extensions/FixedHeader/css/fixedHeader.dataTables.min.css" type="text/css" rel="stylesheet" />
<link href="../js/extensions/FixedHeader/css/fixedHeader.bootstrap.min.css" type="text/css" rel="stylesheet" />

<style type="text/css">
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
	a:hover { color: #000099;}
	.label-md {
  		min-width: 45px !important;
 		display: inline-block !important
	}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>
</head>

<body style="background-color: #e5e5e5;" >
<div id='content' >
<div id='container-fluid' style="margin: <?php echo margins(); ?> ;">
<div id="charts" class="fluid chart" >
	<div id="pad-wrapper" >
		<div id="head-rel" class="fluid">
			<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>
				<div id="titulo_rel" > <?php echo __('Tickets','dashboard') .'  '. __('Technician group') ?> </div>
					<div id="datas-tec" class="col-md-12 col-sm-12 fluid" >
					<form id="form1" name="form1" class="form_rel" method="post" action="rel_tecnicos.php?con=1">

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

								<?php

								// lista de grupos
								$sql_techs = "
								SELECT id AS id, name AS name
								FROM `glpi_groups`
								".$entidade_g."
								ORDER BY `name` ASC";

								$result_techs = $DB->query($sql_techs);
								$grp = $DB->fetch_assoc($result_techs);

								$res_techs = $DB->query($sql_techs);
								$arr_techs = array();										
								$arr_techs[0] = "". __('All') . "" ;

								$DB->data_seek($result_techs, 0) ;

								while ($row_result = $DB->fetch_assoc($result_techs)) {										
								   $v_row_result = $row_result['id'];
								   $arr_techs[$v_row_result] = $row_result['name'] ;										      
								}

								$name = 'sel_group';
								$options = $arr_techs;
								
								if($sel_group != 0 ) { $selected = $sel_group;}										
								else { $selected = 0; }	
																	
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
<?php

//tecnico2
if(isset($_GET['con'])) {

	$con = $_GET['con'];
	
	if($con == "1") {

	if(!isset($_REQUEST['date1'])) {
		  $data_ini2 = $data_ini; 
		  $data_fin2 = $data_fin; 
		  $grupo_tec = "";
		  $grupo_tec1 = "";
		  $grupo_tic = "";
		  $grupo_tic1 = "";
		  $glpi_techs = "";
		  $glpi_groups = "";
		  $id_techs = 0;
	}

	else {
			$data_ini2 = $_REQUEST['date1'];
	    	$data_fin2 = $_REQUEST['date2'];
	    	//$sel_group = $_REQUEST['sel_group'];
	    	$id_techs  = $_REQUEST["sel_group"];

	    if($id_techs > 0) {
		 	$grupo_tec = "AND glpi_groups_users.users_id = glpi_tickets_users.users_id" ;
		 	$grupo_tec1 = "AND glpi_groups_users.groups_id = ". $id_techs ."" ;
	    	$glpi_techs = " , glpi_groups_users";
	    	$glpi_groups = " , glpi_groups_tickets";
	    	$grupo_tic = "AND glpi_groups_tickets.tickets_id = glpi_tickets.id ";
	    	$grupo_tic1 = "AND glpi_groups_tickets.groups_id = ". $id_techs ."" ;
			//$sel_group = $_REQUEST['sel_group'];	
		 }
		 if($id_techs == 0 || $id_techs == '') {
		   $grupo_tec = "";
		   $grupo_tec1 = "";
   		$grupo_tic = "";
		   $grupo_tic1 = "";
		   $glpi_techs = "";
		   $glpi_groups = "";
		   $id_techs = 0;
		 }

	}

	if($data_ini2 === $data_fin2) {
		$datas2 = "LIKE '".$data_ini2."%'";
	}

	else {
		$datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";
	}

$sql_tec = "
SELECT DISTINCT glpi_users.id AS id , glpi_users.firstname AS fname, glpi_users.realname AS rname, COUNT(glpi_tickets.id) AS chamados, glpi_users.picture
FROM glpi_users , glpi_tickets_users, glpi_profiles_users, glpi_tickets". $glpi_techs ."
WHERE glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_profiles_users.users_id = glpi_tickets_users.users_id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets_users.type = 2
AND glpi_tickets.date ". $datas2 ."
". $entidade_u ."
". $grupo_tec ."
". $grupo_tec1 ."
GROUP BY id
ORDER BY fname ASC ";

$result_tec = $DB->query($sql_tec);
$conta_cons = $DB->numrows($result_tec);

//status
$status = "";
$status_closed = "('5','6')";

//check if satisfaction is active
$query_sats = " SELECT * FROM `glpi_ticketsatisfactions` WHERE 1";

$result_sats = $DB->query($query_sats);
$sats = $DB->fetch_assoc($result_sats);

echo "<div class='well info_box fluid col-md-12 report' style='margin-left: -1px;'>";

if( $id_techs != -1 && $id_techs != 0 ) {

	$query_gname = "SELECT name FROM glpi_groups WHERE id = ".$id_techs." ";
	$result_gname = $DB -> query($query_gname);
	$grp_name = $DB -> result($result_gname,0,'name');
	
	echo "
	<table class='fluid' style='font-size: 18px; font-weight:bold; margin-bottom: 30px;' cellpadding = 1px>
		<tr>
			<td style='color: #000;'>". __('Group') .":  ". $grp_name ." </td>
		</tr>
	</table>";
}

echo "
	<table id='tec' class='display' style='font-size: 13px; font-weight:bold;' cellpadding = 2px >
		<thead>
			<tr>
				<th style='text-align:center; cursor:pointer;'> ". __('Technician','dashboard') ." </th>
				<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Tickets')." </th>
				<th style='text-align:center; cursor:pointer;'> ". __('Opened','dashboard') ."</th>
				<th style='text-align:center; cursor:pointer;'> ". __('Late') ."</th>
				<th style='text-align:center; cursor:pointer;'> ". __('Solved','dashboard') ."</th>
				<th style='text-align:center; cursor:pointer;'> ". __('Closed','dashboard') ."</th>				
				<th style='text-align:center; '> % ". __('Closed','dashboard') ."</th> 
				<th style='text-align:center; cursor:pointer;'> ". __('Backlog','dashboard') ."</th>";
				
				if($sats != '') {
					echo "<th style='text-align:center; '> ". __('Satisfaction','dashboard') ."</th>";
				}
				echo "</tr>
		</thead>
	<tbody>";


while($id_tec = $DB->fetch_assoc($result_tec)) {

//chamados total
$sql_total = "SELECT count( glpi_tickets.id ) AS total, glpi_tickets_users.users_id AS id
FROM glpi_tickets_users, glpi_tickets, glpi_users". $glpi_groups ."
WHERE glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets.date ".$datas2."
AND glpi_tickets_users.users_id = ".$id_tec['id']."
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets_users.type = 2
". $entidade ."
". $grupo_tic ."
". $grupo_tic1 ." " ;

$result_total = $DB->query($sql_total) or die ("erro_total");
$data_total = $DB->fetch_assoc($result_total);

$total = $data_total['total'];


//chamados abertos
$sql_ab = "SELECT count( glpi_tickets.id ) AS total, glpi_tickets_users.users_id AS id
FROM glpi_tickets_users, glpi_tickets, glpi_users". $glpi_groups ."
WHERE glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets.date ".$datas2."
AND glpi_tickets_users.users_id = ".$id_tec['id']."
AND glpi_tickets.status NOT IN ".$status_closed."
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets_users.type = 2
". $entidade ."
". $grupo_tic ."
". $grupo_tic1 ." " ;

$result_ab = $DB->query($sql_ab) or die ("erro_ab");
$data_ab = $DB->fetch_assoc($result_ab);

$abertos = $data_ab['total'];


//chamados solucionados
$sql_sol = "SELECT count( glpi_tickets.id ) AS total, glpi_tickets_users.users_id AS id
FROM glpi_tickets_users, glpi_tickets, glpi_users". $glpi_groups ."
WHERE glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets.solvedate ".$datas2."
AND glpi_tickets_users.users_id = ".$id_tec['id']."
AND glpi_tickets.status = 5
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets_users.type = 2
". $entidade ."
". $grupo_tic ."
". $grupo_tic1 ." " ;

$result_sol = $DB->query($sql_sol) or die ("erro_sol");
$data_sol = $DB->fetch_assoc($result_sol);

$solucionados = $data_sol['total'];


//chamados fechados
$sql_clo = "SELECT count( glpi_tickets.id ) AS total, glpi_tickets_users.users_id AS id
FROM glpi_tickets_users, glpi_tickets, glpi_users". $glpi_groups ."
WHERE glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets.closedate ".$datas2."
AND glpi_tickets_users.users_id = ".$id_tec['id']."
AND glpi_tickets.status = 6
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets_users.type = 2
". $entidade ."
". $grupo_tic ."
". $grupo_tic1 ." " ;

$result_clo = $DB->query($sql_clo) or die ("erro_clo");
$data_clo = $DB->fetch_assoc($result_clo);

$fechados = $data_clo['total'];


//satisfação por tecnico   , glpi_users.firstname AS fname , glpi_users.realname AS rname, glpi_users.name
$query_sat = "
SELECT glpi_users.id, avg( glpi_ticketsatisfactions.satisfaction ) AS media
FROM glpi_tickets, glpi_ticketsatisfactions, glpi_tickets_users, glpi_users". $glpi_groups ."
WHERE glpi_tickets.is_deleted = '0'
AND glpi_ticketsatisfactions.tickets_id = glpi_tickets.id
AND glpi_ticketsatisfactions.tickets_id = glpi_tickets_users.tickets_id
AND glpi_users.id = glpi_tickets_users.users_id
AND glpi_tickets_users.type = 2
AND glpi_tickets.closedate ".$datas2." 
AND glpi_tickets_users.users_id = ".$id_tec['id']."
".$entidade."
".$grupo_tic."
".$grupo_tic1." ";

$result_sat = $DB->query($query_sat) or die('erro_sat');
$media = $DB->fetch_assoc($result_sat);

$satisfacao = round(($media['media']/5)*100,1);
$nota = round($media['media'],0);


$total_cham = $abertos + $solucionados + $fechados;

//barra de porcentagem
if($conta_cons > 0) {
	
	if($status == $status_closed ) {
	    $barra = 100;
	    $cor = "progress-bar-success";
	}
	
	else {
	
		//porcentagem
		$barra = round(($fechados*100)/$total,0);
		$width = $barra;
	
		// cor barra
		if($barra >= 100) { $cor = "progress-bar-success"; $text_color="#fff"; $width = 100; }
		if($barra == 100) { $cor = "progress-bar-success"; $text_color="#fff"; }
		if($barra >= 80 and $barra < 100) { $cor = " "; $text_color="#fff"; }
		if($barra > 51 and $barra < 80) { $cor = "progress-bar-warning"; $text_color="#fff"; }
		if($barra > 0 and $barra <= 50) { $cor = "progress-bar-danger"; $text_color="#000";}
		if($barra <= 0) { $cor = "progress-bar-danger"; $barra = 0; $text_color="#000";}
	
	}
}

else { $barra = 0;}

//chamados atrasados
$sql_due = "
SELECT count( glpi_tickets.id ) AS total, glpi_tickets.id as ID
FROM glpi_tickets_users, glpi_tickets, glpi_users". $glpi_groups ."
WHERE glpi_tickets.id = glpi_tickets_users.tickets_id
AND `glpi_tickets`.`time_to_resolve` IS NOT NULL 
AND `glpi_tickets`.`status` <> 4
AND 
(
  `glpi_tickets`.`solvedate` > `glpi_tickets`.`time_to_resolve`  
  OR (
    `glpi_tickets`.`solvedate` IS NULL AND `glpi_tickets`.`time_to_resolve` < NOW()
  )
)
AND glpi_tickets_users.type = 2
AND glpi_tickets_users.users_id = ".$id_tec['id']."
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.solvedate ".$datas2."
". $entidade ."
". $grupo_tic ."
". $grupo_tic1 ." " ;

$result_due = $DB->query($sql_due) or die ("erro_late");
$data_due = $DB->fetch_assoc($result_due);
 
$atrasados = $data_due['total'];


// backlog acumulado
$sql_bac = "SELECT count( glpi_tickets.id ) AS total, glpi_tickets_users.users_id AS id
FROM glpi_tickets_users, glpi_tickets, glpi_users". $glpi_groups ."
WHERE glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets.date < '".$data_ini." 00:00:00' 
AND glpi_tickets_users.users_id = ".$id_tec['id']."
AND glpi_tickets.status <> 6
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets_users.type = 2
". $entidade ."
". $grupo_tic ."
". $grupo_tic1 ." " ;			

$result_bac = $DB->query($sql_bac) or die ("erro_ab");
$data_bac = $DB->fetch_assoc($result_bac);

$back_ac = $data_bac['total'];		


//backlog
$backlog = ($total - $fechados);
			
if($backlog >= 1) { $back_cor = 'label label-md label-danger'; }
if($backlog == 0) { $back_cor = 'label label-md label-primary'; }
if($backlog <= -1) { $back_cor = 'label label-md label-success'; }

$backlog_ac = ($back_ac + $backlog);		
				
if($backlog_ac >= 1) { $back_cor_ac = 'label label-md label-danger'; }
if($backlog_ac == 0) { $back_cor_ac = 'label label-md label-primary'; }
if($backlog_ac <= -1) { $back_cor_ac = 'label label-md label-success'; }

//barra de porcentagem - Chamados no prazo
if($conta_cons > 0) {

		//porcentagem
		$perc_due = round(($atrasados*100)/($solucionados+$fechados+$abertos),0);
		//$perc_due = round(($atrasados*100)/($solucionados+$fechados),1);
		$barra_due = $perc_due;
		//$barra_due = 100 - $perc;		
		
		// cor barra_due
		//if($barra_due == 100) { $cor_due = "progress-bar-danger"; }
		if($barra_due > 80 and $barra_due <= 100) { $cor_due = "progress-bar-danger"; $text_color_due = "#fff"; }
		if($barra_due > 51 and $barra_due <= 80) { $cor_due = "progress-bar-warning"; $text_color_due = "#fff"; }
		if($barra_due > 20 and $barra_due <= 50) { $cor_due = " ";  $text_color_due = "#fff";}
		if($barra_due > 0 and $barra_due <= 20) { $cor_due = "progress-bar-success"; $text_color_due = "#000"; }
		if($barra_due <= 0) { $cor_due = "progress-bar-success"; $barra_due = 0; $text_color_due = "#000";}		
}

else { $barra_due = 0;}

		echo "
		<tr>
			<td style='vertical-align:middle; text-align:left;'><i class='del fa fa-times' style='cursor:pointer;' title='". __('Hide') ."'>&nbsp;&nbsp;&nbsp; </i>
				<img class='avatar2' width='40px' height='43px' src='".User::getURLForPicture($id_tec['picture'])."'></img>&nbsp;&nbsp;
				<a href='rel_tecnico.php?con=1&sel_tec=". $id_tec['id'] ."&date1=".$data_ini."&date2=".$data_fin."' target='_blank' >" . $id_tec['fname'].' '.$id_tec['rname']. ' ('.$id_tec['id'].")</a>
			</td>
			<td style='vertical-align:middle; text-align:center;'> ". $total ." </td>
			<td style='vertical-align:middle; text-align:center;'> ". $abertos ." </td>
		   <td style='vertical-align:middle; text-align:center;'> ". $atrasados ." </td>
			<td style='vertical-align:middle; text-align:center;'> ". $solucionados ." </td>
			<td style='vertical-align:middle; text-align:center;'> ". $fechados ." </td>			
			<td style='vertical-align:middle; text-align:center;'>
				<div class='progress' style='margin-top: 5px; margin-bottom: 5px;'>
					<div class='progress-bar ". $cor ." ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='color:".$text_color."; width: ".$width."%;'>
			 			".$barra."%
			 		</div>
				</div>
		   </td>
		   
		   </td>
			<td style='vertical-align:middle; text-align:center;'><h4><span class='".$back_cor_ac."'>". $backlog_ac ."</span></h4></td> ";			

/*
<td style='vertical-align:middle; text-align:center;'>
				<div class='progress' style='margin-top: 5px; margin-bottom: 5px;'>
					<div class='progress-bar ". $cor_due ." ' role='progressbar' aria-valuenow='".$barra_due."' aria-valuemin='0' aria-valuemax='100' style='color:".$text_color_due."; width: ".$barra_due."%;'>
			 			".$barra_due."%
			 		</div>
				</div>
		   </td> 

			<td style='vertical-align:middle; text-align:center;'><h4><span class='".$back_cor."'>". $backlog ."</span></h4></td>
*/

		   
if($sats != '') {
		echo "<td style='vertical-align:middle; text-align:center;'>		
					<span class='label' style=\"background:url('../img/stars/star". $nota."_22.png') no-repeat;  
					color:#000 !important; padding-left: 8px !important; padding-top: 4px; font-size:11px; \">".$nota. "</span> 
				</td>";
			}

	echo "</tr>";

//fim while1
}

echo "</tbody>
		</table>
		</div>";
//fim $con
}
}

//var_dump($sql_total);

if($sats != '') {
	$sort = "[[1,'desc'],[0,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc'],[6,'desc']],";
}

else {
	$sort = "[[1,'desc'],[0,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc']],";
}
?>

<script type="text/javascript" charset="utf-8">

$('#tec')
	.removeClass('display')
	.addClass('table table-striped table-bordered table-hover dataTable');

$(document).ready(function() {
   table = $('#tec').DataTable({    	

		  select: true,	    	    	
        dom: 'Blfrtip',
        filter: false,        
        pagingType: "full_numbers",
        deferRender: true,
		  fixedHeader: true,
        sorting: <?php echo $sort; ?>
        //sorting: [[1,'desc'],[0,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc'],[6,'desc']],
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
		                 message: "",		     
		                }, 
							  {               
		                 extend: "print",
		                 autoPrint: true,
		                 text: "<?php echo __('Selected','dashboard'); ?>",
		                 message: "",
		                 exportOptions: {
		                    modifier: {
		                        selected: true
		                    }
		                }
		                }
	                ]
             },
             {
                 extend:    "collection",
                 text: "<?php echo _x('button', 'Export'); ?>",
                 buttons: [ "csvHtml5", "excelHtml5",
                  {
                 		extend: "pdfHtml5",
                 		orientation: "landscape",
                 		message: ""
                  } 
                  ]
             }
        ]
        
    } );


	//hide rows
	$('#tec tbody').on('click', '.del', function () {
	    table
	        .row($(this).parents('tr'))
	        .remove()
	        .draw();
	} );

} );

	$(document).ready(function() { $("#sel_group").select2({dropdownAutoWidth : true}); });
</script>

</div>
</div>
</div>
</div>

</body>
</html>
