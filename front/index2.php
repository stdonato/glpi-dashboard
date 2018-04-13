<?php

include ("../../../inc/includes.php");
include ("../../../inc/config.php");

global $DB;

Session::checkLoginUser();

# entity in index
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '' OR strstr($sel_ent,",")) { 
	$ent_name = __('Tickets Statistics','dashboard');
}

else {
	$query = "SELECT name FROM glpi_entities WHERE id IN (".$sel_ent.")";
	$result = $DB->query($query);
	$ent_name1 = $DB->result($result,0,'name');
	$ent_name = __('Tickets Statistics','dashboard')." :  ". $ent_name1 ;
}

# years in index
$sql_y = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'num_years' AND users_id = ".$_SESSION['glpiID']."";
$result_y = $DB->query($sql_y);
$num_years = $DB->result($result_y,0,'value');

if($num_years == '') {
	$num_years = 0;
}

# color theme
$sql_theme = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'theme' AND users_id = ".$_SESSION['glpiID']."";
$result_theme = $DB->query($sql_theme);
$theme = $DB->result($result_theme,0,'value');
$style = $theme;

if($theme == '' || substr($theme,0,5) == 'skin-' ) {
	$theme = 'material.css';
	$style = 'material.css';
}
$_SESSION['theme'] = $theme;
$_SESSION['style'] = $theme;


# background
$sql_back = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'back' AND users_id = ".$_SESSION['glpiID']."";
$result_back = $DB->query($sql_back);
$back = $DB->result($result_back,0,'value');

if($back == '') {
	$back = 'bg1.jpg';	
}
$_SESSION['back'] = $back;


# charts colors 
$sql_colors = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'charts_colors' AND users_id = ".$_SESSION['glpiID']."";
$result_colors = $DB->query($sql_colors);
$colors = $DB->result($result_colors,0,'value');

if($colors == '') {
	$colors = 'default.js';	
}

$_SESSION['charts_colors'] = $colors;

    switch (date("m")) {
	    case "01": $mes = __('January','dashboard'); break;
	    case "02": $mes = __('February','dashboard'); break;
	    case "03": $mes = __('March','dashboard'); break;
	    case "04": $mes = __('April','dashboard'); break;
	    case "05": $mes = __('May','dashboard'); break;
	    case "06": $mes = __('June','dashboard'); break;
	    case "07": $mes = __('July','dashboard'); break;
	    case "08": $mes = __('August','dashboard'); break;
	    case "09": $mes = __('September','dashboard'); break;
	    case "10": $mes = __('October','dashboard'); break;
	    case "11": $mes = __('November','dashboard'); break;
	    case "12": $mes = __('December','dashboard'); break;
    }

    switch (date("w")) {
	    case "0": $dia = __('Sunday','dashboard'); break;    
	    case "1": $dia = __('Monday','dashboard'); break;
	    case "2": $dia = __('Tuesday','dashboard'); break;
	    case "3": $dia = __('Wednesday','dashboard'); break;
	    case "4": $dia = __('Thursday','dashboard'); break;
	    case "5": $dia = __('Friday','dashboard'); break;
	    case "6": $dia = __('Saturday','dashboard'); break;  
    }    

//user image and name
$sql_photo = "SELECT picture 
				FROM glpi_users
				WHERE id = ".$_SESSION["glpiID"]." ";

$res_photo = $DB->query($sql_photo);
$pic = $DB->result($res_photo,0,'picture');
$photo_url = User::getURLForPicture($pic);

//redirect tech profile
if(Session::haveRight("profile", READ)) {		
	$redir = '';
}
else {		
	$redir = '<meta http-equiv="refresh" content="0; url=graphs/graf_tech.php?con=1" />'; 
}   
?>

<!DOCTYPE html>
<html>
<head>
    <title>GLPI - Dashboard - Home</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <meta http-equiv="Pragma" content="public">
    <?php echo $redir; ?>
    
    <link rel="icon" href="img/dash.ico" type="image/x-icon" />
	 <link rel="shortcut icon" href="img/dash.ico" type="image/x-icon" />    
    <link href="css/bootstrap.css" rel="stylesheet"> 

    <!-- Styles -->   
    <!-- Color theme -->       		   
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    
     <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/index.css" type="text/css" media="screen" />    

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/style-dash.css" rel="stylesheet" type="text/css" />
    <link href="css/dashboard.css" rel="stylesheet" type="text/css" /> 
    
    <!-- odometer -->
	<link href="css/odometer.css" rel="stylesheet">
	<script src="js/odometer.js"></script>
    
   <!-- <link href="less/style.less" rel="stylesheet"  title="lessCss" id="lessCss"> -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
     <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
     <![endif]-->         
     <!-- <link href="fonts/fonts.css" rel="stylesheet" type="text/css" /> -->
     
  	 <?php 
 	 	echo '<link rel="stylesheet" type="text/css" href="./css/skin-'.$theme.'">'; 
	 	echo '<link rel="stylesheet" type="text/css" href="./css/style-'.$style.'">';
 	 ?> 
 	 
 	 <script src="js/jquery.js"></script> 
 	 
 	 <script type="text/javascript">
		function scrollWin()
		{
			$('html, body').animate({ scrollTop: 0 }, 'slow');
		}
		
		$(function($) {
			var options = {
				timeNotation: '24h',
				am_pm: true,
				fontSize: '14px'
			}
			$('#clock').jclock(options);
		});	
	
 	 </script>

</head>

	<?php
	if($theme == 'trans.css') {		
   	echo "<body style=\"background: url('./img/".$back."') no-repeat top center fixed; \">";
   }
   else {
   	echo "<body style='background-color:#e5e5e5;'>";
   }	 
   ?>

            <div class="site-holder">
                <!-- .navbar -->
                <nav class="navbar navbar-default nav-delighted navbar-fixed-top shad2" role="navigation" >
                    <a href="#" class="toggle-left-sidebar">
                        <i class="fa fa-th-list"></i>
                    </a>

                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header" style="color:#fff;" >
                        <a class="navbar-brand" href="<?php echo $CFG_GLPI['url_base'].'/front/ticket.php';?>" target="_blank">
                            <span>GLPI</span></a>
                    </div>
					<!-- NAVBAR LEFT  -->					
					<ul id="navbar-left" class="nav navbar-nav pull-left hidden-xs">
					    <li class="">
					        <a href="./index.php" style="margin-top:6px;">           
					            <span class="name" style="color: #FFF; font-size:14pt;">
					                <?php echo $ent_name; ?>  
					            </span>            
					        </a>
					    </li>
					</ul>
                								
					<!-- /NAVBAR LEFT -->					
					<ul class="nav navbar-nav pull-right hidden-xs">
						<li id="header-user" style="color:#FFF; font-size:10pt; margin-top:8px; margin-right:15px;">														
							<span class="username">							
								<script type="text/javascript">
									var d_names = <?php echo '"'.$dia.'"' ; ?>;
									var m_names = <?php echo '"'.$mes.'"' ; ?>;									
									var d = new Date();
									var curr_day = d.getDay();
									var curr_date = d.getDate();
									var curr_month = d.getMonth();
									var curr_year = d.getFullYear();									
									document.write("<i class='fa fa-calendar' style='color:#fff;'> </i><span style='font-size:14px;'>  " + d_names + ", " + curr_date + " " + m_names + " " + curr_year +"</span>" );							
								</script> 
							</span><p>
							<span id="clock" style="float:right;"></span>																					
						</li>
					</ul>     
			   <!-- /.navbar-collapse -->																																	
					               
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li>                                    
                                </li>
                                <li>                                   
                                </li>
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->                         
                    </nav>

                    <!-- .box-holder -->
                    <div class="box-holder">

                        <!-- .left-sidebar -->
                        <div class="left-sidebar">
                            <div class="sidebar-holder">
                                <!-- User   -->
                                <div class="user-menu">
                                    <img src="<?php echo $photo_url;?>" alt="" title="Upload photo in user profile" class="avatar" style="margin-left: -8px;" />
                                    <div class="user-info">
                                        <div class="welcome"><?php echo __('Welcome','dashboard'); ?> , </div>
                                        <div class="username"><a href="#" onclick="window.open('<?php echo $CFG_GLPI['url_base']; ?>/front/user.form.php?id=<?php echo $_SESSION['glpiID']; ?>','_blank'); scrollWin();" ><?php echo $_SESSION["glpifirstname"]; ?></a></div>
                                        
                                    </div>                                  
                                </div>
                                <!-- /.User   -->

                                <!-- Menu -->
                                <ul class="nav nav-list">
                                
                                    <li>
                                    <a href="#" onclick="window.open('<?php echo $CFG_GLPI['url_base']; ?>/plugins/dashboard/front/index.php','_self'); scrollWin();" data-original-title='Dashboard'>
                                        <i class='fa fa-dashboard'></i>
                                        <span class='hidden-minibar'>Dashboard</span>
                                    </a>
                                    </li>
                                    
                                    <li>                                    
                                    <a href="#" onclick="window.open('<?php echo $CFG_GLPI['url_base']; ?>/plugins/dashboard/front/graphs/graf_tech.php?con=1','iframe1'); scrollWin();" target="iframe1" data-original-title='My Dashboard'>
                                        <i class='fa fa-area-chart'></i>
                                        <span class='hidden-minibar'><?php echo __('My Dashboard','dashboard');?></span>
                                    </a>
                                    </li>
                                    
                                    <li class='submenu'>
	                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='Chamados'>
	                                        <i class='fa fa-edit'></i>
	                                        <span class='hidden-minibar'><?php echo __('Tickets','dashboard');?>
	                                            <i class='fa fa-chevron-right  pull-right'></i>
	                                        </span>
	                                    </a>
													<ul  class="animated fadeInDown">
                                        <li>
                                            <a href="./tickets/chamados.php" data-original-title=' Geral' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Overall','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li>
                                             <a href="./tickets/select_ent.php" data-original-title=' por Entidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li>
                                              <a href="./tickets/select_grupo.php" data-original-title=' por Grupo' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?> </span>
                                            </a>
                                        </li>   
                                        
                                       <li class='submenu'>
		                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='Mapas'>
		                                        <i class='fa fa-angle-right'></i>
		                                        <span class='hidden-minibar'><?php echo __('Map','dashboard');?>
		                                            <i class='fa fa-angle-right  pull-right'></i>
		                                        </span>
		                                    </a>
		                                    <ul  class="animated fadeInDown menu2">
	                                        <li>
	                                            <a class='' href="./map/index.php" data-original-title=' Mapa' target="_blank">
	                                                <i class="fa fa-angle-right"></i>
	                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?> </span>
	                                            </a>
	                                        </li>
	                                        <li>
	                                            <a href="./map/map_loc.php" data-original-title=' Mapa' target="_blank">
	                                                <i class="fa fa-angle-right"></i>
	                                                <span class='hidden-minibar'> <?php echo __('by Location','dashboard'); ?> </span>
	                                            </a>
	                                        </li>
	                                        </ul>
		                                    </li>                              
                                    	</ul>                                    
                                </li>
                               
                                                               
											<li class='submenu'>
                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='Relatórios'>
                                        <i class='fa fa-list-alt'></i>
                                        <span class='hidden-minibar'><?php echo __('Reports','dashboard'); //<b class="caret"></b>?>
                                            <i class='fa fa-chevron-right  pull-right'></i>
                                        </span>
                                    </a>
												<ul  class="animated fadeInDown">
                                       <li>                                         
                                            <a href="#" onclick="window.open('./reports/rel_assets.php','iframe1'); scrollWin();" data-original-title=' Ativos'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Assets'); ?></span>
                                            </a>
                                       </li>  
													<li>
                                            <a href="#" onclick="window.open('./reports/rel_tickets.php','iframe1'); scrollWin();" data-original-title=' Tickets'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _n('Ticket','Tickets',2); ?></span>
                                            </a>
                                       </li>                                                                       
                                       <li>
                                            <a href="#" onclick="window.open('./reports/rel_categorias.php?con=1','iframe1'); scrollWin();" data-original-title=' Categorias'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Category'); //echo _n('Category','Categories',2); ?> </span>
                                            </a>
                                        </li>
                                       <li>
                                            <a href="#" onclick="window.open('./reports/rel_entidades.php?con=1','iframe1'); scrollWin();" data-original-title=' Categorias'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _sn('Entity','Entities',2); ?> </span>
                                            </a>
                                        </li>  
                                        <li>
                                            <a href="#" onclick="window.open('./reports/rel_grupos.php?con=1','iframe1'); scrollWin();" data-original-title=' Categorias'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _sn('Group','Groups',2); ?> </span>
                                            </a>
                                        </li>                                         
                                        <li>
	                                         <a href="#" onclick="window.open('./reports/rel_localidades.php?con=1','iframe1'); scrollWin();" data-original-title=' Location' >
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Location'); ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" onclick="window.open('./reports/rel_projects.php','iframe1'); scrollWin();" data-original-title=' Projetos'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _n('Project','Projects',2); ?> </span>
                                            </a>
                                        </li>                                          
                                        <li>
	                                          <a href="#" onclick="window.open('./reports/rel_satisfacao.php','iframe1'); scrollWin();" data-original-title=' Satisfaction'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Satisfaction'); ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" onclick="window.open('./reports/rel_tecnicos.php?con=1','iframe1'); scrollWin();" data-original-title=' Técnicos'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _sn('Technician','Technicians',2,'dashboard'); ?> </span>
                                            </a>
                                        </li>                                        
                             				 <?php
					                        	// distinguish between 0.90.x and 9.1 version
														if (GLPI_VERSION <= intval('9.1')){
		                                     echo '<li>';
	                                            echo '<a href="#" onclick="window.open(\'./reports/rel_slas.php?con=1\',\'iframe1\'); scrollWin();" data-original-title=" SLAs">';
	                                            echo '   <i class="fa fa-angle-right"></i>';
	                                            echo '   <span class="hidden-minibar">'. __('SLA').'</span>';
	                                            echo '</a>
	                                        </li>';
                                        	}
                                        ?>
													<li class='submenu'>
			                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='Custos'>
			                                        <i class='fa fa-angle-right'></i>
			                                        <span class='hidden-minibar'><?php echo __('Cost');?>
			                                            <i class='fa fa-angle-right  pull-right'></i>
			                                        </span>
			                                    </a>
			                                   <ul  class="animated fadeInDown menu2">
			                                      <li>
		                                            <a href="#" onclick="window.open('./reports/rel_custo_ent.php','iframe1'); scrollWin();"  data-original-title=' Custos'>
		                                                <i class="fa fa-angle-right"></i>
		                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?> </span>
		                                            </a>
		                                        </li>
	   	                                     <li>
		                                            <a href="#" onclick="window.open('./reports/rel_custo_loc.php','iframe1'); scrollWin();"  data-original-title=' Custos'>
		                                                <i class="fa fa-angle-right"></i>
		                                                <span class='hidden-minibar'> <?php echo __('by Location','dashboard'); ?> </span>
		                                            </a>
		                                        </li> 		                                         
		                                        <li>
		                                            <a href="#" onclick="window.open('./reports/rel_custo_req.php','iframe1'); scrollWin();"  data-original-title=' Custos'>
		                                                <i class="fa fa-angle-right"></i>
		                                                <span class='hidden-minibar'> <?php echo __('by Requester','dashboard'); ?> </span>
		                                            </a>
		                                         </li> 
						        							 <li>
		                                           <a href="#" onclick="window.open('./reports/rel_custo_tec.php','iframe1'); scrollWin();"  data-original-title=' Custos'>
		                                                <i class="fa fa-angle-right"></i>
		                                                <span class='hidden-minibar'> <?php echo __('by Technician','dashboard'); ?> </span>
		                                            </a>
		                                        </li> 		                                         
		                                      </ul>
		                                  </li>                                                                                                                                                                                                  
		                                  
		                                  <li class='submenu'>
			                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='Sintético'>
			                                        <i class='fa fa-angle-right'></i>
			                                        <span class='hidden-minibar'><?php echo __('Summary','dashboard');?>
			                                            <i class='fa fa-angle-right  pull-right'></i>
			                                        </span>
			                                    </a>
			                                   <ul  class="animated fadeInDown menu2">
   		                                  	<li>
	                                            <a href="#" onclick="window.open('./reports/rel_sint_all.php','iframe1'); scrollWin();" data-original-title=' Sintético' >
	                                                <i class="fa fa-angle-right"></i>
	                                                <span class='hidden-minibar'> <?php echo __('Overall','dashboard'); ?> </span>
	                                            </a>
	                                        	</li>
		                                      <li>
	                                            <a href="#" onclick="window.open('./reports/rel_sint_ent.php','iframe1'); scrollWin();" data-original-title=' Sintético' >
	                                                <i class="fa fa-angle-right"></i>
	                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?> </span>
	                                            </a>
	                                         </li> 
	                                         <li>
	                                            <a href="#" onclick="window.open('./reports/rel_sint_req.php','iframe1'); scrollWin();"  data-original-title=' Sintético' >
	                                                <i class="fa fa-angle-right"></i>
	                                                <span class='hidden-minibar'> <?php echo __('by Requester','dashboard'); ?> </span>
	                                            </a>
	                                         </li> 	                                         
					        							  <li>
	                                            <a href="#" onclick="window.open('./reports/rel_sint_tec.php','iframe1'); scrollWin();" data-original-title=' Sintético'>
	                                                <i class="fa fa-angle-right"></i>
	                                                <span class='hidden-minibar'> <?php echo __('by Technician','dashboard'); ?> </span>
	                                            </a>
	                                         </li> 
		                                        </ul>
		                                  </li>  
		                                  
		                                  <li class='submenu'>
			                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='Tarefas'>
			                                        <i class='fa fa-angle-right'></i>
			                                        <span class='hidden-minibar'><?php echo _n('Task','Tasks',2);?>
			                                            <i class='fa fa-angle-right  pull-right'></i>
			                                        </span>
			                                    </a>
			                                    <ul  class="animated fadeInDown menu2">
	                                        <li>
	                                            <a href="#" onclick="window.open('./reports/rel_task_ent.php','iframe1'); scrollWin();" data-original-title=' Tarefas'>
	                                                <i class="fa fa-angle-right"></i>
	                                                <span class='hidden-minibar'> <?php echo __('Entity','dashboard'); ?> </span>
	                                            </a>
	                                        </li>
	                                        <li>
	                                            <a href="#" onclick="window.open('./reports/rel_task_req.php','iframe1'); scrollWin();" data-original-title=' Tarefas'>
	                                                <i class="fa fa-angle-right"></i>
	                                                <span class='hidden-minibar'> <?php echo __('Requester'); ?> </span>
	                                            </a>
	                                        </li> 	                                        			                                    
		                                     <li>
	                                            <a href="#" onclick="window.open('./reports/rel_tarefa.php','iframe1'); scrollWin();" data-original-title=' Tarefas' >
	                                                <i class="fa fa-angle-right"></i>
	                                                <span class='hidden-minibar'> <?php echo __('Technician'); ?> </span>
	                                            </a>
	                                        </li>
					        							 <li>
	                                            <a href="#" onclick="window.open('./reports/rel_tarefa_cham.php','iframe1'); scrollWin();" data-original-title=' Tarefas'>
	                                                <i class="fa fa-angle-right"></i>
	                                                <span class='hidden-minibar'> <?php echo __('Tickets','dashboard'); ?> </span>
	                                            </a>
	                                        </li>  
		                                        </ul>
		                                  </li>  		                                  		                                    
		                                  
		                     	          <?php
						                        // distinguish between 0.90.x and 9.1 version
														if (GLPI_VERSION >= 9.1){
			                                  echo "<li class='submenu'>";
				                                    echo "<a class='dropdown' onClick='return false;' href='#' data-original-title='SLA'>
				                                        <i class='fa fa-angle-right'></i>";
				                                    echo "<span class='hidden-minibar'>". __('SLA')."
				                                            <i class='fa fa-angle-right  pull-right'></i>
				                                        </span>
				                                    </a>";
				                                    echo '<ul  class="animated fadeInDown menu2">
				                                      <li>';
			                                            echo '<a href="#" onclick="window.open(\'./reports/rel_sltsas.php?con=1\',\'iframe1\'); scrollWin();"  data-original-title=" Custos">
			                                                <i class="fa fa-angle-right"></i>
			                                                <span class="hidden-minibar">'. __('Time to own').'</span>
			                                            </a>
			                                        </li> 
							        							 <li>
			                                           <a href="#" onclick="window.open(\'./reports/rel_sltsrs.php?con=1\',\'iframe1\'); scrollWin();"  data-original-title=" Custos">
			                                                <i class="fa fa-angle-right"></i>
			                                                <span class="hidden-minibar">'. __('Time to resolve').'</span>
			                                            </a>
			                                        </li> 		 
			                                      </ul>
			                                  </li>';
			                               }
			                               ?>

                                       <li>
                                            <a href="#" onclick="window.open('./reports/rel_categoria.php','iframe1'); scrollWin();"  data-original-title=' por Categoria'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Category','dashboard'); ?> </span>
                                            </a>
                                       </li>		                                      
													<li>
                                            <a href="#" onclick="window.open('./reports/rel_data.php','iframe1'); scrollWin();"  data-original-title=' por Data'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Date','dashboard'); ?> </span>
                                            </a>
                                        </li> 
                                        <li>
                                            <a href="#" onclick="window.open('./reports/rel_entidade.php','iframe1'); scrollWin();"  data-original-title=' por Entidade' >
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?> </span>
                                            </a>
                                        </li>                                         
                                        <li>
                                            <a href="#" onclick="window.open('./reports/rel_grupo.php','iframe1'); scrollWin();"  data-original-title=' por Grupo'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?> </span>
                                            </a>
                                        </li>   
                                        <li>
                                              <a href="#" onclick="window.open('./reports/rel_localidade.php','iframe1'); scrollWin();"  data-original-title=' by Localization'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Location','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" onclick="window.open('./reports/rel_usuario.php','iframe1'); scrollWin();"  data-original-title=' por Usuário'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Requester','dashboard'); ?> </span>
                                            </a>
                                        </li>                                                                                                                      
                                        <li>
                                            <a href="#" onclick="window.open('./reports/rel_tecnico.php','iframe1'); scrollWin();"  data-original-title=' por Técnico'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Technician','dashboard'); ?> </span>
                                            </a>
                                        </li> 

                             				 <?php
					                        	// distinguish between 0.90.x and 9.1 version
														if (GLPI_VERSION <= intval('9.1')){
			                                    echo '<li>
		                                            <a href="#" onclick="window.open(\'./reports/rel_sla.php\',\'iframe1\'); scrollWin();"  data-original-title=" por SLA">
		                                                <i class="fa fa-angle-right"></i>
		                                                <span class="hidden-minibar">'. __('by SLA','dashboard').'</span>
		                                            </a>
		                                        </li>'; 
		                                     }
                                        ?>
                                        
                                       <?php
					                        // distinguish between 0.90.x and 9.1 version
													if (GLPI_VERSION >= 9.1){
	                                        echo "<li class='submenu'>
				                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='by SLA'>
				                                        <i class='fa fa-angle-right'></i>
				                                        <span class='hidden-minibar'>". __('by SLA','dashboard')."
				                                            <i class='fa fa-angle-right  pull-right'></i>
				                                        </span>
				                                    </a>";
				                                echo '<ul  class="animated fadeInDown menu2">
				                                      <li>
			                                            <a href="#" onclick="window.open(\'./reports/rel_sltsa.php\',\'iframe1\'); scrollWin();"  data-original-title=" SLA">
			                                                <i class="fa fa-angle-right"></i>
			                                                <span class="hidden-minibar">'. __('Time to own').'</span>
			                                            </a>
			                                        </li> 
							        							 <li>
			                                           <a href="#" onclick="window.open(\'./reports/rel_sltsr.php\',\'iframe1\'); scrollWin();"  data-original-title=" SLA">
			                                                <i class="fa fa-angle-right"></i>
			                                                <span class="hidden-minibar">'. __('Time to resolve').'</span>
			                                            </a>
			                                        </li> 		 
			                                      </ul>
			                                  </li>'; 
                               		 		}
                               		 	?>
                               		 		                             
                                    </ul>                        
                                </li>			                                
                                
                                <li class='submenu'>
                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='Gráficos'>
                                        <i class="fa fa-bar-chart-o"></i>
                                        <span class='hidden-minibar'><?php echo __('Charts','dashboard'); ?>
                                            <i class='fa fa-chevron-right  pull-right'></i>
                                        </span>
                                    </a>
                                    <ul  class="animated fadeInDown">
                                        <li>
                                            <a href="#" onclick="window.open('./graphs/ativos.php','iframe1'); scrollWin();" data-original-title=' Ativos'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Assets'); ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" onclick="window.open('./graphs/categorias.php','iframe1'); scrollWin();"  data-original-title=' Categorias' >
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Category'); ?></span>
                                            </a>
                                        </li> 
                                        <li>
                                            <a href="#" onclick="window.open('./graphs/entidades.php','iframe1'); scrollWin();"  data-original-title=' Entidades'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Entity','dashboard'); ?></span>
                                            </a>
                                        </li>                                                                               
                                        <li>
                                            <a href="#" onclick="window.open('./graphs/geral.php','iframe1'); scrollWin();"  data-original-title=' Geral'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Overall','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" onclick="window.open('./graphs/grupos.php','iframe1'); scrollWin();" data-original-title=' Grupos'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Group','dashboard'); ?></span>
                                            </a>
                                        </li>  
                                        <li class='menu_chart'>
	                                            <a href="#" onclick="window.open('./graphs/local.php','iframe1'); scrollWin();" data-original-title=' Localização'>
	                                                <i class="fa fa-angle-right"></i>
	                                                <span class='hidden-minibar'> <?php echo __('Location'); ?></span>
	                                            </a>
                                        </li>                                                                              
                                        <li>
                                            <a href="#" onclick="window.open('./graphs/usuarios.php','iframe1'); scrollWin();"  data-original-title=' Usuários'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Requester','dashboard'); ?></span>
                                            </a>
                                        </li>        
                                         <li>
                                            <a href="#" onclick="window.open('./graphs/satisfacao.php','iframe1'); scrollWin();" data-original-title=' Satisfação'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Satisfaction','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" onclick="window.open('./graphs/tecnicos.php','iframe1'); scrollWin();"  data-original-title=' Técnicos'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Technician','dashboard'); ?></span>
                                            </a>
                                        </li> 
                                       <li>
                                             <a href="#" onclick="window.open('./graphs/times.php','iframe1'); scrollWin();" data-original-title=' por Tempo'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Time range'); ?></span>
                                            </a>
                                       </li>                                          
                                        <li>
                                             <a href="#" onclick="window.open('./graphs/graf_categoria.php','iframe1'); scrollWin();" data-original-title=' por Categoria'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Category','dashboard'); ?></span>
                                            </a>
                                        </li>                                                                             
                                        <li>
                                            <a href="#" onclick="window.open('./graphs/geral_mes.php','iframe1'); scrollWin();" data-original-title=' por Data'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Date','dashboard'); ?></span>
                                            </a>
                                        </li>
  													 <li>
                                            <a href="#" onclick="window.open('./graphs/graf_entidade.php','iframe1'); scrollWin();" data-original-title=' por Entidade'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?></span>
                                            </a>
                                        </li>      
													 <li>
                                             <a href="#" onclick="window.open('./graphs/graf_grupo.php','iframe1'); scrollWin();" data-original-title=' por Grupo'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li>
                                             <a href="#" onclick="window.open('./graphs/graf_localidade.php','iframe1'); scrollWin();" data-original-title=' por Localidade'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Location','dashboard'); ?></span>
                                            </a>
                                        </li>                                                                          
                                         <li>
                                            <a href="#" onclick="window.open('./graphs/graf_usuario.php','iframe1'); scrollWin();" data-original-title=' por Usuário'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Requester','dashboard'); ?></span>
                                            </a>
                                        </li>
								 					 <li>
                                            <a href="#" onclick="window.open('./graphs/graf_tecnico.php','iframe1'); scrollWin();" data-original-title=' por Técnico'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Technician','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" onclick="window.open('./graphs/graf_tipo.php','iframe1'); scrollWin();" data-original-title=' por Tipo'>
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Type','dashboard'); ?></span>
                                            </a>
                                        </li>                                         
                                        
                                        <?php
					                        	// distinguish between 0.90.x and 9.1 version
														if (GLPI_VERSION <= intval('9.1')){
		                                     echo '<li>';
	                                            echo '<a href="#" onclick="window.open(\'./graphs/slas.php?con=1\',\'iframe1\'); scrollWin();" data-original-title=" SLAs">';
	                                            echo '   <i class="fa fa-angle-right"></i>';
	                                            echo '   <span class="hidden-minibar">'. __('by SLA','dashboard').'</span>';
	                                            echo '</a>
	                                        </li>';
                                        	}
                                        ?>

		                                 <?php
					                        // distinguish between 0.90.x and 9.1 version
													if (GLPI_VERSION >= 9.1){
	                                        echo "<li class='submenu'>
				                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='by SLA'>
				                                        <i class='fa fa-angle-right'></i>
				                                        <span class='hidden-minibar'>". __('by SLA','dashboard')."
				                                            <i class='fa fa-angle-right  pull-right'></i>
				                                        </span>
				                                    </a>";
				                                echo '<ul  class="animated fadeInDown menu2">
				                                      <li>
			                                            <a href="#" onclick="window.open(\'./graphs/sltsa.php\',\'iframe1\'); scrollWin();"  data-original-title=" SLA">
			                                                <i class="fa fa-angle-right"></i>
			                                                <span class="hidden-minibar">'. __('Time to own').'</span>
			                                            </a>
			                                        </li> 
							        							 <li>
			                                           <a href="#" onclick="window.open(\'./graphs/sltsr.php\',\'iframe1\'); scrollWin();"  data-original-title=" SLA">
			                                                <i class="fa fa-angle-right"></i>
			                                                <span class="hidden-minibar">'. __('Time to resolve').'</span>
			                                            </a>
			                                        </li> 		 
			                                      </ul>
			                                  </li>'; 
                               		 		}
                               		 	?>		                                  
                           
                                    </ul>
                                </li><!-- delighted pages -->                               
                                
                                <li class='submenu'>
	                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='Metrics'>
	                                        <i class='fa fa-line-chart'></i>
	                                        <span class='hidden-minibar'><?php echo __('Metrics','dashboard');?>
	                                            <i class='fa fa-chevron-right  pull-right'></i>
	                                        </span>
	                                    </a>
													<ul  class="animated fadeInDown">
                                        <li>
                                            <a href="./metrics/index.php" data-original-title=' Geral' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Overall','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li>
                                             <a href="./metrics/select_ent.php" data-original-title=' por Entidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li>
                                              <a href="./metrics/select_grupo.php" data-original-title=' por Grupo' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?> </span>
                                            </a>
                                        </li>   
                                                                     
                                    	</ul>                                    
                                </li>
                                
                                <li>									     
                                    <a href="#" onclick="window.open('./assets/assets.php','iframe1'); scrollWin();" target="iframe1" data-original-title='Assets'>
                                        <i class='fa fa-desktop'></i>
                                        <span class='hidden-minibar'><?php echo __('Assets'); ?>
                                        </span>
                                    </a>  
                                </li>   
                                                               
                                <li>
                                    <a href="#" onclick="window.open('./config.php','iframe1'); scrollWin();" target="iframe1"  data-original-title='Config'>
                                        <i class='fa fa-gears'></i>
                                        <span class='hidden-minibar'><?php echo __('Setup'); ?>
                                        </span>
                                    </a>  
                                </li>
                                
                                <li>
                                     <a href="#" onclick="window.open('info.php','iframe1'); scrollWin();" target="iframe1" data-original-title='Info'>
                                        <i class='fa fa-info-circle'></i>
                                        <span class='hidden-minibar'><?php echo __('Info','dashboard'); ?>
                                        </span>
                                    </a>  
                                </li>
                                
                               <li>
                                    <a href='https://forge.glpi-project.org/projects/dashboard/wiki' target="_blank" data-original-title='Help'>
                                        <i class='fa fa-question-circle'></i>
                                        <span class='hidden-minibar'><?php echo __('Help'); ?>
                                        </span>
                                    </a>  
                                </li>                                
				 <li>
              <?php

              //version check	                      
					$query_up = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'update'";																
					$result_up = $DB->query($query_up);
					
					$up_option = $DB->result($result_up,0,'value');	
					              
					if($up_option == 1) {               
                    								              								
					$ver = explode(" ",implode(" ",plugin_version_dashboard())); 																																																			
					$urlv = "http://a.fsdn.com/con/app/proj/glpidashboard/screenshots/".$ver[1].".png";
					$headers = get_headers($urlv, 1);										

					if($headers[0] != '') {

					//if ($headers[0] == 'HTTP/1.1 200 OK') { }
					if ($headers[0] == 'HTTP/1.0 404 Not Found') {
						echo '<a href="https://forge.glpi-project.org/projects/dashboard/files" target="_blank">
					 	<i class="fa fa-refresh"></i>                   
                    <span class="blink_me">'. __('New version','dashboard').'</span>
                     <span class="blink_me">'.__('avaliable','dashboard').'</span></a>';		
							}
						}
					}	
				  ?>
            </li>                                                                                                                               
            </ul>
            <!-- /.Menu -->
        </div>
        <!-- /.left-sidebar Holder-->
<?php
 
if(file_exists('/etc/hosts')) { 
        
	echo '<h5 class="label1 label-default"> <i class="fa fa-info-circle"></i>&nbsp; '. __('Server Info','dashboard').'</h5>
		
		<ul class="list-unstyled list-info-sidebar" style="color: #cecece;">
			<li class="data-row">
				<span class="data-name" >OS:</span>
				<span class="data-value">'; include './sh/issue.php'; 
			
	echo		'</span>
			</li>
	
			<li class="data-row">
				<span class="data-name" >UP:</span>
				<span class="data-value">'; include './sh/uptime.php'; 
				
	echo		'</span>
			</li>
	
			<li class="data-row">
				<span class="data-name" >MEM:</span>
				<span class="data-value">'; include './sh/mem.php'; 
	
	echo '<div class="progress" style="height: 5px;">
	    		<div class="progress-bar progress-bar-striped active '.$corm.' " style="width: '.$umem.'%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="'.$umem.'" role="progressbar"></div>
			</div>
				</span>		
			</li>
	
			<li class="data-row">
				<span class="data-name" >DISK:</span>
				<span class="data-value">'; include './sh/df.php'; 
	
	echo '<div class="progress" style="height: 5px;">
	    		<div class="progress-bar progress-bar-striped active '.$cord.'" style="width: '.$udisk.'%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="'.$udisk.'" role="progressbar"></div>
			</div>
				</span>			
			</li>	';			
}

?>	   

	<div id="donate" style="margin-top:30px; margin-left:60px;">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="3SN6KVC4JSB98">
		<input type="image" src="./img/paypal.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="./img/paypal.png" width="1" height="1">
		</form>
	</div>
                           
 </div>
 <!-- /.left-sidebar -->

<?php     
$ano = date("Y");
$month = date("Y-m");
$hoje = date("Y-m-d");

//select entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');	

if($sel_ent != '') {			
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
	$entidade_u = "AND glpi_users.entities_id IN (".$sel_ent.")";
}


if($sel_ent == '') {
	
	$entities = $_SESSION['glpiactiveentities'];	
	$ent = implode(",",$entities);	
	$entidade = "AND glpi_tickets.entities_id IN (".$ent.")";
	$entidade_u = "AND glpi_users.entities_id IN (".$ent.")";	
}

//selecionar anos 
if($num_years == 0) {
	
	$query_y = "SELECT DISTINCT DATE_FORMAT( date, '%Y' ) AS year
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND date IS NOT NULL	
	ORDER BY year ASC ";
}

if($num_years == 1) {
	
	$query_y = "SELECT DISTINCT DATE_FORMAT( date, '%Y' ) AS year
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND date IS NOT NULL
	ORDER BY year DESC
	LIMIT ".$num_years."";
}

if($num_years > 1) {
	
	$query_y = "SELECT DISTINCT DATE_FORMAT( date, '%Y' ) AS year
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND date IS NOT NULL
	ORDER BY year DESC
	LIMIT ".$num_years."";
	
}

$result_y = $DB->query($query_y);

//numero de anos para eixos Y
$conta_y = $DB->numrows($result_y);

$arr_years = array();

while ($row_y = $DB->fetch_assoc($result_y))		
	{ 
		$arr_years[] = $row_y['year'];			
	} 


if($num_years > 1) {
	$arr_years = array_reverse($arr_years);
	$years = implode(",", $arr_years);
}
else {
	$years = implode(",", $arr_years);
}


//chamados ano
$sql_ano =	"SELECT COUNT(glpi_tickets.id) as total        
      FROM glpi_tickets
      LEFT JOIN glpi_entities ON glpi_tickets.entities_id = glpi_entities.id
      WHERE glpi_tickets.is_deleted = '0' 
      AND DATE_FORMAT( glpi_tickets.date, '%Y' ) IN (".$years.") 
      ".$entidade." ";

$result_ano = $DB->query($sql_ano);
$total_ano = $DB->fetch_assoc($result_ano);
      
//chamados mes
$sql_mes =	"SELECT COUNT(glpi_tickets.id) as total        
      FROM glpi_tickets
      LEFT JOIN glpi_entities ON glpi_tickets.entities_id = glpi_entities.id
      WHERE glpi_tickets.date LIKE '$month%'      
      AND glpi_tickets.is_deleted = '0' 
      ".$entidade." ";

$result_mes = $DB->query($sql_mes);
$total_mes = $DB->fetch_assoc($result_mes);

//chamados dia
$sql_hoje =	"SELECT COUNT(glpi_tickets.id) as total        
      FROM glpi_tickets
      LEFT JOIN glpi_entities ON glpi_tickets.entities_id = glpi_entities.id
      WHERE glpi_tickets.date like '$hoje%'      
      AND glpi_tickets.is_deleted = '0'
      ".$entidade." ";

$result_hoje = $DB->query($sql_hoje);
$total_hoje = $DB->fetch_assoc($result_hoje);

// total users
$sql_users = "SELECT COUNT(id) AS total
				FROM `glpi_users`
				WHERE is_deleted = 0
				".$entidade_u."
				AND is_active = 1";

$result_users = $DB->query($sql_users);
$total_users = $DB->fetch_assoc($result_users);

?>

<!-- .content -->
                        
<div class="container-fluid " style="margin-top:60px;">            
</div>   
 <iframe id="iframe1" name="iframe1" class="iframe iframe-side" src="main2.php" scrolling="no"></iframe>

	<script>
	function scrollWin()
	{
		$('html, body').animate({ scrollTop: 0 }, 'slow');
	}
	</script> 

	<div id="go-top" class="go-top" onclick="scrollWin()">
	   <i class="fa fa-chevron-up"></i>&nbsp; Top     							    
	</div>      
        
	
</div>	
	
<!-- end main-content -->	
</div>

</div>
<!-- /.box-holder -->
	<!-- transparent them footer -->
	<style type="text/css">
	@media screen and (min-width: 1201px) and (max-width: 2200px) {
  	#footer-bar {
    margin-top: 5px;
    height: 20px;
  	 }
	}
	</style>
	<?php
	if($theme == 'trans.css') {		 
		echo '<div id="footer-bar" class="footer-bar row-fluid" style="overflow: hidden; height:70px; width:100%; background-color: #000; opacity:0.7; float:left; bottom:0px; margin-top: -70px; position:relative; clear: both; margin-left: 220px; " ></div>';
	}  
	?>
	
</div>
<!-- /.site-holder -->
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
 <!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
   
<script src="js/jquery.accordion.js"></script>            
<script src="js/bootstrap-dropdown.js"></script>
<script src="js/jquery.easy-pie-chart.js"></script> 
<script src="js/jquery.address-1.6.min.js"></script>

<script src="js/bootstrap-switch.js"></script> 
<script src="js/highcharts.js" type="text/javascript" ></script>
<script src="js/highcharts-3d.js" type="text/javascript" ></script>
<script src="js/modules/exporting.js" type="text/javascript" ></script>
<script src="js/modules/no-data-to-display.js" type="text/javascript" ></script>
<script src="js/themes/<?php echo $_SESSION['charts_colors'] ?>"></script>';

<!-- knob -->
<script src="js/jquery.knob.js"></script>

<!-- flot charts -->    
<script src="js/jquery.flot.js"></script>
<script src="js/jquery.flot.stack.js"></script>
<script src="js/jquery.flot.resize.js"></script>
<script src="js/jquery.flot.pie.min.js"></script>
<script src="js/jquery.flot.valuelabels.js"></script>
<script src="js/theme.js"></script>         
<script src="js/jquery.jclock.js"></script>

 <script>
     $('document').ready(function(){
         $("[name='my-checkbox']").bootstrapSwitch();
     });
 </script>
 
<!-- Highcharts export xls, csv -->
<script src="js/export-csv.js"></script>

 <!-- Remove below two lines in production -->  
 <script src="js/theme-options.js"></script>       
 <script src="js/core.js"></script>
 
</body>
</html>
