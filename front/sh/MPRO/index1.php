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
    

//GLPI version
$version = substr($CFG_GLPI["version"],0,4);

//user image and name
if($version == "0.85") {
	$sql_photo = "SELECT picture 
					FROM glpi_users
					WHERE id = ".$_SESSION["glpiID"]." ";
	
	$res_photo = $DB->query($sql_photo);
	$pic = $DB->result($res_photo,0,'picture');
	
	$photo_url = User::getURLForPicture($pic);  
}   

//redirect tech profile
if(Session::haveRight("profile", "r"))
	{
		//$redir = '<meta http-equiv="refresh" content= "120"/>';
		$redir = '';
	}
else {		
		$redir = '<meta http-equiv="refresh" content="0; url=graphs/graf_tech.php?con=1" />'; 
	}
    

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
			$newversion = "<a href='https://forge.indepnet.net/projects/dashboard/files' target='_blank' style='margin-right: 12px; color:#fff;' class='blink_me'><i class='fa fa-refresh'></i><span>&nbsp;&nbsp;".  __('New version','dashboard'). " ". __( 'avaliable','dashboard'). " </span></a>";		
				}
			}     
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
    <link href="css/yamm.css" rel="stylesheet" type="text/css" />  
    
	<script src="js/jquery.js"></script>
	<script src="js/menu.js"></script>     
    
    <!-- odometer 
	<link href="css/odometer.css" rel="stylesheet">
	<script src="js/odometer.js"></script>  -->  
	
	
<script type="text/javascript">
	$(function($) {
		var options = {
		timeNotation: '24h',
		am_pm: false,
		fontFamily: 'Open Sans',
		fontSize: '10pt',
		foreground: '#FFF'
	}
		$('#clock1').jclock(options);
	});	
	
function setIframeHeight(iframe) {
    if (iframe) {
        var iframeWin = iframe.contentWindow || iframe.contentDocument.parentWindow;
        if (iframeWin.document.body) {
            iframe.height = iframeWin.document.documentElement.scrollHeight + 3000 || iframeWin.document.body.scrollHeight + 3000;
        }
    }
};

$(window).load(function () {
    setIframeHeight(document.getElementById('iframe1'));
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
</head>

	<?php
	if($theme == 'trans.css') {		
	   	echo "<body style=\"background: url('./img/".$back."') no-repeat top center fixed; \">";
   	}
	else {
   		echo "<body style='background-color: #FFF;'>";
   	}	 
   ?>

           <div class="site-holder">
		<!-- top -->
		<nav class="navbar navbar-default nav-delighted" role="navigation" >
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header" style="color:#fff;" >
                     <a class="navbar-brand hidden-xs" href="<?php echo $CFG_GLPI['url_base'].'/front/ticket.php';?>" target="_blank" style="width: 250px; margin-left: -45px;">

								<?php 
								if($version == "0.85") {	
									echo '<img src='. $photo_url .' alt="" title="Upload photo in user profile" class="avatar" style="margin-left: -8px;">';
								}
								else {									                     
	                     	echo "<span class='welcome' style='font-size:13px; margin-top:-15px; margin-left: 35px;'>". __('Welcome','dashboard'). " , </span>";
	                     }
	                     ?> 
	                     <span style="font-size:18px;"><?php echo $_SESSION["glpifirstname"]; ?></span>
	                   	<a href="<?php echo $CFG_GLPI['root_doc']; ?>/front/user.form.php?id=<?php echo $_SESSION['glpiID']; ?>" target="_blank" 
	                   	style="margin-left:0px; margin-top: 18px; margin-bottom:0px; color:#fff; font-size: 18px;"></a>
                   	</a>
                    </div>
					<!-- NAVBAR LEFT  -->					
					<div id="navbar-left" class="nav navbar-nav pull-left hidden-xs" style="margin-top: 20px;"> 					    
					        <a href="./index.php" style="margin-top:6px; margin-left: 30px;">           
					            <span class="name" style="color: #FFF; font-size:14pt;">
					                GLPI - <?php echo $ent_name; ?>
					            </span>            
					        </a>					    
					</div>
                								
					<!-- /NAVBAR LEFT -->					
					<ul class="nav navbar-nav pull-right hidden-xs">

						<li id="header-user" style="color:#FFF; font-size:10pt; margin-top:20px; margin-right:15px;">										
							<span class="username">								
								<?php echo $newversion; ?>						
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
							
						</li>
					</ul>     
			   <!-- /.navbar-collapse -->																																						               
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->                         
               </nav>

<!-- Demo navbar -->
    <div class="navbar yamm navbar-default nav-delighted" id="menuHeader"> 
    
        <div class="navbar-header" >
          <button id="btn-collapse" type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
        </div>    
    
        <div id="navbar-collapse-1" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
                   
          <li class="nav-link dropdown"><span onclick="location.href=('index.php')"><a href="#" data-toggle="dropdown" class="dropdown-toggle nav-icon"><i class='fa fa-dashboard'></i>&nbsp;Dashboard</a></span></li>
			 <li class="nav-link dropdown"><span onclick="window.open('./graphs/graf_tech.php?con=1','iframe1');"><a href="#" data-toggle="dropdown" class="dropdown-toggle nav-icon"><i class='fa fa-area-chart'></i>&nbsp;<?php echo __('My Dashboard','dashboard');?></a></span></li>
			 <!-- <li class="nav-link1"><a href="./graphs/graf_tech.php" target="iframe1" data-toggle="dropdown" class="dropdown-toggle" style="color:#fff;">Meu Painel</a></li> -->

           <!-- Classic dropdown -->
            <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="text-nav"><i class='fa fa-edit'></i>&nbsp;<?php echo __('Tickets','dashboard');?>&nbsp;<b class="caret"></b></span></a>
              <ul role="menu" class="dropdown-menu">
                <li><a tabindex="-1" href="./tickets/chamados.php" target="_blank"> <?php echo __('Overall','dashboard'); ?> </a></li>
                <li><a tabindex="-1" href="./tickets/select_ent.php" target="_blank"> <?php echo __('by Entity','dashboard'); ?> </a></li>
                <li><a tabindex="-1" href="./tickets/select_grupo.php" target="_blank">  <?php echo __('by Group','dashboard'); ?> </a></li>                
                <li><a tabindex="-1" href="./map/index.php" target="_blank"> <?php echo __('Map','dashboard'); ?> </a></li>
              </ul>
            </li>            
 
            <!-- Classic list -->
				<li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="text-nav"><i class='fa fa-list-alt'></i>&nbsp;<?php echo __('Reports','dashboard');?>&nbsp;<b class="caret"></b></span></a>
              <ul class="dropdown-menu">
                
                  <!-- Content container to add padding -->
                  <div class="yamm-content" style="width:400px;">
                    <div class="row">
                      <ul class="col-sm-2 list-unstyled" style="width:180px;">
                        <li>
                          <!-- <p><strong>Links Title</strong></p> -->
                        </li>
                        <li><a href="./reports/rel_tecnicos.php?con=1" target="iframe1" style="color:#555;"> <?php echo __('Technician')."s"; ?> </a></li>                        
                        <li><a href="./reports/rel_assets.php" target="iframe1" style="color:#555;"> <?php echo __('Assets'); ?> </a></li>
                        <li><a href="./reports/rel_tickets.php" target="iframe1" style="color:#555;"> <?php echo _n('Ticket','Tickets',2); ?> </a></li>
                        <li><a href="./reports/rel_tarefa.php" target="iframe1" style="color:#555;"> <?php echo _n('Task','Tasks',2)." - ". __('Technician'); ?> </a></li>
                        <li><a href="./reports/rel_tarefa_cham.php" target="iframe1" style="color:#555;"> <?php echo _n('Task','Tasks',2)." - ". __('Tickets','dashboard'); ?> </a></li>                        
                      </ul>

                      <ul class="col-sm-2 list-unstyled" style="width:180px;"> 
                        <li>
                          <!-- <p><strong>Links Title</strong></p> -->
                        <li><a href="./reports/rel_tecnico.php" target="iframe1" style="color:#555;"> <?php echo __('by Technician','dashboard'); ?> </a></li>
                        <li><a href="./reports/rel_usuario.php" target="iframe1" style="color:#555;"> <?php echo __('by Requester','dashboard'); ?> </a></li>
                        <li><a href="./reports/rel_entidade.php" target="iframe1" style="color:#555;"> <?php echo __('by Entity','dashboard'); ?> </a></li>
                        <li><a href="./reports/rel_grupo.php" target="iframe1" style="color:#555;"> <?php echo __('by Group','dashboard'); ?> </a></li>
                        <li><a href="./reports/rel_localidade.php" target="iframe1" style="color:#555;"> <?php echo __('by Location','dashboard'); ?> </a></li>
                        <li><a href="./reports/rel_categoria.php" target="iframe1" style="color:#555;"> <?php echo __('by Category','dashboard'); ?> </a></li>
                        <li><a href="./reports/rel_data.php" target="iframe1" style="color:#555;"> <?php echo __('by Date','dashboard'); ?> </a></li>
                        <li><a href="./reports/rel_sla.php" target="iframe1" style="color:#555;"> <?php echo __('by SLA','dashboard'); ?> </a></li>
                        </li>
     							
                      </ul>
                    </div>
                  </div>
                
              </ul>
            </li>            
            
            <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="text-nav"><i class='fa fa-bar-chart-o'></i>&nbsp;<?php echo __('Charts','dashboard');?>&nbsp;<b class="caret"></b></span></a>
              <ul class="dropdown-menu">                
                  <!-- Content container to add padding -->
                  <div class="yamm-content" style="width:400px;">
                    <div class="row">
                      <ul class="col-sm-2 list-unstyled" style="width:180px;">
                        <li>
                          <!-- <p><strong>Links Title</strong></p> -->
                        </li>
                        <li><a href="./graphs/geral.php" target="iframe1" style="color:#555;"> <?php echo __('Overall','dashboard'); ?></a></li>
                        <li><a href="./graphs/tecnicos.php" target="iframe1" style="color:#555;"> <?php echo __('Technician','dashboard'); ?> </a></li>
                        <li><a href="./graphs/usuarios.php" target="iframe1" style="color:#555;"> <?php echo __('Requester','dashboard'); ?> </a></li>
                        <li><a href="./graphs/entidades.php" target="iframe1" style="color:#555;"> <?php echo __('Entity','dashboard'); ?> </a></li>
                        <li><a href="./graphs/categorias.php" target="iframe1" style="color:#555;"> <?php echo __('Category'); ?> </a></li>
                        <li><a href="./graphs/grupos.php" target="iframe1" style="color:#555;"> <?php echo __('Group','dashboard'); ?> </a></li>
                        <li><a href="./graphs/local.php" target="iframe1" style="color:#555;"> <?php echo __('Location'); ?> </a></li>
                        <li><a href="./graphs/ativos.php" target="iframe1" style="color:#555;"> <?php echo __('Assets'); ?> </a></li>
                        <li><a href="./graphs/satisfacao.php" target="iframe1" style="color:#555;"> <?php echo __('Satisfaction','dashboard'); ?> </a></li>
                        <li><a href="./graphs/times.php" target="iframe1" style="color:#555;"> <?php echo __('Time range'); ?> </a></li>
                      </ul>

                      <ul class="col-sm-2 list-unstyled" style="width:180px;">
                        <li>
                          <!-- <p><strong>Links Title</strong></p> -->
                        </li>                        
                        <li><a href="./graphs/geral_mes.php" target="iframe1" style="color:#555;"> <?php echo __('by Date','dashboard'); ?> </a></li>
                        <li><a href="./graphs/graf_tecnico.php" target="iframe1" style="color:#555;"> <?php echo __('by Technician','dashboard'); ?> </a></li>
                        <li><a href="./graphs/graf_usuario.php" target="iframe1" style="color:#555;"> <?php echo __('by Requester','dashboard'); ?> </a></li>
                        <li><a href="./graphs/graf_entidade.php" target="iframe1" style="color:#555;"> <?php echo __('by Entity','dashboard'); ?> </a></li>
								<li><a href="./graphs/graf_categoria.php" target="iframe1" style="color:#555;"> <?php echo __('by Category','dashboard'); ?> </a></li>
								<li><a href="./graphs/graf_grupo.php" target="iframe1" style="color:#555;"> <?php echo __('by Group','dashboard'); ?> </a></li>
								<li><a href="./graphs/graf_localidade.php" target="iframe1" style="color:#555;"> <?php echo __('by Location','dashboard'); ?> </a></li>
								<li><a href="./graphs/slas.php" target="iframe1" style="color:#555;"> <?php echo __('by SLA','dashboard'); ?> </a></li>
								<li><a href="./pati/graf_pati.php" target="iframe1" style="color:#555;"> <?php echo __('por PATI','dashboard'); ?> </a></li>								
                      </ul>
                    </div>
                  </div>                
              </ul>
            </li>           
            
            <!-- Classic dropdown -->	            
            <li class="nav-link"><span onclick="window.open('./assets/assets.php','iframe1');"><a href="#" data-toggle="dropdown" class="dropdown-toggle nav-icon"><i class='fa fa-desktop'></i>&nbsp;<?php echo __('Assets');?></a></span></li>           
          </ul>
         
	         <ul class="dropdown pull-right">
					<li class="nav-link"><span onclick="window.open('./config.php','iframe1');"><a href="#" data-toggle="dropdown" class="dropdown-toggle nav-icon"><i class='fa fa-gears' title="<?php echo __('Setup');?>" ></i></a></span></li>
				</ul>         
				<ul class="dropdown pull-right">			
					<li class="nav-link"><span onclick="window.open('https://forge.indepnet.net/projects/dashboard/wiki','_blank');"><a href="#" data-toggle="dropdown" class="dropdown-toggle nav-icon"><i class='fa fa fa-question-circle' title="<?php echo __('Help');?>" ></i></a></span></li>
				</ul>			
				<ul class="dropdown pull-right">			
					<li class="nav-link"><span onclick="window.open('./info.php','iframe1');"><a href="#" data-toggle="dropdown" class="dropdown-toggle nav-icon" ><i class='fa fa-info-circle' title="<?php echo __('Info');?>" ></i></a></span></li>
	         </ul>             

        </div>
        </div>      
        
		  <iframe id="iframe1" name="iframe1" class="iframe" src="main.php"  width="100%" scrolling="no" style="overflow-x:hidden; overflow-y:hidden; border:0px solid white; overflow:hidden;"></iframe>          	
</div>

	<!-- /.site-holder -->
 	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
 	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
	<script src="js/bootstrap.min.js"></script>                    
	<script src="js/jquery.jclock.js"></script>	

 	<!-- Remove below two lines in production
 	<script src="js/theme-options.js"></script>       
 	<script src="js/core.js"></script>  -->  
</body>
</html>
