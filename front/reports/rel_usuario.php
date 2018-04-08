<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");
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
    $id_tec = $_GET["sel_tec"];
}

else {
    $id_tec = $_POST["sel_tec"];
}


# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '' || $sel_ent == -1) {

	$entities = $_SESSION['glpiactiveentities'];	
	$ent = implode(",",$entities);

	$entidade = "AND glpi_tickets.entities_id IN (".$ent.")";
	$entidade_u = "AND glpi_profiles_users.entities_id IN (".$ent.")";
	$entidade1 = "";

}
else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
	$entidade_u = "AND glpi_profiles_users.entities_id IN (".$sel_ent.")";
}

?>
<html>
<head>
<title> GLPI - <?php echo __('Tickets', 'dashboard') .'  '. __('by Requester', 'dashboard') ?> </title>
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
	a:link, a:visited, a:active { text-decoration: none; }
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>

</head>

<body style="background-color: #e5e5e5;">

<?php
$sql_tec = "
SELECT DISTINCT glpi_users.id AS id , glpi_users.firstname AS name, glpi_users.realname AS sname
FROM glpi_users, glpi_tickets_users, glpi_profiles_users
WHERE glpi_tickets_users.users_id = glpi_users.id
AND glpi_profiles_users.users_id = glpi_tickets_users.users_id
AND glpi_tickets_users.type = 1
AND glpi_users.is_deleted = 0
AND `glpi_users`.`is_active` = '1'
".$entidade_u."
ORDER BY name ASC ";

$result_tec = $DB->query($sql_tec);
$tec = $DB->fetch_assoc($result_tec);
?>

<div id='content' >
<div id='container-fluid' style="margin: <?php echo margins(); ?> ;">
<div id="charts" class="fluid chart" >
<div id="pad-wrapper" >
<div id="head-rel" class="fluid">

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
			    	$arr_tec[$v_row_result] = $row_result['name']." ".$row_result['sname']." (".$row_result['id'].")" ;
			    }

				$name = 'sel_tec';
				$options = $arr_tec;
				$selected = $id_tec;

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
	$id_tec = $_GET["sel_tec"];
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
FROM `glpi_tickets_users`, glpi_tickets
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets_users.type = 1
AND glpi_tickets_users.users_id = ". $id_tec ."
AND glpi_tickets.is_deleted = 0
AND (glpi_tickets.date ".$datas2." OR glpi_tickets.closedate ".$datas2." )
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
ORDER BY id DESC ";

$result_cons1 = $DB->query($consulta1);
$conta_cons = $DB->numrows($result_cons1);

$consulta = $conta_cons;

//AND (glpi_tickets.date ".$datas2." OR glpi_tickets.closedate ".$datas2." )

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
	if($barra < 0) { $cor = "progress-bar-danger"; $barra = 0; }

	}
}
else { $barra = 0;}


//nome e total
$sql_nome = "
SELECT DISTINCT glpi_users.id, glpi_users.firstname, glpi_users.realname, glpi_users.name, glpi_users.picture
FROM glpi_users, glpi_profiles_users
WHERE glpi_users.id = ".$id_tec."
AND glpi_users.id = glpi_profiles_users.users_id 
".$entidade_u." ";

$result_nome = $DB->query($sql_nome) ;

while($row = $DB->fetch_assoc($result_nome)){

$user = $row['firstname'] ." ". $row['realname'];



	//count by status
	$query_stat = "
	SELECT
	SUM(case when glpi_tickets.status = 1 then 1 else 0 end) AS new,
	SUM(case when glpi_tickets.status = 2 then 1 else 0 end) AS assig,
	SUM(case when glpi_tickets.status = 3 then 1 else 0 end) AS plan,
	SUM(case when glpi_tickets.status = 4 then 1 else 0 end) AS pend
	FROM glpi_tickets_users, glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
   AND glpi_tickets.date ".$datas2." 
	AND glpi_tickets_users.users_id = ".$id_tec."
	AND glpi_tickets_users.type = 1
	AND glpi_tickets_users.tickets_id = glpi_tickets.id ";

	$result_stat = $DB->query($query_stat);
	
	
	$query_stat_c = "
	SELECT count( glpi_tickets.id ) AS close, glpi_tickets_users.users_id AS id
	FROM glpi_tickets_users, glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.closedate ".$datas2." 
	AND glpi_tickets_users.users_id = ".$id_tec."
	AND glpi_tickets_users.type = 1
	AND glpi_tickets.status = 6
	".$entidade_age."
	AND glpi_tickets_users.tickets_id = glpi_tickets.id ";

   $result_stat_c = $DB->query($query_stat_c);
   $close = $DB->result($result_stat_c,0,'close');
   
   
   $query_stat_s = "
	SELECT SUM(case when glpi_tickets.status = 5 then 1 else 0 end) AS solve
	FROM glpi_tickets_users, glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND (glpi_tickets.solvedate ".$datas2." OR glpi_tickets.closedate ".$datas2.") 
	AND glpi_tickets_users.users_id = ".$id_tec."
	AND glpi_tickets_users.type = 1
	".$entidade_age."
	AND glpi_tickets_users.tickets_id = glpi_tickets.id ";

   $result_stat_s = $DB->query($query_stat_s);
   $solve = $DB->result($result_stat_s,0,'solve') + 0; 	

        $new = $DB->result($result_stat,0,'new') + 0;
        $assig = $DB->result($result_stat,0,'assig') + 0;
        $plan = $DB->result($result_stat,0,'plan') + 0;
        $pend = $DB->result($result_stat,0,'pend') + 0;
/*        $solve = $DB->result($result_stat,0,'solve') + 0;
        $close = $DB->result($result_stat,0,'close') + 0;*/


	echo "

	<div class='well info_box fluid col-md-12 report' style='margin-left: -1px;'>

	<table class='fluid' style='width:100%; font-size: 18px; font-weight:bold;' cellpadding = 1px>
	<tr>
		<td><img class='avatar2' width='40px' height='43px' src='".User::getURLForPicture($row['picture'])."'></img></td>
		<td style='vertical-align:middle;'> <span style='color: #000;'>".__('Requester', 'dashboard').": </span>  ". $row['firstname'] ." ". $row['realname']. "</td>
		<td style='vertical-align:middle;'> <span style='color: #000;'>".__('Tickets', 'dashboard').": </span>". $conta_cons ."</td>
		<td colspan='3' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000; font-size: 18px;'>".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)."
		</td>

		<td style='vertical-align:middle; width: 190px; '>
		<div class='progress' style='margin-top: 19px;'>
			<div class='progress-bar ". $cor ." ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
    			".$barra." % ".__('Closed', 'dashboard') ."
    		</div>
		</div>
		</td>
	</tr>
	</table>

	<table align='right' style='margin-bottom:10px;'>
		<tr>
			<td>
				<button class='btn btn-primary btn-sm' type='button' name='abertos' value='Abertos' onclick='location.href=\"rel_usuario.php?con=1&stat=open&sel_tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('Opened', 'dashboard') ." </button>
				<button class='btn btn-primary btn-sm' type='button' name='fechados' value='Fechados' onclick='location.href=\"rel_usuario.php?con=1&stat=close&sel_tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('Closed', 'dashboard')." </button>
				<button class='btn btn-primary btn-sm' type='button' name='todos' value='Todos' onclick='location.href=\"rel_usuario.php?con=1&stat=all&sel_tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('All', 'dashboard')." </button>
			</td>
		</tr>
	</table>

<table style='font-size: 16px; font-weight:bold; width: 50%;' border=0>
	<tr>
		<td><span style='color: #000;'>". _x('status','New').": </span>".$new." </td>
		<td><span style='color: #000;'>". __('Assigned'). ": </span>". ($assig + $plan) ."</td>
		<td><span style='color: #000;'>". __('Pending').": </span>".$pend." </td>
		<td><span style='color: #000;'>". __('Solved','dashboard').": </span>".$solve." </td>
		<td><span style='color: #000;'>". __('Closed').": </span>".$close." </td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
</table>

	<table id='users' class='display' style='font-size: 13px; font-weight:bold;' cellpadding = 2px>
		<thead>
			<tr>
				<th style='text-align:center; cursor:pointer;'> ". __('Tickets', 'dashboard') ." </th>
				<th style='text-align:center; cursor:pointer;'> ". __('Status', 'dashboard') ." </th>
				<th style='text-align:center; cursor:pointer;'> ". __('Title') ."</th>
				<th style='text-align:center; cursor:pointer;'> ". __('Opened', 'dashboard') ."</th>
				<th style='text-align:center; cursor:pointer;'> ". __('Closed') ."</th>
				<th style='text-align:center; cursor:pointer;'> ". __('Resolution time') ."</th>
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
		<tr style='font-weight:normal;'>
			<td style='vertical-align:middle; text-align:center; font-weight:bold;'><a href=".$CFG_GLPI['url_base']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a> </td>
			<td style='vertical-align:middle;'><img src=".$CFG_GLPI['url_base']."/pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/> &nbsp; ".Ticket::getStatus($row['status'])."</td>
			<td> ". substr($row['name'],0,75) ." </td>
			<td style='text-align:center; vertical-align:middle;'> ". conv_data_hora($row['date']) ." </td>
			<td style='text-align:center; vertical-align:middle;'> ". conv_data_hora($row['solvedate']) ." </td>
			<td style='text-align:center; vertical-align:middle;'> ". time_ext($row['time']) ."</td>
		</tr>";
}

echo "</tbody>
		</table>
		</div>"; ?>

<script type="text/javascript" charset="utf-8">

$('#users')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover dataTable');

$(document).ready(function() {
    $('#users').DataTable( {    	

		  select: true,	    	    	
        dom: 'Blfrtip',
        filter: false,        
        pagingType: "full_numbers",
        deferRender: true,
        sorting: [[0,'desc'],[1,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc']],
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
		                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid' style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Requester', 'dashboard'); ?> : </span><?php echo $user; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",		     
		                }, 
							  {               
		                 extend: "print",
		                 autoPrint: true,
		                 text: "<?php echo __('Selected','dashboard'); ?>",
		                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid' style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Requester', 'dashboard'); ?> : </span><?php echo $user; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
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
                 		message: "<?php echo __('Requester', 'dashboard'); ?> : <?php echo $user . '  - '; ?> <?php echo  __('Tickets','dashboard'); ?> : <?php echo $consulta . '  - '; ?> <?php echo  __('Period','dashboard'); ?> : <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?>",
                  } 
                  ]
             }
        ]
        
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
}
?>

<script type="text/javascript" >
	$(document).ready(function() { $("#sel1").select2({dropdownAutoWidth : true}); });
</script>

</div>
</div>
</div>
</body>
</html>
