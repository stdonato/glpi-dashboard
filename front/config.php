<?php


include ("../../../inc/includes.php");
include ("../../../inc/config.php");

Session::checkLoginUser();
Session::checkRight("profile", READ);
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
				sortAttribute:		"value"

			});

			// Example of adding a regular item after picklist creation.
			// Note there is no "element" property as that's for rich content only.
			$("#advanced").pickList("insert",
			{
				value: 7,
				label: "Afterwards #2",
				selected: true
			});
		});
		
	</script>

	<link type="text/css" href="css/jquery-picklist.css" rel="stylesheet" />

</head>

<body style="background-color: #e5e5e5;">

<div id='content'>
	<div id='container-fluid' style="margin: 0px 5% 0px 7%;"> 	
	
		<div id="head-tic" class="fluid" >	
			<a href="./index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:5px; margin-top:15px;"></i><span></span></a>
			<div id="titulo" class="tit-config" style="margin-bottom: 25px;"> <a href="config.php" ><?php echo __('Setup')." ".__('Dashboard','dashboard'); ?> </a></div> 
		</div>
				                                                           
			<div id="charts" class="fluid chart" style="background-color:#fff;">				
			
					<div id="tabela" class="fluid" >		
					<?php
					
					// selected entity for index
					$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
					$result_e = $DB->query($sql_e);					
					$prev_ent = $DB->fetch_assoc($result_e);	
					
			      echo '<div id="datas-tec2" class="col-md-12 fluid" style="background-color:#fff; margin-top:20px;">';
					
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

					$entities = $_SESSION['glpiactiveentities'];
					$ents = implode(",",$entities);
					
					// lista de entidades
					$sql_ent = "
					SELECT id, name, completename AS cname
					FROM `glpi_entities`
					WHERE id IN (".$ents.")
					ORDER BY `name` ASC ";
					
					$result_ent = $DB->query($sql_ent);
					
					$arr_ent = array();
					$arr_ent[0] = "-- ". __('Select a entity', 'dashboard') . " --" ;
				   $arr_ent[-1] = __('All', 'dashboard') ;		
					
					while ($row_result = $DB->fetch_assoc($result_ent))
					 {
					    $v_row_result = $row_result['id'];
					    $arr_ent[$v_row_result] = $row_result['cname'] ;
					 }
					 
					//reload page	
					if(isset($_REQUEST['up'])) {						
						echo "<meta HTTP-EQUIV='refresh' CONTENT='0.1;URL=config.php'>";						
						}	
						
					//reload page	
					if(isset($_REQUEST['layout'])) {						
						echo "<meta HTTP-EQUIV='refresh' CONTENT='0.1;URL=config.php'>";						
						}	
					
					//reload page	
					if(isset($_REQUEST['info'])) {						
						echo "<meta HTTP-EQUIV='refresh' CONTENT='0.1;URL=config.php'>";						
						}		
						
					//reload page	
					if(isset($_REQUEST['due']) || isset($_REQUEST['loc']) || isset($_REQUEST['pop']) ) {						
						echo "<meta HTTP-EQUIV='refresh' CONTENT='0.1;URL=config.php'>";						
						}				
						
					//reload page	
					if(isset($_REQUEST['met'])) {						
						echo "<meta HTTP-EQUIV='refresh' CONTENT='0.1;URL=config.php'>";						
						}													
																		
															
		echo '<div id="datas-tecx" class="col-md-12 fluid">'; 																															 
		echo "<table id='main' class='col-md-12 table-config' border='0' style='width:700px; margin:auto; float:none;'>\n";
			echo "<tr>\n";
				echo "<td>\n";			                                		 
			 		echo '<form id="form2" name="form2" method="post" action="config.php?conf=1">';   					
					echo " -- ".__('Entity','dashboard').":&nbsp;";				
					//echo dropdown( $name, $options, $selected );	
					
					echo '<select name="sel_ent[]" id="sel_ent" multiple style="width: 600px; height: 250px;"';
					
					foreach( $arr_ent as $key=>$option )
					{
						
						if(in_array($key,$formated_arr)) { $select = 'selected'; }	
						else {$select = '';}						
						echo '<option value="'.$key.'" '.$select.'>'.$option.'</option>'."\n";
					}					
						echo "</select>"."\n";
										
					echo "<tr><td align='center'><button type='button' class='btn btn-primary' onclick='javascript:this.form.submit();' > ".__('Save')."</button></td></tr>";
					Html::closeForm(); 										
				echo "</td>\n";		
			echo "</tr>\n";

				   if(isset($_REQUEST['conf']) && $_REQUEST['conf'] == 1 ) {					         					
								
							$ents_sel = $_REQUEST['sel_ent'];	
								
							if(in_array(-1, $ents_sel)) {	

								//$entities = Profile_User::getUserEntities($_SESSION['glpiID'], true);
								$entities = $_SESSION['glpiactiveentities'];	
								$ent = implode(",",$entities);												
								
								$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('entity', '".$ent."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$ent."' ";																
								$result = $DB->query($query);	
								
								//reload page
								echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=config.php'>";	
							}
							else {
																					
								$ent = implode(',',$ents_sel);												
								
								$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('entity', '".$ent."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$ent."' ";																
								$result = $DB->query($query);	
								
								//reload page
								echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=config.php'>";																						
							}												
					}												    
			
					// years in index
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
															VALUES ('charts_colors', '".$colors."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$colors."' ";																
												$result = $DB->query($query);	
												
												$_SESSION['charts_colors'] = $colors;
												
												//reload page
												echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=config.php'>";																																													
										}		
										
									// metricas			
									if(isset($_REQUEST['met']))  {	      	
										
											$metric = $_REQUEST['metric'];																							
											$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
														  VALUES ('metric', '".$metric."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$metric."' ";																
											$result = $DB->query($query);	
											
											//reload page
											echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=config.php'>";		
																																														
										}			
			
			//status for tickets page
			$query_sta = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'status' AND users_id = ".$_SESSION['glpiID']."";
			$result_sta = $DB->query($query_sta);
			$prev_status = $DB->fetch_assoc($result_sta);
			
			//separa string com virgulas e converte em array
			$format_Arr = $prev_status;
			$formated_arr = array();
			
			foreach($format_Arr as $arr){
				$arr1 = explode(",",$arr);
				if(count($arr1)>1){
				    $formated_arr = array_merge($formated_arr,explode(',',$arr));
				}else{
				    $formated_arr[]= $arr;
				}
			}	
						
			//set selected status
			$active_stat = array();
		
			for($i=1; $i <= 4; $i++) {
				if( in_array($i,$formated_arr)) { $active_stat[$i] = 'selected'; }
				else { $active_stat[$i] = ''; }
			}
					
			echo "<tr>\n";
				echo "<td>\n";			                                		 
			 		echo '<form id="formstatus" name="formstatus" method="post" action="config.php?status=1" style="margin-left:15%;">';   					
					echo " -- ".__('Status in Tickets page','dashboard').": ";														
					echo '<select name="sel_stat[]" id="sel_stat" multiple="multiple" style="width: 600px; height: 250px;"';					
					echo "<option value='0'>". __('All')."</option>\n";
					echo "<option value='1' ".$active_stat[1].">". _x('status', 'New')."</option>\n";
					echo "<option value='2' ".$active_stat[2].">". _x('status', 'Processing (assigned)')."</option>\n"; 
					echo "<option value='3' ".$active_stat[3].">". _x('status', 'Processing (planned)')."</option>\n";
					echo "<option value='4' ".$active_stat[4].">". __('Pending')."</option>\n";														
					echo "</select>"."\n";
										
					echo "<tr><td align='center'><button type='button' class='btn btn-primary' onclick='javascript:this.form.submit();' > ".__('Save')."</button></td></tr>";
					Html::closeForm(); 										
				echo "</td>\n";		
			echo "</tr>\n";
			

				if(isset($_REQUEST['status']) && $_REQUEST['status'] == 1 ) {	
				         																												
								$status = implode(',',$_REQUEST['sel_stat']);												
								
								$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('status', '".$status."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$status."' ";																
								$result = $DB->query($query);	
								
								//reload page
								echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=config.php'>\n";																						
											
					}										
																							 
					                               
		echo "<tr>\n";
		echo "<td>\n";					
				
		 		echo '<form id="form1" name="form1" method="post" action="config.php?conf=1">';   						
				echo "-- ".__('Period in index page','dashboard').":&nbsp; ";  
				echo "<select id='num' name='num' style='width: 130px;' onChange='reload(\"form1\")'> 
							<option value=''>".__('Select','dashboard')."</option>
							<option value='0'>".__('All')."</option>
							<option value='1'>".__('Current year','dashboard')."</option>\n";
						
						$year = date("Y");		
						for($i=2; $i <= $conta_y; $i++) {	
							echo "<option value='".$i."'>".$year." - ".($arr_years[0]-($i-1))."</option>\n";
					   }							   			
				Html::closeForm(); 

		echo "</td>\n";					 	
		echo "</tr>\n";			

		// metric period
		$query_met = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'metric' AND users_id = ".$_SESSION['glpiID']." ";																
		$result_met = $DB->query($query_met);
		
		$sel_period = $DB->result($result_met,0,'value');	
		

		if($sel_period == 0) {
			$period0 = 'selected';
			$period1 = ""; $period2 = ""; $period3 = ""; $period4 = ""; $period5 = ""; $period6 = ""; $period7 = ""; 
		}
		if($sel_period == 1) {
			$period1 = 'selected';
			$period0 = ""; $period2 = ""; $period3 = ""; $period4 = ""; $period5 = ""; $period6 = ""; $period7 = "";
		}	
		if($sel_period == 2) {
			$period2 = 'selected';
			$period0 = ""; $period1 = ""; $period3 = ""; $period4 = ""; $period5 = ""; $period6 = ""; $period7 = "";
		}
		if($sel_period == 3) {
			$period3 = 'selected';
			$period0 = ""; $period1 = ""; $period2 = ""; $period4 = ""; $period5 = ""; $period6 = ""; $period7 = "";
		}
		if($sel_period == 4) {
			$period4 = 'selected';
			$period0 = ""; $period1 = ""; $period2 = ""; $period3 = ""; $period5 = ""; $period6 = ""; $period7 = "";
		}
		if($sel_period == 5) {
			$period5 = 'selected';
			$period0 = ""; $period1 = ""; $period2 = ""; $period3 = ""; $period4 = ""; $period6 = ""; $period7 = "";
		}
		if($sel_period == 6) {
			$period6 = 'selected';
			$period0 = ""; $period1 = ""; $period2 = ""; $period3 = "";  $period4 = ""; $period5 = ""; $period7 = "";
		}
		if($sel_period == 7) {
			$period7 = 'selected';
			$period0 = ""; $period1 = ""; $period2 = ""; $period3 = "";  $period4 = ""; $period5 = ""; $period6 = "";
		}
		if($sel_period == '') {
			$period0 = 'selected';
			$period1 = ""; $period2 = ""; $period3 = ""; $period4 = ""; $period5 = ""; $period6 = ""; $period7 = "";
		}

					
		echo "<tr>\n";
		echo "<td>\n";									
				echo '<form id="form_met" name="form_met" class="form_met" method="post" action="config.php?met=1">';   						
				echo "-- ".__('Period for Metrics','dashboard').":&nbsp; ";
				echo "<select id='metric' name='metric' style='width: 160px;' onChange='reload(\"form_met\")'>\n";												
								echo "					
									<option value='0' ".$period0.">".__('Total')."</option>
									<option value='1' ".$period1.">".__('Current year','dashboard')."</option>
									<option value='2' ".$period2.">".__('Current month','dashboard')."</option>
									<option value='3' ".$period3.">".__('Last week')."</option>
									<option value='4' ".$period4.">".__('Last 15 days','dashboard')."</option>
									<option value='5' ".$period5.">".__('Last 30 days','dashboard')."</option>
									<option value='6' ".$period6.">".__('Last 90 days','dashboard')."</option>
									<option value='7' ".$period7.">".__('Last 180 days','dashboard')."</option>
								</select>\n";						   			

				Html::closeForm();	
			
			echo "</td>\n";
			echo "</tr>\n";				

		
		//get update option
		$query_up = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'update'";																
		$result_up = $DB->query($query_up);
		
		$up_option = $DB->result($result_up,0,'value');	
					
		echo "<tr>\n";
		echo "<td>\n";									
				echo '<form id="form3" name="form3" class="form3" method="post" action="config.php?up=1">';   						
				echo "-- ".__('Check for new updates').":&nbsp; 
						<select id='up' name='up' style='width: 130px;' onChange='reload(\"form3\")'> ";
							if($up_option == 1) {							
								echo "					
									<option value='0'>".__('No')."</option>
									<option value='1' selected>".__('Yes')."</option>
								</select>\n";						   			
								}
							else 	{							
								echo "					
									<option value='0' selected>".__('No')."</option>
									<option value='1'>".__('Yes')."</option>
								</select>\n";						   			
								}
				Html::closeForm();	
			
			echo "</td>\n";
			echo "</tr>\n";				
			
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
			
			echo "<tr>\n";
			echo "<td>\n";								
					echo '<form id="form4" name="form4" class="form4" method="post" action="config.php?layout=1">';   						
					echo "-- ".__('Layout','dashboard').":&nbsp; 
							<select id='layout' name='layout' style='width: 170px;' onChange='reload(\"form4\")'> ";
								if($layout == 1) {							
									echo "					
										<option value='0'>".__('Left side menu','dashboard')."</option>
										<option value='1' selected>".__('Top menu','dashboard')."</option>
									</select>\n";						   			
									}
								else 	{							
									echo "					
										<option value='0' selected>".__('Left side menu','dashboard')."</option>
										<option value='1' >".__('Top menu','dashboard')."</option>
									</select>\n";						   			
									}
							Html::closeForm();	
			echo "</td>\n";			
			echo "</tr>\n";				
			
			if(isset($_POST['layout']))  {	      	
							
								$update = $_POST['layout'];																				
								$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('layout', '".$update."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$update."' ";																
								$result = $DB->query($query);																																					
					}					

					
			// server info
			$query_info = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'info' AND users_id = ".$_SESSION['glpiID']." ";																
			$result_info = $DB->query($query_info);			
			$info = $DB->result($result_info,0,'value');	
			
			echo "<tr>\n";
			echo "<td>\n";								
					echo '<form id="form5" name="form5" class="form5" method="post" action="config.php?info=1">';   						
					echo "-- ".__('Show Server info','dashboard').":&nbsp; 
							<select id='info' name='info' style='width: 130px;' onChange='reload(\"form5\")'> ";
								if($info == 1) {							
									echo "					
										<option value='0'>".__('No')."</option>
										<option value='1' selected>".__('Yes')."</option>
									</select>\n";						   			
									}
								else 	{							
									echo "					
										<option value='0' selected>".__('No')."</option>
										<option value='1'>".__('Yes')."</option>
									</select>\n";						   			
									}
					Html::closeForm();	
			echo "</td>\n";			
			echo "</tr>\n";				
			
			if(isset($_POST['info']))  {	      	
							
								$info = $_POST['info'];																				
								$query = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('info', '".$info."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$info."' ";																
								$result = $DB->query($query);																																					
					}	
					
					
			// Tickets page settings
			$query_due = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'duedate' AND users_id = ".$_SESSION['glpiID']." ";																
			$result_due = $DB->query($query_due);			
			$due = $DB->result($result_due,0,'value');	

			$query_ent = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity_cham' AND users_id = ".$_SESSION['glpiID']." ";																
			$result_ent = $DB->query($query_ent);			
			$ent_cham = $DB->result($result_ent,0,'value');
			
			$query_loc = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'location' AND users_id = ".$_SESSION['glpiID']." ";																
			$result_loc = $DB->query($query_loc);			
			$loc = $DB->result($result_loc,0,'value');
			
			$query_pop = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'popup' AND users_id = ".$_SESSION['glpiID']." ";																
			$result_pop = $DB->query($query_pop);			
			$pop = $DB->result($result_pop,0,'value');
			
			$query_tit = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'title' AND users_id = ".$_SESSION['glpiID']." ";																
			$result_tit = $DB->query($query_tit);			
			$tit = $DB->result($result_tit,0,'value');				
						
			
			echo "<tr>\n";
			echo "<td>\n";								
					echo '<form id="form6" name="form6" class="form6" method="post" action="config.php?due=1&loc=1&entity=1">';
					
					echo "-- ".__('Tickets Page','dashboard').":&nbsp; <br><p>";
					
					echo "<table><tr><td>";   						
						echo _sx('button', 'Show')." ".__('Entity').":&nbsp;&nbsp; 
							<select id='entity' name='entity' style='width: 130px;' onChange='reload(\"form6\")'> ";
								if($ent_cham == 1) {							
									echo "					
										<option value='0'>".__('No')."</option>
										<option value='1' selected>".__('Yes')."</option>
									</select>\n";						   			
									}
								else 	{							
									echo "					
										<option value='0' selected>".__('No')."</option>
										<option value='1'>".__('Yes')."</option>
									</select>\n";						   			
									}
					echo "</td><td>\n";			
														
						echo _sx('button', 'Show')." ".__('Location').":&nbsp;&nbsp; 
							<select id='loc' name='loc' style='width: 130px;' onChange='reload(\"form6\")'> ";			
								if($loc == 1) {							
									echo "					
										<option value='0'>".__('No')."</option>
										<option value='1' selected>".__('Yes')."</option>
									</select>\n";						   			
									}
								else 	{							
									echo "					
										<option value='0' selected>".__('No')."</option>
										<option value='1'>".__('Yes')."</option>
									</select>\n";						   			
									}				
					
					echo "</td><td>\n";
						echo _sx('button', 'Show')." ".__('Due Date', 'dashboard').":&nbsp; 
							<select id='due' name='due' style='width: 130px;' onChange='reload(\"form6\")'>\n ";
								if($due == 1) {							
									echo "					
										<option value='0'>".__('No')."</option>
										<option value='1' selected>".__('Yes')."</option>
									</select><br><p>";						   			
									}
								else 	{							
									echo "					
										<option value='0' selected>".__('No')."</option>
										<option value='1'>".__('Yes')."</option>
									</select><br></p>";						   			
									}				
		
					echo "</td><td>\n";				
					echo _sx('button', 'Show')." ".__('Popup','dashboard').":&nbsp;&nbsp; 
							<select id='pop' name='pop' style='width: 130px;' onChange='reload(\"form6\")'>\n";
								if($pop == 1) {							
									echo "					
										<option value='0'>".__('No')."</option>
										<option value='1' selected>".__('Yes')."</option>
									</select>\n";						   			
									}
								else 	{							
									echo "					
										<option value='0' selected>".__('No')."</option>
										<option value='1'>".__('Yes')."</option>
									</select>\n";						   			
									}		
									
					echo "</td><td>\n";				
					echo _sx('button', 'Show')." ".__('Title').":&nbsp;&nbsp; 
							<select id='tit' name='tit' style='width: 130px;' onChange='reload(\"form6\")'>\n";
								if($tit == 1) {							
									echo "					
										<option value='0'>".__('No')."</option>
										<option value='1' selected>".__('Yes')."</option>
									</select>\n";						   			
									}
								else 	{							
									echo "					
										<option value='0' selected>".__('No')."</option>
										<option value='1'>".__('Yes')."</option>
									</select>\n";						   			
									}

																																					
					Html::closeForm();
					echo "</td></tr></table>\n";	
			echo "</td>\n";			
			echo "</tr>\n";				
			
			if(isset($_POST['due']))  {	      	
							
								//Update duedate value
								$up_due = $_POST['due'];																				
								$query_due = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('duedate', '".$up_due."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$up_due."' ";																
								$result_due = $DB->query($query_due);																																																			
					}	

			if(isset($_POST['entity']))  {	      	
														
								//Update entity value
								$up_ent = $_POST['entity'];																				
								$query_ent = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('entity_cham', '".$up_ent."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$up_ent."' ";																
								$result_ent = $DB->query($query_ent);																																																				
					}
					
			if(isset($_POST['loc']))  {	      	
														
								//Update location value
								$up_loc = $_POST['loc'];																				
								$query_loc = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('location', '".$up_loc."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$up_loc."' ";																
								$result_loc = $DB->query($query_loc);																																																				
					}	
					
			if(isset($_POST['pop']))  {	      	
														
								//Update popup value
								$up_pop = $_POST['pop'];																				
								$query_pop = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('popup', '".$up_pop."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$up_pop."' ";																
								$result_pop = $DB->query($query_pop);																																																				
					}	
					
			if(isset($_POST['tit']))  {	      	
														
								//Update title value
								$up_tit = $_POST['tit'];																				
								$query_tit = "INSERT INTO glpi_plugin_dashboard_config (name, value, users_id)
											  VALUES ('title', '".$up_tit."', '".$_SESSION['glpiID']."') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), value = '".$up_tit."' ";																
								$result_tit = $DB->query($query_tit);																																																				
					}										
			
			//index widgets
			echo "<tr>\n";
			echo "<td>\n";		
			echo "-- ". __('Widgets settings','dashboard').":  &nbsp; &nbsp;  <button class='btn btn-sm btn-primary' onclick='window.localStorage.clear();'>". __('Clear')." ". __('Settings')."</button>";
			echo "</td>\n";
			echo "</tr>\n";	
			
			// google maps API key
			echo "<tr>\n";
			echo "<td>\n";		
			echo "-- ". __('Google maps API key','dashboard').":  &nbsp; &nbsp;  <button class='btn btn-sm btn-primary' onclick='window.open(\"./map/map_key.php\");'>". __('Edit')."</button>";
			echo "</td>\n";
			echo "</tr>\n";	
					
			/*		
			//file upload				
			echo "<tr>\n";
			echo "<td>\n";
			echo '
			<form action="upload.php" method="post" enctype="multipart/form-data">
   		 Select sound to upload:
    		<input type="file" name="fileToUpload" id="fileToUpload">
    		<button class="btn btn-primary btn-sm" type="submit">Upload File</button>';
    		
    		//<input type="submit" value="Upload File" name="submit"> ';
    		
			Html::closeForm();	
			
			
			echo "</td>\n";
			echo "</tr>\n";				
			*/						
			echo "</table>";
			//end table configs
												 
			 			 echo '<div id="temas"  class="col-md-12">'; 
			 			  
						 echo "<table border='0' style='margin-bottom: 5px; margin-top:20px; margin-left:auto; margin-right: auto; width:80%;'>									
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
														
								</tr>																
								</table>
																
								<table border='0' style='width: 40%; margin:auto; margin-bottom: 5px; margin-top:20px;'>									
								<tr style='text-align:center;'>									
									<td><div id='darkt-t' style='cursor:pointer;' ><img src='./img/darkt-t.png' alt='dark'/></div></td>	
									<td>&nbsp;&nbsp;</td>												
									<td><div id='trans-t' style='cursor:pointer;' ><img src='./img/trans-t.png' alt='trans'/></div></td>
								</tr>
								<tr><td height='10px'></td><td height='10px'></td></tr>
								
								<tr style='text-align:center;'>																						
									<td><button class='btn btn-primary btn-sm' type='button' name='glpi_skin' value=\"Dark\" id='skin-dark' onclick=\"changeTheme('dark')\">Dark</button></td>
									<td>&nbsp;&nbsp;</td>
									<td><button class='btn btn-primary btn-sm' type='button' name='glpi_skin' value=\"Transparent\" id='skin-trans' onclick=\"changeTheme('trans')\">Transparent</button></td>					
								</tr>
								
								<tr>
									<td>&nbsp;</td><td>&nbsp;</td><td style='vertical-align:bottom; height:35px;'>". __('Background','dashboard').":</td>
								</tr>
									
								<tr><td>&nbsp;</td><td>&nbsp;</td>
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
														
								<div id='skins' class='form1'>
									<table border='0' width=420px>	
										<tr style='text-align:center;'></tr>								
									</table>				
								</div> 
								</div>\n";
							
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
									<td><button class='btn btn-primary btn-sm' type='button' id='default' name='gcolor' value=\"Default\" onclick=\"chart('default.js')\"> Default </button></td>
									<td>&nbsp;&nbsp;</td>						
									<td><button class='btn btn-primary btn-sm' type='button' id='dark' name='gcolor' value=\"Dark Unica\" onclick=\"chart('dark-unica.js')\">Dark Unica</button></td>
									<td>&nbsp;&nbsp;</td>
									<td><button class='btn btn-primary btn-sm' type='button' name='gcolor' value=\"Sand Signika\" id='sand' onclick=\"chart('sand-signika.js')\">Sand Signika</button></td>
									<td>&nbsp;&nbsp;</td>
									<td><button class='btn btn-primary btn-sm' type='button' name='gcolor' value=\"Clean\" id='clean' onclick=\"chart('grid-light.js')\">Grid-light</button></td>
								</tr>															
							</table>\n";									
							
					echo "</div>\n"; 	
																														  
			?>
					<style type="text/css">
						#default-s { display:none; }
						#material-s { display:none; }
						#glpi-s{ display:none; }
						#graphite-s { display:none; }
						#nature-s { display:none; }
						#darkt-s { display:none; }
						#trans-s { display:none; }
						
						#defaultc-s { display:none; } 
						#dark-s { display:none; }
						#sand-s { display:none; }
						#clean-s { display:none; } 
					</style>  		
					  		
					<div id="default-s" style="position:absolute; margin-left:13%; margin-top: -60%; cursor:pointer;">
						<img src="./img/default-s.png" alt="default" />
					</div>
					<div id="material-s" style="position:absolute; margin-left:13%; margin-top: -60%; cursor:pointer;">
						<img src="./img/material-s.png" alt="default" />
					</div>
					<div id="glpi-s" style="position:absolute; margin-left:13%; margin-top: -60%; cursor:pointer;">
						<img src="./img/glpi-s.png" alt="glpi" />
					</div>   		
					<div id="graphite-s" style="position:absolute; margin-left:13%; margin-top: -60%; cursor:pointer;">
						<img src="./img/graphite-s.png" alt="graphite" />
					</div>
					<div id="nature-s" style="position:absolute; margin-left:13%; margin-top: -60%; cursor:pointer;">
						<img src="./img/nature-s.png" alt="nature" />
					</div> 
					<div id="darkt-s" style="position:absolute; margin-left:13%; margin-top: -60%; cursor:pointer;">
						<img src="./img/darkt-s.png" alt="dark" />
					</div>  
					<div id="trans-s" style="position:absolute; margin-left:13%; margin-top: -60%; cursor:pointer;">
						<img src="./img/trans-s.png" alt="transparent" />
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
$(document).ready(function() { $("#sel_stat").select2(); placeholder: "Selecione os Status"; });
$(document).ready(function() { $("#metric").select2(); });
$(document).ready(function() { $("#up").select2(); });
$(document).ready(function() { $("#layout").select2(); });
$(document).ready(function() { $("#info").select2(); });
$(document).ready(function() { $("#due").select2(); });
$(document).ready(function() { $("#entity").select2(); });
$(document).ready(function() { $("#loc").select2(); });
$(document).ready(function() { $("#pop").select2(); });
$(document).ready(function() { $("#tit").select2(); });

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
	
	$('#darkt-t').on("click", "img", function () {
	    $('#darkt-s').show(); 
	});	
	$('#darkt-s').on("click", "img", function () {    
	    $('#darkt-s').hide(); 
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
		$('#dark-s').show(); 
	});
		
	$('#dark-s').on("click", "img", function () {    
	    $('#dark-s').hide(); 
	});
	
	$('#sand-t').on("click", "img", function () {	    
	    $('#sand-s').show(); 
	});	
	$('#sand-s').on("click", "img", function () {    
	    $('#sand-s').hide(); 
	});
	
	$('#clean-t').on("click", "img", function () {	    
	    $('#clean-s').show(); 
	});	
	$('#clean-s').on("click", "img", function () {    
	    $('#clean-s').hide(); 
	});
    
});

</script>

<!--</div>-->
</body>
</html>
