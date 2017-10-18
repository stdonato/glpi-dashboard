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
	$data_ini = date("Y-01-01");
	$data_fin = date("Y-m-d");	
	}  

// entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

//select entity
if($sel_ent == '' || $sel_ent == -1) {	

	//get all user entities
	$entities = $_SESSION['glpiactiveentities'];										
	$ent = implode(",",$entities);

	$entidade = "AND glpi_tickets.entities_id IN (".$ent.") ";	
	$entidade1 = "";
	
}
else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.") ";
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
   a:hover {color: #000099;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>
 
</head>

<body style="background-color: #e5e5e5;">

<div id='content' >
	<div id='container-fluid' style="margin: <?php echo margins(); ?> ;">
		<div id="charts" class="fluid chart"> 
			<div id="pad-wrapper" >
			<div id="head-rel" class="fluid">			
			
			<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>
			
				<div id="titulo_rel"> <?php echo __('Tickets', 'dashboard') .'  '. __('by Category', 'dashboard') ?> </div>				
				<div id="datas-tec" class="col-md-12 col-sm-12 fluid" >		
					 
				<form id="form1" name="form1" class="form_rel" method="post" action="rel_categorias.php?con=1" style="margin-left: 37%;"> 
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
					</td>
			</tr>
			<tr><td height="15px"></td></tr>
			<tr>
				<td colspan="2" align="center">
					<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?> </button>
					<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2.'?con=1'; ?>'" ><i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button>
				</td>
			</tr>
				
				</table>
			<?php Html::closeForm(); ?>
			<!-- </form> -->
					</div>
				</div>
			
			<?php 
			
			//categorias			
			$con = $_GET['con'];
			
			if($con == "1") {
			
			if(!isset($_POST['date1']))
			{	
				$data_ini2 = $data_ini; //$_GET['date1'];	
				$data_fin2 = $data_fin; //$_GET['date2'];
			}
			
			else {	
				$data_ini2 = $_POST['date1'];	
				$data_fin2 = $_POST['date2'];	
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
						
			if(isset($_GET['stat'])) {
				
				if($_GET['stat'] == "open") {		
					$status = $status_open;
				}
				elseif($_GET['stat'] == "close") {
					$status = $status_closed;
				}
				else {
					$status = $status_all;	
				}
			}
			
			else {
				$status = $status_all;
				}
		
		//select categories with tickets	
		$sql_cat = 
		"SELECT glpi_itilcategories.id, glpi_itilcategories.completename AS name, count(glpi_tickets.id) AS total
		FROM glpi_itilcategories, glpi_tickets
		WHERE glpi_tickets.is_deleted = 0
		AND glpi_tickets.itilcategories_id = glpi_itilcategories.id
		AND glpi_tickets.date ".$datas2."
		".$entidade."		
		GROUP BY id
		ORDER BY total DESC ";			
		
		$result_cat = $DB->query($sql_cat);				
			
echo "<div class='well info_box fluid col-md-12 col-sm-12 report' style='margin-left: -1px;'>";		
echo "							
			<table id='cat' class='display'  style='font-size: 12px; font-weight:bold;' cellpadding = 2px>
				<thead>
					<tr>
						<th style='text-align:center; cursor:pointer;'> ". __('Category')." </th>
						<th style='font-size: 12px; font-weight:bold; text-align: center; cursor:pointer;'> ".__('Tickets')." </th>
						<th style='text-align:center; cursor:pointer;'> ". __('Opened','dashboard') ."</th>
						<th style='text-align:center; cursor:pointer;'> ". __('Solved','dashboard') ."</th>	
						<th style='text-align:center; cursor:pointer;'> ". __('Closed','dashboard') ."</th>															
					</tr>
				</thead>
			<tbody> ";
			
			while($row = $DB->fetch_assoc($result_cat)){			
											
				 // Chamados
				$sql_cham = 
				"SELECT count( glpi_tickets.id ) AS total
				FROM glpi_tickets
				WHERE glpi_tickets.is_deleted = 0
				AND glpi_tickets.date ".$datas2."
				AND glpi_tickets.itilcategories_id = ".$row['id']."
				".$entidade." ";
				
				$result_cham = $DB->query($sql_cham);
				$data_cham = $DB->fetch_assoc($result_cham);
				$chamados = $data_cham['total'];			
				
				//chamados abertos
				$sql_abe = 
				"SELECT count( glpi_tickets.id ) AS total
				FROM glpi_tickets
				WHERE glpi_tickets.is_deleted = 0
				AND glpi_tickets.date ".$datas2."
				AND glpi_tickets.status NOT IN ".$status_closed."
				AND glpi_tickets.itilcategories_id = ".$row['id']."
				".$entidade." ";
				
				$result_abe = $DB->query($sql_abe);	
				$data_abe = $DB->fetch_assoc($result_abe);
				$abertos = $data_abe['total'];	
				
				//chamados solucionados
				$sql_sol = 
				"SELECT count( glpi_tickets.id ) AS total
				FROM glpi_tickets
				WHERE glpi_tickets.is_deleted = 0
				AND glpi_tickets.date ".$datas2."
				AND glpi_tickets.status = 5
				AND glpi_tickets.itilcategories_id = ".$row['id']."
				".$entidade." ";
				
				$result_sol = $DB->query($sql_sol);	
				$data_sol = $DB->fetch_assoc($result_sol);
				$solucionados = $data_sol['total'];
				
				//chamados fechados
				$sql_fech = 
				"SELECT count( glpi_tickets.id ) AS total
				FROM glpi_tickets
				WHERE glpi_tickets.is_deleted = 0
				AND glpi_tickets.date ".$datas2."
				AND glpi_tickets.status = 6
				AND glpi_tickets.itilcategories_id = ".$row['id']."
				".$entidade." ";
				
				$result_fech = $DB->query($sql_fech);	
				$data_fech = $DB->fetch_assoc($result_fech);
				$fechados = $data_fech['total'];							
				
				echo "	
				<tr>
					<td style='vertical-align:middle; text-align:left;'><a href='rel_categoria.php?con=1&cat=". $row['id'] ."&date1=".$data_ini."&date2=".$data_fin."' target='_blank' >".$row['name']." </a></td>
					<td style='vertical-align:middle; text-align:center;'> ".$chamados." </td>
					<td style='vertical-align:middle; text-align:center;'> ". $abertos ." </td>
					<td style='vertical-align:middle; text-align:center;'> ". $solucionados ." </td>
					<td style='vertical-align:middle; text-align:center;'> ". $fechados ." </td>				
				</tr>";
			}

			echo "</tbody>
					</table>
					</div>"; 
				
					?>
			
			<script type="text/javascript" charset="utf-8">
			
			$('#cat')
				.removeClass( 'display' )
				.addClass('table table-striped table-bordered');
			
			$(document).ready(function() {
			    $('#cat').DataTable( {    	
			
					  select: true,	    	    	
			        dom: 'Blfrtip',
			        filter: false,        
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
					                 message: "<div id='print' class='info_box fluid col-md-12 col-sm-12' style='margin-bottom:12px; margin-left: -1px;'></div>",		     
					                }, 
										  {               
					                 extend: "print",
					                 autoPrint: true,
					                 text: "<?php echo __('Selected','dashboard'); ?>",
					                 message: "<div id='print' class='info_box fluid col-md-12 col-sm-12' style='margin-bottom:12px; margin-left: -1px;'></div>",
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

