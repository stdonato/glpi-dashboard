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
	$data_ini = date("Y-01-01");
   $data_fin = date("Y-m-d");
}

/*if(!isset($_POST["sel_pro"])) {
    $id_pro = $_REQUEST["pro"];
}

else {
    $id_pro = $_POST["sel_pro"];
}*/

# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

//select entity
if($sel_ent == '' || $sel_ent == -1) {
	
	//get all user entities
	$entities = $_SESSION['glpiactiveentities'];	
	$ent = implode(",",$entities);

	$entidade = "AND glpi_projects.entities_id IN (".$ent.") ";
	$entidade1 = "";

}
else {
	$entidade = "AND glpi_projects.entities_id IN (".$sel_ent.") ";
}

?>

<html>
<head>
<title> GLPI - <?php echo _n('Project','Projects',2); ?> </title>
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
   a:hover {color: #000099;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>

</head>

<body style="background-color: #e5e5e5; margin-left:0%;">

<div id='content' >
<div id='container-fluid' style="margin: <?php echo margins(); ?> ;">
<div id="charts" class="fluid chart" >
<div id="pad-wrapper" >
	<div id="head-rel" class="fluid">	
	<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>	
	    <div id="titulo_rel"> <?php echo _n('Project','Projects',2); ?>  </div>
	    <div id="datas-tec3" class="span12 fluid" >
	    <form id="form1" name="form1" class="form_rel" method="post" action="./rel_projects.php?con=1" style="margin-left: 37%;">
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
					<td style="margin-top:2px;">
	
			</td>
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

if(!isset($_POST['date1'])) {
	 $data_ini2 = $data_ini;
	 $data_fin2 = $data_fin; 
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

// Projetos
$sql_cham =
"SELECT *
FROM glpi_projects
WHERE glpi_projects.date ". $datas2 ."
".$entidade."
ORDER BY id ASC ";

$result_cham = $DB->query($sql_cham);

$conta_cons = $DB->numrows($result_cham);

	echo "
	<div class='well info_box fluid col-md-12 report' style='margin-left: -1px;'>

	<table class='fluid' style='font-size: 18px; font-weight:bold; margin-bottom: 30px; width:100%;' cellpadding = '1px'>
		<tr>
			<td style='vertical-align:middle; width:350px;'> <span style='color: #000;'>"._n('Project', 'Projects',2).": </span>". $conta_cons ."</td>
			<td colspan='4' style='font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)."</td>
		</tr>
	</table>";

	echo "
	<table id='tarefa' class='display' style='font-size: 13px; font-weight:bold;' cellpadding = 2px>
		<thead>
			<tr>
				<th style='text-align:center; cursor:pointer; vertical-align:middle'> ". __('ID') ."  </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle'> ". __('Name') ."  </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle'> ". __('Status') ." </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle'> ". __('Planned start date') ." </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle'> ". __('Planned end date') ." </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle'> ". __('Manager') ."  </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle'> ". _n('Task', 'Tasks',2) ." </th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle'> ". __('Progress') ."</th>
				<th style='text-align:center; cursor:pointer; vertical-align:middle'> ". __('Due Date','dashboard') ."</th>
			</tr>
		</thead>
	<tbody>
	";

//listar projetos

$DB->data_seek($result_cham, 0);
while($row = $DB->fetch_assoc($result_cham)){

	//status
	$sql_stat = "
	SELECT id, name, color, is_finished
	FROM glpi_projectstates
	WHERE id = ".$row['projectstates_id']." ";

	$result_stat = $DB->query($sql_stat) ;
	$row_stat = $DB->fetch_assoc($result_stat);

	//tasks
	$sql_task = "
	SELECT COUNT(*) as tasks
	FROM glpi_projecttasks
	WHERE projects_id = ".$row['id']." ";

	$result_task = $DB->query($sql_task) ;
	$row_task = $DB->fetch_assoc($result_task);

	//planned time
	$sql_time = "SELECT TIMESTAMPDIFF(SECOND,plan_start_date,plan_end_date) AS time FROM glpi_projects WHERE id = ".$row['id']."";
	$res_time = $DB->query($sql_time);
	$plan_time = $DB->result($res_time,0,'time');

	//real time
	$sql_timer = "SELECT TIMESTAMPDIFF(SECOND,plan_start_date,NOW()) AS timer FROM glpi_projects WHERE id = ".$row['id']."";
	$res_timer = $DB->query($sql_timer);
	$plan_timer = $DB->result($res_timer,0,'timer');


	//time percent
	$now = date("Y-m-d H:i:s");

		if($row['plan_end_date'] <= $now and $row_stat['is_finished'] == 0) {
			if($row['real_end_date'] <= $now ) {
		   	$barra = 100;
		    	$cor_due = "progress-bar-danger";
		    	$message = __('Late');
			}
		}

	else {

	//if($row_stat['is_finished'] == 0) {

		$time_plan = round(($plan_timer * 100)/$plan_time,0);
    if($time_plan <= 100) {
		  $message = $time_plan.'%';
    }
    else {
      $message = '100%';
    }
		//porcentagem
		$barra = $time_plan;

		// cor barra
		if($barra == 100) { $cor_due = "progress-bar-danger"; }
		if($barra >= 80 and $barra < 100) { $cor_due = "progress-bar-warning "; }
		if($barra > 51 and $barra < 80) { $cor_due = "progress-bar-warning"; }
		if($barra > 0 and $barra <= 50) { $cor_due = " "; }
		if($barra < 0) { $cor_due = "progress-bar-success"; $barra = 0; }

	}

	// progress bar color
	if($row['percent_done'] == 100) { $cor = "progress-bar-success"; }
	else { $cor = ""; }

	echo "
	<tr style='font-weight:normal;'>
		<td style='text-align:center; vertical-align:middle'><a href=".$CFG_GLPI['url_base']."/front/project.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a></td>
		<td style='vertical-align:middle'> ". $row['name'] ." </td>
		<td style='text-align:center; vertical-align:middle; color:".$row_stat['color'].";'> ". $row_stat['name'] ." </td>
		<td style='text-align:center; vertical-align:middle'> ". conv_data_hora($row['plan_start_date']) ."</td>
		<td style='text-align:center; vertical-align:middle'> ". conv_data_hora($row['plan_end_date']) ."</td>
		<td style='text-align:center; vertical-align:middle;'> ". getUserName($row['users_id']) ." </td>
		<td style='text-align:center; vertical-align:middle'><a href='./rel_projecttasks.php?sel_pro=". $row['id'] ."' target=_self >" . $row_task['tasks'] . "</a></td>
		<td style='text-align:center; vertical-align:middle;'>
			<div class='progress' style='margin-top: 5px; margin-bottom: 5px;'>
				<div class='progress-bar " . $cor . " ' role='progressbar' aria-valuenow='".$row['percent_done']."' aria-valuemin='0' aria-valuemax='100' style='width: ".$row['percent_done']."%;'>
				 			".$row['percent_done']." %
				</div>
			</div>
		</td>
		<td style='text-align:center; vertical-align:middle;'>
		<div class='progress' style='margin-top: 19px;'>
			<div class='progress-bar ". $cor_due ." ' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
	 				 		" . $message . "
	 			</div>
	 		</div>
		</td>
	</tr>";
}

echo "</tbody>
		</table>
		</div>"; ?>

<script type="text/javascript" charset="utf-8">

$('#tarefa')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered table-hover dataTable');

$(document).ready(function() {
    $('#tarefa').DataTable( {    	

		  select: true,	    	    	
        dom: 'Blfrtip',
        filter: false,        
        pagingType: "full_numbers",
        "aaSorting": [[0,'asc'],[1,'desc'],[2,'desc'],[3,'desc'],[4,'desc'],[5,'desc'],[6,'desc'],[7,'desc'],[8,'desc']],
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
		                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'>  </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  _n('Task','Tasks',2); ?> : </span><?php echo $conta_cons ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
		                }, 
							  {               
		                 extend: "print",
		                 autoPrint: true,
		                 text: "<?php echo __('Selected','dashboard'); ?>",
		                 message: "<div id='print' class='info_box fluid span12' style='margin-bottom:35px; margin-left: -1px;'><table id='print_tb' class='fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'>  </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  _n('Task','Tasks',2); ?> : </span><?php echo $conta_cons ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>",
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
                 		message: "<?php echo  __('Period','dashboard'); ?> : <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?>",
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
//}
?>

</div>
</div>
</div>
</body>
</html>
