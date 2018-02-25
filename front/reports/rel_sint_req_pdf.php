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
	$id_date = $_REQUEST["date"];
}

else {
	$id_date = $_REQUEST["sel_date"];
}

if(isset($_REQUEST["sel_tec"])) {

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
<title> GLPI - <?php echo  __('Summary Report','dashboard')." - ". __('Requester') ?> </title>
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
<script src="../js/bootstrap.min.js"></script>

<style type="text/css">	
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 

</head>
<body style="background-color: #fff !important;">		
		
		<?php
		
		$con = $_REQUEST['con'];
		if($con == "1") {
			
		if(!isset($_REQUEST["sel_tec"])) {
 	   	$id_tec = $_REQUEST["sel_tec"];
		}	
		
		if(!isset($_REQUEST['date1']))
		{
		    $data_ini2 = $_REQUEST['date1'];
		    $data_fin2 = $_REQUEST['date2'];
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
		AND glpi_tickets_users.type = 1
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
		AND glpi_tickets_users.type = 1
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
			FROM `glpi_tickets_users`, glpi_tickets, glpi_users
			WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`			
			AND glpi_tickets_users.`users_id` = glpi_users.id
			AND glpi_tickets_users.type = 2
			AND glpi_tickets.date ".$sel_date."	
			".$entidade."						
			AND glpi_tickets_users.tickets_id IN (SELECT id FROM glpi_tickets_users gtu WHERE gtu.users_id = ". $id_tec ." AND gtu.type = 1)
			GROUP BY name
			ORDER BY conta DESC
			LIMIT 5";
			
			$result_req = $DB->query($sql_req);	
				
											
			//avg time
			$sql_time =
			"SELECT COUNT(glpi_tickets.id) AS total, AVG(glpi_tickets.close_delay_stat) AS avgtime
			FROM glpi_tickets, glpi_tickets_users
			WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
			AND glpi_tickets_users.type = 1
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
			AND glpi_tickets_users.type = 1			
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
			AND glpi_tickets_users.type = 1		
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
			AND glpi_tickets_users.type = 1
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

<page backtop='5mm' backbottom='5mm' backleft='15mm' backright='10mm'> 
      <page_header> 
      </page_header>
      <page_footer align='center'>
    		[[page_cu]]/[[page_nb]]
  		</page_footer>
       
 		<!-- <div class='fluid col-md-12 report' style='margin-left: 0px; margin-top: -50px;'> --> 				 				
			
			 <div id='logo' class='fluid'>
				 <div class='col-md-2' ><img src='".$logo."' alt='GLPI' style='".$imgsize."'> </div>
				 <div class='col-md-8' style='margin-top:-80px; height:60px; height:120px; text-align:center; margin:auto;'><h3 style='vertical-align:middle;' >". __('Summary Report','dashboard')." - " .__('Requester')." </h3></div>
			 </div>
									
			 <table id='data' class='table table-condensed table-striped' style='font-size: 16px; width:55%; margin:auto; margin-top:-30px; margin-bottom:25px;'>			
			 <tbody>				
			 <tr>
			 <td width='300'>" .__('Requester')."</td>
			 <td margin-top:-80px; height:60px; align='right'>".$tec_name['firstname'] ." ". $tec_name['realname']."</td>
			 </tr>
			 <tr>
			 <td>". __('Period','dashboard')." </td>";
			 
			if($data_ini2 == $data_fin2) {
				$content .= "<td  width='200' align='right'>".conv_data($data_ini2)."</td>";		
			}
			else {
				$content .= "<td  width='200' align='right'>".conv_data($data_ini2)." to ".conv_data($data_fin2)."</td>";
			}	

$content .= "					
			 </tr>
			
			 <tr>
			 <td>". __('Date')." </td>
			 <td align='right'>".conv_data_hora(date("Y-m-d H:i"))."</td>			
			 </tr>
			 <tr><td>&nbsp;</td></tr>
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
			 <td width='300'>". __('Tickets Total','dashboard')."</td>
			 <td width='200' align='right'>".$total_cham."</td>			
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
			 <tr><td>&nbsp;</td></tr>				
		    </tbody> 
		    </table>		   		    

			 <table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
			 <thead>
			 <tr>
			 <th colspan='2' style='text-align:center; background:#286090; color:#fff;'>". __('Tickets by Status','dashboard')."</th>						
			 </tr>
			 </thead>	

			 <tbody>							
			 <tr>
			 <td width='300'>". _x('status','New')."</td>
			 <td width='200' align='right'>".$new."</td>			
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
			 <tr><td>&nbsp;</td></tr>										
		    </tbody> </table>
		   
		    		   
			 <table class='fluid table table-striped table-condensed'  style='font-size: 16px; width:55%; margin:auto; margin-bottom:25px;'>
			 <thead>
			 <tr>
			 <th colspan='2' style='text-align:center; background:#286090; color:#fff;'>". __('Tickets','dashboard')." ". __('by Type','dashboard')."</th>						
			 </tr>
			 </thead>	

			 <tbody>							
			 <tr>
			 <td width='300'>". __('Incident')."</td>
			 <td width='200' align='right'>".$incident."</td>			
			 </tr>	
			
			 <tr>
			 <td>". __('Request')."</td>
			 <td align='right'>".$request."</td>			
			 </tr>	
			 <tr><td>&nbsp;</td></tr>
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
				 <td width='300'>".$row['cat_name']."</td>
				 <td width='200' align='right'>".$row['cat_conta']."</td>			
				 </tr> ";	
			}		    

$content .= "	
			 <tr><td>&nbsp;</td></tr>				
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
				 <td width='300'>".$row['name']." ".$row['sname']."</td>
				 <td width='200' align='right'>".$row['conta']."</td>			
				 </tr> ";	
			}		
									
$content .= "</tbody></table> </page> ";		   		   			
										
		}					
	}


require_once('../inc/html2pdf/html2pdf.class.php');

$filename = "summary_report_requester.pdf";

$html2pdf = new HTML2PDF('P', 'A4', 'en');
$html2pdf->writeHTML($content);

ob_end_clean();
$html2pdf->Output($filename,'D');		

//header("Location:".$filename);
				
?>
			
</div>
</body>
</html>
