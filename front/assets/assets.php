<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");
include "../inc/functions.php";

Session::checkLoginUser();
Session::checkRight("profile", READ);

# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

$asset = '';

//select entity
if($sel_ent == '' || $sel_ent == -1) {	

	$entities = $_SESSION['glpiactiveentities'];	
	$ent = implode(",",$entities);

	$ent_comp = "AND glpi_computers.entities_id IN (".$ent.")"; 
	$ent_mon = "AND glpi_monitors.entities_id IN (".$ent.")";
	$ent_print = "AND glpi_printers.entities_id IN (".$ent.")";
	$ent_net = "AND glpi_networkequipments.entities_id IN (".$ent.")";
	$ent_peri = "AND glpi_peripherals.entities_id IN (".$ent.")";
	$ent_phone = "AND glpi_phones.entities_id IN (".$ent.")";
	$ent_soft = "AND glpi_softwares.entities_id IN (".$ent.")";
	$ent_cons = "WHERE entities_id IN (".$ent.")";
	$ent_global = "AND glpi_". strtolower($asset)."s.entities_id IN (".$ent.")";
	$ent_global1 = "AND glpi_tickets.entities_id IN (".$ent.")";
	$ent = "AND entities_id IN (".$ent.")";
	$entidade1 = "";
	
}	

else {
	$ent_comp = "AND glpi_computers.entities_id IN (".$sel_ent.")"; 
	$ent_mon = "AND glpi_monitors.entities_id IN (".$sel_ent.")";
	$ent_print = "AND glpi_printers.entities_id IN (".$sel_ent.")";
	$ent_net = "AND glpi_networkequipments.entities_id IN (".$sel_ent.")";
	$ent_peri = "AND glpi_peripherals.entities_id IN (".$sel_ent.")";
	$ent_phone = "AND glpi_phones.entities_id IN (".$sel_ent.")";
	$ent_soft = "AND glpi_softwares.entities_id IN (".$sel_ent.")";
	$ent_cons = "WHERE entities_id IN (".$sel_ent.")";
	$ent_global = "AND glpi_". strtolower($asset)."s.entities_id IN (".$sel_ent.")";
	$ent_global1 = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
	$ent = "AND entities_id IN (".$sel_ent.")";
	}


//Count assets 
function conta($asset, $sel_ent) {

	global $DB;
	
	if($sel_ent == '' || $sel_ent == -1) {
		
		$entities = $_SESSION['glpiactiveentities'];	
		$ent = implode(",",$entities);
		$ent_global= "AND entities_id IN (".$ent.")";	
	}
	else {
		$ent_global = "AND entities_id IN (".$sel_ent.")";
	}
	
	$query = "
	SELECT count(id) AS id
	FROM glpi_".$asset."
	WHERE is_deleted = 0
	AND is_template = 0 
	".$ent_global." ";
	
	$result = $DB->query($query);
	$total = $DB->result($result,0,'id');
	
	if($total != "") {
		return $total;
	}
	
	else {
		return "0";
	}
}	

// Count cartridges and consumables
function conta1($asset,$sel_ent) {

	global $DB;
	
	if($sel_ent == '' || $sel_ent == -1) {
		
		$entities = $_SESSION['glpiactiveentities'];	
		$ent = implode(",",$entities);
		$ent_global= "WHERE entities_id IN (".$ent.")";
	}
	else {
		$ent_global = "WHERE entities_id IN (".$sel_ent.")";
	}
	
	$query = "
	SELECT count(id) AS id
	FROM glpi_".$asset." 
	".$ent_global." ";
	
	$result = $DB->query($query);
	$total = $DB->result($result,0,'id');
	
	if($total != "") {
		return $total;
	}
	
	else {
		return "0";
	}	
}	

//all assets - global
$arr_assets =  array('computers', 'monitors', 'printers', 'networkequipments', 'phones', 'peripherals');
$global = 0;

foreach($arr_assets as $asset) {
	
	if($sel_ent == '' || $sel_ent == -1) {
		$entities = $_SESSION['glpiactiveentities'];	
		$ent = implode(",",$entities);
		$ent_global= "AND entities_id IN (".$ent.")";
	}
	else {
		$ent_global = "AND entities_id IN (".$sel_ent.")";
	}	
	
	$query = "
	SELECT count(id) AS id
	FROM glpi_".$asset."
	WHERE is_deleted = 0
	AND is_template = 0 
	".$ent_global." ";
	
	$result = $DB->query($query);
	$total = $DB->result($result,0,'id');
	
	$global+=$total;
}
?>        

<html> 
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<title> GLPI - <?php echo __('Assets'); ?> </title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<meta http-equiv="content-language" content="en-us">
<meta charset="utf-8">

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="../css/styles.css" rel="stylesheet" type="text/css">
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css">
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />

<script src="../js/jquery.min.js" type="text/javascript"></script>
<script src="../js/highcharts.js"></script>
<script src="../js/modules/exporting.js"></script>

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

<?php echo '<script src="../js/themes/'.$_SESSION['charts_colors'].'"></script>'; ?>

<style type="text/css">		
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?>  
<?php echo '<script src="../js/themes/'.$_SESSION['charts_colors'].'"></script>'; ?>


<script type="text/javascript">
	function showDiv(div){
		
		if (document.getElementById(div).style.display == 'block') 
		  { 		
			document.getElementById(div).style.display = 'none';
			}
		else {		
			document.getElementById(div).style.display = 'block';
			}
	
	}
</script>	

</head>

<body style=" margin-left:0%; background:#E5E5E5;">
<div id='content' style="margin: 20px 0% 0% 0%; float:none;">	
	  	 	
		<div id="head" class="fluid head-asset" style="min-height: 100px;">
			<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>
			<div id="titulo_graf" style="margin-bottom: 20px;"> <?php echo __('Assets'); ?> </div> 	 
		<!--</div>-->
      
         <div id="tabela_assets" class="tab_assets">  
	        <table id="assets" class="assets" border="0" cellpadding="3" style="font-size:14px;">
	          <tbody>	         
	            <tr>
	            <?php echo "
	              <td style='width:100px; text-align:center;'><div id='asset_img' style='background: url(../img/computer.jpg) repeat-x; 
	      			background-size: 70px 70px; background-position: center; background-color: ffffff; cursor:pointer;' onclick=showDiv('computers')></div> </td>
	       			
	              <td style='width:100px; text-align:center;'><div id='asset_img' style='background: url(../img/monitor.jpg) no-repeat; 
	      			background-size: 70px 70px; background-position: center; background-color: ffffff; cursor:pointer;' onclick=showDiv('monitors')></div></td>
	      			
	              <td style='width:100px; text-align:center;'><div id='asset_img' style='background: url(../img/printer.jpg) repeat-x; 
	      			background-size: 70px 70px; background-position: center; background-color: ffffff; cursor:pointer;' onclick=showDiv('printers')></div></td>      		             
	      			
	              <td style='width:100px; text-align:center;'><div id='asset_img' style='background: url(../img/network.jpg) repeat-x; 
	      			background-size: 70px 70px; background-position: center; background-color: ffffff; cursor:pointer;' onclick=showDiv('net')></div> </td>
	      			
	              <td style='width:100px; text-align:center;'><div id='asset_img' style='background: url(../img/phone.jpg) repeat-x; 
	      			background-size: 70px 70px; background-position: center; background-color: ffffff; cursor:pointer;' onclick=showDiv('phone')></div></td>
	              
	              <td style='width:100px; text-align:center;'><div id='asset_img' style='background: url(../img/device.jpg) no-repeat; 
	      			background-size: 70px 70px; background-position: center; background-color: ffffff; cursor:pointer;' onclick=showDiv('peripheral')></div></td>
	
	 					<td style='width:100px; text-align:center;'><div id='asset_img' style='background: url(../img/software.jpg) repeat-x; 
	      			background-size: 70px 70px; background-position: center; background-color: ffffff; cursor:pointer;' onclick=showDiv('soft')></div> </td>
	
						<td style='width:100px; text-align:center;'><div id='asset_img' style='background: url(../img/cartridges.jpg) repeat-x; 
	      			background-size: 70px 70px; background-position: center; background-color: ffffff; cursor:pointer;' onclick=showDiv('cart')></div> </td>
	      			
	      			<td style='width:100px; text-align:center;'><div id='asset_img' style='background: url(../img/consumables.jpg) repeat-x; 
	      			background-size: 70px 70px; background-position: center; background-color: ffffff; cursor:pointer;' onclick=showDiv('consum')></div> </td>
	              
	              <td style='width:100px; text-align:center;'><div id='asset_img' style='background: url(../img/global.jpg) repeat-x; 
	      			background-size: 70px 70px; background-position: center; background-color: ffffff; cursor:pointer;' onclick=showDiv('global')></div> </td>              
	               ";                                 
	             ?> 
	            </tr>  
	            <tr>
	            <?php echo '
	            	<td> <a href="assets.php#" onclick=showDiv(\'computers\') style="color: #fff;">
	            	'. _n('Computer', 'Computers',2).'<br>'. conta('computers',$sel_ent) .'</a></td>
	            	
	            	<td> <a href="assets.php#" onclick=showDiv(\'monitors\') style="color: #fff;">
	            	'._n('Monitor','Monitors',2).'<br>'. conta('monitors',$sel_ent) .'</a></td>
	            	
	            	<td> <a href="assets.php#" onclick=showDiv(\'printers\') style="color: #fff;">
	            	'._n('Printer','Printers',2).'<br>'. conta('printers',$sel_ent) .'</a></td>
	            	
	            	<td> <a href="assets.php#" onclick=showDiv(\'net\') style="color: #fff;">
	            	'._n('Network','Networks',2).'<br>'. conta('networkequipments',$sel_ent) .'</a></td>
	            	
	            	<td> <a href="assets.php#" onclick=showDiv(\'phone\') style="color: #fff;">
	            	'._n('Phone','Phones',2).'<br>'. conta('phones',$sel_ent) .' </a></td>
	            	
	            	<td> <a href="assets.php#" onclick=showDiv(\'peripheral\') style="color: #fff;">
	            	'._n('Device','Devices',2).'<br>'. conta('peripherals',$sel_ent) .' </a></td>
	            	
	            	<td> <a href="assets.php#" onclick=showDiv(\'soft\') style="color: #fff;">
	            	'._n('Software','Softwares',2).'<br>'. conta('softwares',$sel_ent) .'</a></td>
	            	
	            	<td> <a href="assets.php#" onclick=showDiv(\'cart\') style="color: #fff;">
	            	'._n('Cartridge','Cartridges',2).'<br>'. conta1('cartridgeitems',$sel_ent) .'</a></td>
	            	
	            	<td> <a href="assets.php#" onclick=showDiv(\'consum\') style="color: #fff;">
	            	'._n('Consumable','Consumables',2).'<br>'. conta1('consumableitems',$sel_ent) .' </td>
	            	
	            	<td> <a href="assets.php#" onclick=showDiv(\'global\') style="color: #fff;">
	            	'.__('Global').'<br>'. $global .' </a></td> ';
	            ?>	
	            </tr>                                 
	          </tbody>
	        </table>
			</div>  
		</div>
	</div>	  

<div id='container-fluid' style="margin: 0; float:none;"> 
	<div id='charts_assets' style="col-md-12 fluid">		    	    	   		 	      		 	
	
				<div id="computers" class="col-md-12 col-sm-12" style="display:none; margin:auto; float:none; color:#000;"> 										
					
					<div id="graf_os" class="col-md-6 col-sm-6" style="min-height:500px;">
						<?php  include('./comp_os.php'); ?>		
					</div>
				
					<div id="graf_cat" class="col-md-6 col-sm-6" style="min-height:500px;">
						<?php  include('./comp_cat.php'); ?>		
					</div>
					
					<div id="graf_manufac" class="well col-md-12" style="margin-top: 25px; margin-left: 1%;">
						<?php  include('./comp_manuf.php'); ?>		
					</div>
									
					<div id="graf_ticket" class="well col-md-12" style="margin-top: 20px; margin-left: 1%;">
						<?php  include('./comp_ticket.php'); ?>		
					</div>			
														
				</div>
						
					<div id="monitors" style="display:none; margin:auto; float:none;" class="col-md-12 col-sm-12"> 							
						<div id="graf_mon1" class="col-md-6 col-sm-6" style="margin-top: 0; min-height:500px;">
							<?php  include('./mon_manuf.php'); ?>		
						</div>
						<div id="graf_mon2" class="col-md-6 col-sm-6" style="margin-top: 0; min-height:500px;">
							<?php  include('./mon_model.php'); ?>		
						</div>						
					</div>
			
					<div id="printers" style="display:none; margin: auto;" class="col-md-12 col-sm-12"> 				
						<div id="graf_printer1" class="col-md-6 col-sm-6" style="margin-top: 0; min-height:500px;">
							<?php  include('./printer_manuf.php'); ?>		
						</div>
						<div id="graf_printer2" class="col-md-6 col-sm-6" style="margin-top: 0; min-height:500px;">
							<?php  include('./printer_model.php'); ?>		
						</div>							
					</div>	

		
					<div id="net" style="display:none; margin: auto; float:none;" class="col-md-12 col-sm-12"> 						
						<div id="graf_net1" class="col-md-6 col-sm-6" style="margin-top: 0; min-height:500px;">
							<?php  include('./net_manuf.php'); ?>		
						</div>
						<div id="graf_net2" class="col-md-6 col-sm-6" style="margin-top: 0; min-height:500px;">
							<?php  include('./net_model.php'); ?>		
						</div>							
					</div>				
	
					<div id="phone" style="display:none; margin: auto;" class="col-md-12 col-sm-12"> 							
						<div id="graf_phone1" class="col-md-6 col-sm-6" style="margin-top: 0; min-height:500px;">
							<?php  include('./phone_manuf.php'); ?>		
						</div>
						<div id="graf_phone2" class="col-md-6 col-sm-6" style="margin-top: 0; min-height:500px;">
							<?php  include('./phone_model.php'); ?>		
						</div>
						<div id="phones_report" class="col-md-12 col-sm-12 well" style="margin-top:25px; margin-left: 1%;">
							<?php  include('./phone_report.php'); ?>		
						</div>							
					</div>				
						
					<div id="peripheral" style="display:none; margin: auto;" class="col-md-12 col-sm-12"> 					
						<div id="graf_perip1" class="col-md-6 col-sm-6" style="margin-top: 0; min-height:500px;">
							<?php  include('./perip_manuf.php'); ?>		
						</div>
						<div id="graf_perip2" class="col-md-6 col-sm-6" style="margin-top: 0; min-height:500px;">
							<?php  include('./perip_model.php'); ?>		
						</div>							
					</div>			
					
					<div id="soft" style="display:none; margin: auto;" class="col-md-12 col-sm-12">
					<!--<a href="assets.php#"><img src="../img/close.png" alt="close" onclick="showDivS('soft')" style="position:absolute; float:right;"></a>-->
						<div id="graf_soft1" class="col-md-6 col-sm-6" style="margin-top: 0; min-height:500px;">
							<?php  include('./soft_manuf.php'); ?>		
						</div>
						<div id="graf_soft2" class="col-md-6 col-sm-6" style="margin-top: 0; min-height:500px;">
							<?php  include('./soft_install.php'); ?>		
						</div>							
					</div>							
				
					<div id="cart" style="display:none; margin: auto;" class="col-md-12 col-sm-12"> 					
						<div id="graf_cart1" class="col-md-12 col-sm-12" style="margin-top: 0; min-height:500px; width: 98%;">
							<?php  include('./cart_manuf.php'); ?>		
						</div>
						<div id="graf_cart2" class="col-md-12 col-sm-12 well" style="margin-top: 25px; margin-left: 1%;">
							<?php  include('./cart_quant.php'); ?>		
						</div>							
					</div>
					
					<div id="consum" style="display:none; margin: auto;" class="col-md-12 col-sm-12"> 					
						<div id="graf_consum1" class="well col-md-12" style="margin-top: 0; min-height:500px; width: 98%;">
							<?php  include('./consumables.php'); ?>		
						</div>						
					</div>
				
					<div id="global" style="display:none; margin: auto;" class="col-md-12 col-sm-12">
					<!--<a href="assets.php#"><img src="../img/close.png" alt="close" onclick="showDivG('global')" style="position:absolute; float:right;"></a>-->			
						<div id="graf_global1" class="col-md-12 col-sm-12" style="margin-top: 0; min-height:500px; width: 98%;">
							<?php  include('./global_assets.php'); ?>		
						</div>
						<div id="asset_tickets" class="col-md-12 col-sm-12 well" style="margin-top: 25px; margin-left: 1%;">
							<?php  include('./global_tickets.php'); ?>		
						</div>							
					</div>	 
		<!--</div>-->	
	</div>
</div>		

<!-- Highcharts export xls, csv -->
<script src="../js/export-csv.js"></script>

</body>
</html>
