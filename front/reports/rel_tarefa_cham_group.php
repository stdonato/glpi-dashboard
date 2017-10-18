<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");
include "../inc/functions.php";

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

if(!empty($_POST['submit']))
{
    $data_ini =  $_POST['date1'];
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

//select entity
if($sel_ent == '' || $sel_ent == -1) {	

	$entities = $_SESSION['glpiactiveentities'];	
	$ent = implode(",",$entities);

	$entidade = "AND glpi_tickets.entities_id IN (".$ent.") ";
	$entidade_u = "AND glpi_profiles_users.entities_id IN (".$ent.") ";
	$entidade1 = "";
	
}
else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.") ";
	$entidade_u = "AND glpi_profiles_users.entities_id IN (".$sel_ent.") ";
}

?>

<html>
<head>
<title> GLPI - <?php echo _n('Task','Tasks',2) .' - '. __('Tickets', 'dashboard') ?> </title>
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
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 

</head>

<body style="background-color: #e5e5e5;">
<?php

?>
<div id='content' >
<div id='container-fluid' style="margin: <?php echo margins(); ?> ;">

<div id="charts" class="fluid chart" >

<div id="pad-wrapper" >
<div id="head-rel" class="fluid">

<style type="text/css">
	a:link, a:visited, a:active {text-decoration: none;}
	a, a:visited, a:focus, a:hover { color: #0076CC;}
</style>

<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

    <div id="titulo_rel"> <?php echo _n('Task','Tasks',2) .' - '. __('Tickets','dashboard') ?>  </div>

    <div id="datas-tec3" class="span12 fluid" >
    <form id="form1" name="form1" class="form_rel" method="post" action="./rel_tarefa_cham_group.php?con=1" style="margin-left: 37%;">
	    <table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef">
		    <tr>
					<td style="width: 310px;">
					<?php
					$url = $_SERVER['REQUEST_URI'];
					$arr_url = explode("?", $url);
					$url2 = $arr_url[0];
					
					echo '
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
					
				<td style="margin-top:2px;"></td>
			</tr>
			<tr><td height="15px"></td></tr>
			<tr>
				<td colspan="2" align="center">
					<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult', 'dashboard'); ?></button>
					<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" > <i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean', 'dashboard'); ?> </button></td>
				</td>
			</tr>	
	    </table>
	<?php Html::closeForm(); ?>
	<!-- </form> -->
        </div>
    </div>
</div>

<script type="text/javascript" >
	$(document).ready(function() { $("#sel1").select2({dropdownAutoWidth : true}); });
</script>

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

if($data_ini2 === $data_fin2) {
    $datas2 = "LIKE '".$data_ini2."%'";
}

else {
    $datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";
}

// Chamados
/*$sql_cham =
"SELECT glpi_tickets.id AS id, glpi_tickettasks.id AS taskid, glpi_tickettasks.taskcategories_id AS tipo, glpi_tickettasks.date AS date, glpi_tickettasks.content,
glpi_tickettasks.users_id AS uid, glpi_tickettasks.actiontime, glpi_tickettasks.begin AS begin, glpi_tickettasks.end
FROM `glpi_tickets` , glpi_tickettasks
WHERE glpi_tickets.id = glpi_tickettasks.`tickets_id`
AND glpi_tickets.is_deleted = 0
AND glpi_tickettasks.date ". $datas2 ."
".$entidade."
ORDER BY id DESC, begin ASC ";*/

$sql_cham =
"SELECT DISTINCT glpi_tickets.id AS id
FROM `glpi_tickets` , glpi_tickettasks
WHERE glpi_tickets.id = glpi_tickettasks.`tickets_id`
AND glpi_tickets.is_deleted = 0
AND glpi_tickettasks.date ". $datas2 ."
".$entidade."
ORDER BY id DESC ";

$result_cham = $DB->query($sql_cham);


$consulta1 =
"SELECT glpi_tickets.id AS id, glpi_tickettasks.id AS taskid, glpi_tickettasks.taskcategories_id AS tipo, glpi_tickettasks.date AS date, glpi_tickettasks.content,
glpi_tickettasks.users_id AS uid, glpi_tickettasks.actiontime, glpi_tickettasks.begin, glpi_tickettasks.end
FROM `glpi_tickets` , glpi_tickettasks
WHERE glpi_tickets.id = glpi_tickettasks.`tickets_id`
AND glpi_tickets.is_deleted =0
AND glpi_tickettasks.date ". $datas2 ."
".$entidade."
ORDER BY id DESC ";

$result_cons1 = $DB->query($consulta1);
$conta_cons = $DB->numrows($result_cons1);
$consulta = $conta_cons;


if($conta_cons > 0) {

//total time of tasks
while($row = $DB->fetch_assoc($result_cons1)){
    $tempo_total += $row['actiontime'];
}
	
	echo "
	<div class='well info_box fluid col-md-12 report' style='margin-left: -1px;'>
	
	<table class='fluid' style='width:100%; font-size: 18px; font-weight:bold; margin-bottom: 30px;' cellpadding = 1px>
		<tr>
			<td style='vertical-align:middle; width:350px;'> <span style='color: #000;'>"._n('Task', 'Tasks',2).": </span>". $conta_cons ."</td>
			<td style='vertical-align:middle; width:350px;'> <span style='color: #000;'>".__('Time').": </span>". time_ext($tempo_total) ."</td>
			<td colspan='4' style='font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)."</td>
		</tr>
	</table>";

	echo "
	<table id='tarefa' class='display' style='font-size: 12px; font-weight:bold;' cellpadding = 2px>
		<thead>
			<tr>
				<th style='text-align:center;'> ". __('Ticket') ."  </th>
				<th style='text-align:center;'> ". __('Date') ." </th>
				<th style='text-align:center;'> ". __('Technician') ." </th>
				<th style='text-align:center;'> ". __('Requester') ." </th>
				<th style='text-align:center;'> ". __('Description') ."</th>
				<th style='text-align:center;'> ". __('Duration') ." </th>
				<th style='text-align:center;'> ". __('Begin') ." </th>
				<th style='text-align:center;'> ". __('End') ."  </th>
			</tr>
		</thead>
	<tbody> ";

//listar chamados

while($row = $DB->fetch_assoc($result_cham)){

	echo "	
	<tr>
		<td colspan='9' style='text-align:left; vertical-align:middle; background:#ddd;'><a href=".$CFG_GLPI['url_base']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
	</tr>";


$sql_data =
"SELECT glpi_tickets.id AS id, glpi_tickettasks.id AS taskid, glpi_tickettasks.taskcategories_id AS tipo, glpi_tickettasks.date AS date, glpi_tickettasks.content,
glpi_tickettasks.users_id AS uid, glpi_tickettasks.actiontime, glpi_tickettasks.begin, glpi_tickettasks.end
FROM `glpi_tickets` , glpi_tickettasks
WHERE glpi_tickets.id = glpi_tickettasks.`tickets_id`
AND glpi_tickets.is_deleted =0
AND glpi_tickets.id = ".$row['id']."
ORDER BY id DESC ";

$res_data = $DB->query($sql_data);


	while($row = $DB->fetch_assoc($res_data)){
			
		//nome e total
		$sql_nome = "
		SELECT glpi_users.id, glpi_users.firstname , glpi_users.realname, glpi_users.name
		FROM glpi_users, glpi_tickettasks
		WHERE glpi_tickettasks.users_id = glpi_users.id
		AND glpi_users.id = ".$row['uid']." ";
		
		$result_nome = $DB->query($sql_nome) ;
		$row_nome = $DB->fetch_assoc($result_nome);
		
		//Requester
		$sql_req = "SELECT gu.firstname AS name, gu.realname AS sname
						FROM glpi_users gu, glpi_tickets_users gtu
						WHERE gtu.tickets_id = ".$row['id']."
						AND gtu.users_id = gu.id
						AND gtu.type = 1 ";
		
		$result_req = $DB->query($sql_req) ;
		$req = $DB->fetch_assoc($result_req);
				
	echo"
		<tr>	
			<td colspan='2' style='text-align:center; vertical-align:middle;'> ". conv_data_hora($row['date']) ." </td>
			<td style='vertical-align:middle;'> ". $row_nome['firstname'] ." ".$row_nome['realname']." </td>
			<td style='vertical-align:middle;'> ". $req['name']." ".$req['sname']." </td>
			<td style='max-width:400px; vertical-align:middle;'> ". $row['content'] ." </td>
			<td style='text-align:center; vertical-align:middle;'> ". time_ext($row['actiontime']) ."</td>
			<td style='text-align:center; vertical-align:middle;'> ". conv_data_hora($row['begin']) ."</td>
			<td style='text-align:center; vertical-align:middle;'> ". conv_data_hora($row['end']) ."</td>
		</tr>";
	}

}

echo "</tbody>
		</table>
		</div>"; ?>

<script type="text/javascript" charset="utf-8">

$('#tarefa')
	.removeClass( 'display' )
	.addClass('table table-bordered table-hover table-condensed dataTable');

$(document).ready(function() {
    $('#tarefa').DataTable( {    	

		  select: true,	    	    	
        dom: 'Blfrtip',
        filter: false,        
        pagingType: "full_numbers",
        sorting: [[0,'desc'],[1,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc'],[6,'desc']],
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
		                 autoPrint: false,
		                 text: "<?php echo __('All','dashboard'); ?>",
		                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'>  </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  _n('Task','Tasks',2); ?> : </span><?php echo $conta_cons ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'><?php echo __('Time'); ?></span> : <?php echo time_ext($tempo_total); ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",		     
		                }, 
							  {               
		                 extend: "print",
		                 autoPrint: true,
		                 text: "<?php echo __('Selected','dashboard'); ?>",
		                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'>  </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  _n('Task','Tasks',2); ?> : </span><?php echo $conta_cons ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'><?php echo __('Time'); ?></span> : <?php echo time_ext($tempo_total); ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
		                 exportOptions: {
		                    modifier: {
		                        selected: true
		                    }
		                }
		                }
	                ]
             },
             {
                 extend: "collection",
                 text: "<?php echo _x('button', 'Export'); ?>",
                 buttons: [ "csvHtml5", "excelHtml5",
                  {
                 		extend: "pdfHtml5",
                 		orientation: "landscape",
							message: "<?php echo  _n('Task','Tasks',2); ?> : <?php echo $conta_cons.'\n'; ?>  <?php echo  __('Period','dashboard'); ?> : <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2).'\n'; ?> <?php echo __('Time'); ?> : <?php echo time_ext($tempo_total); ?>",                 		
                 		
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

</div>
</div>
</div>
</body>
</html>

