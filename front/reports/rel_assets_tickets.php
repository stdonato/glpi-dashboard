<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", "r");

if(!empty($_POST['submit']))
{
    $data_ini =  $_POST['date1'];
    $data_fin = $_POST['date2'];
}

else {
    $data_ini = date("Y-m-01");
    $data_fin = date("Y-m-d");
    }

if(isset($_POST['sel_item'])) {
    $id_item = $_REQUEST['sel_item'];
}

else {
    $id_item = $_POST["sel_item"];
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

?>

<html>
<head>
	<title> GLPI - <?php echo __('Assets'). " - ".__('Tickets'); ?> </title>
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
	
	<script src="../js/media/js/jquery.dataTables.min.js"></script>
	<link href="../js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />  
	<script src="../js/media/js/dataTables.bootstrap.js"></script> 
	<link href="../js/extensions/TableTools/css/dataTables.tableTools.css" type="text/css" rel="stylesheet" />
	<script src="../js/extensions/TableTools/js/dataTables.tableTools.js"></script>

	<style type="text/css">	
		select { width: 60px; }
		table.dataTable { empty-cells: show; }
	   a:link, a:visited, a:active { text-decoration: none;}
	</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 

</head>

<body style="background-color: #e5e5e5; margin-left:0%;">

<div id='content' >
<div id='container-fluid' style="margin: 0px 8% 0px 8%;">

<div id="charts" class="row-fluid chart" >
<div id="pad-wrapper" >

	<div id="head" class="row-fluid">	
		<style type="text/css">
		a:link, a:visited, a:active {
		    text-decoration: none
		    }
		a:hover {
		    color: #000099;
		    }
		</style>
		
		<?php
		
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
		
		if(!isset($_POST["sel_item"])) {
		    $id_item = $_REQUEST["sel_item"];
		}
		
		else {
		    $id_item = $_POST["sel_item"];
		}
		
		
		if(!isset($_POST["itemtype"])) {
		    $type = $_REQUEST["itemtype"];
		}
		
		else {
		    $type = $_POST["itemtype"];
		}
		
		if(!isset($_POST["sel_fab"])) {
		    $id_fab = $_REQUEST["sel_fab"];
		}
		
		else {
		    $type = $_POST["sel_fab"];
		}
		
		
		if($id_item == 0) {
			echo '<script language="javascript"> alert(" ' . __('Select a asset', 'dashboard') . ' "); </script>';
			echo '<script language="javascript"> location.href="rel_assets_tickets.php"; </script>';
		}
		
		
		if($data_ini2 === $data_fin2) {
		    $datas2 = "LIKE '".$data_ini2."%'";
		}
		
		else {
		    $datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";
		}
		
		//item
			$sql_item = "SELECT id,name
					 		FROM glpi_". strtolower($type)."s
					 		WHERE id = ".$id_item. "			 		
					 		AND is_deleted = 0 ";
			
			$result_item = $DB->query($sql_item);		
			$item = $DB->fetch_assoc($result_item);
		?>
		
		<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>
		   	<div id="titulo_graf" style="margin-bottom:20px;"> <?php echo __('Assets'); ?>  
			    	<div style="font-size:24px; margin-top:-60px;"> <br>&nbsp;<p></p><?php echo __('Tickets').": ".$item['name']; ?></div>        
		    	</div>    
	    </div>
</div>	


<?php
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


$url = $_SERVER['REQUEST_URI']; 
$arr_url = explode("?", $url);
$url2 = $arr_url[0];

// Chamados

$typeuc = ucfirst($type);

$sql_cham =
"SELECT glpi_tickets.id AS id, glpi_tickets.name AS descr, glpi_tickets.date AS date, glpi_tickets.solvedate as solvedate, glpi_tickets.status
FROM glpi_tickets
WHERE glpi_tickets.items_id = ".$id_item."
AND itemtype = '".$typeuc."'
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status IN ".$status."
ORDER BY id DESC ";

$result_cham = $DB->query($sql_cham);


$consulta1 =
"SELECT glpi_tickets.id AS id, glpi_tickets.name AS descr, glpi_tickets.date AS date, glpi_tickets.solvedate as solvedate, glpi_tickets.status
FROM glpi_tickets
WHERE glpi_tickets.items_id = ".$id_item."
AND itemtype = '".$typeuc."'
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.status IN ".$status."
AND glpi_tickets.date ".$datas2."
ORDER BY id DESC ";

$result_cons1 = $DB->query($consulta1);
$conta_cons = $DB->numrows($result_cons1);
$consulta = $conta_cons;


if($consulta > 0) {
	

//chamados abertos
$sql_abertos =
"SELECT glpi_tickets.id AS id, glpi_tickets.name AS descr, glpi_tickets.date AS date, glpi_tickets.solvedate as solvedate, glpi_tickets.status
FROM glpi_tickets
WHERE glpi_tickets.items_id = ".$id_item."
AND itemtype = '".$typeuc."'
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.status IN ".$status_open."
AND glpi_tickets.date ".$datas2." ";

$result_abertos = $DB->query($sql_abertos);
$abertos = $DB->numrows($result_abertos);


//barra de porcentagem
$total_cham = $consulta;

if($conta_cons > 0) {

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


//fabricante
	if($id_fab != '') {	 
		$sql_fab = "SELECT name
				 		FROM glpi_manufacturers
				 		WHERE id = ".$id_fab." ";
		
		$result_fab = $DB->query($sql_fab);
		$fab = $DB->fetch_assoc($result_fab);	
	}

//table thread
echo "
<div class='well info_box row-fluid span12' style='margin-top:25px; margin-left: -1px;'>

<table class='row-fluid'  style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
	<td  style='font-size: 15px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Type').": </span>". __($typeuc)." </td>
	<td  style='font-size: 15px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Manufacturer').": </span>". $fab['name'] ." </td>
	<td  style='font-size: 15px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> ".__('Tickets', 'dashboard').": </span>".$consulta." </td>
	<td colspan='3' style='font-size: 15px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>
	".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)."
</td>

	<td style='vertical-align:middle; width: 190px; '>
		<div class='progress' style='margin-top: 19px;'>
			<div class='progress-bar ". $cor ." progress-bar-striped active' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
    			".$barra." % ".__('Closed', 'dashboard') ."	
    		</div>		
		</div>		   
		</td>
</table>

<table align='right' style='margin-bottom:10px;'>
		<tr>
			<td colspan=3>
				<button class='btn btn-primary btn-sm' type='button' name='abertos' value='Abertos' onclick='location.href=\"rel_localidade.php?con=1&stat=open&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('Opened', 'dashboard') ." </button>
				<button class='btn btn-primary btn-sm' type='button' name='fechados' value='Fechados' onclick='location.href=\"rel_localidade.php?con=1&stat=close&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('Closed', 'dashboard')." </button>	
				<button class='btn btn-primary btn-sm' type='button' name='todos' value='Todos' onclick='location.href=\"rel_localidade.php?con=1&stat=all&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('All', 'dashboard')." </button>
			</td>	
		</tr>
</table>

<table>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
</table>

<table id='asset1' class='display'  style='font-size: 12px; font-weight:bold;' cellpadding = 2px>
	<thead>
		<tr>
			<th style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Ticket')." </th>
			<th> </th>
			<th style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Title', 'dashboard')." </th>
			<th style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Requester', 'dashboard')." </th>
			<th style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Technician', 'dashboard')." </th>
			<th style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Opening date', 'dashboard')."</th>
			<th style='font-size: 12px; font-weight:bold; color:#000; text-align: center; cursor:pointer;'> ".__('Close date', 'dashboard')." </th>
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

//requerente

    $sql_user = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
FROM `glpi_tickets_users` , glpi_tickets, glpi_users
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets.id = ". $row['id'] ."
AND glpi_tickets_users.`users_id` = glpi_users.id
AND glpi_tickets_users.type = 1
";
$result_user = $DB->query($sql_user);

    $row_user = $DB->fetch_assoc($result_user);

//tecnico

    $sql_tec = "SELECT glpi_tickets.id AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
FROM `glpi_tickets_users` , glpi_tickets, glpi_users
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets.id = ". $row['id'] ."
AND glpi_tickets_users.`users_id` = glpi_users.id
AND glpi_tickets_users.type = 2
";
$result_tec = $DB->query($sql_tec);

    $row_tec = $DB->fetch_assoc($result_tec);

echo "

<tr>
<td style='vertical-align:middle; text-align:center;'><a href=".$CFG_GLPI['root_doc']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
<td style='vertical-align:middle;' align='center' ><img src=".$CFG_GLPI['root_doc']."/pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/> </td>
<td> ". substr($row['descr'],0,55) ." </td>
<td> ". $row_user['name'] ." ".$row_user['sname'] ." </td>
<td> ". $row_tec['name'] ." ".$row_tec['sname'] ." </td>
<td> ". conv_data($row['date']) ." </td>
<td> ". conv_data($row['solvedate']) ." </td>
</tr>";
}

echo "</tbody>
		</table>
		</div>"; ?>

<script type="text/javascript" charset="utf-8">

$('#asset1')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered');


$(document).ready(function() {
    oTable = $('#asset1').dataTable({
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
                 "sMessage": "<div class='info_box row-fluid span12' style='margin-top:20px; margin-bottom:12px; margin-left: -1px;'><table class='row-fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Name'); ?> : </span><?php echo $item['name']; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Type'); ?> : </span><?php echo __($typeuc); ?> </td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Manufacturer'); ?> : </span><?php echo $fab['name']; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Quantity','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>"
             },
             {
                 "sExtends":    "collection",
                 "sButtonText": "<?php echo __('Export'); ?>",
                 "aButtons":    [ "csv", "xls",
                  {
                 "sExtends": "pdf",
                 "sPdfOrientation": "landscape",
                 "sPdfMessage": ""
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
<div class='well info_box row-fluid span12' style='margin-top:30px; margin-left: -3px;'>
<table class='table' style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
<tr><td style='vertical-align:middle; text-align:center;'> <span style='color: #000;'>" . __('No ticket found', 'dashboard') . "</td></tr>
<tr></tr>
</table></div>";

}

}

}
?>

<script type="text/javascript" >
$('.chosen-select').chosen({disable_search_threshold: 10});
</script>

</div>

</div>
</div>

</body>
</html>

