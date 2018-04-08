<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");
include "../inc/functions.php";

global $DB, $con;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if(!empty($_REQUEST['submit'])) {
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

	$query_ent1 = "
	SELECT entities_id
	FROM glpi_users
	WHERE id = ".$_SESSION['glpiID']." ";
	
	$res_ent1 = $DB->query($query_ent1);
	$user_ent = $DB->result($res_ent1,0,'entities_id');

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
<title> GLPI - <?php echo __('Tickets','dashboard') .'  '. __('by Group','dashboard').'s'; ?> </title>
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

<style type="text/css">	
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
	a:hover { color: #000099; }
	.label-md {
  		min-width: 45px !important;
 		display: inline-block !important
	}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 

</head>

<body style="background-color: #e5e5e5; margin-left:0%;" >

<div id='content' >
<div id='container-fluid' style="margin: <?php echo margins(); ?> ;">
<div id="charts" class="fluid chart" >
	<div id="pad-wrapper" >
		<div id="head-rel" class="fluid">
			<a href="../index.php"><i class="fa fa-home home-rel" style="font-size:14pt; margin-left:25px;"></i><span></span></a>
				<div id="titulo_rel" > <?php echo __('Tickets','dashboard') .'  '. __('by Group','dashboard').'s'; ?> </div>
					<div id="datas-tec" class="span12 fluid" > 
					<form id="form1" name="form1" class="form_rel" method="post" action="rel_grupos.php?con=1"  style="margin-left: 37%;"> 
					
						<table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef">
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

//tecnico2
if(isset($_GET['con'])) {
	
	$con = $_GET['con'];

	if($con == "1") {
	
		if(!isset($_REQUEST['date1']))
		{	
			$data_ini2 = $data_ini;
			$data_fin2 = $data_fin; 
		}
		
		else {	
			$data_ini2 = $_REQUEST['date1'];	
			$data_fin2 = $_REQUEST['date2'];	
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
		$status_closed = "('5','6')";	
		$status_all = "('2','1','3','4','5','6')";

		//actors - 1 - req, 2 - tec, 3 - observer
		$actors = "";
		$actors_req = "('1')";
		$actors_tec = "('2')";
		$actors_all = "('1','2','3')";
		
		if(isset($_GET['actor'])) {
		
		    if($_GET['actor'] == "req") {
		      $actors = $actors_req;
		    }
		    elseif($_GET['actor'] == "tec") {
		      $actors = $actors_tec;
		    }
		    else {
		    	$actors = $actors_all;
		    }
		}
		else {
		    $actors = $actors_all;
		}

				
		//select groups with tickets
		$sql_tec = 
		"SELECT count(glpi_tickets.id) AS total, glpi_groups.name AS name, glpi_groups.id AS id
		FROM `glpi_groups_tickets`, glpi_tickets, glpi_groups
		WHERE glpi_groups_tickets.`groups_id` = glpi_groups.id
		AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
		AND glpi_tickets.is_deleted = 0
		AND glpi_tickets.date ".$datas2."
		".$entidade."
		AND glpi_groups_tickets.type IN ".$actors."
		GROUP BY name
		ORDER BY total DESC ";			
		
		$result_tec = $DB->query($sql_tec);	
		$conta_cons = $DB->numrows($result_tec);
				
		echo "<div class='well info_box fluid col-md-12 report' style='margin-left: -1px;'>";
		echo "
		<table class='col-md-12 right' align='right' style='margin-bottom:20px;'>
				<tr>			
					<td> 
						". __('Actor')." : &nbsp;
						<button class='btn btn-primary btn-sm' type='button' name='requerente' value='Requerentes' onclick='location.href=\"rel_grupos.php?con=1&actor=req&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('Requester', 'dashboard') ." </button>
						<button class='btn btn-primary btn-sm' type='button' name='tecnico' value='Técnicos' onclick='location.href=\"rel_grupos.php?con=1&actor=tec&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('Technician', 'dashboard')." </button>
						<button class='btn btn-primary btn-sm' type='button' name='todos' value='Todos' onclick='location.href=\"rel_grupos.php?con=1&date1=".$data_ini2."&date2=".$data_fin2."\"' <i class='icon-white icon-trash'></i> ".__('All', 'dashboard')." </button>				
					</td>
				</tr>
		</table> ";
		
		echo "
			<table id='tec' class='display' style='font-size: 13px; font-weight:bold;' cellpadding = 2px >
				<thead>
					<tr>
						<th style='text-align:center; cursor:pointer;'> ". _n('Group','Groups',2) ." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Tickets')." </th>
						<th style='text-align:center; cursor:pointer;'> ". __('Opened','dashboard') ."</th>
						<th style='text-align:center; cursor:pointer;'> ". __('Late','dashboard') ."</th>
						<th style='text-align:center; cursor:pointer;'> ". __('Solved','dashboard') ."</th>	
						<th style='text-align:center; cursor:pointer;'> ". __('Closed','dashboard') ."</th>									
						<th style='text-align:center; '> % ". __('Closed','dashboard') ."</th> 

						<th style='text-align:center; '>". __('Backlog','dashboard') ." </th>";
		
						echo "</tr>
				</thead>
			<tbody>";
			
		
		while($id_grp = $DB->fetch_assoc($result_tec)) {	
		
			//tickets
			$sql_cham = "SELECT count( glpi_tickets.id ) AS total, glpi_groups_tickets.groups_id AS id
			FROM glpi_groups_tickets, glpi_tickets
			WHERE glpi_tickets.id = glpi_groups_tickets.tickets_id
			AND glpi_groups_tickets.tickets_id = glpi_tickets.id
			AND glpi_tickets.is_deleted = 0
			AND glpi_groups_tickets.groups_id = ".$id_grp['id']."
			AND glpi_tickets.date ".$datas2."
			". $entidade ." ";
			
			$result_cham = $DB->query($sql_cham) or die ("erro_cham");
			$data_cham = $DB->fetch_assoc($result_cham);
			
			$chamados = $data_cham['total'];
			
			
			//chamados abertos
			$sql_ab = "SELECT count( glpi_tickets.id ) AS total, glpi_groups_tickets.groups_id AS id
			FROM glpi_groups_tickets, glpi_tickets
			WHERE glpi_tickets.id = glpi_groups_tickets.tickets_id
			AND glpi_groups_tickets.tickets_id = glpi_tickets.id
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.status NOT IN ".$status_closed."
			AND glpi_groups_tickets.groups_id = ".$id_grp['id']."
			AND glpi_tickets.date ".$datas2."
			". $entidade ."  ";
			
			$result_ab = $DB->query($sql_ab) or die ("erro_ab");
			$data_ab = $DB->fetch_assoc($result_ab);
			
			$abertos = $data_ab['total'];
			
			
			//chamados solucionados
			$sql_sol = "SELECT count( glpi_tickets.id ) AS total, glpi_groups_tickets.groups_id AS id
			FROM glpi_groups_tickets, glpi_tickets
			WHERE glpi_tickets.id = glpi_groups_tickets.tickets_id
			AND glpi_groups_tickets.tickets_id = glpi_tickets.id
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.status = 5
			AND glpi_groups_tickets.groups_id = ".$id_grp['id']."
			AND glpi_tickets.date ".$datas2."
			". $entidade ." " ;
			
			$result_sol = $DB->query($sql_sol) or die ("erro_ab");
			$data_sol = $DB->fetch_assoc($result_sol);
			
			$solucionados = $data_sol['total'];
			
			
			//chamados atrasados
			$sql_due = "
			SELECT count( glpi_tickets.id ) AS total, glpi_groups_tickets.groups_id AS id
			FROM glpi_groups_tickets, glpi_tickets
			WHERE glpi_tickets.id = glpi_groups_tickets.tickets_id			
			AND `glpi_tickets`.`time_to_resolve` IS NOT NULL 
			AND `glpi_tickets`.is_deleted = 0
			AND `glpi_tickets`.`status` <> 4
			AND glpi_groups_tickets.groups_id = ".$id_grp['id']."
			AND glpi_tickets.date ".$datas2." 
			". $entidade ." 
			AND 
			(
			  `glpi_tickets`.`solvedate` > `glpi_tickets`.`time_to_resolve`  
			  OR (
			    `glpi_tickets`.`solvedate` IS NULL AND `glpi_tickets`.`time_to_resolve` < NOW()
			  )
			) ";
			
			$result_due = $DB->query($sql_due) or die ("erro_late");
			$data_due = $DB->fetch_assoc($result_due);
			 
			$atrasados = $data_due['total'];
			
			
			//chamados fechados
			$sql_clo = "SELECT count( glpi_tickets.id ) AS total, glpi_groups_tickets.groups_id AS id
			FROM glpi_groups_tickets, glpi_tickets
			WHERE glpi_tickets.id = glpi_groups_tickets.tickets_id
			AND glpi_groups_tickets.tickets_id = glpi_tickets.id
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.status = 6
			AND glpi_groups_tickets.groups_id = ".$id_grp['id']."
			AND glpi_tickets.closedate ".$datas2."
			". $entidade ." " ;
			
			$result_clo = $DB->query($sql_clo) or die ("erro_ab");
			$data_clo = $DB->fetch_assoc($result_clo);
			
			$fechados = $data_clo['total'];
			
			
			// backlog acumulado
			$sql_bac = "SELECT count( glpi_tickets.id ) AS total, glpi_groups_tickets.groups_id AS id
			FROM glpi_groups_tickets, glpi_tickets
			WHERE glpi_tickets.id = glpi_groups_tickets.tickets_id
			AND glpi_groups_tickets.tickets_id = glpi_tickets.id
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.status <> 6
			AND glpi_groups_tickets.groups_id = ".$id_grp['id']."
			". $entidade ."			
			AND glpi_tickets.date < '".$data_ini." 00:00:00' ";
			
			$result_bac = $DB->query($sql_bac) or die ("erro_ab");
			$data_bac = $DB->fetch_assoc($result_bac);
			
			$back_ac = $data_bac['total'];		
			
			
			//opened		
			$cont_abertos = ($chamados - $fechados);
			if($cont_abertos < 0) { $abertos = 0; }
			else { $abertos = $cont_abertos; }
			
			//backlog
			$backlog = ($chamados - $fechados);
						
			if($backlog >= 1) { $back_cor = 'label label-md label-danger'; }
			if($backlog == 0) { $back_cor = 'label label-md label-primary'; }
			if($backlog <= -1) { $back_cor = 'label label-md label-success'; }		

			$backlog_ac = ($back_ac + $backlog);	
							
			if($backlog_ac >= 1) { $back_cor_ac = 'label label-md label-danger'; }
			if($backlog_ac == 0) { $back_cor_ac = 'label label-md label-primary'; }
			if($backlog_ac <= -1) { $back_cor_ac = 'label label-md label-success'; }
									
			//barra de porcentagem
			if($conta_cons > 0) {

				//porcentagem
				$perc = round(($backlog*100)/$chamados,0);
				$barra = 100 - $perc;
				$width = $barra;
				
				// cor barra
				if($barra >= 100) { $cor = "progress-bar-success"; $width = 100; }
				if($barra >= 80 and $barra < 100) { $cor = " ";  }
				if($barra > 51 and $barra < 80) { $cor = "progress-bar-warning";  }
				if($barra > 0 and $barra <= 50) { $cor = "progress-bar-danger";  }
				if($barra < 0) { $cor = "progress-bar-danger"; $barra = 0;  }			
			}
			
			else { $barra = 0;}
		
				echo "
				<tr>
					<td style='vertical-align:middle; text-align:left;'><a href='rel_tecnicos.php?con=1&sel_group=". $id_grp['id'] ."&date1=".$data_ini."&date2=".$data_fin."' target='_blank' >" . $id_grp['name'] .' ('.$id_grp['id'].")</a></td>
					<td style='vertical-align:middle; text-align:center;'><a href='rel_grupo.php?con=1&sel_group=". $id_grp['id'] ."&date1=".$data_ini."&date2=".$data_fin."' target='_blank' >" . $chamados ."</a></td>
					<td style='vertical-align:middle; text-align:center;'> ". $abertos ." </td>
					<td style='vertical-align:middle; text-align:center;'> ". $atrasados ." </td>
					<td style='vertical-align:middle; text-align:center;'> ". $solucionados ." </td>
					<td style='vertical-align:middle; text-align:center;'> ". $fechados ." </td>			
					<td style='vertical-align:middle; text-align:center;'> 
						<div class='progress' style='margin-top: 5px; margin-bottom: 5px;'>
							<div class='progress-bar ". $cor ." ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$width."%;'>
					 			".$barra." % 	
					 		</div>		
						</div>			
				   </td>					
				   <td style='vertical-align:middle; text-align:center;'><h4><span class='".$back_cor_ac."'>". $backlog_ac ."</span></h4></td> ";			
							
			echo "</tr>";
				
//				   <td style='vertical-align:middle; text-align:center;'><h4><span class='".$back_cor."'>". $backlog ."</span></h4></td>
		//fim while1
		}	
		
		echo "</tbody>
				</table>
				</div>"; 
		//fim $con
		}
}

?>

<script type="text/javascript" charset="utf-8">

$('#tec')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover dataTable');

$(document).ready(function() {
var table = $('#tec').dataTable({

		  select: true,	    	    	
        dom: 'Blfrtip',
        filter: false,        
        pagingType: "full_numbers",
        sorting: [[1,'desc'],[0,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc'],[6,'desc']],
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
		                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:12px; margin-left: -1px;'></div>"		     
		                }, 
							  {               
		                 extend: "print",
		                 autoPrint: true,
		                 text: "<?php echo __('Selected','dashboard'); ?>",
		                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:12px; margin-left: -1px;'></div>",
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
                 		message: "<?php echo  __('Period','dashboard'); ?> : <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?>"
                  }]
             }
        ]
		  
    });    
} );

var column = table.column( 2 );
 
$( column.footer() ).html(
    column.data().reduce( function (a,b) {
        return a+b;
    } )
);

</script>  

</div>
</div>
</div>
</div>
					
</body>
</html>

