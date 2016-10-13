<?php

$sql = "
SELECT COUNT(gt.id) AS total
FROM glpi_tickets_users gtu, glpi_tickets gt
WHERE gtu.users_id = ". $_SESSION['glpiID'] ."
AND gtu.type = 2
AND gt.is_deleted = 0
AND gt.id = gtu.tickets_id" ;

$resulta = $DB->query($sql);
$abertos = $DB->result($resulta,0,'total');

//$abertos = $data; 
$init = $abertos - 1;

$query_u = "
INSERT IGNORE INTO glpi_plugin_dashboard_notify (users_id, quant, type) 
VALUES ('". $_SESSION['glpiID'] ."', '" . $init ."', '0' )  ";

$result_u = $DB->query($query_u);


$query = "SELECT users_id, quant, type 
FROM glpi_plugin_dashboard_notify
WHERE users_id = ". $_SESSION['glpiID'] ."
AND type = 0 " ;

$result = $DB->query($query);

$user = $DB->result($result,0,'users_id');
$atual = $DB->result($result,0,'quant');
$type = $DB->result($result,0,'type');

$dif = $abertos - $atual;

/*
if(isset($_REQUEST['tic']) && $_REQUEST['tic'] == 1 ) {

$passo = $abertos - ($dif - 1);

	//update tickets count
	//function apaga() {
	$query_up = "UPDATE glpi_plugin_dashboard_notify 
	SET quant=". $passo ."
	WHERE users_id = ". $_SESSION['glpiID'] ." ";
	
	$result_up = $DB->query($query_up);
	//}
}
*/
	if($abertos > $atual) {
					
	if($dif >= 5) { $dif = 5; }
		
		$queryc = 
		"SELECT gt.id AS id, gt.name AS name 
		FROM glpi_tickets_users gtu, glpi_tickets gt
		WHERE gtu.users_id = ". $_SESSION['glpiID'] ."
		AND gtu.type = 2
		AND gt.is_deleted = 0
		AND gt.id = gtu.tickets_id
		ORDER BY id DESC
		LIMIT ".$dif." ";
		
		$res = $DB->query($queryc);
		
		$conta_ticket1 = array();
			
		while($row = $DB->fetch_assoc($res)) {
			$conta_ticket1[] = $row['id'];	
		}
		
		$conta_ticket = count($conta_ticket1);	
}	


/*

//followup notification
$query_n = "SELECT refresh_ticket_list AS refresh FROM glpi_configs";
$result_n = $DB->query($query_n);
$refresh = $DB->result($result_n,0,'refresh') * 5;

//$refresh = 5;

$queryf = "SELECT DISTINCT gtf.tickets_id AS id, gtu.users_id, gtf.content AS name, date_format(gtf.date,'%d-%m-%Y %H:%i') AS data
FROM glpi_ticketfollowups gtf, glpi_tickets_users gtu
WHERE date BETWEEN DATE_ADD( NOW() , INTERVAL -".$refresh." MINUTE ) AND NOW()
AND gtf.tickets_id =  gtu.tickets_id 
AND gtu.users_id = ". $_SESSION['glpiID'] ." ";

$resultf = $DB->query($queryf);

while($row = $DB->fetch_assoc($resultf)) {
		$conta_follow1[] = $row['id'];	
	}
*/	

//followups

$queryn = "SELECT COUNT(gtf.id) AS total
FROM glpi_ticketfollowups gtf, glpi_tickets_users gtu
WHERE gtf.tickets_id =  gtu.tickets_id 
AND gtu.type = 2
AND gtu.users_id = ". $_SESSION['glpiID'] ." ";

$resultf = $DB->query($queryn);

//$resultan = $DB->query($queryn);
$abertosn = $DB->result($resultf,0,'total');

//$abertos = $data; 
$initn = $abertosn - 1;

$query_un = "
INSERT IGNORE INTO glpi_plugin_dashboard_notify (users_id, quant, type) 
VALUES ('". $_SESSION['glpiID'] ."', '" . $initn ."', '1' )  ";

$result_un = $DB->query($query_un);


$queryn1 = "SELECT users_id, quant, type 
FROM glpi_plugin_dashboard_notify
WHERE users_id = ". $_SESSION['glpiID'] ."
AND type = 1 " ;

$resultn1 = $DB->query($queryn1);

$usern = $DB->result($resultn1,0,'users_id');
$atualn = $DB->result($resultn1,0,'quant');
$typen = $DB->result($resultn1,0,'type');

$difn = $abertosn - $atualn;


	if($abertosn > $atualn) {
					
	if($difn >= 5) { $difn = 5; }
		
		$queryc = 
		"SELECT DISTINCT gt.id AS id, gt.name AS name, gtf.content AS content 
		FROM glpi_tickets_users gtu, glpi_tickets gt, glpi_ticketfollowups gtf
		WHERE gtu.users_id = ".$_SESSION['glpiID']."
		AND gtf.tickets_id =  gtu.tickets_id 
		AND gtu.type = 2
		AND gt.is_deleted = 0
		AND gt.id = gtu.tickets_id
		ORDER BY id DESC
		LIMIT ".$difn." ";
		
		$resn = $DB->query($queryc);
		
		$conta_follow1 = array();
			
		while($row = $DB->fetch_assoc($resn)) {
			$conta_follow1[] = $row['id'];	
		}
		
		$conta_follow = count($conta_follow1);	
}	
	
//$conta_follow = count($conta_follow1);

$total_notif = $conta_ticket + $conta_follow;

echo "
	<div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
	<ul class='nav navbar-nav navbar-right navbar-top-links'>
	<ul class='nav navbar-top-links ' >			
	<li class='dropdown'>
   <a href='#' class='dropdown-toggle' data-toggle='dropdown' href='#'><i class='fa fa-bell fa-fw faa-ring animated-hover' style='color:#fff;'></i> <span class='caret' style='color:#fff;'></span></a>
 	";
 	
	if($total_notif != 0) { 
    echo  "<span class='badge badge-info'>". $total_notif ."</span>";
	}
	echo "
       <ul class='dropdown-menu' role='menu'>
			<li style='background:#f2f2f2;'><a href='#'><b>" . _n('Notification','Notifications',2). "</b></a></li>	
			<li class='divider'></li>	";	            

	if($abertos > $atual) {

	$DB->data_seek($res, 0);	
		
	while($row = $DB->fetch_assoc($res)) {
				
			$titulo = "<b>". __('New ticket')."</b>";
			$text = "<b>".$row['id']."</b> - ".$row['name'];	

			//echo '<li><a href="http://'.$_SERVER['SERVER_ADDR'].'/glpi/front/ticket.form.php?id='.$row['id'].'" target="_blank" onclick="location.href=\'./inc/update.php?usr='.$user.'&ab='.$abertos.'&dif='.$dif.'&type='.$type.'\' "><i style="color:green" class="fa fa-ticket" ></i>&nbsp;  ' .$titulo."<br>".$text. '</a></li><li class="divider"></li>';
			echo '<li><span class="delete"><a href="./inc/del.php?usr='.$user.'&ab='.$abertos.'&dif='.$dif.'&type='.$type.' "><i class="fa fa-times-circle"></i></a></span>
		  <div id="del">
		  <a href="http://'.$_SERVER['SERVER_ADDR'].'/glpi/front/ticket.form.php?id='.$row['id'].'" target="_blank" onclick="location.href=\'./inc/update.php?usr='.$user.'&ab='.$abertos.'&dif='.$dif.'&type='.$type.'\' "><i style="color:green" class="fa fa-ticket"></i>&nbsp;  ' .$titulo."<br>&nbsp;&nbsp;&nbsp;&nbsp;".$text. '</a>
		  </div></li><li class="divider"></li>';																			
		}			
	}	

//follow
if($conta_follow != 0 ) {

	$DB->data_seek($resn, 0);
	while($row = $DB->fetch_assoc($resn)) {
	
		$titulo = "<b>" .__('New followup') . "</b>";
		$text = "<b>".$row['id']."</b> - ". substr($row['content'],0,50); 					
		
		//echo '<li><a href="http://'.$_SERVER['SERVER_ADDR'].'/glpi/front/ticket.form.php?id='.$row['id'].'" target="_blank"><i style="color:orange" class="fa fa-envelope"></i>&nbsp;  ' .$titulo."<br>".$text. '</a></li><li class="divider"></li>';
		  echo '<li><span class="delete"><a href="./inc/del.php?usr='.$usern.'&ab='.$abertosn.'&dif='.$difn.'&type='.$typen.' "><i class="fa fa-times-circle"></i></a></span>
		  <div id="del">
		  <a href="http://'.$_SERVER['SERVER_ADDR'].'/glpi/front/ticket.form.php?id='.$row['id'].'" target="_blank" onclick="location.href=\'./inc/update.php?usr='.$usern.'&ab='.$abertosn.'&dif='.$difn.'&type='.$typen.'\' "><i style="color:orange" class="fa fa-envelope"></i>&nbsp;  ' .$titulo."<br>&nbsp;&nbsp;&nbsp;&nbsp;".$text. '</a>
		  </div></li><li class="divider"></li>';	
	}
}

echo " </ul>
     </li>	
     </ul>
     </ul>
     </div>	
";

 ?>	
