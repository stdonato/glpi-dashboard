<?php

include ("../../../../inc/includes.php");
include "../inc/functions.php";
global $DB, $CFG_GLPI;

Session::checkLoginUser();
Session::checkRight("profile", READ);
?>

<html> 
	<head>
		<title> GLPI - <?php echo __('Open Tickets','dashboard'); ?> </title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
		<meta http-equiv="content-language" content="en-us" />
		<meta http-equiv="refresh" content= "45"/>
		
		<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
		<link href="../css/styles.css" rel="stylesheet" type="text/css" />
		<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
		
		<script src="../js/jquery.min.js" type="text/javascript" ></script>
		<script src="../js/jquery.jclock.js"></script>
		
		<script src="../js/media/js/jquery.dataTables.min.js"></script>
		<link href="../js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />  
		<script src="../js/media/js/dataTables.bootstrap.js"></script> 
		
		<script src="../js/extensions/ColVis/css/dataTables.colVis.min.css"></script>
		<script src="../js/extensions/ColVis/js/dataTables.colVis.min.js"></script>
		
		<script src="../lib/sweet-alert.min.js"></script>
		<link href="../lib/sweet-alert.css" type="text/css" rel="stylesheet" />
		
		<script type="text/javascript">
		    $(function($) {
			    var options = {
			    timeNotation: '24h',
			    am_pm: true,
			    //fontFamily: 'Open Sans',
			    fontSize: '38px'
		    }
		    	$('#clock').jclock(options);
		    });
		    
		</script>
		
		<style>
			table.dataTable thead .sorting::after { content: "" !important; }
			  .sorting {
		   	color: #fff;
		    	background-color: #555 !important;
		 	}
		    .sorting > a { color: #fff !important;}
		</style>
		
		<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">'; 
		
		$sql_s = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'status' AND users_id = ".$_SESSION['glpiID']."";
		$result_s = $DB->query($sql_s);
		$res_status = $DB->result($result_s,0,'value');
		
		if($res_status != '') {
			$status = "(".$res_status.")";
			$status1 = "AND glpi_tickets.status IN ".$status."";	
		}
		
		else {
			$status = "('5','6')";
			$status1 = "AND glpi_tickets.status NOT IN ".$status."";
		}
		
		$id_grp = $_REQUEST['grp'];
		$grp = $id_grp;
		
		$sql =
		"SELECT count(glpi_tickets.id) AS total
		FROM `glpi_groups_tickets` , glpi_tickets, glpi_groups
		WHERE glpi_groups_tickets.`groups_id` = ".$id_grp."
		AND glpi_groups_tickets.`groups_id` = glpi_groups.id
		AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
		AND glpi_tickets.is_deleted = 0
		AND glpi_groups_tickets.type = 2
		".$status1."";
		
		$result = $DB->query($sql);
		$data = $DB->fetch_assoc($result);
		
		$abertos = $data['total']; 
		
		//insert if not exist Group
		$query_i = "
		INSERT IGNORE INTO glpi_plugin_dashboard_count (type, id, quant) 
		VALUES ('3','". $id_grp ."', '" . $abertos ."')  ";
		
		$result_i = $DB->query($query_i);
		
		// get quantity
		$query = "SELECT quant 
		FROM glpi_plugin_dashboard_count
		WHERE id = ".$id_grp." 
		AND type = 3 ";
		
		$result = $DB->query($query);
		$quant = $DB->fetch_assoc($result);
		
		$atual = $quant['quant']; 
		
		//update tickets count
		$query_up = "UPDATE glpi_plugin_dashboard_count 
		SET quant=".$data['total']."
		WHERE id = ".$id_grp." 
		AND type = 3 ";
		
		$result_up = $DB->query($query_up);
		
		//contar chamados do dia 
		$datahoje = date("Y-m-d");
		
		$sql = "
		SELECT count(glpi_tickets.id) AS total
		FROM `glpi_groups_tickets` , glpi_tickets, glpi_groups
		WHERE glpi_groups_tickets.`groups_id` = ".$id_grp."
		AND glpi_groups_tickets.`groups_id` = glpi_groups.id
		AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
		AND glpi_tickets.is_deleted = 0
		AND glpi_groups_tickets.type = 2
		AND glpi_tickets.date LIKE '".$datahoje."%' " ;
		
		$result = $DB->query($sql);
		$hoje=$DB->fetch_assoc($result);
		
		// chamados de ontem - yesterday tickets
		$dataontem = date('Y-m-d', strtotime('-1 day'));
		
		$sql = "
		SELECT count(glpi_tickets.id) AS total
		FROM `glpi_groups_tickets` , glpi_tickets, glpi_groups
		WHERE glpi_groups_tickets.`groups_id` = ".$id_grp."
		AND glpi_groups_tickets.`groups_id` = glpi_groups.id
		AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
		AND glpi_tickets.is_deleted = 0
		AND glpi_groups_tickets.type = 2
		AND glpi_tickets.date LIKE '".$dataontem."%' ";
		
		$result = $DB->query($sql);
		$ontem = $DB->fetch_assoc($result);
		
		if ($ontem['total'] > $hoje['total']) { $up_down = "../img/down.png"; }
		if ($ontem['total'] < $hoje['total']) { $up_down = "../img/up.png"; }
		if ($ontem['total'] == $hoje['total']) { $up_down = "../img/blank.gif"; }		
		
		//Group name
		$query_name = "
		SELECT name 
		FROM glpi_groups
		WHERE glpi_groups.id = ".$id_grp." " ;
		
		$result_n = $DB->query($query_name);
		$group_name = $DB->result($result_n, 0, 'name');
		
		?> 
	</head>

<?php

if($abertos > $atual) {
	
	//modal alert	
	$sql_pop = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'popup' AND users_id = ".$_SESSION['glpiID']."";
	$result_pop = $DB->query($sql_pop);
	$popup = $DB->result($result_pop,0,'value');		
	
	if($popup == 1) {
		echo '<body onload=\'swal("' . __('New ticket') . '!", "'. __('') .'","success")\'>';
	}		
		
	//sound
	if($_SESSION['glpilanguage'] == "pt_BR") {	
	
	    // IE    
	    echo '<!--[if IE]>';
	    echo '<embed src="../sounds/novo_chamado.mp3" autostart="true" width="0" height="0" type="application/x-mplayer2"></embed>';
	    echo '<![endif]-->';   
	    // Browser HTML5    
	    echo '<audio preload="auto" autoplay>';
	    echo '<source src="../sounds/novo_chamado.ogg" type="audio/ogg"><source src="sounds/novo_chamado.ogg" type="audio/mpeg">';
	    echo '</audio>';
	}
	
	else {
	
	    // IE    
	    echo '<!--[if IE]>';
	    echo '<embed src="../sounds/new_ticket.mp3" autostart="true" width="0" height="0" type="application/x-mplayer2"></embed>';
	    echo '<![endif]-->';
	    // Browser HTML5   
	    echo '<audio preload="auto" autoplay>';
	    echo '<source src="../sounds/new_ticket.ogg" type="audio/ogg"><source src="sounds/new_ticket.ogg" type="audio/mpeg">';
	    echo '</audio>';
	}
}	

else {
	echo '<body>';		
	}
?>

<div id="clock" style="align:right; position:absolute; margin-top:5px;"></div>

<div id='content'>
	<div id='container-fluid' > 
		<div id="head-cham" class="row-fluid">
			<table class="col-lg-12 col-md-12 col-sm-12" style="margin-left: auto; margin-right: auto;">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td align="center"><span class="titulo_cham"><?php echo  $group_name; ?></span> </td>
				</tr>				
				<tr>
					<td align="center">
					</td>				
				</tr>												
				<tr>
					<td align="center" >
						<span class="titulo_cham"><?php echo __('Open Tickets','dashboard'); ?>:</span> 
						<span class="total"> <?php echo " ".$data['total'] ; ?> </span> 
						<span class="titulo_cham"><a href="cham_grupos.php?grp=<?php echo $grp; ?>" > <?php echo " / " . __('Today','dashboard'); ?>: </a> 
						<a href="../../../../front/ticket.php" target="_blank" class="total" style="font-size: 32pt;"> <?php echo " ".$hoje['total'] ; ?> </a>
						<img src= <?php echo $up_down ;?> class="up_down" alt="" style="margin-top: -10px;" title= <?php echo __('Yesterday','dashboard'). ':';  echo $ontem['total'] ;?>  > </span> 
					</td>
				</tr>							
			</table>
			<p></p>
		</div>
<div id="lista_chamados" class="well info_box row-fluid report" style="">

<?php 

if(isset($_REQUEST['order'])) {

	$order1 = $_REQUEST['order'];
	
	switch($order1) {
		 case "td": $order = "ORDER BY glpi_tickets.id DESC"; break;
		 case "ta": $order = "ORDER BY glpi_tickets.id ASC"; break;
		 case "sd": $order = "ORDER BY glpi_tickets.status DESC, glpi_tickets.id ASC"; break;
		 case "sa": $order = "ORDER BY glpi_tickets.status ASC, glpi_tickets.id ASC"; break;
		 case "tid": $order = "ORDER BY glpi_tickets.name DESC"; break;
		 case "tia": $order = "ORDER BY glpi_tickets.name ASC"; break;
		 case "ted": $order = "ORDER BY glpi_tickets.id DESC"; break;
		 case "tea": $order = "ORDER BY glpi_tickets.id ASC"; break;
		 case "pd": $order = "ORDER BY glpi_tickets.priority DESC, glpi_tickets.date ASC"; break;
		 case "pa": $order = "ORDER BY glpi_tickets.priority ASC, glpi_tickets.date ASC"; break;	
 		 case "dd": $order = "ORDER BY glpi_tickets.time_to_resolve DESC"; break;
		 case "da": $order = "ORDER BY glpi_tickets.time_to_resolve ASC"; break;	  
		}	
	}
	
else {
		$order = "ORDER BY glpi_tickets.date_mod DESC";
}
			//select tickets			
			$sql_cham = "SELECT glpi_tickets.id AS id, glpi_tickets.name AS descri, glpi_tickets.status AS status, glpi_tickets.date_mod, 
			glpi_tickets.date AS initdate, glpi_tickets.priority,  glpi_tickets.time_to_resolve AS duedate, glpi_tickets.locations_id AS lid
			FROM glpi_tickets, glpi_groups,`glpi_groups_tickets` 
			WHERE glpi_tickets.is_deleted = 0
			AND glpi_groups_tickets.`groups_id` = ".$id_grp."
			AND glpi_groups_tickets.`groups_id` = glpi_groups.id
			AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
			AND glpi_groups_tickets.type = 2
			".$status1."
			GROUP BY id
			".$order."";
					
			
			$result_cham = $DB->query($sql_cham);
			
			//check time_to_resolve	
			$sql_due = "SELECT COUNT(glpi_tickets.id) AS count_due
			FROM glpi_tickets
			WHERE  glpi_tickets.status NOT IN (4,5,6) 
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.time_to_resolve IS NOT NULL";
					
			$result_due = $DB->query($sql_due);			
			$count_due = $DB->result($result_due,0,'count_due');	
			
			//Show due date or location	
			$query_due = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'duedate' AND users_id = ".$_SESSION['glpiID']." ";																
			$result_due = $DB->query($query_due);
			
			$show_due = $DB->result($result_due,0,'value');	
			
			$query_loc = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'location' AND users_id = ".$_SESSION['glpiID']." ";																
			$result_loc = $DB->query($query_loc);
			
			$show_loc = $DB->result($result_loc,0,'value');
			
			$query_tit = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'title' AND users_id = ".$_SESSION['glpiID']." ";																
			$result_tit = $DB->query($query_tit);
			
			$show_tit = $DB->result($result_tit,0,'value');			
				
			
			if($show_due == 1) {
				if($count_due > 0) {
					$th_due = "<th style='text-align:center;'><a href='chamados.php?order=da'>&nbsp<font size=2.5pt; font-family='webdings'>&#x25BE;&nbsp;</font></a>". __('Due Date','dashboard')."<a href='chamados.php?order=dd'><font size=2.5pt; font-family='webdings'>&nbsp;&#x25B4;</font></a></th>";
				}			
			}
										
				echo "<table id='tickets' class='display' style='font-size: 20px; font-weight:bold;' cellpadding = 2px >				
				<thead>
					<tr class='up-down'>
						<th style='text-align:center;'><a href='cham_grupos.php?grp=".$grp."&order=ta'>&nbsp<font size=2.5pt; font-family='webdings'>&#x25BE;&nbsp;</font></a>". __('ID','dashboard')."<a href='cham_grupos.php?grp=".$grp."&order=td'><font size=2.5pt; font-family='webdings'>&nbsp;&#x25B4;</font></a></th>
						<th style='text-align:center;'><a href='cham_grupos.php?grp=".$grp."&order=sa'><font size=2.5pt; font-family='webdings'>&#x25BE;&nbsp;</font></a>". __('Status')."<a href='cham_grupos.php?grp=".$grp."&order=sd'><font size=2.5pt; font-family='webdings'>&nbsp;&#x25B4;</font></a></th>";
						
				if($show_tit != 0 || $show_tit == '') {	
					echo	"<th style='text-align:center;'>". __('Title')."</th>";
					}
					
				echo "<th style='text-align:center;'>". __('Technician')."</th>
						<th style='text-align:center;'>". __('Requester')."</th>";
					
				if($show_loc == 1) {	
					echo	"<th style='text-align:center;'>". __('Location')."</th>";
					}	
					
				if(isset($th_due)) {				
					echo $th_due;		
				}							
				echo "<th style='text-align:center;'><a href='cham_grupos.php?grp=".$grp."&order=pa'>&nbsp<font size=2.5pt; font-family='webdings'>&#x25BE;&nbsp;</font></a>". __('Priority')."<a href='cham_grupos.php?grp=".$grp."&order=pd'><font size=2.5pt; font-family='webdings'>&nbsp;&#x25B4;</font></a></th>
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
	if($status1 == "13" ) { $status1 = "feedback";}
	if($status1 == "14" ) { $status1 = "waiting_list";}
	if($status1 == "15" ) { $status1 = "in_attendance";}
	
	
	$sql_grp = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
	FROM `glpi_tickets_users` , glpi_tickets, glpi_users
	WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
	AND glpi_tickets.id = ". $row['id'] ."
	AND glpi_tickets_users.`users_id` = glpi_users.id
	AND glpi_tickets_users.type = 2
	";
	$result_grp = $DB->query($sql_grp);	

	$row_grp = $DB->fetch_assoc($result_grp);


	//get technician
	$sql_tec = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
		FROM `glpi_tickets_users` , glpi_tickets, glpi_users
		WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
		AND glpi_tickets.id = ". $row['id'] ."
		AND glpi_tickets_users.`users_id` = glpi_users.id
		AND glpi_tickets_users.type = 2";
	    
	$result_tec = $DB->query($sql_tec);	
	$row_tec = $DB->fetch_assoc($result_tec);
	
	//get requester
	$sql_req = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
		FROM `glpi_tickets_users` , glpi_tickets, glpi_users
		WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
		AND glpi_tickets.id = ". $row['id'] ."
		AND glpi_tickets_users.`users_id` = glpi_users.id
		AND glpi_tickets_users.type = 1";
	    
	$result_req = $DB->query($sql_req);	
	$row_req = $DB->fetch_assoc($result_req);
	
	//get priority
	$sql_prio = "SELECT name, value
				FROM glpi_configs
				WHERE name LIKE 'priority_".$row['priority']."' ";

		$result_prio = $DB->query($sql_prio);	
		$row_prio = $DB->fetch_assoc($result_prio);	
		
		$priority = substr($row_prio['name'],9,10);
		
		if($priority == 1) {
			$prio_name = _x('priority', 'Very low'); }
		
		if($priority == 2) {
			$prio_name = _x('priority', 'Low'); }
			
		if($priority == 3) {
			$prio_name = _x('priority', 'Medium'); } 		
			
		if($priority == 4) {	
			$prio_name = _x('priority', 'High'); }
			
		if($priority == 5) {
			$prio_name = _x('priority', 'Very high'); } 	
			
		if($priority == 6) {
			$prio_name = _x('priority', 'Major'); } 
	
		//get Location
		$sql_loc = "SELECT id, name
		FROM glpi_locations
		WHERE glpi_locations.id = ". $row['lid'] ." ";
		    
		$result_loc = $DB->query($sql_loc);	
		$row_loc = $DB->fetch_assoc($result_loc);		 			 				 		

		echo "
		<tr class='title' style='font-weight:normal;'>
			<td style='text-align:center; vertical-align:middle; font-weight:bold;'> <a href=../../../../front/ticket.form.php?id=". $row['id'] ." target=_blank > <span >" . $row['id'] . "</span> </a></td>
			<td style='vertical-align:middle;'><span style='color:#000099';><img src=../../../../pics/".$status1.".png />  ".Ticket::getStatus($row['status'])."</span ></td>";		
	
		if($show_tit != 0 || $show_tit == '') {	
			echo "<td style='vertical-align:middle;'><a href=../../../../front/ticket.form.php?id=". $row['id'] ." target=_blank > <span >" . $row['descri'] . "</span> </a></td>";
		}
	
		echo "<td style='vertical-align:middle;'><span >". $row_tec['name'] ." ".$row_tec['sname'] ."</span> </td>		
			<td style='vertical-align:middle;'><span >". $row_req['name'] ." ".$row_req['sname'] ."</span> </td>";
						
		if($show_loc == 1) {
			echo "<td style='vertical-align:middle; text-align:center; font-size:14pt;'>" . $row_loc['name'] . "</td>";
		}
					
		if($show_due != 0) {
			if($count_due > 0) {
				$now = date("Y-m-d H:i");
							
				//barra de porcentagem
				/*if($status == $status_close ) {
				    $barra = 100;
				    $cor = "progress-bar-success";
				}	*/
		
				//else {
					//porcentagem
					$time_total = strtotime($row['duedate']) - strtotime($row['initdate']);
					$time_pass = strtotime($row['duedate']) - strtotime($now);
					$perc = round(($time_pass*100)/$time_total,1);
					$barra = round(100 - $perc,1);
			
					// cor barra
					if($barra == 100) { $cor = "progress-bar-danger"; }
					if($barra > 95 and $barra < 100) { $cor = "progress-bar-danger"; }
					if($barra > 70 and $barra < 95) { $cor = "progress-bar-warning"; }
					if($barra > 50 and $barra <= 70) { $cor = "progress-bar-default"; }
					if($barra > 0 and $barra <= 50) { $cor = "progress-bar-success"; }
					if($barra < 0) { $cor = ""; $barra = 0; }					
				//}	
					
				if($row['duedate'] != ''  && $row['duedate'] < $now ) {
					echo "<td style='text-align:center; vertical-align:middle; font-size:14pt; color:red;'><span>". conv_data_hora($row['duedate']) ."</span> 
								<div class='progress' style='margin-top: 9px;'>
									<div class='progress-bar progress-bar-danger ' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width: 100%;'>
						    			<span style='font-weight:bold; color:#fff;'> 100% </span>
						    		</div>
								</div>								
							</td>";
				}
				else {
					echo "<td style='text-align:center; vertical-align:middle; font-size:14pt; color:green;'><span>". conv_data_hora($row['duedate']) ."</span>";
						if($barra != 0) { 
						echo " 
								<div class='progress' style='margin-top: 9px;'>
									<div class='progress-bar ". $cor ."  ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
						    			<span style='font-weight:bold; color:#fff;'>".$barra."% </span> 
						    		</div>
								</div>								
							</td>";
						}
						else { echo "</td>";}
				}	
			}
		}
				
			echo "			
				<td style='vertical-align:middle; text-align:center; background-color:". $row_prio['value'] .";'>" . $prio_name . "</td>
			</tr>"; 		 
			 } 
 
		echo "</tbody>
				</table>"; ?>

</div>
</div>
</div>

<style type="text/css">
	table.dataTable thead > tr > th {
	    padding-right: 10px;
	}
</style>

<script type="text/javascript" charset="utf-8">

$('#tickets')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover');

$(document).ready(function() {
    oTable = $('#tickets').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bFilter": false,
        //"aaSorting": [],
        "aaSorting": false,
        "bLengthChange": false,
        "bPaginate": false, 
        "scrollY":        "69vh",
        "scrollCollapse": true,
        "paging":         false,
        //"iDisplayLength": 15,
    	  //"aLengthMenu": [[15, 25, 50, 100, -1], [15, 25, 50, 100, "All"]],
    	  
    	   
        "sDom": 'T<"clear">lfrtip', 
        		
          colVis: {
          	"buttonText": "<?php echo __('Show/hide columns','dashboard'); ?>",
  				 	"restore": "<?php echo __('Restore'); ?>",
				"showAll": "<?php echo __('Show all'); ?>",
				"exclude": [0]     
  						},
        "bSortCellsTop": true,
        "sAlign": "right"    	      	      	  		  
    });    
} );

</script>

</body>
</html>
