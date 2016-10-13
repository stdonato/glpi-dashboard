<?php
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", "r");

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

if($theme == '') {
	$theme = 'skin-default.css';
}

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
?>

<!DOCTYPE html>
<html>
<head>
    <title>GLPI - Dashboard - Home</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="refresh" content= "120"/> -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" href="img/dash.ico" type="image/x-icon" />
	 <link rel="shortcut icon" href="img/dash.ico" type="image/x-icon" />    
    <link href="css/bootstrap.css" rel="stylesheet">
 
 	 <!-- <script src="js/skin.js" type="text/javascript"></script> -->

    <!-- Styles -->   
    <!-- Color theme -->         
 	 <?php echo '<link rel="stylesheet" type="text/css" title="skin-default" href="./css/'.$theme.'">'; ?>     
		   
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <link rel="stylesheet" type="text/css" href="css/elements.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">

    <!-- Custom Styles -->
    <link href="css/custom.css" rel="stylesheet">
    
     <!-- this page specific styles -->
    <link rel="stylesheet" href="css/compiled/index.css" type="text/css" media="screen" />    

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- lato font -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/style-dash.css" rel="stylesheet" type="text/css" />
    <link href="css/dashboard.css" rel="stylesheet" type="text/css" />
    <link href="less/style.less" rel="stylesheet"  title="lessCss" id="lessCss">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
     <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
     <![endif]-->
     <script src="js/jquery.js"></script>
     <script src="js/jquery-ui-1.10.2.custom.min.js"></script>

<script type="text/javascript">
$(function($) {
var options = {
timeNotation: '24h',
am_pm: false,
fontFamily: 'Open Sans',
fontSize: '11pt',
foreground: '#FFF'
}
$('#clock').jclock(options);
});


function autoResize(id)
{ 
var newheight; var newwidth;

	if(document.getElementById){
	    newheight=document.getElementById(id).contentWindow.document.body.scrollHeight;
	    newwidth=document.getElementById(id).contentWindow.document.body.scrollWidth;
		}
		document.getElementById(id).height= (newheight) + "px";
		document.getElementById(id).width= (newwidth) + "px"; 
}

</script>  

<base target="iframe1" /> 

</head>
        <body style="background-color: #FFF;" >

            <div class="site-holder">
                <!-- .navbar -->
                <nav class="navbar  navbar-default nav-delighted" role="navigation">
                    <a href="#" class="toggle-left-sidebar">
                        <i class="fa fa-th-list"></i>
                    </a>

                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <a class="navbar-brand" href="<?php echo $CFG_GLPI['url_base'].'/front/ticket.php';?>" target="_blank">
                            <span>GLPI</span></a>
                    </div>
					<!-- NAVBAR LEFT  -->
					
					<ul id="navbar-left" class="nav navbar-nav pull-left hidden-xs">
					    <li class="dropdown">
					        <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="margin-top: 6px;">           
					            <span class="name" style="color:#FFF; font-size:14pt;">
					                <?php echo __('Tickets Statistics','dashboard'); ?>  
					            </span>            
					        </a>
					        <ul class="dropdown-menu skins">
					            <li class="dropdown-title"></li>
					        </ul>
					    </li>
					</ul>
					<!-- /NAVBAR LEFT -->
					
					<ul class="nav navbar-nav pull-right hidden-xs">
						<li id="header-user" class="dropdown user">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color:#FFF; font-size:11pt; margin-top:5px;">							

							<span class="username">
							
							<script type="text/javascript">
							var d_names = <?php echo '"'.$dia.'"' ; ?>;
							var m_names = <?php echo '"'.$mes.'"' ; ?>;
							
							var d = new Date();
							var curr_day = d.getDay();
							var curr_date = d.getDate();
							var curr_month = d.getMonth();
							var curr_year = d.getFullYear();
							
							document.write(d_names + ", " + curr_date + " " + m_names + " " + curr_year );
							
							</script> 
							</span>
							<span id="clock"></span>							
							
							</a>
						</li>
					</ul>                    
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
                    <div class="box-holder" style="background-color:#e5e5e5;">

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
                                <ul class="nav  nav-list">
                                
                                  <li class=' '>
                                    <a id="dash" href='./main.php' data-original-title='Dashboard'>
                                        <i class='fa fa-home'></i>
                                        <span class='hidden-minibar'>Dashboard</span>
                                    </a>
                                    <li class='submenu'>
                                    <a class='dropdown' onClick='return false;' href='main.php' data-original-title='Chamados'>
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
                                </li>
                                                               
											<li class='submenu  '>
                                    <a class='dropdown' onClick='return false;' href='main.php' data-original-title='Relatórios'>
                                        <i class='fa fa-list-alt'></i>
                                        <span class='hidden-minibar'><?php echo __('Reports','dashboard'); //<b class="caret"></b>?>
                                            <i class='fa fa-chevron-right  pull-right'></i>
                                        </span>
                                    </a>
													<ul  class="animated fadeInDown">
                                        <li class=' '>
                                            <a id="tec" href="./reports/rel_tecnico.php" data-original-title=' por Técnico' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Technician','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                             <a href="./reports/rel_usuario.php" data-original-title=' por Usuário' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Requester','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./reports/rel_grupo.php" data-original-title=' por Grupo' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?> </span>
                                            </a>
                                        </li> 
                                        <li class=' '>
                                              <a href="./reports/rel_entidade.php" data-original-title=' por Entidade' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./reports/rel_localidade.php" data-original-title=' by Localization' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Location','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./reports/rel_categoria.php" data-original-title=' por Categoria' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Category','dashboard'); ?> </span>
                                            </a>
                                        </li> 
      											<li class=' '>
                                              <a href="./reports/rel_data.php" data-original-title=' por Data' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Date','dashboard'); ?> </span>
                                            </a>
                                        </li>
                                       <li class=' '>
                                            <a href="./reports/rel_sla.php" data-original-title=' por SLA' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by SLA','dashboard'); ?> </span>
                                            </a>
                                        </li>  
                                        <li class=' '>
                                              <a href="./reports/rel_tarefa.php" data-original-title=' Tarefas' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _n('Task','Tasks',2); ?> </span>
                                            </a>
                                        </li> 
													 <li class=' '>
                                              <a href="./reports/rel_tickets.php" data-original-title=' Tickets' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _n('Ticket','Tickets',2); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./reports/rel_assets.php" data-original-title=' Tickets' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Assets'); ?></span>
                                            </a>
                                        </li>                              
                                    </ul>                                    
                                </li>			                                
                                
                                <li class='submenu'>
                                    <a class='dropdown' onClick='return false;' href='main.php' data-original-title='Gráficos'>
                                        <i class="fa fa-bar-chart-o"></i>
                                        <span class='hidden-minibar'><?php echo __('Charts','dashboard'); ?>
                                            <i class='fa fa-chevron-right  pull-right'></i>
                                        </span>
                                    </a>
                                    <ul  class="animated fadeInDown">
                                         <li class=' '>
                                              <a href="./graficos/geral.php" data-original-title=' Geral' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Overall','dashboard'); ?></span>
                                            </a>
                                        </li>
                                          <li class=' '>
                                              <a href="./graficos/tecnicos.php" data-original-title=' Técnicos' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Technician','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graficos/usuarios.php" data-original-title=' Usuários' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Requester','dashboard'); ?></span>
                                            </a>
                                        </li>
                                          <li class=' '>
                                              <a href="./graficos/grupos.php" data-original-title=' Grupos' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Group','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graficos/entidades.php" data-original-title=' Entidades' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Entity','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graficos/ativos.php" data-original-title=' Ativos' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Assets'); ?></span>
                                            </a>
                                        </li>
                                         <li class=' '>
                                              <a href="./graficos/satisfacao.php" data-original-title=' Satisfação' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Satisfaction','dashboard'); ?></span>
                                            </a>
                                        </li>
								 					 <li class=' '>
                                              <a href="./graficos/graf_tecnico.php" data-original-title=' por Técnico' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Technician','dashboard'); ?></span>
                                            </a>
                                        </li>
                                         <li class=' '>
                                              <a href="./graficos/graf_usuario.php" data-original-title=' por Usuário' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Requester','dashboard'); ?></span>
                                            </a>
                                        </li>
													 <li class=' '>
                                              <a href="./graficos/graf_grupo.php" data-original-title=' por Grupo' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?></span>
                                            </a>
                                        </li>
  													 <li class=' '>
                                              <a href="./graficos/graf_entidade.php" data-original-title=' por Entidade' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graficos/graf_localidade.php" data-original-title=' por Localidade' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Location','dashboard'); ?></span>
                                            </a>
                                        </li>
                                         <li class=' '>
                                              <a href="./graficos/geral_mes.php" data-original-title=' por Data' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Date','dashboard'); ?></span>
                                            </a>
                                        </li>
                                       <li class=' '>
                                              <a href="./graficos/slas.php" data-original-title=' por SLA' target="iframe1">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by SLA','dashboard'); ?></span>
                                            </a>
                                        </li>
                           
                                    </ul>
                                </li><!-- delighted pages -->  

									     <li class=' '>
                                    <a href='assets/assets.php' target="iframe1" data-original-title='Assets'>
                                        <i class='fa fa-desktop'></i>
                                        <span class='hidden-minibar'><?php echo __('Assets'); ?>
                                        </span>
                                    </a>  
                                </li>
                                
                                <li class=' '>
                                    <a href='info.php' target="iframe1" data-original-title='Info'>
                                        <i class='fa fa-info-circle'></i>
                                        <span class='hidden-minibar'>Info
                                        </span>
                                    </a>  
                                </li>
                                
                                <li class=' '>
                                    <a href='config.php' target="iframe1" data-original-title='Config'>
                                        <i class='fa fa-gears'></i>
                                        <span class='hidden-minibar'><?php echo __('Setup'); ?>
                                        </span>
                                    </a>  
                                </li>
                                
				 <li>
              <?php

              //version check	              								              								
					$ver = explode(" ",implode(" ",plugin_version_dashboard())); 																																																			
					$urlv = "http://a.fsdn.com/con/app/proj/glpidashboard/screenshots/".$ver[1].".png";
					$headers = get_headers($urlv, 1);										

					if($headers[0] != '') {
					//if ($headers[0] == 'HTTP/1.1 200 OK') { }
					if ($headers[0] == 'HTTP/1.0 404 Not Found') {
						echo '<a href="https://sourceforge.net/projects/glpidashboard/files/?source=navbar" target="_blank">
					 	<i class="fa fa-refresh"></i>                   
                    <span>'. __('New version','dashboard').'</span>
                     <span>'.__('avaliable','dashboard').'</span></a>';		
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
        
echo '<h5 class="label1 label-default"> <i class="fa fa-info-circle"></i>  Server Info</h5>
	
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
    		<div class="progress-bar progress-striped '.$corm.' " style="width: '.$umem.'%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="'.$umem.'" role="progressbar"></div>
		</div>
			</span>		
		</li>

		<li class="data-row">
			<span class="data-name" >DISK:</span>
			<span class="data-value">'; include './sh/df.php'; 

echo '<div class="progress" style="height: 5px;">
    		<div class="progress-bar  progress-striped '.$cord.'" style="width: '.$udisk.'%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="'.$udisk.'" role="progressbar"></div>
		</div>
			</span>			
		</li>		
		
		   <li class="data-row">
			<span class="data-name" >LOAD:</span>
			<span class="data-value">'; include './sh/load.php'; 

echo '<div class="progress" style="height: 5px;">
    		<div class="progress-bar  progress-striped '.$corl.'" style="width: '.$load.'%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="'.$load.'" role="progressbar"></div>
		</div>
			</span>			
		</li>
		
	</ul>';  
}
?>	                              
</div>
<!-- /.left-sidebar -->
<iframe onload="autoResize('iframe1')" id="iframe1" name="iframe1" class="iframe1" style="background-color:#e5e5e5; float:right;" sandbox="allow-same-origin allow-scripts allow-forms allow-top-navigation" src="main.php" frameborder="0" scrolling="no"></iframe>
<!-- /.site-holder -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- Include all compiled plugins (below), or include individual files as needed -->

 <!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/less-1.5.0.min.js"></script>
<script src="js/jquery.storage.js"></script>        
<script src="js/jquery.accordion.js"></script>
<script src="js/bootstrap-typeahead.js"></script>                
<script src="js/bootstrap-progressbar.js"></script>
<script src="js/galaxy/hovermenu.js" charset="utf-8"></script>
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="js/jquery.easy-pie-chart.js"></script> 
<script src="js/bootstrap-switch.js"></script>
<script src="js/jquery.address-1.6.min.js"></script>
<script src="js/highcharts.js" type="text/javascript" ></script>
<script src="js/highcharts-3d.js" type="text/javascript" ></script>
<script src="js/modules/exporting.js" type="text/javascript" ></script>
<script src="js/themes/grid.js" type="text/javascript" ></script>

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

<!-- odometer -->
<link href="css/odometer.css" rel="stylesheet">
<script src="js/odometer.js"></script>

 <script>
     $('document').ready(function(){
         $("[name='my-checkbox']").bootstrapSwitch();
     });
 </script>
 
 <script type="text/javascript">

 $(document).ready(function() {
            $("a").click(function(ev) {                
                var href = $(this).attr('href');
                if (href != './tickets/chamados.php' && href !== './tickets/select_ent.php' && href !== './tickets/select_grupo.php' && href !== './map/index.php') {                	                	                
	                $('#iframe1').attr('src', href);    
	                //ev.preventDefault(); 
	             }
	            });	             
        });

</script>


 <!-- Remove below two lines in production --> 
 
 <script src="js/theme-options.js"></script>       
 <script src="js/core.js"></script>
</body>
</html>