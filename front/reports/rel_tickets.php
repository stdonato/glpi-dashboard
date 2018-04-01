<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if(!empty($_POST['submit']))
{	
	$data_ini =  $_POST['date1'];	
	$data_fin = $_POST['date2'];
}

else {	
	$data_ini = date("Y-01-01");
	$data_fin = date("Y-m-d");	
}  

if(!isset($_POST["sel_ent"])) {
	$id_ent = $_REQUEST["sel_ent"];	
}

else {
	$id_ent = $_POST["sel_ent"];
}

function conv_data($data) {
	if($data != "") {
		$source = $data;
		$date = new DateTime($source);	
		return $date->format('d-m-Y');}
	else {
		return "";	
	}
}

function conv_data_hora($data) {
	if($data != "") {
		$source = $data;
		$date = new DateTime($source);	
		return $date->format('d-m-Y H:i:s');}
	else {
		return "";	
	}
}

function dropdown( $name, array $options, $selected=null ) {
    /*** begin the select ***/
    $dropdown = '<select style="width: 300px; height: 27px;" autofocus name="'.$name.'" id="'.$name.'">'."\n";

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

function margins() {

	global $DB;
	$query_lay = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'layout' AND users_id = ".$_SESSION['glpiID']." ";																
						$result_lay = $DB->query($query_lay);					
						$layout = $DB->result($result_lay,0,'value');
						
	//redirect to index
	if($layout == '0')
	{
		// sidebar
		$margin = '0px 3% 0px 5%';
	}
	
	if($layout == 1 || $layout == '' )
	{
		//top menu
		$margin = '0px 2% 0px 2%';
	}
		
	return $margin;	
}
?>

<html> 
<head>
<title> GLPI - <?php echo __('Tickets', 'dashboard') ?> </title>
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
<script src="../js/extensions/Buttons/js/buttons.colVis.min.js"></script>

<script src="../js/extensions/Select/js/dataTables.select.min.js"></script>
<link href="../js/extensions/Select/css/select.bootstrap.css" type="text/css" rel="stylesheet" />
	
<style type="text/css">	
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 
   
</head>

<body style="background-color: #e5e5e5;">

<div id='content' >
<div id='container-fluid' style="margin: <?php echo margins(); ?> ;">
<div id="charts" class="fluid chart"> 
<div id="head-lg" class="fluid" style="height: 450px;">

<style type="text/css">
	a:link, a:visited, a:active {text-decoration: none;}
	a:hover {color: #000099;}
</style>

<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

	<div id="titulo_rel"> <?php echo __('Tickets', 'dashboard') ?> </div>	
		<div id="datas-tec3" class="col-md-12 fluid" > 
		<form id="form1" name="form1" class="form_rel" method="post" action="rel_tickets.php?con=1" style="margin-left: 15%;"> 
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef" class="tab_tickets" width="550">
		<tr>			
			<td style="margin-top:2px; width:110px;"><?php echo __('Period'); ?>: </td>	
			<td style="width: 200px;">
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
			<td class="separator">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td style="margin-top:2px; width:100px;"><?php echo __('Entity'); ?>: </td>		
			<td style="margin-top:2px;">
			<?php
			
			//select user entities
			$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
			$result_e = $DB->query($sql_e);
			$sel_ent = $DB->result($result_e,0,'value');
			
			if($sel_ent == '' || $sel_ent == -1) {
				
				$entities = $_SESSION['glpiactiveentities'];
				$ents = implode(",",$entities);			
			}
			else {
				$ents = $sel_ent;
			}

			//echo "teste";
			$user_ents = Profile_User::getUserEntities($_SESSION['glpiID'], true);
			//var_dump($_SESSION['glpiactiveentities']);					
				
			// lista de entidades
			$sql_ent = "
			SELECT id, name, completename AS cname
			FROM glpi_entities
			WHERE id IN (".$ents.")
			ORDER BY cname ASC ";
			
			$result_ent = $DB->query($sql_ent);
			
			$arr_ent = array();
			$arr_ent[-1] = "-----" ;
			
			$arr_ent[0] = __('All') ;
			
			while ($row_ent = $DB->fetch_assoc($result_ent)) { 
				$v_row_ent = $row_ent['id'];
				$arr_ent[$v_row_ent] = $row_ent['cname'] ;			
			} 
				
			$name = 'sel_ent';
			$options = $arr_ent;
			$selected = $id_ent;
			
			echo dropdown( $name, $options, $selected );	
			
			$id_sta1 = '';
			$id_due1 = '';
			
			if(isset($_REQUEST["sel_sta"]) && $_REQUEST["sel_sta"] != '0') { 
				
				$id_sta1 = $_REQUEST["sel_sta"];
			
				if($_REQUEST["sel_sta"] == 'notclosed') {
					$id_sta = "AND glpi_tickets.status <> 6"; 
				}
				elseif($_REQUEST["sel_sta"] == 'notold') {
					$id_sta = "AND glpi_tickets.status NOT IN ('5','6')"; 
				}
				else {
					$id_sta = "AND glpi_tickets.status = ".$_REQUEST["sel_sta"] ;
				}
			}
			else { $id_sta = ''; }
			
			//AND glpi_tickets.status LIKE '%".$id_sta."'
			
			if(isset($_REQUEST["sel_req"]) && $_REQUEST["sel_req"] != '0') { $id_req = $_REQUEST["sel_req"]; }
			else { $id_req = ''; }
			
			if(isset($_REQUEST["sel_pri"]) && $_REQUEST["sel_pri"] != '0') { $id_pri = $_REQUEST["sel_pri"]; }
			else { $id_pri = ''; }
			
			if(isset($_REQUEST["sel_cat"]) && $_REQUEST["sel_cat"] != '0') { $id_cat = $_REQUEST["sel_cat"]; }
			else { $id_cat = ''; }
			
			if(isset($_REQUEST["sel_tip"]) && $_REQUEST["sel_tip"] != '0') { $id_tip = $_REQUEST["sel_tip"]; }
			else { $id_tip = ''; }
			
			if(isset($_REQUEST["sel_due"]) && $_REQUEST["sel_due"] != '0') {
				 
				$id_due1 = $_REQUEST["sel_due"];
				 
				if($_REQUEST["sel_due"] == 1) {
					$id_due = "AND time_to_resolve < solvedate";		
					}
				if($_REQUEST["sel_due"] == 2) {		
					$id_due = "AND time_to_resolve >= solvedate"; 
					}
			}
			else { $id_due = ''; }										
			?>
			
			</td>
		</tr>	
		<tr><td height="12px"></td></tr>				
		<tr>
			<td style="margin-top:2px; width:100px;"><?php echo __('Status'); ?>:  </td>		
			<td style="margin-top:2px;">
			<?php
			
			// lista de status		
			$sql_sta = "
			SELECT DISTINCT status
			FROM glpi_tickets
			ORDER BY status ASC";
			
			$result_sta = $DB->query($sql_sta);
			
			$arr_sta = array();
			$arr_sta[0] = "-----";
			
			while ($row_sta = $DB->fetch_assoc($result_sta))		
			{ 
				$v_row_sta = $row_sta['status'];
				$arr_sta[$v_row_sta] = Ticket::getStatus($row_sta['status']) ;			
			} 
				
			$arr_sta['notold']    = _x('status', 'Not solved');
         $arr_sta['notclosed'] = _x('status', 'Not closed'); 	
				
			$name = 'sel_sta';
			$options = $arr_sta;
			$selected = $id_sta1;
						
			echo dropdown( $name, $options, $selected );
			?>
			</td>
			
			
			<td height="12px" width="25px"></td>							
			<td style="margin-top:2px; width:100px;"><?php echo __('Priority'); ?>:  </td>		
			<td style="margin-top:2px;">
			<?php
			// lista de tipos		
			$arr_pri = array();
			$arr_pri[0] = "-----" ;
			$arr_pri[1] = _x('priority', 'Very low');
			$arr_pri[2] = _x('priority', 'Low');
			$arr_pri[3] = _x('priority', 'Medium');
			$arr_pri[4] = _x('priority', 'High');
			$arr_pri[5] = _x('priority', 'Very high');
			$arr_pri[6] = _x('priority', 'Major');
						
			$name = 'sel_pri';
			$options = $arr_pri;
			$selected = $id_pri;
			
			echo dropdown( $name, $options, $selected );
			?>
			</td>
		</tr>
		<tr><td height="12px"></td></tr>			
		<tr>
			<td style="margin-top:2px; width:100px;"><?php echo __('Category'); ?>:  </td>		
			<td style="margin-top:2px;">
			<?php
			
			// lista de categorias		
			$sql_cat = "
			SELECT id, completename AS name
			FROM glpi_itilcategories
			ORDER BY name ASC ";
			
			$result_cat = $DB->query($sql_cat);
			
			$arr_cat = array();
			$arr_cat[0] = "-----" ;
			
			while ($row_cat = $DB->fetch_assoc($result_cat))		
			{ 
				$v_row_cat = $row_cat['id'];
				$arr_cat[$v_row_cat] = $row_cat['name'] ;			
			} 
				
			$name = 'sel_cat';
			$options = $arr_cat;
			$selected = $id_cat;
			
			echo dropdown( $name, $options, $selected );
			?>
			</td>
		
		<td height="12px"></td>	
		
			<td style="margin-top:2px; width:100px;"><?php echo __('Type'); ?>:  </td>		
			<td style="margin-top:2px;">
			<?php
			// lista de tipos		
			$arr_tip = array();
			$arr_tip[0] = "-----" ;
			$arr_tip[1] = __('Incident') ;
			$arr_tip[2] = __('Request');			
			$name = 'sel_tip';
			$options = $arr_tip;
			$selected = $id_tip;
			
			echo dropdown( $name, $options, $selected );
			?>
			</td>
		</tr>
		<tr><td height="12px"></td></tr>			
		<tr>
			<td style="margin-top:2px; width:100px;"><?php echo __('Due Date','dashboard'); ?>:  </td>		
			<td style="margin-top:2px;">
			<?php
			// lista de tipos		
			$arr_due = array();
			$arr_due[0] = "-----" ;
			$arr_due[1] = __('Overdue', 'dashboard') ;
			$arr_due[2] = __('Within','dashboard');			
			$name = 'sel_due';
			$options = $arr_due;
			$selected = $id_due1;
			
			echo dropdown( $name, $options, $selected );
			?>

			</td>
			
		<td height="12px"></td>
		
			<td style="margin-top:2px; width:165px;"><?php echo __('Source'); ?>: </td>		
			<td style="margin-top:2px;">
			<?php
			// lista de origem		
			$sql_req = "
			SELECT id, name
			FROM glpi_requesttypes
			ORDER BY id ASC ";
			
			$result_req = $DB->query($sql_req);
			
			$arr_req = array();
			$arr_req[0] = "-----";
			
			while ($row_req = $DB->fetch_assoc($result_req))		
			{ 
				$v_row_req = $row_req['id'];
				$arr_req[$v_row_req] = $row_req['name'] ;			
			} 
				
			$name = 'sel_req';
			$options = $arr_req;
			$selected = $id_req;
			
			echo dropdown( $name, $options, $selected );
			?>
			</td>
		</tr>
		<tr><td height="12px"></td></tr>	
		<tr>
			<td colspan="5" align="center">		 
				<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult', 'dashboard'); ?></button>
				<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" > <i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean', 'dashboard'); ?> </button></td>
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

<?php 

//entidades
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
	
	//entity
	if(!isset($_REQUEST["sel_ent"]) || $_REQUEST["sel_ent"] == 0 || $_REQUEST["sel_ent"] == "" ) 
	{ 
		if(in_array(0,$user_ents)) {
			$id_ent = 0 ;
			$entidade = '';
		}
		else {			
			$id_ent = implode(',',$_SESSION['glpiactiveentities']); 
	   	$entidade = "AND glpi_tickets.entities_id IN (".$id_ent.")";
	   }	
	}
	
	else { 
		$id_ent = $_REQUEST["sel_ent"]; 
		$entidade = "AND glpi_tickets.entities_id IN (".$id_ent.") ";
	}
	
	$arr_param = array($id_ent, $id_sta, $id_req, $id_pri, $id_cat, $id_tip);
	
	//dates
	if($data_ini2 == $data_fin2) {
		$datas2 = "LIKE '%".$data_ini2."%'";			
	}	
	
	else {
		$datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";	
	}	
	
	//date type
	if($id_sta1 == 5) {
		$period = "AND glpi_tickets.solvedate ".$datas2." ";	
	}
	
	elseif($id_sta1 == 6) {
		$period = "AND glpi_tickets.closedate ".$datas2." ";	
	}	
	
	else {
		$period = "AND glpi_tickets.date ".$datas2." ";	 
	}
	
	
	// Chamados
	$sql_cham = 
	"SELECT id, entities_id, name, date, closedate, solvedate, status, users_id_recipient, requesttypes_id, priority, itilcategories_id, type, time_to_resolve 
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = 0
	".$entidade."
	".$period."
	".$id_sta."
	".$id_due."
	AND glpi_tickets.requesttypes_id LIKE '%".$id_req."'
	AND glpi_tickets.priority LIKE '%".$id_pri."'
	AND glpi_tickets.itilcategories_id LIKE '%".$id_cat."'
	AND glpi_tickets.type LIKE '%".$id_tip."'
	ORDER BY id DESC ";
	
	$result_cham = $DB->query($sql_cham);
	
	$consulta1 = 
	"SELECT COUNT(glpi_tickets.id) AS total
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = 0
	".$entidade."
	".$period."
	".$id_sta."
	".$id_due."
	AND glpi_tickets.requesttypes_id LIKE '%".$id_req."'
	AND glpi_tickets.priority LIKE '%".$id_pri."'
	AND glpi_tickets.itilcategories_id LIKE '%".$id_cat."'
	AND glpi_tickets.type LIKE '%".$id_tip."' ";
	
	$result_cons1 = $DB->query($consulta1);
	//$conta_cons = $DB->numrows($result_cons1);
	$conta_cons = $DB->result($result_cons1,0,'total');
	
	$consulta = $conta_cons;

if($consulta > 0) {
	
	// nome da entidade
	
	$chk_ent = explode(',',$id_ent);
	$count_ent = count($chk_ent);
	
	if($count_ent == 1) {
		$sql_nm = "
		SELECT name, completename AS cname
		FROM `glpi_entities`
		WHERE id IN (".$id_ent.")";
		
		$result_nm = $DB->query($sql_nm);
		$ent_name = $DB->fetch_assoc($result_nm);	
	}
	else { $ent_name['cname'] = __("All"); }
	
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
	".$entidade."
	".$period."
	".$id_sta."
	".$id_due."
	AND glpi_tickets.requesttypes_id LIKE '%".$id_req."'
	AND glpi_tickets.priority LIKE '%".$id_pri."'
	AND glpi_tickets.itilcategories_id LIKE '%".$id_cat."'
	AND glpi_tickets.type LIKE '%".$id_tip."' ";
	
	 $result_stat = $DB->query($query_stat);
	
	$new = $DB->result($result_stat,0,'new') + 0;
	$assig = $DB->result($result_stat,0,'assig') + 0;
	$plan = $DB->result($result_stat,0,'plan') + 0;
	$pend = $DB->result($result_stat,0,'pend') + 0;
	$solve = $DB->result($result_stat,0,'solve') + 0;
	$close = $DB->result($result_stat,0,'close') + 0;	
	
	//listar chamados
	echo "
	<div class='well info_box fluid col-md-12 report-tic' style='margin-left: -1px;'>
	
	<table class='fluid'  style=' width:100%; font-size: 18px; font-weight:bold;  margin-bottom:25px;  margin-top:20px; ' cellpadding = 1px>
		<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Entity', 'dashboard').": </span>".$ent_name['cname']." </td>
		<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Tickets', 'dashboard').": </span>".$consulta." </td>
		<td colspan='3' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>
		".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)." 
		</td>
		<td>&nbsp;</td>
		
	</table>
	
	<table style='font-size: 16px; font-weight:bold; width: 50%;' border=0>
		<tr>
			  <td><span style='color: #000;'>". _x('status','New').": </span><b>".$new." </b></td>
	        <td><span style='color: #000;'>". __('Assigned'). ": </span><b>". ($assig + $plan) ."</b></td>
	        <td><span style='color: #000;'>". __('Pending').": </span><b>".$pend." </b></td>
	        <td><span style='color: #000;'>". __('Solved','dashboard').": </span><b>".$solve." </b></td>
	        <td><span style='color: #000;'>". __('Closed').": </span><b>".$close." </b></td>
		</tr>
		<tr><td>&nbsp;</td></tr>	
	</table>
	
	<table id='ticket' class='display'  style='width: 99%; font-size: 11px; font-weight:bold;' cellpadding = 2px>
		<thead>
			<tr>
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('ID')." </th>
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('Status')." </th>
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('Type')." </th>
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('Source')." </th>
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('Priority')." </th>
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('Category')." </th>
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('Title')." </th>
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('Content')." </th>
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('Requester')." </th>
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('Technician')." </th>			
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('Opened','dashboard')."</th>
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('Closed')." </th>
				<th style='font-size: 12px; text-align: center; cursor:pointer;'> ".__('Due Date','dashboard')." </th>
			</tr>
		</thead>
	<tbody>";
	
	
	while($row = $DB->fetch_assoc($result_cham)){
		
		$status1 = $row['status']; 
	
		if($status1 == "1" ) { $status1 = "new";} 
		if($status1 == "2" ) { $status1 = "assign";} 
		if($status1 == "3" ) { $status1 = "plan";} 
		if($status1 == "4" ) { $status1 = "waiting";} 
		if($status1 == "5" ) { $status1 = "solved";}  	            
		if($status1 == "6" ) { $status1 = "closed";}	
		
		//type
		if($row['type'] == 1) { $type = __('Incident'); }
		else { $type = __('Request'); }
		
		//priority
		$prio = $row['priority'];
		
		if($prio == "1" ) { $pri = _x('priority', 'Very low');} 
		if($prio == "2" ) { $pri = _x('priority', 'Low');} 
		if($prio == "3" ) { $pri = _x('priority', 'Medium');} 
		if($prio == "4" ) { $pri = _x('priority', 'High');} 
		if($prio == "5" ) { $pri = _x('priority', 'Very high');} 
		if($prio == "6" ) { $pri = _x('priority', 'Major');} 
		
		//requerente	
		$sql_user = "SELECT glpi_tickets.id AS id, glpi_tickets.name AS title, glpi_tickets.content AS content, glpi_users.firstname AS name, glpi_users.realname AS sname
		FROM `glpi_tickets_users` , glpi_tickets, glpi_users
		WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
		AND glpi_tickets.id = ". $row['id'] ."
		AND glpi_tickets_users.`users_id` = glpi_users.id
		AND glpi_tickets_users.type = 1 ";
	
		$result_user = $DB->query($sql_user);
				
		$row_user = $DB->fetch_assoc($result_user);
					
		//tecnico	
		$sql_tec = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
		FROM `glpi_tickets_users` , glpi_tickets, glpi_users
		WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
		AND glpi_tickets.id = ". $row['id'] ."
		AND glpi_tickets_users.`users_id` = glpi_users.id
		AND glpi_tickets_users.type = 2 ";
		
		$result_tec = $DB->query($sql_tec);	
		$row_tec = $DB->fetch_assoc($result_tec);
			
			
		//origem	
		$sql_req = "SELECT glpi_tickets.id AS id, glpi_requesttypes.name AS name
		FROM `glpi_tickets` , glpi_requesttypes
		WHERE glpi_tickets.requesttypes_id = glpi_requesttypes.`id`
		AND glpi_tickets.id = ". $row['id'] ." ";
		
		$result_req = $DB->query($sql_req);	
		$row_req = $DB->fetch_assoc($result_req);
			
			
		//categoria	
		$sql_cat = "SELECT glpi_tickets.id AS id, glpi_itilcategories.completename AS name
		FROM `glpi_tickets`, glpi_itilcategories
		WHERE glpi_tickets.itilcategories_id = glpi_itilcategories.`id`
		AND glpi_tickets.id = ". $row['id'] ." ";
		
		$result_cat = $DB->query($sql_cat);	
		$row_cat = $DB->fetch_assoc($result_cat);
				
		//check time_to_resolve	
		$sql_due = "SELECT time_to_resolve, closedate, solvedate 
		FROM glpi_tickets
		WHERE glpi_tickets.is_deleted = 0
		AND glpi_tickets.id = ". $row['id'] ." ";
				
		$result_due = $DB->query($sql_due);
		$row_due = $DB->fetch_assoc($result_due);
	
			
	echo "	
		<tr style='font-weight:normal;'>
			<td style='vertical-align:middle; text-align:center; font-weight:bold;'><a href=".$CFG_GLPI['url_base']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
			<td style='vertical-align:middle;'><img src=".$CFG_GLPI['url_base']."/pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/>&nbsp; ".Ticket::getStatus($row['status'])."</td>
			<td style='vertical-align:middle;'> ". $type ." </td>
			<td style='vertical-align:middle;'> ". $row_req['name'] ." </td>
			<td style='vertical-align:middle;text-align:center;'> ". $pri ." </td>
			<td style='vertical-align:middle; max-width:150px;'> ". $row_cat['name'] ." </td>		
			<td style='vertical-align:middle;'> ". substr($row_user['title'],0,55) ." </td>
			<td style='vertical-align:middle; max-width:550px;'> ". html_entity_decode($row_user['content']) ." </td>
			<td style='vertical-align:middle;'> ". $row_user['name'] ." ".$row_user['sname'] ." </td>
			<td style='vertical-align:middle;'> ". $row_tec['name'] ." ".$row_tec['sname'] ." </td>
			<td style='vertical-align:middle; text-align:center;'> ". conv_data_hora($row['date']) ." </td>		
			<td style='vertical-align:middle; text-align:center;'> ". conv_data_hora($row['solvedate']) ." </td>";		
				
				$today = date("Y-m-d H:i:s");
				
				if($row['solvedate'] > $row['time_to_resolve']) {
						echo "<td style='vertical-align:middle; text-align:center; color:red;'> ". conv_data_hora($row['time_to_resolve']) ." </td>";
					}	
				
					else {	
						
						if(!isset($row['solvedate']) AND $today > $row['time_to_resolve']) {
							echo "<td style='vertical-align:middle; text-align:center; color:red;'> ". conv_data_hora($row['time_to_resolve']) ." </td>";
						}
					
						else {
							echo "<td style='vertical-align:middle; text-align:center; color:green;'> ". conv_data_hora($row['time_to_resolve']) ." </td>";
						}					
				}
			
	echo "		
		</tr>";
}

echo "</tbody>
		</table>
		</div>";	
				 	
?>

<script type="text/javascript" charset="utf-8">
$('#ticket')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover dataTable');

$(document).ready(function() {
    $('#ticket').DataTable( {    	

		  select: true,	    	    	
        dom: 'Blfrtip',
        stateSave: true,
        filter: false,        
        deferRender: true,
        pagingType: "full_numbers",
        sorting: [[0,'desc'],[1,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc'],[6,'desc'],[7,'desc'],[8,'desc'],[9,'desc'],[10,'desc'],[11,'desc']],
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
		                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Entity'); ?> : </span><?php echo $ent_name['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
		                 exportOptions: {				                   			                        
		                 		columns: ':visible'				                    
		                }		     
		                }, 
							  {               
		                 extend: "print",
		                 autoPrint: true,
		                 text: "<?php echo __('Selected','dashboard'); ?>",
		                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Entity'); ?> : </span><?php echo $ent_name['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
		                 exportOptions: {
		                 	  columns: ':visible',
		                    modifier: {
		                        selected: true,				                        
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
                 		message: "<?php echo __('Entity', 'dashboard'); ?> : <?php echo $ent_name['name'] . '  -  '; ?> <?php echo  __('Tickets','dashboard'); ?> : <?php echo $consulta .'  -  '; ?> <?php echo  __('Period','dashboard'); ?> : <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> ",
                 		exportOptions: {			                        
		                  columns: ':visible'				                   
		                }
                  } 
                  ]
             },
             {
          		extend: 'colvis',
          		text: "<?php echo __('Show/hide columns', 'dashboard'); ?>",
          		columns: ':not(:first-child)',
          		postfixButtons: [ 'colvisRestore' ]

      	   }
        ],
          columnDefs: [ {
          	 // "width": "30%", 
          	 //"targets": 7, 
             //targets: [7],
             //visible: false
        } ]
        
    } );
} );		
</script>  

<?php

echo '</div><br>';
}

else {
	
	echo "
	<div id='nada_rel' class='well info_box fluid col-md-12'>
	<table class='table' style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
	<tr><td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
	<tr></tr>
	</table></div>";	

}	
}
?>

<script type='text/javascript' >
	$(document).ready(function() { $("#sel_ent").select2({dropdownAutoWidth : true}); });
	$(document).ready(function() { $("#sel_sta").select2({dropdownAutoWidth : true}); });
	$(document).ready(function() { $("#sel_req").select2({dropdownAutoWidth : true}); });
	$(document).ready(function() { $("#sel_pri").select2({dropdownAutoWidth : true}); });
	$(document).ready(function() { $("#sel_cat").select2({dropdownAutoWidth : true}); });
	$(document).ready(function() { $("#sel_tip").select2({dropdownAutoWidth : true}); });
	$(document).ready(function() { $("#sel_due").select2({dropdownAutoWidth : true}); });
</script>	

</div>
</div>
</div>

</body> 
</html>

