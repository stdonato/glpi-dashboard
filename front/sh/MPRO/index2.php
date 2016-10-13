<?php
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

global $DB;

Session::checkLoginUser();
//Session::checkRight("profile", "r");

# entity in index
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '' || $sel_ent == -1) {
	$sel_ent = 0;
	$ent_name = __('Tickets Statistics','dashboard');
}

else {
	$query = "SELECT name FROM glpi_entities WHERE id IN (".$sel_ent.")";
	$result = $DB->query($query);
	$ent_name1 = $DB->result($result,0,'name');
	$ent_name = __('Tickets Statistics','dashboard')." :  ". $ent_name1 ;
	//$ent_name = __('Tickets Statistics','dashboard');
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
	$colors = 'grid-light.js';	
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
    

//redirect tech profile
if(Session::haveRight("profile", "r"))
	{
		$redir = '<meta http-equiv="refresh" content= "120"/>';
	}
else {		
		$redir = '<meta http-equiv="refresh" content="0; url=graficos/graf_tech.php?con=1" />'; 
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

    <!-- Custom Styles -->
    <link href="css/custom.css" rel="stylesheet">
    
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
	
<script type="text/javascript">
	$(function($) {
	var options = {
	timeNotation: '24h',
	am_pm: false,
	fontFamily: 'Open Sans',
	fontSize: '10pt',
	foreground: '#FFF'
	}
		$('#clock').jclock(options);
	});
</script> 
    
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

</head>

	<?php
	if($theme == 'trans.css') {		
   	echo "<body style=\"background: url('./img/".$back."') no-repeat top center fixed; \">";
   	}
   else {
   	echo "<body style='background-color: #FFF;' >";
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
					        <a href="./index.php" style="margin-top: 6px;">           
					            <span class="name" style="color: #FFF; font-size:14pt;">
					                <?php echo $ent_name; ?>  
					            </span>            
					        </a>
					    </li>
					</ul>
                								
					<!-- /NAVBAR LEFT -->					
					<ul class="nav navbar-nav pull-right hidden-xs">
						<li id="header-user" class=" user">
							<a  href="#" style="color:#FFF; font-size:10pt; margin-top:5px;">							
							<span class="username">
							
							<script type="text/javascript">
								var d_names = <?php echo '"'.$dia.'"' ; ?>;
								var m_names = <?php echo '"'.$mes.'"' ; ?>;
								
								var d = new Date();
								var curr_day = d.getDay();
								var curr_date = d.getDate();
								var curr_month = d.getMonth();
								var curr_year = d.getFullYear();
								
								document.write("<i class='fa fa-calendar-o' style='color:#fff;'> </i>  " + d_names + ", " + curr_date + " " + m_names + " " + curr_year );							
							</script> 
							</span>
							<span id="clock"></span>							
							
							</a>
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
                                    <div class="user-info" style="margin-left: 30px;">
                                        <div class="welcome"><?php echo __('Welcome','dashboard'); ?> , </div>
                                        <div class="username"><a href="<?php echo $CFG_GLPI['root_doc']; ?>/front/user.form.php?id=<?php echo $_SESSION['glpiID']; ?>" target="_blank"><?php echo $_SESSION["glpifirstname"]; ?></a></div>
                                    </div>                                  
                                </div>
                                <!-- /.User   -->

                                <!-- Menu -->
                                <ul class="nav nav-list">
                                
                                    <li class=' '>
                                    <a href="<?php echo $CFG_GLPI['url_base']; ?>/plugins/dashboard/front/index.php" data-original-title='Dashboard'>
                                        <i class='fa fa-dashboard'></i>
                                        <span class='hidden-minibar'>Dashboard</span>
                                    </a>
                                    </li>
                                    
                                    <li class=' '>
                                    <a href="<?php echo $CFG_GLPI['url_base']; ?>/plugins/dashboard/front/graficos/graf_tech.php?con=1" target="_blank" data-original-title='My Dashboard'>
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
                                        <li class=' '>
                                            <a href="./tickets/chamados.php" data-original-title=' Geral' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Overall','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                             <a href="./tickets/select_ent.php" data-original-title=' por Entidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./tickets/select_grupo.php" data-original-title=' por Grupo' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?> </span>
                                            </a>
                                        </li>   
                                         <li class=' '>
                                              <a href="./map/index.php" data-original-title=' Mapa' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Map','dashboard'); ?> </span>
                                            </a>
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
                                       <li class=' '>
                                            <a href="./reports/rel_tecnicos.php?con=1" data-original-title=' Técnicos' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Technician')."s"; ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                            <a href="./reports/rel_tecnico.php" data-original-title=' por Técnico' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Technician','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                             <a href="./reports/rel_usuario.php" data-original-title=' por Usuário' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Requester','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./reports/rel_entidade.php" data-original-title=' por Entidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./reports/rel_grupo.php" data-original-title=' por Grupo' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?> </span>
                                            </a>
                                        </li> 
                                        <li class=' '>
                                              <a href="./reports/rel_localidade.php" data-original-title=' by Localization' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Location','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./reports/rel_categoria.php" data-original-title=' por Categoria' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Category','dashboard'); ?> </span>
                                            </a>
                                        </li> 
      											<li class=' '>
                                              <a href="./reports/rel_data.php" data-original-title=' por Data' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Date','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                       <li class=' '>
                                            <a href="./reports/rel_sla.php" data-original-title=' por SLA' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by SLA','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./reports/rel_assets.php" data-original-title=' Tickets' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Assets'); ?></span>
                                            </a>
                                        </li>
					<li class=' '>
                                              <a href="./reports/rel_tickets.php" data-original-title=' Tickets' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _n('Ticket','Tickets',2); ?></span>
                                            </a>
                                        </li>   
            				<li class=' '>
                                              <a href="./reports/rel_tarefa.php" data-original-title=' Tarefas' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _n('Task','Tasks',2)." - ". __('Technician'); ?> </span>
                                            </a>
                                        </li> 
				        <li class=' '>
                                              <a href="./reports/rel_tarefa_cham.php" data-original-title=' Tarefas' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _n('Task','Tasks',2)." - ". __('Tickets','dashboard'); ?> </span>
                                            </a>
                                        </li> 			                             
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
                                         <li class=' '>
                                              <a href="./graficos/geral.php" data-original-title=' Geral' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Overall','dashboard'); ?></span>
                                            </a>
                                        </li>
                                          <li class=' '>
                                              <a href="./graficos/tecnicos.php" data-original-title=' Técnicos' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Technician','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graficos/usuarios.php" data-original-title=' Usuários' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Requester','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graficos/entidades.php" data-original-title=' Entidades' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Entity','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graficos/categorias.php" data-original-title=' Categorias' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Category'); ?></span>
                                            </a>
                                        </li>
                                          <li class=' '>
                                              <a href="./graficos/grupos.php" data-original-title=' Grupos' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Group','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class='menu_chart'>
	                                            <a href="./graficos/local.php" data-original-title=' Localização' target="_blank">
	                                                <i class="fa fa-angle-right"></i>
	                                                <span class='hidden-minibar'> <?php echo __('Location'); ?></span>
	                                            </a>
	                                        </li>	
                                        <li class=' '>
                                              <a href="./graficos/ativos.php" data-original-title=' Ativos' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Assets'); ?></span>
                                            </a>
                                        </li>
                                         <li class=' '>
                                              <a href="./graficos/satisfacao.php" data-original-title=' Satisfação' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Satisfaction','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graficos/geral_mes.php" data-original-title=' por Data' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Date','dashboard'); ?></span>
                                            </a>
                                        </li>
								 					 <li class=' '>
                                              <a href="./graficos/graf_tecnico.php" data-original-title=' por Técnico' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Technician','dashboard'); ?></span>
                                            </a>
                                        </li>
                                         <li class=' '>
                                              <a href="./graficos/graf_usuario.php" data-original-title=' por Usuário' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Requester','dashboard'); ?></span>
                                            </a>
                                        </li>
  													 <li class=' '>
                                              <a href="./graficos/graf_entidade.php" data-original-title=' por Entidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graficos/graf_categoria.php" data-original-title=' por Categoria' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Category','dashboard'); ?></span>
                                            </a>
                                        </li>
													 <li class=' '>
                                              <a href="./graficos/graf_grupo.php" data-original-title=' por Grupo' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graficos/graf_localidade.php" data-original-title=' por Localidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Location','dashboard'); ?></span>
                                            </a>
                                        </li>
                                       <li class=' '>
                                              <a href="./graficos/slas.php" data-original-title=' por SLA' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by SLA','dashboard'); ?></span>
                                            </a>
                                        </li>
                                       <li class=' '>
                                              <a href="./graficos/times.php" data-original-title=' por Tempo' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Time range'); ?></span>
                                            </a>
                                        </li>
                                       <li class=' '>
                                              <a href="./pati/graf_pati.php" data-original-title=' por PATI' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Time range'); ?></span>
                                            </a>
                                        </li>
                           
                                    </ul>
                                </li><!-- delighted pages -->  

									     <li class=' '>
                                    <a href='assets/assets.php' target="_blank" data-original-title='Assets'>
                                        <i class='fa fa-desktop'></i>
                                        <span class='hidden-minibar'><?php echo __('Assets'); ?>
                                        </span>
                                    </a>  
                                </li>
                                                               
                                <li class=' '>
                                    <a href='config.php' target="_blank" data-original-title='Config'>
                                        <i class='fa fa-gears'></i>
                                        <span class='hidden-minibar'><?php echo __('Setup'); ?>
                                        </span>
                                    </a>  
                                </li>
                                
                                <li class=' '>
                                    <a href='info.php' target="_blank" data-original-title='Info'>
                                        <i class='fa fa-info-circle'></i>
                                        <span class='hidden-minibar'>Info
                                        </span>
                                    </a>  
                                </li>
                                
                               <li class=' '>
                                    <a href='https://forge.indepnet.net/projects/dashboard/wiki' target="_blank" data-original-title='Help'>
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
							echo '<a href="https://forge.indepnet.net/projects/dashboard/files" target="_blank" >
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
        
echo '<h5 class="label1 label-default"> <i class="fa fa-info-circle"></i>&nbsp;  Server Info</h5>
	
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
if ($sel_ent == 0) {
	$entidade = "";
	$entidade_u = "";
}

else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.") ";
	$entidade_u = "AND glpi_users.entities_id IN (".$sel_ent.") ";
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
<div class="content animated fadeInBig" style="margin-top: 60px; margin-left:220px;">
    <!-- main-content -->
   <div class="main-content masked-relative masked" style="margin-left: -50px;">
      
						<div class="row" style="margin-left: 3%;">
							<!-- COLUMN 1 -->															
								  <div class="col-sm-3 col-md-3 stat">
									 <div class="dashbox shad panel panel-default db-blue">
										<div class="panel-body">
										   <div class="panel-left red">
												<i class="fa fa-calendar-o fa-3x"></i>
										   </div>
										   <div class="panel-right">
										     <div id="odometer1" class="odometer" style="font-size: 25px;">   </div><p></p>
                        				<span class="chamado"><?php echo __('Tickets','dashboard'); ?></span><br>
                        				<span class="date"><b><?php echo __('Today','dashboard'); ?></b></span>												
										   </div>
										</div>
									 </div>
								  </div>
								  
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox shad panel panel-default db-green">
										<div class="panel-body">
										   <div class="panel-left blue">
												<i class="fa fa-calendar fa-3x fa-calendar-index"></i>
										   </div>
										   <div class="panel-right">										 
											<div id="odometer2" class="odometer" style="font-size: 25px;">   </div><p></p>
                        				<span class="chamado"><?php echo __('Tickets','dashboard'); ?></span><br>
                        				<span class="date"><b><?php echo $mes ?></b></span>
										   </div>
										</div>
									 </div>
								  </div>																		
                     								
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox shad panel panel-default db-red">
										<div class="panel-body">
										   <div class="panel-left yellow">
												<i class="fa fa-plus-square fa-3x"></i>
										   </div>
										   <div class="panel-right">
												<div id="odometer3" class="odometer" style="font-size: 25px;">   </div><p></p>
                        				<span class="chamado"><?php echo __('Tickets','dashboard'); ?></span><br>
                        				<span class="date"><b><?php echo __('Total','dashboard'); ?></b></span>
										   </div>										   
										</div>
									 </div>
								  </div>
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox shad panel panel-default db-orange">
										<div class="panel-body">
										   <div class="panel-left green">
												<i class="fa fa-users fa-3x"></i>
										   </div>
								   		<div class="panel-right">
												<div id="odometer4" class="odometer" style="font-size: 25px;">   </div><p></p>
                        				<span class="chamado"><?php echo __('users','dashboard'); ?></span><br>
                        				
										   </div>
										</div>
									 </div>
								  </div>																	                          				                           							
						</div>        
                
<div class="container-fluid">            
  
<script type="text/javascript" >
window.odometerOptions = {
   format: '( ddd).dd'
};

setTimeout(function(){
    odometer1.innerHTML = <?php echo $total_hoje['total']; ?>;
    odometer2.innerHTML = <?php echo $total_mes['total']; ?>;
    odometer3.innerHTML = <?php echo $total_ano['total']; ?>;
    odometer4.innerHTML = <?php echo $total_users['total']; ?>;
}, 1000);

</script> 

<div id='content' class="container-fluid" style="margin-left:55px;"> 
 
<!-- <div id="pad-wrapper"> -->

	<div class="row-fluid" style="margin-top: 30px;" >
      <h4> <?php echo __('Tickets Evolution','dashboard'); ?> </h4>
      <p id="choices" style="float:right; width:600px; margin-right: 0px; margin-top: 5px; text-align:right;"></p>	  	

		<div class="demo-container">
			<div id="graflinhas1" class="demo-placeholder col-sm-12 col-md-12" style="float:left;"></div>		
		</div>
	</div>

	   <?php 
			include ("graficos/inc/index/graflinhas_index_sel.inc.php");
		?>					
	
	<div class="row-fluid" style="margin-top: 75px;">	
		<div class="col-sm-6 col-md-6 knob-wrapper">
			<div id="pie1" style="height:320px;"> 			
				<?php
					include ("graficos/inc/index/grafpie_index.inc.php");
				?> 	 						            
			</div> 
		</div>

		<div class="col-sm-6 col-md-6 knob-wrapper" style="margin-bottom: 35px;">
			<div id="graf7" style="height:320px;"> 
				<?php
					include ("graficos/inc/index/grafcol_setedias.inc.php");
				?> 	 				              
			</div> 
  		</div>      
   </div>   
  	
	<div class="row-fluid" style="margin-top: 110px;">	
		<div class="col-sm-6 col-md-6 knob-wrapper">
			<div id="graf9" style="height:320px;"> 			
				<?php
					include ("graficos/inc/index/grafbar_age.inc.php");
				?> 	 						            
			</div> 
		</div>

		<div class="col-sm-6 col-md-6 knob-wrapper">
			<div id="graf8" style="height:320px;"> 
				<?php
					include ("graficos/inc/index/grafpie_time.inc.php");
				?> 	 				              
			</div> 
  		</div>      
   </div> 

<div class="row" style="margin-top: 40px;">
	
	<div id="last_tickets" class="col-sm-6 col-md-6" style="margin-top:50px;"> 
 	 				              
		      <div class="widget widget-table action-table striped">
            <div class="widget-header"> <i class="fa fa-list-alt" style="margin-left:7px;"></i>

              <h3><a href="../../../front/ticket.php" target="_blank" style="color: #525252;"><?php echo __('Last Tickets','dashboard'); ?></a></h3>
             
            </div>
            <!-- /widget-header -->
            <div class="widget-content" style="height:322px;">
            <?php

		// last tickets
			$status = "('5','6')"	;	         
                        
            $query_wid = "
            SELECT glpi_tickets.id AS id, glpi_tickets.name AS name
				FROM glpi_tickets
				WHERE glpi_tickets.is_deleted = 0
				AND glpi_tickets.status NOT IN $status
				".$entidade."
				ORDER BY id DESC
				LIMIT 10 ";
            
            $result_wid = $DB->query($query_wid);			            
            
            ?>    
              <table id="last_tickets" class="table table-hover table-bordered table-condensed" >
              <th style="text-align: center;"><?php echo __('Tickets','dashboard'); ?></th><th style="text-align: center;" ><?php echo __('Title','dashboard'); ?></th>
              
				<?php
					while($row = $DB->fetch_assoc($result_wid)) 
					{					
						echo "<tr><td style='text-align: center;'><a href=../../../front/ticket.form.php?id=".$row['id']." target=_blank style='color: #526273;'>".$row['id']."</a>
						</td><td>". substr($row['name'],0,60)."</td></tr>";											
					}				
				?>                                       
              </table>
              
            </div>
            <!-- /widget-content --> 
          </div>
	</div> 

<!--  open tickets by tech-->

	<div id="open_tickets" class="col-sm-6 col-md-6 " style="margin-top:50px;"> 
	
		<div class="widget widget-table action-table striped">
            <div class="widget-header"> <i class="fa fa-list-alt" style="margin-left:7px;"></i>

           		<h3><a href="../../../front/ticket.php" target="_blank" style="color: #525252;"><?php echo __('Open Tickets','dashboard'). " " .__('by Technician','dashboard') ?></a></h3>
           
            </div>
            <!-- /widget-header -->
            
            <div class="widget-content" style="height:322px;">
            <?php
                                
            $query_tec = "
            SELECT DISTINCT glpi_users.id AS id, glpi_users.`firstname` AS name, glpi_users.`realname` AS sname, count(glpi_tickets_users.tickets_id) AS tick
				FROM `glpi_users` , glpi_tickets_users, glpi_tickets
				WHERE glpi_tickets_users.users_id = glpi_users.id
				AND glpi_tickets_users.type = 2
				AND glpi_tickets.is_deleted = 0
				AND glpi_tickets.id = glpi_tickets_users.tickets_id
				AND glpi_tickets.status NOT IN ".$status."
				".$entidade."
				GROUP BY `glpi_users`.`firstname` ASC
				ORDER BY tick DESC
				LIMIT 10 ";
            
            $result_tec = $DB->query($query_tec);			            
            
            ?>    
              <table id="open_tickets" class="table table-hover table-bordered table-condensed" >
              <th style="text-align: center;"><?php echo __('Technician','dashboard'); ?></th><th style="text-align: center;">
              <?php echo __('Open Tickets','dashboard'); ?></th>
              
				<?php
					while($row = $DB->fetch_assoc($result_tec)) 
					{					
						echo "<tr><td><a href=./reports/rel_tecnico.php?con=1&tec=".$row['id']."&stat=open target=_blank style='color: #526273;'>
						".$row['name']." ".$row['sname']."</a></td><td style='text-align: center;' >".$row['tick']."</td></tr>";											
					}				
				?>                                       
              </table>
              
            </div>
            <!-- /widget-content --> 
          </div>
       </div>   
</div>

<div class="row ">


<div id="events" class="col-sm-6 col-md-6 " style="margin-top:35px;"> 
 	 				              
		      <div class="widget widget-table action-table striped">
            <div class="widget-header"> <i class="fa fa-list-alt" style="margin-left:7px;"></i>

              <h3><a href="../../../front/event.php" target="_blank" style="color: #525252;"><?php echo __('Last Events','dashboard'); ?></a></h3>

            </div>
            <!-- /widget-header -->
            <div class="widget-content">
   
				<?php
				
				$query_evt = "
				SELECT *
				FROM `glpi_events`
				ORDER BY `glpi_events`.id DESC
				LIMIT 10 ";   
					
				$result_evt = $DB->query($query_evt);
				$number = $DB->numrows($result_evt);
				
				function tipo($type) {
				
				    switch ($type) {
				    case "system": $type 	  = __('System'); break;
				    case "ticket": $type 	  = __('Ticket'); break;
				    case "devices": $type 	  = _n('Component', 'Components', 2); break;
				    case "planning": $type 	  = __('Planning'); break;
				    case "reservation": $type = _n('Reservation', 'Reservations', 2); break;
				    case "dropdown": $type 	  = _n('Dropdown', 'Dropdowns', 2); break;
				    case "rules": $type 	  = _n('Rule', 'Rules', 2); break;
				   };
					return $type;
					}
				
				
				function servico($service) {
				
				    switch ($service) {
				    case "inventory": $service 	  = __('Assets'); break;
				    case "tracking": $service 	  = __('Ticket'); break;
				    case "maintain": $service 	  = __('Assistance'); break;
				    case "planning": $service  	  = __('Planning'); break;
				    case "tools": $service 	  	  = __('Tools'); break;
				    case "financial": $service 	  = __('Management'); break;
				    case "login": $service 	         = __('Connection'); break;
				    case "setup": $service 	  	  = __('Setup'); break;
				    case "security": $service 	  = __('Security'); break;
				    case "reservation": $service     = _n('Reservation', 'Reservations', 2); break;
				    case "cron": $service 	  	  = _n('Automatic action', 'Automatic actions', 2); break;
				    case "document": $service 	  = _n('Document', 'Documents', 2); break;
				    case "notification": $service    = _n('Notification', 'Notifications', 2); break;
				    case "plugin": $service 	  = __('Plugin'); break;
				   }
				
					return $service;
					}
					     ?>    
				          <table id="events" class="table table-hover table-bordered table-condensed" >
				            <th style="text-align: center;"><?php echo __('Type'); ?></th>
								<th style="text-align: center;"><?php echo __('Date'); ?></th>
								<!-- <th style="text-align: center;"><?php echo __('Service'); ?></th>  -->
								<th style="text-align: center;"><?php echo __('Message'); ?></th>                 
								<?php
								
									 switch ($_SESSION['glpidate_format']) {
								    case "0": $dataf = 'Y-m-d'; break;
								    case "1": $dataf = 'd-m-Y'; break;
								    case "2": $dataf = 'm-d-Y'; break;    
								    } 								
								
							   $i = 0;	
							   while ($i < $number) {
							   
								  $type     = $DB->result($result_evt, $i, "type");
			       			  $date     = date_create($DB->result($result_evt, $i, "date"));
						        // $service  = $DB->result($result_evt, $i, "service");         							        
						        $message  = $DB->result($result_evt, $i, "message");
								
								echo "<tr><td style='text-align: left;'>". tipo($type) ."</td>
										<td style='text-align: left;'>" . date_format($date, $dataf.' H:i:s') . "</td>					
										<td style='text-align: left;'>". substr($message,0,50) ."</td></tr>
								";
								++$i;													
								}												
						?>                                       
              </table>  
              
            </div>
            <!-- /widget-content --> 
          </div>
	</div>


	<div id="logged_users" class="col-sm-6 col-md-6 " style="margin-top:35px;"> 
 	 				              
		 <div class="widget widget-table action-table">
            <div class="widget-header"> <i class="fa fa-group" style="margin-left:7px;"></i>
				<?php
				//logged users
				
				//$path = "../../../files/_sessions/";				
				$path = GLPI_SESSION_DIR . '/' ;
				$diretorio = opendir($path);        
				
				$arr_arq = array();
				$arquivos = array();    
				       
				while($arquivo = readdir($diretorio)){   
				      
				     $arr_arq[] = $path.$arquivo;           
				 }
				 //  $diretorio -> close();
				
				
				foreach ($arr_arq as $listar) {
				// retira "./" e "../" para que retorne apenas pastas e arquivos
								  
				   if ( is_file($listar) && $listar != '.' && $listar != '..'){ 
							$arquivos[]=$listar;
				   }
				}
				
				$conta = count($arquivos);
				
				if($conta > 0) {
				
				for($i=0; $i < $conta; $i++) {
				
					$file = $arquivos[$i];
					
					$string = file_get_contents( $file ); 
					// poderia ser um string ao invés de file_get_contents().
					
					$list = preg_match( '/glpiID\|s:[0-9]:"(.+)/', $string, $matches );
					
					$posicao = strpos($matches[0], 'glpiID|s:');
					$string2 = substr($matches[0], $posicao, 25);
					$string3 = explode("\"", $string2); 
					
					$arr_ids[] = $string3[1];
					
					}
				}
				
				$ids = array_values($arr_ids);
				$ids2 = implode("','",$ids);
				
				$query_name = 
				"SELECT firstname AS name, realname AS sname, id AS uid, name AS glpiname 
				FROM glpi_users				
				WHERE id IN ('".$ids2."')
				ORDER BY name"; 
				
				$result_name = $DB->query($query_name); 
				$num_users = $DB->numrows($result_name);          
				            
				?>    
           <h3><?php echo __('Logged Users','dashboard')."  :  " .$num_users; ?></h3>

            </div>
            <!-- /widget-header -->
            
				<?php
	          if($num_users <= 10) {
	          	echo '<div class="widget-content striped" style="min-height:318px;">'; }
	          else {
	          	echo '   <div class="widget-content striped ">'; }	          	
				?>        
              <table id="logged_users" class="table table-hover table-bordered table-condensed" >                         
				<?php
								
				while($row_name = $DB->fetch_assoc($result_name)) 
	  			   {
						echo "<tr>
									<td style='text-align: left;'><a href=../../../front/user.form.php?id=".$row_name['uid']." target=_blank style='color: #526273;'>
										".$row_name['name']." ".$row_name['sname']." (".$row_name['uid'].")</a>	
									</td>									
								</tr>";												
					}	

				?>                                       
              </table>
              
            </div>
            <!-- /widget-content --> 
          </div>
	</div>                    		
          <!-- content row 2 --> 
	<!-- </div> -->                    
   </div>       
	</div> 
 
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

 <!-- Remove below two lines in production --> 
 
 <script src="js/theme-options.js"></script>       
 <script src="js/core.js"></script>
</body>
</html>
