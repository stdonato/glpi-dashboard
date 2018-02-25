<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");
include "../inc/functions.php";

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if(!empty($_REQUEST['submit']))
{
    $data_ini =  $_REQUEST['date1'];
    $data_fin = $_REQUEST['date2'];
}

else {
    $data_ini = date("Y-m-01");
    $data_fin = date("Y-m-d");
    }

if(!isset($_REQUEST["sel_date"])) {
	$id_date = $_GET["date"];
}

else {
	$id_date = $_REQUEST["sel_date"];
}

if(!isset($_REQUEST["sel_tec"])) {
    $id_tec = $_GET["tec"];
}

else {
    $id_tec = $_REQUEST["sel_tec"];
}


# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '' || $sel_ent == -1) {

	$entities = $_SESSION['glpiactiveentities'];	
	$ent = implode(",",$entities);
	$entidade = "AND glpi_tickets.entities_id IN (".$ent.") ";
	$entidade_u = "AND glpi_profiles_users.entities_id IN (".$ent.") ";
}

else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.") ";
	$entidade_u = "AND glpi_profiles_users.entities_id IN (".$sel_ent.") ";	
}

?>
<html>
<head>
<title> GLPI - <?php echo  __('Summary Report','dashboard')." - ". __('Technician') ?> </title>
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

<script src="../js/bootstrap.min.js"></script>
<script src="../js/bootstrap-datepicker.js"></script>
<link href="../css/datepicker.css" rel="stylesheet" type="text/css">

<style type="text/css">	
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 

</head>

<body style="background-color: #e5e5e5;">

<div id='content' >
	<div id='container-fluid' style="margin: 0px 5% 0px 5%;">
		<div id="charts" class="fluid chart">
			<div id="pad-wrapper" >
			
				<div id="head-rel" class="fluid">
					<style type="text/css">
					a:link, a:visited, a:active {
					    text-decoration: none
					    }
					a:hover {
					    color: #000099;
					    }
					</style>
					
					<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:20px;"></i><span></span></a>
					
					<div id="titulo_rel"> <?php echo __('Summary Report','dashboard') ." - ". __('Technician') ?> </div>		
						<div id="datas-tec" class="span12 fluid" >			
						    <form id="form1" name="form1" class="form_rel" method="post" action="rel_sint_tec.php?con=1">
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
										
										//$res_date = $DB->query($sql_date);
										$arr_date = array(
											 __('Period','dashboard'),
										    __('Current month','dashboard'),
										    __('Last 7 days','dashboard'),
										    __('Last 15 days','dashboard'),
										    __('Last 30 days','dashboard'),
										    __('Last 3 months','dashboard'),
										);
										
										$name = 'sel_date';
										$options = $arr_date;
										$selected = $id_date;
										
										echo dropdown2( $name, $options, $selected );
										
										echo "</td></tr><tr><td height='15px'></td><td></td></tr> <tr align=center><td colspan='2'>\n";
										
										//list techs										
										$sql_tec = "
										SELECT DISTINCT glpi_users.`id` AS id , glpi_users.`firstname` AS name, glpi_users.`realname` AS sname										        										       										        
										 FROM `glpi_profiles_users`										
										 LEFT JOIN `glpi_tickets_users`
										      ON (`glpi_tickets_users`.`users_id`=`glpi_profiles_users`.`users_id`)										
										 LEFT JOIN `glpi_users`
										      ON (`glpi_users`.`id` = `glpi_profiles_users`.`users_id`)
										 WHERE `glpi_users`.`is_deleted` = '0' 
											   AND `glpi_users`.`is_active` = '1'
											   AND glpi_tickets_users.type = 2											 										 
										".$entidade_u."
										ORDER BY name ASC ";
		
										$result_tec = $DB->query($sql_tec);										
										
										// lista de técnicos
										$res_tec = $DB->query($sql_tec);
										$arr_tec = array();
										$arr_tec[0] = "-- ". __('Select a technician','dashboard') . " --" ;
										
										$DB->data_seek($result_tec, 0) ;
										
										while ($row_result = $DB->fetch_assoc($result_tec))
									    {
										    $v_row_result = $row_result['id'];
									   	 $arr_tec[$v_row_result] = $row_result['name']." ".$row_result['sname']." (".$row_result['id'].")" ;
									    }
										    										
										$name = 'sel_tec';
										$options = $arr_tec;
										$selected = $id_tec;
										
										echo dropdown2( $name, $options, $selected );
										?>
										
										</td>
										</tr>
										<tr><td height="15px"></td><td></td></tr>
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
		
		
		<?php
		
		if(isset($_GET['con'])){$con = $_GET['con'];}
		else {$con = '';}
		
		if($con == "1") {
		
		if(!isset($_REQUEST['date1']))
		{
		    $data_ini2 = $_GET['date1'];
		    $data_fin2 = $_GET['date2'];
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
		
		// do select
		$post_date = $_REQUEST["sel_date"];
		
		if(!isset($post_date) or $post_date == "0") {
		    $sel_date = $datas2;
		}
		
		else {
		    $sel_date = $_REQUEST["sel_date"];
		}
		
		switch($post_date) {
		
          case ("1") :
             $data_ini2 = date('Y-m-01');
             $data_fin2 = date('Y-m-d');
              $sel_date = "BETWEEN '" . $data_ini2 ." 00:00:00' AND '". $data_fin2 ." 23:59:59'";
          break;
          case ("2") :
             $data_ini2 = date('Y-m-d', strtotime('-1 week'));
              $sel_date = "BETWEEN '" . $data_ini2 ." 00:00:00' AND '".$data_fin2." 23:59:59'";
          break;
          case ("3") :
             $data_ini2 = date('Y-m-d', strtotime('-15 day'));
              $sel_date = "BETWEEN '" . $data_ini2 ." 00:00:00' AND '".$data_fin2." 23:59:59'";
          break;
          case ("4") :
              $data_ini2 = date('Y-m-d', strtotime('-1 month'));
              $sel_date = "BETWEEN '" . $data_ini2 ." 00:00:00' AND '".$data_fin2." 23:59:59'";
          break;
          case ("5") :
              $data_ini2 = date('Y-m-d', strtotime('-3 month'));
              $sel_date = "BETWEEN '" . $data_ini2 ." 00:00:00' AND '".$data_fin2." 23:59:59'";
          break;		
		}		
		
		// Chamados
		$sql_cham = 
		"SELECT glpi_tickets.id AS id, COUNT(glpi_tickets.id) AS conta_id, glpi_tickets.name AS name, glpi_tickets.date AS date	
		FROM `glpi_tickets_users` , glpi_tickets
		WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
		AND glpi_tickets_users.type = 2
		AND glpi_tickets_users.users_id = ". $id_tec ."
		AND glpi_tickets.is_deleted = 0
		AND glpi_tickets.date ".$sel_date."
		".$entidade."
		GROUP BY id
		ORDER BY id DESC ";
		
		$result_cham = $DB->query($sql_cham);
		$chamados = $DB->fetch_assoc($result_cham) ;
		
				
		//quant de chamados
		$sql_cham2 =
		"SELECT count(glpi_tickets.id) AS total, count(glpi_tickets.date) AS numdias, AVG(glpi_tickets.close_delay_stat) AS avgtime
		FROM glpi_tickets, glpi_tickets_users
		WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
		AND glpi_tickets_users.type = 2
		AND glpi_tickets_users.users_id = ". $id_tec ."
		AND glpi_tickets.is_deleted = 0
		AND glpi_tickets.date ".$sel_date."
		".$entidade." ";
		
		$result_cham2 = $DB->query($sql_cham2);		
		$conta_cham = $DB->fetch_assoc($result_cham2);
		
		$total_cham = $conta_cham['total'];
		//$numdias = $conta_cham['numdias'];
		
		
		if($total_cham > 0) {

			//nome e total
			$sql_nome = "
			SELECT firstname , realname, name
			FROM glpi_users
			WHERE id = ".$id_tec." ";
			
			$result_nome = $DB->query($sql_nome);
			$tec_name = $DB->fetch_assoc($result_nome);
			
			//date diff
			$numdias = round(abs(strtotime($data_fin2) - strtotime($data_ini2)) / 86400,0);							
			
			//requester
				$sql_req = "SELECT count(glpi_tickets.id) AS conta, glpi_users.firstname AS name, glpi_users.realname AS sname
			FROM `glpi_tickets_users` , glpi_tickets, glpi_users
			WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`			
			AND glpi_tickets_users.`users_id` = glpi_users.id
			AND glpi_tickets_users.type = 1
			AND glpi_tickets.users_id_lastupdater = ". $id_tec ."		
			AND glpi_tickets.date ".$sel_date."	
			".$entidade."
			GROUP BY name
			ORDER BY conta DESC
			LIMIT 5";
			
			$result_req = $DB->query($sql_req);	
				
											
			//avg time
			$sql_time =
			"SELECT COUNT(glpi_tickets.id) AS total, AVG(glpi_tickets.close_delay_stat) AS avgtime
			FROM glpi_tickets, glpi_tickets_users
			WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
			AND glpi_tickets_users.type = 2
			AND glpi_tickets_users.users_id = ". $id_tec ."
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.date ".$sel_date."			
			".$entidade." ";
			
			$result_time = $DB->query($sql_time);		
			$time_cham = $DB->fetch_assoc($result_time);
			
			$avgtime = $time_cham['avgtime'];
			
			
			//count by status
			$query_stat = "
			SELECT
			SUM(case when glpi_tickets.status = 1 then 1 else 0 end) AS new,
			SUM(case when glpi_tickets.status = 2 then 1 else 0 end) AS assig,
			SUM(case when glpi_tickets.status = 3 then 1 else 0 end) AS plan,
			SUM(case when glpi_tickets.status = 4 then 1 else 0 end) AS pend,
			SUM(case when glpi_tickets.status = 5 then 1 else 0 end) AS solve,
			SUM(case when glpi_tickets.status = 6 then 1 else 0 end) AS close
			FROM `glpi_tickets_users`, glpi_tickets
			WHERE glpi_tickets.is_deleted = '0'
			AND glpi_tickets.date ".$sel_date."			
			AND glpi_tickets.id = glpi_tickets_users.`tickets_id`			
			AND glpi_tickets_users.users_id = ".$id_tec."
			AND glpi_tickets_users.type = 2			
			".$entidade."";
		
			$result_stat = $DB->query($query_stat);
		
                        $new = $DB->result($result_stat,0,'new') + 0;
                        $assig = $DB->result($result_stat,0,'assig') + 0;
                        $plan = $DB->result($result_stat,0,'plan') + 0;
                        $pend = $DB->result($result_stat,0,'pend') + 0;
                        $solve = $DB->result($result_stat,0,'solve') + 0;
                        $close = $DB->result($result_stat,0,'close') + 0;
			
			
			//count by type
			$query_type = "
			SELECT
			SUM(case when glpi_tickets.type = 1 then 1 else 0 end) AS incident,
			SUM(case when glpi_tickets.type = 2 then 1 else 0 end) AS request
			FROM `glpi_tickets_users`, glpi_tickets
			WHERE glpi_tickets.is_deleted = '0'
			AND glpi_tickets.date ".$sel_date."			
			AND glpi_tickets.id = glpi_tickets_users.`tickets_id`			
			AND glpi_tickets_users.users_id = ".$id_tec."
			AND glpi_tickets_users.type = 2		
			".$entidade."";
		
			$result_type = $DB->query($query_type);
		
			$incident = $DB->result($result_type,0,'incident');
			$request = $DB->result($result_type,0,'request');
			
			//categories
			$query_cat = "
			SELECT glpi_itilcategories.name as cat_name, COUNT(glpi_tickets.id) as cat_conta, glpi_itilcategories.id
			FROM glpi_tickets, glpi_itilcategories, glpi_tickets_users
			WHERE glpi_itilcategories.id = glpi_tickets.itilcategories_id
			AND glpi_tickets.is_deleted = '0'
			AND glpi_tickets.date ".$sel_date."
			AND glpi_tickets_users.users_id = ".$id_tec."
			AND glpi_tickets_users.tickets_id = glpi_tickets.id
			AND glpi_tickets_users.type = 2
			".$entidade."
			GROUP BY glpi_itilcategories.id
			ORDER BY `cat_conta` DESC			
			LIMIT 5 ";
			
			$result_cat = $DB->query($query_cat) or die('erro');
			
			
			//select groups
			$sql_grp = 
			"SELECT count(glpi_tickets.id) AS conta, glpi_groups.name AS name
			FROM `glpi_groups_tickets`, glpi_tickets, glpi_groups
			WHERE glpi_groups_tickets.`groups_id` = glpi_groups.id
			AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
			AND glpi_tickets.is_deleted = 0
			AND glpi_tickets.date ".$sel_date."
			".$entidade."
			GROUP BY name
			ORDER BY conta DESC
			LIMIT 5 ";			
			
			$result_grp = $DB->query($sql_grp);	
						
			//logo						
			if (file_exists('../../../../pics/logo_big.png')) {
				$logo = "../../../../pics/logo_big.png";
				$imgsize = "width:100px; height:100px;";
			}
			else {					
				if ($CFG_GLPI['version'] >= 0.90){
					//$logo = "../img/logo-glpi-login-b.png";
					$logo = "../../../../pics/logo-glpi-login.png";
					$imgsize = "background-color:#000;";
				}	
				else {
					$logo = "../../../../pics/logo-glpi-login.png";
					$imgsize = "";
				}
			}
			
									
$content = "
		<div class='well info_box fluid col-md-12 report-tic' style='margin-left: -1px;'>	
 			<div class='btn-right'> <button class='btn btn-primary btn-sm' type='button' onclick=window.open(\"./rel_sint_tec_pdf.php?con=1&date1=".$data_ini2."&date2=".$data_fin2."&sel_tec=".$id_tec."\",\"_blank\")>Export PDF</button>  </div>	
			
			 <div id='logo' class='fluid'>
				 <div class='col-md-2' ><img src='".$logo."' alt='GLPI' style='".$imgsize."'> </div>
				 <div class='col-md-8' style='height:120px; text-align:center; margin:auto;'><h3 style='vertical-align:middle;' >". __('Summary Report','dashboard')." - " .__('Technician')." </h3></div>
			 </div>
									
			 <table id='data' class='table table-condensed table-striped' style='font-size: 16px; width:55%; margin:auto; margin-top:5px; margin-bottom:25px;'>			
			 <tbody>				
			 <tr>
			 <td>" .__('Technician')."</td>
			 <td align='right'>".$tec_name['firstname'] ." ". $tec_name['realname']."</td>
			 </tr>
			 <tr>
			 <td>". __('Period','dashboard')." </td>";
			 
			if($data_ini2 == $data_fin2) {
				$content .= "<td align='right'>".conv_data($data_ini2)."</td>";		
			}
			else {
				$content .= "<td align='right'>".conv_data($data_ini2)." to ".conv_data($data_fin2)."</td>";
			}	

$content .= "					
			 </tr>
			
			 <tr>
			 <td>". __('Date')." </td>
			 <td align='right'>".conv_data_hora(date("Y-m-d H:i"))."</td>			
			 </tr>
			 </tbody>
			 </table>			 

			 <table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
			 <thead>
			 <tr>
			 <th colspan='2' style='text-align:center; background:#286090; color:#fff;'>". __('Tickets','dashboard')."</th>						
			 </tr>
			 </thead>	

			 <tbody>			
			 <tr>
			 <td>". __('Tickets Total','dashboard')."</td>
			 <td align='right'>".$total_cham."</td>			
			 </tr>			
			
			 <tr>
			 <td>". _n('Day','Days',2)."</td>
			 <td align='right'>".$numdias."</td>
			 </tr>				
			 <tr>
			 <td>". __('Tickets','dashboard')." ". __('By day')." - ". __('Average')."</td>
			 <td align='right'>".round($total_cham / $numdias,0)."</td>
			 </tr>			
			 <tr>
			 <td>". __('Average time to closure')."</td>
			 <td align='right'>". time_hrs($avgtime )."</td>
			 </tr>					
		    </tbody> </table>		   		    

			 <table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
			 <thead>
			 <tr>
			 <th colspan='2' style='text-align:center; background:#286090; color:#fff;'>". __('Tickets by Status','dashboard')."</th>						
			 </tr>
			 </thead>	

			 <tbody>							
			 <tr>
			 <td>". _x('status','New')."</td>
			 <td align='right'>".$new."</td>			
			 </tr>				
			 <tr>
			 <td>". __('Assigned')."</td>
			 <td align='right'>".$assig."</td>			
			 </tr>				
			 <tr>
			 <td>". __('Planned')."</td>
			 <td align='right'>".$plan."</td>			
			 </tr>				
			 <tr>
			 <td>". __('Pending')."</td>
			 <td align='right'>".$pend."</td>			
			 </tr>			
			 <tr>
			 <td>". __('Solved','dashboard')."</td>
			 <td align='right'>".$solve."</td>			
			 </tr>				
			 <tr>
			 <td>". __('Closed')."</td>
			 <td align='right'>".$close."</td>			
			 </tr>								
													
		    </tbody> </table>
		   
		    		   
			 <table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
			 <thead>
			 <tr>
			 <th colspan='2' style='text-align:center; background:#286090; color:#fff;'>". __('Tickets','dashboard')." ". __('by Type','dashboard')."</th>						
			 </tr>
			 </thead>	

			 <tbody>							
			 <tr>
			 <td>". __('Incident')."</td>
			 <td align='right'>".$incident."</td>			
			 </tr>	
			
			 <tr>
			 <td>". __('Request')."</td>
			 <td align='right'>".$request."</td>			
			 </tr>	
			 </tbody> </table>
		   		    		    			   
			 <table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
			 <thead>
			 <tr>
			 <th colspan='2' style='text-align:center; background:#286090; color:#fff;'>Top 5 - ". __('Tickets','dashboard')." ". __('by Category','dashboard')."</th>						
			 </tr>
			 </thead>	

			 <tbody>	";		
			
			while($row = $DB->fetch_assoc($result_cat)) {
				$content .= "<tr>
				 <td>".$row['cat_name']."</td>
				 <td align='right'>".$row['cat_conta']."</td>			
				 </tr> ";	
			}		    

$content .= "					
		    </tbody> </table>		   		    	
		   
			 <table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
			 <thead>
			 <tr>
			 <th colspan='2' style='text-align:center; background:#286090; color:#fff;'>Top 5 - ". __('Tickets','dashboard')." ". __('by Requester','dashboard')."</th>						
			 </tr>
			 </thead>	

			 <tbody>	";		
			
			while($row = $DB->fetch_assoc($result_req)) {
				$content .= "<tr>
				 <td>".$row['name']." ".$row['sname']."</td>
				 <td align='right'>".$row['conta']."</td>			
				 </tr> ";	
			}		
									
$content .= "</tbody></table></div> ";		   		   			
										
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

	else {
		$content =''; 
	}	
		
//output report
echo $content;
		
   ?>
				
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() { $("#sel_tec").select2({dropdownAutoWidth : true}); });
		$(document).ready(function() { $("#sel_date").select2({dropdownAutoWidth : true}); });					
	</script>
	
		</div>
		</div>	
	</div>
</div>
</body>
</html>
