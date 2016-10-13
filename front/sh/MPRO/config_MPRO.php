<?php

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

Session::checkLoginUser();
Session::checkRight("profile", "r");
?>        

<html> 
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<title> GLPI - <?php echo __('Setup'); ?> </title>
<!-- <base href= "<?php $_SERVER['SERVER_NAME'] ?>" > -->
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<meta http-equiv="content-language" content="en-us">
<meta charset="utf-8">

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="./css/styles.css" rel="stylesheet" type="text/css">
<link href="./css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="./css/bootstrap-responsive.css" rel="stylesheet" type="text/css">
<link href="./css/font-awesome.css" type="text/css" rel="stylesheet" />

<script src="./js/jquery.js" type="text/javascript"></script>
<script src="./inc/select2/select2.js" type="text/javascript" language="javascript"></script>
<link href="./inc/select2/select2.css" rel="stylesheet" type="text/css">

<?php echo '<link rel="stylesheet" type="text/css" href="./css/style-'.$_SESSION['style'].'">';  ?> 

<script type="text/javascript">
function reload(id) {	
	document.getElementById(id).submit();
}

function changeTheme(theme) {		
	location.href='config.php?theme=' + theme + '.css';
}

function chart(theme) {		
	location.href='config.php?colors=' + theme ;	
}

</script>
<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="js/jquery-picklist.js"></script>
	<script type="text/javascript">
		$(function()
		{
			$("#sel_ent").pickList(
			{
				sourceListLabel:	"Available",
				targetListLabel:	"Added",
				addAllLabel:		"Add All",
				addLabel:			"Add",
				removeAllLabel:	"Remove All",
				removeLabel:		"Remove",
				sortAttribute:		"value",
				 items:
				[
					{
						value: "29,30,37,39",
						label: "PATI Ariquemes",
						selected: false
					},
					{
						value: "27,31,46",
						label: "PATI Cacoal",
						selected: false
					},
					{
						value: "36",
						label: "PATI Guajará-Mirim",
						selected: false
					},
					{
						value: "38,41,43,28,47,34,40",
						label: "PATI Ji-Paraná",
						selected: false
					},
					{
						value: "35,42",
						label: "PATI Pimenta Bueno",
						selected: false
					},
										{
						value: "44,45",
						label: "PATI Rolim de Moura",
						selected: false
					},
					{
						value: "32,33,48",
						label: "PATI Vilhena",
						selected: false
					}
				] 
			});

			// Example of adding a regular item after picklist creation.			
			$("#sel_ent1").pickList("insert",
			
			{
				value: "29,30,37,39",
				label: "PATI Ariquemes",
				selected: false
			},
			{
				value: "27,31,46",
				label: "PATI Cacoal",
				selected: false
			}
			);
		});
	</script>

	<link type="text/css" href="css/jquery-picklist.css" rel="stylesheet" />

</head>

<body style="background-color: #e5e5e5;">

<div id='content'>
	<div id='container-fluid' style="margin: 0px 8% 0px 8%;"> 	
	
		<div id="head-tic" class="row-fluid">	
			<a href="./index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px; margin-top:15px;"></i><span></span></a>
			<div id="titulo" class="tit-config" style="margin-bottom: 25px;"> <a href="config.php" ><?php echo __('Setup')." ".__('Dashboard','dashboard'); ?> </a></div> 
		</div>
				                                                           
			<div id="charts" class="row-fluid chart" style="background-color:#fff;">
				<!-- <div id="pad-wrapper" style="background-color:#e5e5e5;"> -->		
			
					<div id="tabela" class="row-fluid" >		
					<?php
					
					# selected entity for index
					$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
					$result_e = $DB->query($sql_e);
					//$prev_ent = $DB->result($result_e,0,'value');
					$prev_ent = $DB->fetch_assoc($result_e);	
					
					//$prev_ent = array(29,30);
					
			      echo '<div id="datas-tec2" class="col-md-12 row-fluid" style="background-color:#fff;">';
					
					//separa string com virgulas e converte em array
					$format_Arr = $prev_ent;
					$formated_arr = array();
					foreach($format_Arr as $arr){
					$arr1 = explode(",",$arr);
					if(count($arr1)>1){
					    $formated_arr = array_merge($formated_arr,explode(',',$arr));
					}else{
					    $formated_arr[]= $arr;
					}
					}
					//print_r($formated_arr);
			                                              			                        			
					//select user entities
					$entities = Profile_User::getUserEntities($_SESSION['glpiID'], true);
					$ents = implode(",",$entities);
					
					// lista de entidades
					$sql_ent = "
					SELECT id, name
					FROM `glpi_entities`
					WHERE id IN (".$ents.")
					ORDER BY `name` ASC ";
					
					$result_ent = $DB->query($sql_ent);
					
					$arr_ent = array();
					$arr_ent[0] = "-- ". __('Select a entity', 'dashboard') . " --" ;
				   $arr_ent[-1] = __('All', 'dashboard') ;					   // Para MPRO								   				   	
					
					while ($row_result = $DB->fetch_assoc($result_ent))
					 {
					    $v_row_result = $row_result['id'];
					    $arr_ent[$v_row_result] = $row_result['name'] ;
					 }
					 
					//reload page	
					if(isset($_REQUEST['up'])) {						
						echo "<meta HTTP-EQUIV='refresh' CONTENT='0.1;URL=config.php'>";						
						}	
						
					//reload page	
					if(isset($_REQUEST['layout'])) {						
						echo "<meta HTTP-EQUIV='refresh' CONTENT='0.1;URL=config.php'>";						
						}	
										
					
		echo '<div id="datas-tecx" class="col-md-12 row-fluid">'; 																															 

		echo "<table id='main' class='table-config' border='0' style='width:700px;'>";
			echo "<tr>";
				echo "<td>";			                                		 
			 		echo '<form id="form2" name="form2" method="post" action="config.php?conf=1">';   					
					echo " -- ".__('Entity','dashboard').":&nbsp;";				
					//echo dropdown( $name, $options, $selected );	
					
					echo '<select name="sel_ent[]" id="sel_ent" multiple style="width: 600px; height: 250px;"';
					
					foreach( $arr_ent as $key=>$option )
					{					
						if(in_array($key,$formated_arr)) { $select = 'selected'; }	
						else {$select = '';}
						
						echo '<option value="'.$key.'"'.$select.'>'.$option.'</option>'."\n";
					}
					
					echo "</select>"."\n";
					
					
					echo "<tr><td align='center'><button type='button' class='btn btn-primary' onclick='javascript:this.form.submit();' > ".__('Save')."</button></td></tr>";
					Html::closeForm(); 										
				echo "</td>";		
			echo "</tr>";

				   if(isset($_REQUEST['conf']) && $_REQUEST['conf'] == 1 ) {	      	
						if(isset($_REQUEST['sel_ent'])) {				
								
								$ents_sel = $_REQUEST['sel_ent'];
								
								$ent = implode(',',$ents_sel);												
								
								$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('entity', '".$ent."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$ent."' ";																
								$result = $DB->query($query);	
								
								//reload page
								echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=config.php'>";																						
						}								
					}				
								    
			
					# years in index
					$sql_y = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'num_years' AND users_id = ".$_SESSION['glpiID']."";
					$result_y = $DB->query($sql_y);
					$prev_years = $DB->result($result_y,0,'value');
			                        
					//count years
					$query = "SELECT DISTINCT DATE_FORMAT( date, '%Y' ) AS year
						FROM glpi_tickets
						WHERE glpi_tickets.is_deleted = '0'
						AND date IS NOT NULL
						ORDER BY year DESC";
					
					$result = $DB->query($query);
					$conta_y = $DB->numrows($result);
					
					$arr_years = array();
					
					while ($row_y = $DB->fetch_assoc($result))		
						{ 
							$arr_years[] = $row_y['year'];			
						} 
						
					$count_y = count($arr_years);
					            
							      if(isset($_REQUEST['conf']) && $_REQUEST['conf'] == 1 ) {	      	
										if(isset($_REQUEST['num'])) {				
												$num = $_REQUEST['num'];												
												
												$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
															  VALUES ('num_years', '".$num."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$num."' ";																
												$result = $DB->query($query);	
												
												//reload page
												echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=config.php'>";													
										}								
									}		
									
									// color theme  	
										if(isset($_REQUEST['theme'])) {				
												$skin = $_REQUEST['theme'];													
												
												$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
															  VALUES ('theme', '".$skin."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$skin."' ";																
												$result = $DB->query($query);	
												
												$_SESSION['theme'] = $skin;
												$_SESSION['style'] = $skin;
												
												//reload page
												echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=config.php'>";
																																													
										}	
										
										// backgrounds  	
										if(isset($_REQUEST['back'])) {				
												$back = $_REQUEST['back'];													
												
												$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
															  VALUES ('back', '".$back."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$back."' ";																
												$result = $DB->query($query);	
												
												$_SESSION['back'] = $back;																																													
										}
										
									// chats colors  	
										if(isset($_REQUEST['colors'])) {				
												$colors = $_REQUEST['colors'];													
												
												$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
														$que	  VALUES ('charts_colors', '".$colors."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$colors."' ";																
												$result = $DB->query($query);	
												
												$_SESSION['charts_colors'] = $colors;
												
												//reload page
												echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=config.php'>";																																													
										}																						 
					                               
		echo "<tr>";
		echo "<td>";					
					
			 		echo '<form id="form1" name="form1" method="post" action="config.php?conf=1">';   						
					echo "-- ".__('Period in index page','dashboard').":&nbsp; 
							<select id='num' name='num' style='width: 130px;' onChange='reload(\"form1\")'>
								<option value=''>".__('Select','dashboard')."</option>
								<option value='0'>".__('All')."</option>
								<option value='1'>".__('Current year','dashboard')."</option>";
							
							$year = date("Y");		
							for($i=2; $i <= $conta_y; $i++) {	
								echo "<option value='".$i."'>".$year." - ".($arr_years[0]-($i-1))."</option>";
						   }							   			
					Html::closeForm(); 

		echo "</td>";					 	
		echo "</tr>";	
					//get update option
					$query_up = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'update'";																
					$result_up = $DB->query($query_up);
					
					$up_option = $DB->result($result_up,0,'value');	
		
		echo "<tr>";
		echo "<td>";									
					echo '<form id="form3" name="form3" class="form3" method="post" action="config.php?up=1">';   						
					echo "-- ".__('Check for new updates').":&nbsp; 
							<select id='up' name='up' style='width: 130px;' onChange='reload(\"form3\")'> ";
								if($up_option == 1) {							
									echo "					
										<option value='0'>".__('No')."</option>
										<option value='1' selected>".__('Yes')."</option>
									</select>";						   			
									}
								else 	{							
									echo "					
										<option value='0' selected>".__('No')."</option>
										<option value='1'>".__('Yes')."</option>
									</select>";						   			
									}
					Html::closeForm();	
			
			echo "</td>";
			echo "</tr>";				
			
			if(isset($_POST['up']))  {	      	
							
								$update = $_POST['up'];												
								
								$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('update', '".$update."', 'x') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$update."' ";																
								$result = $DB->query($query);																																					
					}					
									
			
			// index layout
					$query_lay = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'layout' AND users_id = ".$_SESSION['glpiID']." ";																
					$result_lay = $DB->query($query_lay);
					
					$layout = $DB->result($result_lay,0,'value');	
			
			echo "<tr>";
			echo "<td>";								
					echo '<form id="form4" name="form4" class="form4" method="post" action="config.php?layout=1">';   						
					echo "-- ".__('Layout').":&nbsp; 
							<select id='layout' name='layout' style='width: 170px;' onChange='reload(\"form4\")'> ";
								if($layout == 1) {							
									echo "					
										<option value='0'>".__('Left side menu')."</option>
										<option value='1' selected>".__('Top menu')."</option>
									</select>";						   			
									}
								else 	{							
									echo "					
										<option value='0' selected>".__('Left side menu')."</option>
										<option value='1' >".__('Top menu')."</option>
									</select>";						   			
									}
							Html::closeForm();	
			echo "</td>";			
			echo "</tr>";				
			
			if(isset($_POST['layout']))  {	      	
							
								$update = $_POST['layout'];												
								
								$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('layout', '".$update."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$update."' ";																
								$result = $DB->query($query);																																					
					}					
						
			echo "</table>";
			//end table configs
												 
			 			 echo '<div id="skins" class="form_skin" style="">';  
						 echo "<table border='0' style='margin-left: -15%; margin-right: auto; margin-bottom: 5px; margin-top:20px;'>									
								<tr>
									<td>-- ".__('Theme','dashboard').":&nbsp;</td>
			  				   </tr>
								<tr style='text-align:center;'>
									<td><div id='default-t' style='cursor:pointer;' ><img src='./img/default-t.png' alt='default'/></div></td>
									<td>&nbsp;&nbsp;</td>	
									<td><div id='material-t' style='cursor:pointer;' ><img src='./img/material-t.png' alt='material'/></div></td>
									<td>&nbsp;&nbsp;</td>				
									<td><div id='glpi-t' style='cursor:pointer;' ><img src='./img/glpi-t.png' alt='glpi'/></div></td>
									<td>&nbsp;&nbsp;</td>						
									<td><div id='graphite-t' style='cursor:pointer;' ><img src='./img/graphite-t.png' alt='graphite'/></div></td>
									<td>&nbsp;&nbsp;</td>						
									<td><div id='nature-t' style='cursor:pointer;' ><img src='./img/nature-t.png' alt='nature'/></div></td>	
									<td>&nbsp;&nbsp;</td>						
									<td><div id='trans-t' style='cursor:pointer;' ><img src='./img/trans-t.png' alt='trans'/></div></td>
								</tr>
								<tr><td height='10px'></td></tr>
								<tr style='text-align:center;'>				
									<td><button class='btn btn-primary btn-sm' type='submit' id='skin-default' name='glpi_skin' value=\"Default\" onclick=\"changeTheme('default')\"> Default </button></td>
									<td>&nbsp;&nbsp;</td>		
									<td><button class='btn btn-primary btn-sm' type='button' id='skin-material' name='glpi_skin' value=\"Material\" onclick=\"changeTheme('material')\"> Material </button></td>
									<td>&nbsp;&nbsp;</td>				
									<td><button class='btn btn-primary btn-sm' type='button' id='skin-glpi' name='glpi_skin' value=\"GLPI\" onclick=\"changeTheme('glpi')\">GLPI</button></td>
									<td>&nbsp;&nbsp;</td>										
									<td><button class='btn btn-primary btn-sm' type='button' name='glpi_skin' value=\"Graphite\" id='skin-graphite' onclick=\"changeTheme('graphite')\">Graphite</button></td>
									<td>&nbsp;&nbsp;</td>
									<td><button class='btn btn-primary btn-sm' type='button' name='glpi_skin' value=\"Nature\" id='skin-nature' onclick=\"changeTheme('nature')\">Nature</button></td>
									<td>&nbsp;&nbsp;</td>
									<td><button class='btn btn-primary btn-sm' type='button' name='glpi_skin' value=\"Transparent\" id='skin-trans' onclick=\"changeTheme('trans')\">Transparent</button></td>					
								</tr>
								
								<tr>
									<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
									<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td style='vertical-align:bottom; height:35px;'>Background:</td>
								</tr>
									
								<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
									<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
									<td>
										<div id='theme-bg' class='theme-bg'>
											<div id='button-bg' onclick='location.href=\"config.php?back=back.jpg\"'></div>
											<div id='button-bg1' onclick='location.href=\"config.php?back=bg1.jpg\"'></div>
											<div id='button-bg2' onclick='location.href=\"config.php?back=bg2.jpg\"'></div>
											<div id='button-bg3' onclick='location.href=\"config.php?back=bg3.jpg\"'></div>
											<div id='button-bg4' onclick='location.href=\"config.php?back=bg4.jpg\"'></div>								
											<div id='button-bg5' onclick='location.href=\"config.php?back=bg5.jpg\"'></div>																																															
											<div id='button-bg6' onclick='location.href=\"config.php?back=bg6.jpg\"'></div>
											<div id='button-bg7' onclick='location.href=\"config.php?back=bg7.jpg\"'></div>
											<div id='button-bg8' onclick='location.href=\"config.php?back=bg8.jpg\"'></div>
											<div id='button-bg9' onclick='location.href=\"config.php?back=bg9.jpg\"'></div>
											<div id='button-bg10' onclick='location.href=\"config.php?back=bg10.jpg\"'></div>
											<div id='button-bg11' onclick='location.href=\"config.php?back=bg11.jpg\"'></div>
										</div>
									</td>
								</tr>
								</table>	
								</div>				
			
								<div id='skins' class='form1'>
									<table border='0' width=420px>	
										<tr style='text-align:center;'></tr>								
									</table>				
								</div> ";
							
						 echo '<div id="gcolors"  style="left: -14%; ">';  
						 echo "<table border='0' style='width: 460px; margin-left: auto; margin-right: auto; margin-bottom: 20px; margin-top:20px;'>									
								<tr>
									<td>-- ".__('Charts Theme','dashboard').":&nbsp;</td>
			  				   </tr>
								<tr style='text-align:center;'>
									<td><div id='defaultc-t' style='cursor:pointer;' ><img src='./img/defaultc-t.png' alt='default'/></div></td>
									<td>&nbsp;&nbsp;</td>					
									<td><div id='dark-t' style='cursor:pointer;' ><img src='./img/dark-t.png' alt='dark'/></div></td>
									<td>&nbsp;&nbsp;</td>							
									<td><div id='sand-t' style='cursor:pointer;' ><img src='./img/sand-t.png' alt='sand'/></div></td>
									<td>&nbsp;&nbsp;</td>						
									<td><div id='clean-t' style='cursor:pointer;' ><img src='./img/clean-t.png' alt='clean'/></div></td>
								</tr>
								<tr><td height='10px'></td></tr>
								<tr style='text-align:center;'>				
									<td><button class='btn btn-primary btn-sm' type='button' id='default' name='gcolor' value=\"Default\" onclick=\"chart('grid-light.js')\"> Default </button></td>
									<td>&nbsp;&nbsp;</td>						
									<td><button class='btn btn-primary btn-sm' type='button' id='dark' name='gcolor' value=\"Dark Unica\" onclick=\"chart('dark-unica.js')\">Dark Unica</button></td>
									<td>&nbsp;&nbsp;</td>
									<td><button class='btn btn-primary btn-sm' type='button' name='gcolor' value=\"Sand Signika\" id='sand' onclick=\"chart('sand-signika.js')\">Sand Signika</button></td>
									<td>&nbsp;&nbsp;</td>
									<td><button class='btn btn-primary btn-sm' type='button' name='gcolor' value=\"Clean\" id='clean' onclick=\"chart('grid_light.js')\">Clean</button></td>
								</tr>								
							
							</table>	";			
					//echo "</div>";				
							
					echo "</div> "; 	
																														  
			?>
					<style type="text/css">
						#default-s { display:none; }
						#material-s { display:none; }
						#glpi-s{ display:none; }
						#graphite-s { display:none; }
						#nature-s { display:none; }
						#trans-s { display:none; }
						
						#defaultc-s { display:none; } 
						#dark-s { display:none; }
						#sand-s { display:none; }
						#clean-s { display:none; } 
					</style>  		
					  		
					<div id="default-s" style="position:absolute; margin-left:18%; margin-top: -45%; cursor:pointer;">
						<img src="./img/default-s.png" alt="default" />
					</div>
					<div id="material-s" style="position:absolute; margin-left:18%; margin-top: -45%; cursor:pointer;">
						<img src="./img/material-s.png" alt="default" />
					</div>
					<div id="glpi-s" style="position:absolute; margin-left:18%; margin-top: -45%; cursor:pointer;">
						<img src="./img/glpi-s.png" alt="glpi" />
					</div>   		
					<div id="graphite-s" style="position:absolute; margin-left:18%; margin-top: -45%; cursor:pointer;">
						<img src="./img/graphite-s.png" alt="graphite" />
					</div>
					<div id="nature-s" style="position:absolute; margin-left:18%; margin-top: -45%; cursor:pointer;">
						<img src="./img/nature-s.png" alt="nature" />
					</div>  
					<div id="trans-s" style="position:absolute; margin-left:18%; margin-top: -45%; cursor:pointer;">
						<img src="./img/trans-s.png" alt="nature" />
					</div>	
					
					
					<div id="defaultc-s" style="position:absolute; margin-left:18%; margin-top: -40%; cursor:pointer;">
						<img src="./img/defaultc-s.png" alt="default" />
					</div>
					<div id="dark-s" style="position:absolute; margin-left:18%; margin-top: -40%; cursor:pointer;">
						<img src="./img/dark-s.png" alt="dark" />
					</div>   		
					<div id="sand-s" style="position:absolute; margin-left:18%; margin-top: -40%; cursor:pointer;">
						<img src="./img/sand-s.png" alt="sand" />
					</div>
					<div id="clean-s" style="position:absolute; margin-left:18%; margin-top: -40%; cursor:pointer;">
						<img src="./img/clean-s.png" alt="clean" />
					</div>  	
			  		
			      </div>
			
			<!-- charts -->
			</div>	
	</div>
</div>

	
<script type="text/javascript" >
$(document).ready(function() { $("#num").select2(); });

$(document).ready(function() { $("#sel_ent1").select2(); });

$(document).ready(function() { $("#up").select2(); });

$(document).ready(function() { $("#layout").select2(); });

$(document).ready(function () {
	
	$('#default-t').on("click", "img", function () {
	    //alert('You Clicked Me');
	    $('#default-s').show(); 
	});	
	$('#default-s').on("click", "img", function () {    
	    $('#default-s').hide(); 
	});	
	
	$('#material-t').on("click", "img", function () {
	    $('#material-s').show(); 
	});	
	$('#material-s').on("click", "img", function () {    
	    $('#material-s').hide(); 
	});	
	
	$('#glpi-t').on("click", "img", function () {
	    $('#glpi-s').show(); 
	});	
	$('#glpi-s').on("click", "img", function () {    
	    $('#glpi-s').hide(); 
	});
	
	$('#graphite-t').on("click", "img", function () {   
	    $('#graphite-s').show(); 
	});	
	$('#graphite-s').on("click", "img", function () {    
	    $('#graphite-s').hide(); 
	});
	
	$('#nature-t').on("click", "img", function () {
	    $('#nature-s').show(); 
	});	
	$('#nature-s').on("click", "img", function () {    
	    $('#nature-s').hide(); 
	});
	
	$('#trans-t').on("click", "img", function () {
	    $('#trans-s').show(); 
	});	
	$('#trans-s').on("click", "img", function () {    
	    $('#trans-s').hide(); 
	});	
	
	
	
		$('#defaultc-t').on("click", "img", function () {
	    //alert('You Clicked Me');
	    $('#defaultc-s').show(); 
	});	
	$('#defaultc-s').on("click", "img", function () {    
	    $('#defaultc-s').hide(); 
	});
	
		$('#dark-t').on("click", "img", function () {
	    //alert('You Clicked Me');
	    $('#dark-s').show(); 
	});	
	$('#dark-s').on("click", "img", function () {    
	    $('#dark-s').hide(); 
	});
	
	$('#sand-t').on("click", "img", function () {
	    //alert('You Clicked Me');
	    $('#sand-s').show(); 
	});	
	$('#sand-s').on("click", "img", function () {    
	    $('#sand-s').hide(); 
	});
	
		$('#clean-t').on("click", "img", function () {
	    //alert('You Clicked Me');
	    $('#clean-s').show(); 
	});	
	$('#clean-s').on("click", "img", function () {    
	    $('#clean-s').hide(); 
	});
    
});

</script>
<?php 
//print_r($_SESSION['glpiactiveprofile']['entities']); 
//print_r(Profile_User::getUserEntities($_SESSION['glpiID'], true));
?>
<!--</div>-->
</body>
</html>