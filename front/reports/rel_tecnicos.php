<?php

include ("../../../../inc/includes.php");
include ("../../../../config/config.php");

global $DB, $con;

Session::checkLoginUser();
Session::checkRight("profile", READ);


function dropdown( $name, array $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select id="sel_techs" style="width: 300px;" autofocus onChange="javascript: document.form1.submit.focus()" name="'.$name.'" id="'.$name.'">'."\n";

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

function time_ext($solvedate)
{

$time = $solvedate; // time duration in seconds

 if ($time == 0){
        return '';
    }

	$days = floor($time / (60 * 60 * 24));
	$time -= $days * (60 * 60 * 24);
	
	$hours = floor($time / (60 * 60));
	$time -= $hours * (60 * 60);
	
	$minutes = floor($time / 60);
	$time -= $minutes * 60;
	
	$seconds = floor($time);
	$time -= $seconds;
	
	$return = "{$days}d {$hours}h {$minutes}m {$seconds}s"; // 1d 6h 50m 31s
	
	return $return;
}


if(!empty($_POST['submit']))
	{
   	$data_ini = $_REQUEST['date1'];
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
	//$entidade_u = "AND glpi_users.entities_id IN (".$ent.") ";
	$entidade_u = "AND glpi_profiles_users.entities_id IN (".$ent.") ";
	$entidade_g = "WHERE entities_id IN (".$ent.") OR is_recursive = 1";
	$entidade1 = "";

}
else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.") ";
	//$entidade_u = "AND glpi_users.entities_id IN (".$sel_ent.") ";
	$entidade_u = "AND glpi_profiles_users.entities_id IN (".$sel_ent.") ";
	$entidade_g = "WHERE entities_id IN (".$sel_ent.") OR is_recursive = 1";
}

?>

<html>
<head>
<title> GLPI - <?php echo __('Tickets','dashboard') .'  '. __('by Technician','dashboard') ?> </title>
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
<link href="../less/datepicker.less" rel="stylesheet" type="text/css">

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

<style type="text/css">
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
	a:hover { color: #000099;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>

</head>

<body style="background-color: #e5e5e5; margin-left:0%;" >

<div id='content' >
<div id='container-fluid' style="margin: 0px 5% 0px 5%;">
<div id="charts" class="fluid chart" >
	<div id="pad-wrapper" >
		<div id="head-rel" class="fluid">
			<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>
				<div id="titulo_rel" > <?php echo __('Tickets','dashboard') .'  '. __('by Technician','dashboard') ?> </div>
					<div id="datas-tec" class="span12 fluid" >
					<form id="form1" name="form1" class="form_rel" method="post" action="rel_tecnicos.php?con=1" onsubmit="datai();dataf();">

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
										//$arr_techs[0] = "-- ". __('Select a group', 'dashboard') . " --" ;
										$arr_techs[0] = "". __('All') . "" ;

										$DB->data_seek($result_techs, 0) ;

										while ($row_result = $DB->fetch_assoc($result_techs))
										    {
										   	$v_row_result = $row_result['id'];
										    	$arr_techs[$v_row_result] = $row_result['name'] ;										      
										    }

										$name = 'sel_techs';
										$options = $arr_techs;
										$selected = -1;										
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
if(isset($_REQUEST['con'])) {

	$con = $_REQUEST['con'];

	//$con = 1;
	if($con == "1") {

	if(!isset($_REQUEST['date1']))
		{
		  $data_ini2 = $data_ini; //$_REQUEST['date1'];
		  $data_fin2 = $data_fin; //$_REQUEST['date2'];
		  $grupo = "";
		  $grupo1 = "";
		}

	else {
	    $data_ini2 = $_REQUEST['date1'];
	    $data_fin2 = $_REQUEST['date2'];
	    $sel_techs = $_REQUEST['sel_techs'];
	    $id_techs = $_POST["sel_techs"];

	    if($id_techs > 0) {
	    	$glpi_techs = " , glpi_groups_users";
		 	$grupo = "AND glpi_groups_users.users_id = glpi_tickets_users.users_id" ;
		 	$grupo1 = "AND glpi_groups_users.groups_id = ". $id_techs ."" ;
		 	}
		 if($id_techs == 0 || $id_techs == '') {
		  $glpi_techs = "";
		  $grupo = "";
		  $grupo1 = "";
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
". $grupo ."
". $grupo1 ."
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

if($id_techs != -1 && $id_techs != 0) {

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
				<th style='text-align:center; cursor:pointer;'> ". __('Solved','dashboard') ."</th>
				<th style='text-align:center; cursor:pointer;'> ". __('Closed','dashboard') ."</th>
				<th style='text-align:center; '> % ". __('Closed','dashboard') ."</th> 
				<!--<th style='text-align:center; cursor:pointer;'> ". __('Tasks sum','dashboard') ."</th>-->";
				if($sats != '') {
					echo "<th style='text-align:center; '> ". __('Satisfaction','dashboard') ."</th>";
					}
				echo "</tr>
		</thead>
	<tbody>";


while($id_tec = $DB->fetch_assoc($result_tec)) {

//chamados abertos
$sql_ab = "SELECT count( glpi_tickets.id ) AS total, glpi_tickets_users.users_id AS id
FROM glpi_tickets_users, glpi_tickets, glpi_users". $glpi_techs ."
WHERE glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets.date ".$datas2."
AND glpi_tickets_users.users_id = ".$id_tec['id']."
AND glpi_tickets.status NOT IN ".$status_closed."
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets_users.type = 2
". $entidade ."
". $grupo ."
". $grupo1 ." " ;

$result_ab = $DB->query($sql_ab) or die ("erro_ab");
$data_ab = $DB->fetch_assoc($result_ab);

$abertos = $data_ab['total'];


//chamados solucionados
$sql_sol = "SELECT count( glpi_tickets.id ) AS total, glpi_tickets_users.users_id AS id
FROM glpi_tickets_users, glpi_tickets, glpi_users". $glpi_techs ."
WHERE glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets.date ".$datas2."
AND glpi_tickets_users.users_id = ".$id_tec['id']."
AND glpi_tickets.status = 5
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets_users.type = 2
". $entidade ."
". $grupo ."
". $grupo1 ." " ;

$result_sol = $DB->query($sql_sol) or die ("erro_ab");
$data_sol = $DB->fetch_assoc($result_sol);

$solucionados = $data_sol['total'];


//chamados fechados
$sql_clo = "SELECT count( glpi_tickets.id ) AS total, glpi_tickets_users.users_id AS id
FROM glpi_tickets_users, glpi_tickets, glpi_users". $glpi_techs ."
WHERE glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets.date ".$datas2."
AND glpi_tickets_users.users_id = ".$id_tec['id']."
AND glpi_tickets.status = 6
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets_users.type = 2
". $entidade ."
". $grupo ."
". $grupo1 ." " ;

$result_clo = $DB->query($sql_clo) or die ("erro_ab");
$data_clo = $DB->fetch_assoc($result_clo);

$fechados = $data_clo['total'];

/*
//Tasks sum
$sql_tasksum = "SELECT SUM(actiontime) as actiontimesum
FROM glpi_tickettasks
WHERE date ".$datas2."
AND users_id_tech = ".$id_tec['id']."
AND state = 2";

$actiontime_total = 0;
$result_tasksum = $DB->query($sql_tasksum) or die ("erro_ab");
$data_tasksum = $DB->fetch_assoc($result_tasksum);

$tasksum = $data_tasksum['actiontimesum'];
*/

//satisfação por tecnico   , glpi_users.firstname AS fname , glpi_users.realname AS rname, glpi_users.name
$query_sat = "
SELECT glpi_users.id, avg( glpi_ticketsatisfactions.satisfaction ) AS media
FROM glpi_tickets, glpi_ticketsatisfactions, glpi_tickets_users, glpi_users". $glpi_techs ."
WHERE glpi_tickets.is_deleted = '0'
AND glpi_ticketsatisfactions.tickets_id = glpi_tickets.id
AND glpi_ticketsatisfactions.tickets_id = glpi_tickets_users.tickets_id
AND glpi_users.id = glpi_tickets_users.users_id
AND glpi_tickets_users.type = 2
AND glpi_tickets.date ".$datas2."
AND glpi_tickets_users.users_id = ".$id_tec['id']."
".$entidade."
".$grupo."
".$grupo1." ";

$result_sat = $DB->query($query_sat) or die('erro');
$media = $DB->fetch_assoc($result_sat);

$satisfacao = round(($media['media']/5)*100,1);
$nota = round($media['media'],0);



//barra de porcentagem
if($conta_cons > 0) {

if($status == $status_closed ) {
    $barra = 100;
    $cor = "progress-bar-success";
	}

else {

	//porcentagem
	$perc = round(($abertos*100)/$id_tec['chamados'],1);
	$barra = 100 - $perc;

	// cor barra
	if($barra == 100) { $cor = "progress-bar-success"; }
	if($barra >= 80 and $barra < 100) { $cor = " "; }
	if($barra > 51 and $barra < 80) { $cor = "progress-bar-warning"; }
	if($barra > 0 and $barra <= 50) { $cor = "progress-bar-danger"; }
	if($barra <= 0) { $cor = "progress-bar-danger"; $barra = 0; }

	}
}

else { $barra = 0;}

$total_cham = $abertos + $solucionados + $fechados;

		echo "
		<tr>
			<td style='vertical-align:middle; text-align:left;'><i class='del fa fa-times' style='cursor:pointer;' title='". __('Hide') ."'>&nbsp;&nbsp;&nbsp; </i>
			<img class='avatar2' width='40px' height='43px' src='".User::getURLForPicture($id_tec['picture'])."'></img>&nbsp;&nbsp;
			<a href='rel_tecnico.php?con=1&tec=". $id_tec['id'] ."&date1=".$data_ini."&date2=".$data_fin."' target='_blank' >" . $id_tec['fname'].' '.$id_tec['rname']. ' ('.$id_tec['id'].")</a>
			</td>
			<td style='vertical-align:middle; text-align:center;'> ". $total_cham ." </td>
			<td style='vertical-align:middle; text-align:center;'> ". $abertos ." </td>
			<td style='vertical-align:middle; text-align:center;'> ". $solucionados ." </td>
			<td style='vertical-align:middle; text-align:center;'> ". $fechados ." </td>
			<td style='vertical-align:middle; text-align:center;'>
				<div class='progress' style='margin-top: 5px; margin-bottom: 5px;'>
					<div class='progress-bar ". $cor ." progress-bar-striped active' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
			 			".$barra." %
			 		</div>
				</div>
		   </td>";
		   
if($sats != '') {
		echo "<td style='vertical-align:middle; text-align:center;'>
					<img src='../img/s". $nota .".png' alt='".$satisfacao." %' title='".$satisfacao." %'>
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
        sorting: <?php echo $sort; ?>
        //sorting: [[1,'desc'],[0,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc'],[6,'desc']],
		  displayLength: 25,
        lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],        
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

	$(document).ready(function() { $("#sel_techs").select2(); });
</script>

</div>
</div>
</div>
</div>

</body>
</html>
