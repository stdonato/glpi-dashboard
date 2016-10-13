<?php

//define('GLPI_ROOT', '../../../..');
include ("../../../../inc/includes.php");
include ("../../../../config/config.php");
include "../inc/functions.php";

global $DB, $style;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if(!empty($_POST['submit']))
{
    $data_ini = $_POST['date1'];
    $data_fin = $_POST['date2'];
}

else {
    $data_ini = date("Y-m-01");
    $data_fin = date("Y-m-d");
    }

if(!isset($_POST["sel_tec"])) {
    $id_tec = $_GET["tec"];
}

else {
    $id_tec = $_POST["sel_tec"];
}


# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '' || $sel_ent == -1) {
	$sel_ent = 0;
	$entidade = "";
	$entidade_u = "";
}
else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
	$entidade_u = "AND glpi_users.entities_id IN (".$sel_ent.")";
}

?>
<html>
<head>
<title> GLPI - <?php echo __('Tickets', 'dashboard') .'  '. __('by Requester', 'dashboard') ?> </title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
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

<script language="javascript" src="../js/jquery.js"></script>
<link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
<script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>

<script src="../js/bootstrap-datepicker.js"></script>
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">
<link href="../less/datepicker.less" rel="stylesheet" type="text/css">

<script src="../js/media/js/jquery.dataTables.min.js"></script>
<link href="../js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />  
<script src="../js/media/js/dataTables.bootstrap.js"></script> 
<link href="../js/extensions/TableTools/css/dataTables.tableTools.css" type="text/css" rel="stylesheet" />
<script src="../js/extensions/TableTools/js/dataTables.tableTools.js"></script>

<style type="text/css">	
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
	a:link, a:visited, a:active { text-decoration: none; }
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 

</head>

<body style="background-color: #e5e5e5; margin-left:0%;">
<?php

$sql_tec = "
SELECT DISTINCT glpi_users.id AS id , glpi_users.firstname AS name, glpi_users.realname AS sname
FROM glpi_users, glpi_tickets_users
WHERE glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets_users.type = 1
AND glpi_users.is_deleted = 0
AND glpi_users.is_active = 1
".$entidade_u."
ORDER BY name ASC ";

$result_tec = $DB->query($sql_tec);
$tec = $DB->fetch_assoc($result_tec);

?>
<div id='content' >
<div id='container-fluid' style="margin: 0px 5% 0px 5%;">
<div id="charts" class="fluid chart" >
<div id="pad-wrapper" >
<div id="head" class="fluid">

<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

    <div id="titulo_rel"> <?php echo __('Tickets', 'dashboard') .'  '. __('by Requester', 'dashboard') ?>  </div>
    <div id="datas-tec" class="col-md-12 fluid" >
    <form id="form1" name="form1" class="form_rel" method="post" action="rel_usuario.php?con=1">
	    <table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef">
	    <tr>
			<td style="width: 310px;">
			
			<?php
			$url = $_SERVER['REQUEST_URI'];
			$arr_url = explode("?", $url);
			$url2 = $arr_url[0];
			
			echo '
			<table style="margin-top:0px;" border=0>
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
			
			// lista de técnicos
			$res_tec = $DB->query($sql_tec);
			$arr_tec = array();
			$arr_tec[0] = "-- ". __('Select a requester', 'dashboard') . " --" ;
			
			$DB->data_seek($result_tec, 0) ;
			
			while ($row_result = $DB->fetch_assoc($result_tec))
			    {
			    	$v_row_result = $row_result['id'];
			    	$arr_tec[$v_row_result] = $row_result['name']." ".$row_result['sname'] ;
			    }
			
			$name = 'sel_tec';
			$options = $arr_tec;
			$selected = 0;
			
			echo dropdown( $name, $options, $selected );									
			?>
			</td>
	</tr>
	<tr>
			<td height="15px"></td>
	</tr>
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
</div>

<script language="Javascript">
	$('#dp1').datepicker('update');
	$('#dp2').datepicker('update');
</script>

<?php

//tecnico2
if(isset($_GET['con'])) {

$con = $_GET['con'];

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

if(!isset($_POST["sel_tec"])) {
	$id_tec = $_GET["tec"];
}

else {
	$id_tec = $_POST["sel_tec"];
}

if($id_tec == 0) {
	echo '<script language="javascript"> alert(" ' . __('Select a requester', 'dashboard') . ' "); </script>';
	echo '<script language="javascript"> location.href="rel_usuario.php"; </script>';
}

if($data_ini2 === $data_fin2) {
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
        $stat = "open";
    }
    elseif($_GET['stat'] == "close") {
        $status = $status_close;
        $stat = "close";
    }
    else {
        $status = $status_all;
        $stat = "all";
    }
}

else {
        $status = $status_all;
        $stat = "all";
    }


// Chamados
$sql_cham =
"SELECT glpi_tickets.id AS id, glpi_tickets.name AS name, glpi_tickets.date AS date, glpi_tickets.solvedate as solvedate, glpi_tickets.status,
FROM_UNIXTIME( UNIX_TIMESTAMP( `glpi_tickets`.`solvedate` ) , '%Y-%m' ) AS date_unix, AVG( glpi_tickets.solve_delay_stat ) AS time
FROM `glpi_tickets_users` , glpi_tickets
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets_users.type = 1
AND glpi_tickets_users.users_id = ". $id_tec ."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
".$entidade."
AND glpi_tickets.status IN ".$status."
GROUP BY id
ORDER BY id DESC ";

$result_cham = $DB->query($sql_cham);


$consulta1 =
"SELECT glpi_tickets.id AS id, glpi_tickets.name, glpi_tickets.date AS adate, glpi_tickets.solvedate AS sdate,
FROM_UNIXTIME( UNIX_TIMESTAMP( `glpi_tickets`.`solvedate` ) , '%Y-%m' ) AS date_unix, AVG( glpi_tickets.solve_delay_stat ) AS time
FROM `glpi_tickets_users` , glpi_tickets
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets_users.type = 1
AND glpi_tickets_users.users_id = ". $id_tec ."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
".$entidade."
AND glpi_tickets.status IN ".$status."
GROUP BY id
ORDER BY id DESC
";

$result_cons1 = $DB->query($consulta1);
$conta_cons = $DB->numrows($result_cons1);

$consulta = $conta_cons;


if($consulta > 0) {

//abertos

$sql_ab = "SELECT count( glpi_tickets.id ) AS total, glpi_tickets_users.`users_id` AS id
FROM `glpi_tickets_users`, glpi_tickets
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets.date ".$datas2."
AND glpi_tickets_users.users_id = ".$id_tec."
AND glpi_tickets.status IN ".$status_open."
AND glpi_tickets.is_deleted = 0
".$entidade." " ;

$result_ab = $DB->query($sql_ab) or die ("erro_ab");
$data_ab = $DB->fetch_assoc($result_ab);

$abertos = $data_ab['total'];

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
	
	// cor barra
	if($barra == 100) { $cor = "progress-bar-success"; }
	if($barra >= 80 and $barra < 100) { $cor = " "; }
	if($barra > 51 and $barra < 80) { $cor = "progress-bar-warning"; }
	if($barra > 0 and $barra <= 50) { $cor = "progress-bar-danger"; }

	}
}
else { $barra = 0;}


//nome e total
$sql_nome = "
SELECT firstname , realname, name
FROM glpi_users
WHERE id = ".$id_tec."
".$entidade_u." ";

$result_nome = $DB->query($sql_nome) ;

while($row = $DB->fetch_assoc($result_nome)){

	$user = $row['firstname'] ." ". $row['realname'];
	
	echo "
	
	<div class='well info_box fluid span12' style='margin-top:25px; margin-left: -1px;'>
	
	<table class='fluid'  style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
	<tr>
		<td style='vertical-align:middle;'> <span style='color: #000;'>".__('Requester', 'dashboard').": </span>  ". $row['firstname'] ." ". $row['realname']. "</td>
		<td style='vertical-align:middle;'> <span style='color: #000;'>".__('Tickets', 'dashboard').": </span>". $conta_cons ."</td>
		<td colspan='3' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)."
		</td>
		
		<td style='vertical-align:middle; width: 190px; '>
		<div class='progress' style='margin-top: 19px;'>
			<div class='progress-bar ". $cor ." progress-bar-striped active' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
    			".$barra." % ".__('Closed', 'dashboard') ."	
    		</div>		
		</div>		   
		</td>
	</tr>
	</table>
	
	<table align='right' style='margin-bottom:10px;'>
		<tr>
			<td>
				<button class='btn btn-primary btn-sm' type='button' name='abertos' value='Abertos' onclick='location.href=\"rel_usuario.php?con=1&stat=open&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('Opened', 'dashboard') ." </button>
				<button class='btn btn-primary btn-sm' type='button' name='fechados' value='Fechados' onclick='location.href=\"rel_usuario.php?con=1&stat=close&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('Closed', 'dashboard')." </button>	
				<button class='btn btn-primary btn-sm' type='button' name='todos' value='Todos' onclick='location.href=\"rel_usuario.php?con=1&stat=all&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('All', 'dashboard')." </button>
			</td>	
		</tr>
	</table>
	
	<table>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	
	<table id='users' class='display' style='font-size: 13px; font-weight:bold;' cellpadding = 2px>
		<thead>
			<tr>
				<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Tickets', 'dashboard') ." </th>
				<th style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Status')." </th>
				<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Title') ."</th>
				<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Opened', 'dashboard') ."</th>
				<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Closed') ."</th>
				<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Resolution time') ."</th>
			</tr>
		</thead>
		<tbody>
	";
}

//listar chamados

while($row = $DB->fetch_assoc($result_cham)){

    $status1 = $row['status'];

    if($status1 == "1" ) { $status1 = "new";}
    if($status1 == "2" ) { $status1 = "assign";}
    if($status1 == "3" ) { $status1 = "plan";}
    if($status1 == "4" ) { $status1 = "waiting";}
    if($status1 == "5" ) { $status1 = "solved";}
    if($status1 == "6" ) { $status1 = "closed";}


    echo "
<tr>
	<td style='vertical-align:middle; text-align:center;'><a href=".$CFG_GLPI['root_doc']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a> </td>
	<td style='vertical-align:middle;'><img src=".$CFG_GLPI['root_doc']."/pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/> &nbsp; ".Ticket::getStatus($row['status'])."</td>
	<td> ". substr($row['name'],0,75) ." </td>
	<td> ". conv_data_hora($row['date']) ." </td>
	<td> ". conv_data_hora($row['solvedate']) ." </td>
	<td> ". time_ext($row['time']) ."</td>
</tr>";
}

echo "</tbody>
		</table>
		</div>"; ?>

<script type="text/javascript" charset="utf-8">

$('#users')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered');

$(document).ready(function() {
    oTable = $('#users').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bFilter": false,
        "aaSorting": [[0,'desc']], 
        "iDisplayLength": 25,
    	  "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]], 

        "sDom": 'T<"clear">lfrtip',
         "oTableTools": {
         "aButtons": [
             {
                 "sExtends": "copy",
                 "sButtonText": "<?php echo __('Copy'); ?>"
             },
             {
                 "sExtends": "print",
                 "sButtonText": "<?php echo __('Print','dashboard'); ?>",
                 "sMessage": "<div class='info_box fluid col-md-12' style='margin-top:20px; margin-bottom:12px; margin-left: -1px;'><table class='fluid' border='0' style='width: 89%; margin-left: 7%; font-size: 18px; font-weight:bold;' cellpadding = '1px'> <tr><td> <img src='../img/logo.png' style='width:300px;' /> </td></tr> <tr><td>&nbsp;</td></tr>  <tr><td colspan='1' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Requester', 'dashboard'); ?> : </span><?php echo $user; ?> </td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td></tr></table></div>"
             },
             {
                 "sExtends":    "collection",
                 "sButtonText": "<?php echo _x('button', 'Export'); ?>",
                 "aButtons":    [ "csv", "xls",
                  {
                 "sExtends": "pdf",
                 "sPdfOrientation": "landscape",
                 "sPdfMessage":" "
                 //"sPdfMessage": "<div class='info_box fluid span12' style='margin-top:20px; margin-bottom:12px; margin-left: -1px;'><table class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'> <tr><td> <img src='../img/logo-login-glpi.png' /> </td></tr> <tr><td>&nbsp;</td></tr>  <tr><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Requester', 'dashboard'); ?> : </span><?php echo $user; ?> </td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td></tr></table></div>"
                  } ]
             }
         ]
        }
		  
    });    
} );
		
</script>  

<?php

echo '</div><br>';

}


	else {
	
		echo "
		<div class='well info_box fluid span12' style='margin-top:30px; margin-left: -3px;'>
		<table class='table' style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
		<tr><td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
		<tr></tr>
		</table></div>";
	
	}
}
}
?>

<script type="text/javascript" >
	$(document).ready(function() { $("#sel1").select2(); });
</script>

</div>
</div>
</div>
</body>
</html>
