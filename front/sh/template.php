<?php
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/inc/config.php");

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);

# entity in index
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '' || $sel_ent == -1) {
	$sel_ent = 0;
	$ent_name = __('Tickets Statistics','dashboard');
}

else {
	$query = "SELECT name FROM glpi_entities WHERE id = ".$sel_ent."";
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

if($theme == '') {
	$theme = 'skin-default1.css';
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
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
 

    <!-- Styles -->   
    <!-- Color theme -->      
    <!-- <link href="css/skin-default.css" rel="stylesheet"> -->
 	 <?php echo '<link rel="stylesheet" type="text/css" title="currentStyle" href="css/skin-default1.css">'; ?>     

<!-- <link rel="stylesheet" type="text/css" title="currentStyle" href="css/skin-default1.css"> -->		
				   
	 <link href="css/styles1.css" rel="stylesheet" type="text/css" />
    <link href="css/layout11.css" rel="stylesheet" type="text/css" >    
    <link href="css/elementss.css" rel="stylesheet" type="text/css" >
    <link href="css/icons.css" rel="stylesheet" type="text/css" >

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
    <link href="css/style-dash.css" rel="stylesheet" type="text/css" />
    <link href="css/dashboard.css" rel="stylesheet" type="text/css" />
   <!-- <link href="less/style.less" rel="stylesheet"  title="lessCss" id="lessCss"> -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
     <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
     <![endif]-->
<script src="js/jquery.js"></script>
     
<link href="./inc/select2/select2.css" rel="stylesheet" type="text/css">
<script src="./inc/select2/select2.js" type="text/javascript" language="javascript"></script>

<script src="./js/bootstrap-datepicker.js"></script>
<link href="./css/datepicker.css" rel="stylesheet" type="text/css">
<link href="./less/datepicker.less" rel="stylesheet" type="text/css">

<script src="./js/media/js/jquery.dataTables.min.js"></script>
<link href="./js/media/css/dataTables.bootstrap.css" type="text/css" rel="stylesheet" />  
<script src="./js/media/js/dataTables.bootstrap.js"></script> 
<link href="./js/extensions/TableTools/css/dataTables.tableTools.css" type="text/css" rel="stylesheet" />
<script src="./js/extensions/TableTools/js/dataTables.tableTools.js"></script>

<style type="text/css" >	
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
	a:link, a:visited, a:active { text-decoration: none; }
	a:hover { color: #000099; }
	body {background-color: #e5e5e5;}
</style>       

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
</script> 
</head>
        <body>

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
					                <?php echo $ent_name; ?>  
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
                                <ul class="nav  nav-list">
                                
                                  <li class=' '>
                                    <a href='./index.php' data-original-title='Dashboard'>
                                        <i class='fa fa-home'></i>
                                        <span class='hidden-minibar'>Dashboard</span>
                                    </a>
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
                                </li>
                                                               
											<li class='submenu  '>
                                    <a class='dropdown' onClick='return false;' href='#' data-original-title='Relatórios'>
                                        <i class='fa fa-list-alt'></i>
                                        <span class='hidden-minibar'><?php echo __('Reports','dashboard'); //<b class="caret"></b>?>
                                            <i class='fa fa-chevron-right  pull-right'></i>
                                        </span>
                                    </a>
													<ul  class="animated fadeInDown">
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
                                              <a href="./reports/rel_grupo.php" data-original-title=' por Grupo' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?> </span>
                                            </a>
                                        </li> 
                                        <li class=' '>
                                              <a href="./reports/rel_entidade.php" data-original-title=' por Entidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?> </span>
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
                                              <a href="./reports/rel_tarefa.php" data-original-title=' Tarefas' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _n('Task','Tasks',2); ?> </span>
                                            </a>
                                        </li> 
													 <li class=' '>
                                              <a href="./reports/rel_tickets.php" data-original-title=' Tickets' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo _n('Ticket','Tickets',2); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./reports/rel_assets.php" data-original-title=' Tickets' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Assets'); ?></span>
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
                                              <a href="./graphs/geral.php" data-original-title=' Geral' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Overall','dashboard'); ?></span>
                                            </a>
                                        </li>
                                          <li class=' '>
                                              <a href="./graphs/tecnicos.php" data-original-title=' Técnicos' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Technician','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graphs/usuarios.php" data-original-title=' Usuários' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Requester','dashboard'); ?></span>
                                            </a>
                                        </li>
                                          <li class=' '>
                                              <a href="./graphs/grupos.php" data-original-title=' Grupos' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Group','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graphs/entidades.php" data-original-title=' Entidades' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Entity','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graphs/ativos.php" data-original-title=' Ativos' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Assets'); ?></span>
                                            </a>
                                        </li>
                                         <li class=' '>
                                              <a href="./graphs/satisfacao.php" data-original-title=' Satisfação' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('Satisfaction','dashboard'); ?></span>
                                            </a>
                                        </li>
								 					 <li class=' '>
                                              <a href="./graphs/graf_tecnico.php" data-original-title=' por Técnico' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Technician','dashboard'); ?></span>
                                            </a>
                                        </li>
                                         <li class=' '>
                                              <a href="./graphs/graf_usuario.php" data-original-title=' por Usuário' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Requester','dashboard'); ?></span>
                                            </a>
                                        </li>
													 <li class=' '>
                                              <a href="./graphs/graf_grupo.php" data-original-title=' por Grupo' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Group','dashboard'); ?></span>
                                            </a>
                                        </li>
  													 <li class=' '>
                                              <a href="./graphs/graf_entidade.php" data-original-title=' por Entidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Entity','dashboard'); ?></span>
                                            </a>
                                        </li>
                                        <li class=' '>
                                              <a href="./graphs/graf_localidade.php" data-original-title=' por Localidade' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Location','dashboard'); ?></span>
                                            </a>
                                        </li>
                                         <li class=' '>
                                              <a href="./graphs/geral_mes.php" data-original-title=' por Data' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by Date','dashboard'); ?></span>
                                            </a>
                                        </li>
                                       <li class=' '>
                                              <a href="./graphs/slas.php" data-original-title=' por SLA' target="_blank">
                                                <i class="fa fa-angle-right"></i>
                                                <span class='hidden-minibar'> <?php echo __('by SLA','dashboard'); ?></span>
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
                                    <a href='info.php' target="_blank" data-original-title='Info'>
                                        <i class='fa fa-info-circle'></i>
                                        <span class='hidden-minibar'>Info
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
		</li>	';	
		
}

?>	                              
 </div>
 <!-- /.left-sidebar -->

<!-- .content -->
<div class="content animated fadeInBig">
    <!-- main-content -->
   <div class="main-content masked-relative masked row-fluid">      					

<?php

if(!empty($_POST['submit']))
{
    $data_ini = $_POST['date1'];
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

function dropdown( $name, array $options, $selected=null )
{
    /*** begin the select ***/
    $dropdown = '<select id="sel1" style="width: 300px; autofocus onChange="javascript: document.form1.submit.focus()" name="'.$name.'" id="'.$name.'">'."\n";

    $selected = $selected;
    /*** loop over the options ***/
    foreach( $options as $key=>$option )
    {
        /*** assign a selected value ***/
        $select = $selected==$key ? ' selected' : null;

        /*** add each option to the dropdown ***/
        $dropdown .= '<option value="'.$key.'"'.$select.'>'.$option.'</option>'."\n";
    }

    /*** close the select ***/
    $dropdown .= '</select>'."\n";

    /*** and return the completed dropdown ***/
    return $dropdown;
}


function time_ext($solvedate)
{

// 1 Day 6 Hours 50 Minutes 31 Seconds ~ 111031 seconds
$time = $solvedate; // time duration in seconds

 if ($time == 0){
        return '';
    }

$days = floor($time / (60 * 60 * 24));
$time -= $days * (60 * 60 * 24);

$hours = floor($time / (60 * 60));
$time -= $hours * (60 * 60);

$minutes = floor($time / 60);
$time -= $minutes * 60;

$seconds = floor($time);
$time -= $seconds;

$return = "{$days}d {$hours}h {$minutes}m {$seconds}s"; // 1d 6h 50m 31s

return $return;

}

$sql_tec = "
SELECT DISTINCT glpi_users.`id` AS id , glpi_users.`firstname` AS name, glpi_users.`realname` AS sname
FROM `glpi_users` , glpi_tickets_users
WHERE glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets_users.type = 1
AND glpi_users.is_deleted = 0
AND glpi_users.is_active = 1
ORDER BY `glpi_users`.`firstname` ASC ";

$result_tec = $DB->query($sql_tec);
$tec = $DB->fetch_assoc($result_tec);

?>
<div id='content' >
<div id='container-fluid' style="margin: 0px 5% 0px 5%;">

<div id="charts" class="row-fluid chart" >
<div id="pad-wrapper" >
<div id="head" class="row-fluid">

<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:25px;"></i><span></span></a>

    <div id="titulo_graf"> <?php echo __('Tickets', 'dashboard') .'  '. __('by Requester', 'dashboard') ?>  </div>

    <div id="datas-tec" class="col-md-12 row-fluid" >
    <form id="form1" name="form1" class="form_rel" method="post" action="template.php?con=1">
	    <table border="0" cellspacing="0" cellpadding="3" bgcolor="#efefef">
	    <tr>
			<td style="width: 310px;">
			
			<?php
			$url = $_SERVER['REQUEST_URI'];
			$arr_url = explode("?", $url);
			$url2 = $arr_url[0];
			
			echo'
			<table style="margin-top:0px;" border=0>
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
			</td>

			<td style="margin-top:2px;">
			<?php
			
			// lista de técnicos
			$res_tec = $DB->query($sql_tec);
			$arr_tec = array();
			$arr_tec[0] = "-- ". __('Select a requester', 'dashboard') . " --" ;
			
			$DB->data_seek($result_tec, 0) ;
			
			while ($row_result = $DB->fetch_assoc($result_tec))
			    {
			    $v_row_result = $row_result['id'];
			    $arr_tec[$v_row_result] = $row_result['name']." ".$row_result['sname'] ;
			    }
			
			$name = 'sel_tec';
			$options = $arr_tec;
			$selected = 0;
			
			echo dropdown( $name, $options, $selected );									
			?>
			</td>
	</tr>
	<tr>
			<td height="15px"></td>
	</tr>
	<tr>
			<td colspan="2" align="center">
				<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Consult','dashboard'); ?> </button>
				<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" ><i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button>
			</td>
	</tr>

	</table>
	<?php Html::closeForm(); ?>
<!-- </form> -->
    </div>
    </div>
</div>

<script language="Javascript">
	$('#dp1').datepicker('update');
	$('#dp2').datepicker('update');
</script>

<?php

//tecnico2
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

if(!isset($_POST["sel_tec"])) {
	$id_tec = $_GET["tec"];
}

else {
	$id_tec = $_POST["sel_tec"];
}

if($id_tec == 0) {
	echo '<script language="javascript"> alert(" ' . __('Select a requester', 'dashboard') . ' "); </script>';
	echo '<script language="javascript"> location.href="rel_usuario.php"; </script>';
}

if($data_ini2 === $data_fin2) {
	$datas2 = "LIKE '".$data_ini2."%'";
}

else {
	$datas2 = "BETWEEN '".$data_ini2." 00:00:00' AND '".$data_fin2." 23:59:59'";
}

//status
$status = "";
$status_open = "('2','1','3','4')";
$status_close = "('5','6')";
$status_all = "('2','1','3','4','5','6')";


if(isset($_GET['stat'])) {

    if($_GET['stat'] == "open") {
        $status = $status_open;
        $stat = "open";
    }
    elseif($_GET['stat'] == "close") {
        $status = $status_close;
        $stat = "close";
    }
    else {
        $status = $status_all;
        $stat = "all";
    }
}

else {
        $status = $status_all;
        $stat = "all";
    }


// Chamados
$sql_cham =
"SELECT glpi_tickets.id AS id, glpi_tickets.name AS name, glpi_tickets.date AS date, glpi_tickets.solvedate as solvedate, glpi_tickets.status,
FROM_UNIXTIME( UNIX_TIMESTAMP( `glpi_tickets`.`solvedate` ) , '%Y-%m' ) AS date_unix, AVG( glpi_tickets.solve_delay_stat ) AS time
FROM `glpi_tickets_users` , glpi_tickets
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets_users.type = 1
AND glpi_tickets_users.users_id = ". $id_tec ."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status IN ".$status."
GROUP BY id
ORDER BY id DESC ";

$result_cham = $DB->query($sql_cham);


$consulta1 =
"SELECT glpi_tickets.id AS id, glpi_tickets.name, glpi_tickets.date AS adate, glpi_tickets.solvedate AS sdate,
FROM_UNIXTIME( UNIX_TIMESTAMP( `glpi_tickets`.`solvedate` ) , '%Y-%m' ) AS date_unix, AVG( glpi_tickets.solve_delay_stat ) AS time
FROM `glpi_tickets_users` , glpi_tickets
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets_users.type = 1
AND glpi_tickets_users.users_id = ". $id_tec ."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas2."
AND glpi_tickets.status IN ".$status."
GROUP BY id
ORDER BY id DESC
";

$result_cons1 = $DB->query($consulta1);
$conta_cons = $DB->numrows($result_cons1);

$consulta = $conta_cons;


if($consulta > 0) {

if(!isset($_GET['pagina'])) {
$primeiro_registro = 0;
$pagina = 1;

}
else {
    $pagina = $_GET['pagina'];
    $primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;
}


//abertos

$sql_ab = "SELECT count( glpi_tickets.id ) AS total, glpi_tickets_users.`users_id` AS id
FROM `glpi_tickets_users`, glpi_tickets
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets.date ".$datas2."
AND glpi_tickets_users.users_id = ".$id_tec."
AND glpi_tickets.status IN ".$status_open."
AND glpi_tickets.is_deleted = 0" ;

$result_ab = $DB->query($sql_ab) or die ("erro_ab");
$data_ab = $DB->fetch_assoc($result_ab);

$abertos = $data_ab['total'];

if($conta_cons > 0) {

//barra de porcentagem
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


//nome e total
$sql_nome = "
SELECT `firstname` , `realname`, `name`
FROM `glpi_users`
WHERE `id` = ".$id_tec."
";

$result_nome = $DB->query($sql_nome) ;

while($row = $DB->fetch_assoc($result_nome)){

	$user = $row['firstname'] ." ". $row['realname'];
	
	echo "
	
	<div class='well info_box row-fluid col-md-12' style='margin-top:25px; margin-left: -1px;'>
	
	<table class='row-fluid'  style='font-size: 18px; font-weight:bold;' cellpadding = 1px>
	<tr>
		<td style='vertical-align:middle;'> <span style='color: #000;'>".__('Requester', 'dashboard').": </span>  ". $row['firstname'] ." ". $row['realname']. "</td>
		<td style='vertical-align:middle;'> <span style='color: #000;'>".__('Tickets', 'dashboard').": </span>". $conta_cons ."</td>
		<td colspan='3' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'>".__('Period', 'dashboard') .": </span> " . conv_data($data_ini2) ." a ". conv_data($data_fin2)."
		</td>
		
		<td style='vertical-align:middle; width: 190px; '>
		<div class='progress' style='margin-top: 19px;'>
			<div class='progress-bar ". $cor ." progress-bar-striped active' role='progressbar' aria-valuenow='".$barra."' aria-valuemin='0' aria-valuemax='100' style='width: ".$barra."%;'>
    			".$barra." % ".__('Closed', 'dashboard') ."	
    		</div>		
		</div>		   
		</td>
	</tr>
	</table>
	
	<table align='right' style='margin-bottom:10px;'>
		<tr>
			<td>
				<button class='btn btn-primary btn-sm' type='button' name='abertos' value='Abertos' onclick='location.href=\"rel_usuario.php?con=1&stat=open&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('Opened', 'dashboard') ." </button>
				<button class='btn btn-primary btn-sm' type='button' name='fechados' value='Fechados' onclick='location.href=\"rel_usuario.php?con=1&stat=close&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('Closed', 'dashboard')." </button>	
				<button class='btn btn-primary btn-sm' type='button' name='todos' value='Todos' onclick='location.href=\"rel_usuario.php?con=1&stat=all&tec=".$id_tec."&date1=".$data_ini2."&date2=".$data_fin2."&npage=".$num_por_pagina."\"' <i class='icon-white icon-trash'></i> ".__('All', 'dashboard')." </button>
			</td>	
		</tr>
	</table>
	
	<table>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
	</table>
	
	<table id='users' class='display' style='font-size: 13px; font-weight:bold;' cellpadding = 2px>
		<thead>
			<tr>
				<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Tickets', 'dashboard') ." </th>
				<th>&nbsp;</th>
				<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Title', 'dashboard') ."</th>
				<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Opening date', 'dashboard') ."</th>
				<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Close date', 'dashboard') ."</th>
				<th style='text-align:center; color: #000; cursor:pointer;'> ". __('Resolution time') ."</th>
			</tr>
		</thead>
		<tbody>
	";
}

//listar chamados

while($row = $DB->fetch_assoc($result_cham)){

    $status1 = $row['status'];

    if($status1 == "1" ) { $status1 = "new";}
    if($status1 == "2" ) { $status1 = "assign";}
    if($status1 == "3" ) { $status1 = "plan";}
    if($status1 == "4" ) { $status1 = "waiting";}
    if($status1 == "5" ) { $status1 = "solved";}
    if($status1 == "6" ) { $status1 = "closed";}


    echo "
<tr>
	<td style='vertical-align:middle; text-align:center;'><a href=".$CFG_GLPI['root_doc']."/front/ticket.form.php?id=". $row['id'] ." target=_blank >" . $row['id'] . "</a> </td>
	<td style='vertical-align:middle;' align='center' ><img src=".$CFG_GLPI['root_doc']."/pics/".$status1.".png title='".Ticket::getStatus($row['status'])."' style=' cursor: pointer; cursor: hand;'/> </td>
	<td> ". substr($row['name'],0,75) ." </td>
	<td> ". conv_data_hora($row['date']) ." </td>
	<td> ". conv_data_hora($row['solvedate']) ." </td>
	<td> ". time_ext($row['time']) ."</td>
</tr>";
}

echo "</tbody>
		</table>
		</div>"; ?>

<script type="text/javascript" charset="utf-8">

$('#users')
	.removeClass( 'display' )
	.addClass('table table-striped table-bordered');

$(document).ready(function() {
    oTable = $('#users').dataTable({
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
                 "sMessage": "<div class='info_box row-fluid span12' style='margin-top:20px; margin-bottom:12px; margin-left: -1px;'><table class='row-fluid'  style='width: 80%; margin-left: 10%; font-size: 18px; font-weight:bold;' cellpadding = '1px'><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo __('Requester', 'dashboard'); ?> : </span><?php echo $user; ?> </td> <td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle;'><span style='color:#000;'> <?php echo  __('Tickets','dashboard'); ?> : </span><?php echo $consulta ; ?></td><td colspan='2' style='font-size: 16px; font-weight:bold; vertical-align:middle; width:200px;'><span style='color:#000;'> <?php echo  __('Period','dashboard'); ?> : </span> <?php echo conv_data($data_ini2); ?> a <?php echo conv_data($data_fin2); ?> </td> </table></div>"
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
	$(document).ready(function() { $("#sel1").select2(); });
</script>

</div>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- end content -->

</div>  
</div>
<!-- /.box-holder -->
</div>

<!-- /.site-holder -->

 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

 <!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
<!-- <script src="js/less-1.5.0.min.js"></script> -->
 
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

       <!-- Remove below two lines in production --> 
       
       <script src="js/theme-options.js"></script>       
       <script src="js/core.js"></script>
</body>
</html>
