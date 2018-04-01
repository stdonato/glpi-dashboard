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

if(!isset($_POST["sel_cat"])) {
	$id_cat = $_GET["sel_cat"];
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

	//get all user entities
	$entities = $_SESSION['glpiactiveentities'];										
	$ent = implode(",",$entities);

	$entidade = "AND glpi_tickets.entities_id IN (".$ent.") ";
	$entidade_c = "AND entities_id IN (".$ent.") ";
	$entidade_cw = "WHERE (entities_id IN (".$ent.") OR is_recursive = 1)";	
}

else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.") ";
	$entidade_c = "AND entities_id IN (".$sel_ent.") ";
	$entidade_cw = "WHERE (entities_id IN (".$sel_ent.") OR is_recursive = 1)";
}

?>

<html>
<head>
<title> GLPI - <?php echo __('Tickets', 'dashboard') .'  '. __('by Category', 'dashboard') ?> </title>
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
			<div id="head-lg" class="fluid">
			<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

				<div id="titulo_rel"> <?php echo __('Tickets', 'dashboard') .'  '. __('by Category', 'dashboard') ?> </div>
					<div id="datas-tec" class="col-md-12 col-sm-12 fluid" >
		
						<form id="form1" name="form1" class="form_rel" method="post" action="rel_categoria.php?con=1">
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
				
									// lista de categorias
									$sql_cat = "
									SELECT id, completename AS name
									FROM `glpi_itilcategories`
									". $entidade_cw ."								
									ORDER BY `name` ASC ";
				
									$result_cat = $DB->query($sql_cat);
				
									$arr_cat = array();
									$arr_cat[0] = "-- ". __('Select a category', 'dashboard') . " --" ;
				
									while ($row_result = $DB->fetch_assoc($result_cat)) {
										$v_row_result = $row_result['id'];
										$arr_cat[$v_row_result] = $row_result['name'] ;
									}
				
									$name = 'sel_cat';
									$options = $arr_cat;
									$selected = $id_cat;
				
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

			//categorias
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

			if(!isset($_POST["sel_cat"])) {
				$id_cat = $_REQUEST["cat"];
			}

			else {
				$id_cat = $_POST["sel_cat"];
			}

			if($id_cat == "" || $id_cat == 0) {
				echo '<script language="javascript"> alert(" ' . __('Select a category', 'dashboard') . ' "); </script>';
				echo '<script language="javascript"> location.href="rel_categoria.php"; </script>';
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
			"SELECT glpi_tickets.id AS id, glpi_tickets.name AS name, glpi_tickets.date AS date, glpi_tickets.closedate as closedate,
			glpi_tickets.type, glpi_tickets.status, FROM_UNIXTIME( UNIX_TIMESTAMP( `glpi_tickets`.`closedate` ) , '%Y-%m' ) AS date_unix, AVG( glpi_tickets.solve_delay_stat ) AS time,
			glpi_tickets.solve_delay_stat AS time_sec
			FROM glpi_tickets
			WHERE glpi_tickets.itilcategories_id = ".$id_cat."
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.date ".$datas2."
			AND glpi_tickets.status IN ".$status."
			".$entidade."
			GROUP BY id
			ORDER BY id DESC ";

			$result_cham = $DB->query($sql_cham);


			$consulta1 =
			"SELECT glpi_tickets.id AS id, glpi_tickets.name, glpi_tickets.date AS adate, glpi_tickets.closedate AS sdate,
			FROM_UNIXTIME( UNIX_TIMESTAMP( `glpi_tickets`.`closedate` ) , '%Y-%m' ) AS date_unix, AVG( glpi_tickets.solve_delay_stat ) AS time
			FROM glpi_tickets
			WHERE glpi_tickets.itilcategories_id = ".$id_cat."
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.date ".$datas2."
			AND glpi_tickets.status IN ".$status."
			".$entidade."
			GROUP BY id
			ORDER BY id DESC ";

			$result_cons1 = $DB->query($consulta1);
			$conta_cons = $DB->numrows($result_cons1);
			$consulta = $conta_cons;

			if($consulta > 0) {

			//montar barra
			$sql_ab = "SELECT glpi_tickets.id AS total
			FROM glpi_tickets
			WHERE glpi_tickets.itilcategories_id = ".$id_cat."
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.date ".$datas2."
			AND glpi_tickets.status IN ".$status_open."
			".$entidade." ";

			$result_ab = $DB->query($sql_ab) or die ("erro_ab");
			$data_ab = $DB->numrows($result_ab);

			$abertos = $data_ab;

			//barra de porcentagem
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

			// nome da categoria
			$sql_nm = "
			SELECT id , completename AS name
			FROM `glpi_itilcategories`
			WHERE id = ".$id_cat." ";

			$result_nm = $DB->query($sql_nm);
			$cat_name = $DB->fetch_assoc($result_nm);

			//listar chamados
			echo "
			<div class='well info_box fluid col-md-12 report' style='margin-left: -1px;'>

			<table class='col-md-12 col-sm-12 fluid'  style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
				<tr>
					<td  style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Category').": </span>".$cat_name['name']." </td>
				</tr>
				<tr>
					<td style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Tickets', 'dashboard').": </span>".$consulta." </td>
					<td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)." </td>

					<td style='vertical-align:middle; width: 190px; '>
					<div class='progress' style='margin-top: 19px;' >
						<div class='progress-bar ". $cor ." ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
			    			".$barra." % ".__('Closed', 'dashboard') ."
			    		</div>
					</div>
					</td>
				</tr>
			</table>";

		   //count by status
		   $query_stat =
			"SELECT
			SUM(case when glpi_tickets.status = 1 then 1 else 0 end) AS new,
			SUM(case when glpi_tickets.status = 2 then 1 else 0 end) AS assig,
			SUM(case when glpi_tickets.status = 3 then 1 else 0 end) AS plan,
			SUM(case when glpi_tickets.status = 4 then 1 else 0 end) AS pend,
			SUM(case when glpi_tickets.status = 5 then 1 else 0 end) AS solve,
			SUM(case when glpi_tickets.status = 6 then 1 else 0 end) AS close
			FROM glpi_tickets
			WHERE glpi_tickets.is_deleted = '0'
			AND glpi_tickets.date ".$datas2."
			AND glpi_tickets.itilcategories_id = ".$id_cat."
			".$entidade."";

		    $result_stat = $DB->query($query_stat);

			$new = $DB->result($result_stat,0,'new') + 0;
			$assig = $DB->result($result_stat,0,'assig') + 0;
			$plan = $DB->result($result_stat,0,'plan') + 0;
			$pend = $DB->result($result_stat,0,'pend') + 0;
			$solve = $DB->result($result_stat,0,'solve') + 0;
			$close = $DB->result($result_stat,0,'close') + 0;

echo "
			<table align='right' style='margin-bottom:10px; border=1'>
				<tr>
					<td colspan='3'>
						<button class='btn btn-primary btn-sm' type='button' name='abertos' value='Abertos' onclick='location.href=\"rel_categoria.php?con=1&stat=open&cat=".$id_cat."&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('Opened', 'dashboard')." </button>
						<button class='btn btn-primary btn-sm' type='button' name='fechados' value='Fechados' onclick='location.href=\"rel_categoria.php?con=1&stat=close&cat=".$id_cat."&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('Closed', 'dashboard')." </button>
						<button class='btn btn-primary btn-sm' type='button' name='todos' value='Todos' onclick='location.href=\"rel_categoria.php?con=1&stat=all&cat=".$id_cat."&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('All', 'dashboard')." </button>
					</td>
				</tr>
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
				<tr><td>&nbsp;</td></tr>
			</table>

			<table id='cat' class='display'  style='font-size: 12px; font-weight:normal;' cellpadding = 2px>
				<thead>
					<tr>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer; vertical-align:middle;'> ".__('Tickets', 'dashboard')." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer; vertical-align:middle;'> ".__('Status')." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer; vertical-align:middle;'> ".__('Title')." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer; vertical-align:middle;'> ".__('Requester')." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer; vertical-align:middle;'> ".__('Technician')." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer; vertical-align:middle;'> ".__('Opened','dashboard')."</th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer; vertical-align:middle;'> ".__('Closed')." </th>
						<th style='text-align:center; cursor:pointer; vertical-align:middle;'> ". __('Resolution time') ."</th>
					</tr>
				</thead>
			<tbody> ";

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
			".$entidade." ";

			$result_user = $DB->query($sql_user);
			$row_user = $DB->fetch_assoc($result_user);

			//tecnico
				$sql_tec = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
			FROM `glpi_tickets_users` , glpi_tickets, glpi_users
			WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
			AND glpi_tickets.id = ". $row['id'] ."
			AND glpi_tickets_users.`users_id` = glpi_users.id
			AND glpi_tickets_users.type = 2
			".$entidade." ";

			$result_tec = $DB->query($sql_tec);
			$row_tec = $DB->fetch_assoc($result_tec);

			echo "
			<tr style='font-size:11px;'>
				<td style='vertical-align:middle; text-align:center; font-weight:bold;'><a href=".$CFG_GLPI['url_base']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
				<td style='vertical-align:middle;'><img src=".$CFG_GLPI['url_base']."/pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/>&nbsp; ".Ticket::getStatus($row['status'])."  </td>
				<td style='vertical-align:middle;'> ". substr($row['name'],0,55) ." </td>
				<td style='vertical-align:middle;'> ". $row_user['name'] ." ".$row_user['sname'] ." </td>
				<td style='vertical-align:middle;'> ". $row_tec['name'] ." ".$row_tec['sname'] ." </td>
				<td style='vertical-align:middle; text-align:center;'> ". conv_data_hora($row['date']) ." </td>
				<td style='vertical-align:middle; text-align:center;'> ". conv_data_hora($row['closedate']) ." </td>
				<td style='vertical-align:middle; text-align:center;'> ". time_ext($row['time']) ."</td>
			</tr>";
			}

			echo "</tbody>
					</table>
					</div>"; ?>

			<script type="text/javascript" charset="utf-8">

			$('#cat')
				.removeClass( 'display' )
				.addClass('table table-striped table-bordered dataTable');

			$(document).ready(function() {
			    $('#cat').DataTable( {    	
			
					  select: true,	    	    	
			        dom: 'Blfrtip',
			        filter: false,    
			        deferRender: true,    
			        pagingType: "full_numbers",
			        sorting: [[1,'desc'],[0,'desc'],[2,'desc'],[3,'desc'],[4,'desc']],
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
					                 message: "<div id='print' class='info_box fluid col-md-12 col-sm-12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><tr><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Category'); ?> : </span><?php echo $cat_name['name']; ?> </td></tr><tr><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td></tr></table></div>",		     
					                }, 
										  {               
					                 extend: "print",
					                 autoPrint: true,
					                 text: "<?php echo __('Selected','dashboard'); ?>",
					                 message: "<div id='print' class='info_box fluid col-md-12 col-sm-12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><tr><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Category'); ?> : </span><?php echo $cat_name['name']; ?> </td></tr><tr><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td></tr></table></div>",
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
			                 		message: "<?php echo __('Category'); ?> : <?php echo $cat_name['name'] . '  -  '; ?> <?php echo  __('Tickets','dashboard'); ?> : <?php echo $consulta .'  -  '; ?> <?php echo  __('Period','dashboard'); ?> : <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?>",
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
