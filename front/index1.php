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
if(Session::haveRight("profile", READ)){		
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
				$newversion = "<a href='https://forge.glpi-project.org/projects/dashboard/files' target='_blank' style='margin-right: 12px; color:#fff;' class='blink_me'><i class='fa fa-refresh'></i><span>&nbsp;&nbsp;".  __('New version','dashboard'). " ". __( 'avaliable','dashboard'). " </span></a>";		
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
    <link href="css/material.min.css" rel="stylesheet">	  

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
    <link href="css/yamm.css" rel="stylesheet" type="text/css" />  
 
	<script src="js/jquery.js"></script>
	<script src="js/menu.js"></script>
	<script src="js/material.min.js"></script>      		
	
<script type="text/javascript">
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
		

	$(function($) {
		var options = {
		timeNotation: '24h',
		am_pm: true,
		fontSize: '14px'
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
</head>

	<?php
	if($theme == 'trans.css') {		
	   	echo "<body style=\"background: url('./img/".$back."') no-repeat top center fixed; \">";
   	}
	else {
   		echo "<body style='background-color: #e5e5e5;'>";
   	}	 
   ?>

   <div class="site-holder">
		<!-- top -->
		<nav class="navbar navbar-default nav-delighted" role="navigation" >
          <!-- Brand and toggle get grouped for better mobile display -->
           <div class="navbar-header" style="color:#fff;" >
               <a class="navbar-brand hidden-xs" href="<?php echo $CFG_GLPI['url_base'].'/front/ticket.php';?>" target="_blank" style="width: 290px; margin-left: -55px; margin-top:-2px;">
						 
                  <span style="font-size:18px;"><?php echo '<img src='. $photo_url .' alt="" title="Upload photo in user profile" class="avatar" style="height: 40px; margin-left: 0px; " />&nbsp;&nbsp;'; ?><?php echo $_SESSION["glpifirstname"];?></span>                  
                	</a>          	
           </div>
				<!-- NAVBAR LEFT  -->					
				<div id="navbar-left" class="nav navbar-nav pull-left hidden-xs" style="margin-top: 20px;"> 					    
		        <a href="index.php" style="margin-top:6px; margin-left: 30px;">           
		            <span class="name" style="color: #FFF; font-size:14pt;">
		                GLPI - <?php echo $ent_name; ?>
		            </span>            
		        </a>					    
				</div>
             								
				<!-- /NAVBAR LEFT -->					
				<ul class="nav navbar-nav pull-right hidden-xs">
					<li id="header-user" style="color:#FFF; font-size:10pt; margin-top:8px; margin-right:15px;">										
						<span class="username">		
							<?php if(isset($newversion)) echo $newversion; ?>					
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
                   
          <!--<li class="nav-link"><span onclick="location.href=('index.php')"><a href="#" data-toggle="dropdown" class="dropdown-toggle nav-icon"><i class='fa fa-dashboard'></i>&nbsp;Dashboard</a></span></li>-->
          <li class="dropdown menu"><a href="#" data-toggle="dropdown" class="dropdown-toggle" style="color:#fff;"><span class="text-nav1" onclick="location.href=('index.php')"><i class='fa fa-dashboard'></i>&nbsp; Dashboard</span></a></li>
		  <li class="dropdown menu"><a href="#" data-toggle="dropdown" class="dropdown-toggle" style="color:#fff;"><span class="text-nav1" onclick="window.open('./graphs/graf_tech.php?con=1','iframe1');"><i class='fa fa-area-chart'></i>&nbsp; <?php echo __('My Dashboard','dashboard');?></span></a></li>

           <!-- Classic dropdown -->            
            <li class="dropdown menu"><a href="#" data-toggle="dropdown" class="dropdown-toggle" style="color:#fff;"><span class="text-nav1"><i class='fa fa-edit'></i>&nbsp;<?php echo __('Tickets','dashboard');?>&nbsp;<b class="caret"></b></span></a>
              <ul role="menu" class="dropdown-menu">
                <li><a tabindex="-1" href="./tickets/chamados.php" target="_blank"> <?php echo __('Overall','dashboard'); ?> </a></li>
                <li><a tabindex="-1" href="./tickets/select_ent.php" target="_blank"> <?php echo __('by Entity','dashboard'); ?> </a></li>
                <li><a tabindex="-1" href="./tickets/select_grupo.php" target="_blank">  <?php echo __('by Group','dashboard'); ?> </a></li>                
                <!-- <li><a tabindex="-1" href="./map/index.php" target="_blank"> <?php echo __('Map','dashboard'); ?> </a></li> -->
                <li class="dropdown-submenu">
       			   <a tabindex="-1" href="#"><?php echo __('Map','dashboard'); ?></a>
	               <ul class="dropdown-menu">
	                  <li><a href="./map/index.php" target="_blank" > <?php echo __('by Entity','dashboard'); ?> </a></li>
	                  <li><a href="./map/map_loc.php" target="_blank" > <?php echo __('by Location','dashboard'); ?> </a></li>							   
	               </ul>
	            </li> 
              </ul>
            </li>            
 
            <!-- Classic list -->
			<li class="dropdown menu"><a href="#" data-toggle="dropdown" class="dropdown-toggle" style="color:#fff;"><span class="text-nav1"><i class='fa fa-file-text'></i>&nbsp;<?php echo __('Reports','dashboard');?>&nbsp;<b class="caret"></b></span></a>
              <ul class="dropdown-menu multi-level" role="menu">
                
                  <!-- Content container to add padding -->
                  <div class="yamm-content" style="width:400px;">
                    <div class="row">
                      <ul class="col-sm-2 list-unstyled menu1" style="width:180px;">
                       <li>
                          <!-- <p><strong>Links Title</strong></p> -->
                        </li>
                         
                        <li><a href="./reports/rel_assets.php" target="iframe1" > <?php echo __('Assets'); ?> </a></li>                        
                        <li><a href="./reports/rel_categorias.php?con=1" target="iframe1" > <?php echo __('Category'); ?> </a></li>
                        <li><a href="./reports/rel_tickets.php" target="iframe1" > <?php echo _sn('Ticket','Tickets',2); ?> </a></li>                                                						                                                                                                                       
                        <li><a href="./reports/rel_entidades.php?con=1" target="iframe1" > <?php echo _sn('Entity','Entities',2); ?> </a></li>
                        <li><a href="./reports/rel_grupos.php?con=1" target="iframe1" > <?php echo _sn('Group','Groups',2); ?> </a></li>
                        <li><a href="./reports/rel_localidades.php?con=1" target="iframe1" > <?php echo _n('Location', 'Locations', 2); ?> </a></li>
                        <li><a href="./reports/rel_projects.php?con=1" target="iframe1" > <?php echo _sn('Project','Projects',2); ?> </a></li>                       
                        <li><a href="./reports/rel_satisfacao.php" target="iframe1" > <?php echo __('Satisfaction'); ?> </a></li>
                        <li><a href="./reports/rel_tecnicos.php?con=1" target="iframe1" > <?php echo _sn('Technician','Technicians',2,'dashboard'); ?> </a></li>
							<?php
								// distinguish between 0.90.x and 9.1 version
								//if (GLPI_VERSION <= intval('9.1')){
									//echo '<li><a href="./reports/rel_slas.php?con=1" target="iframe1" >'. __('SLA').'</a></li>';
								//}	
							?>
                       
							<li class="dropdown-submenu">
                				<a tabindex="-1" href="#"><?php echo __('Cost'); ?></a>
				               <ul class="dropdown-menu">
									<li><a href="./reports/rel_custo_ent.php" target="iframe1" style="color:#000;"> <?php echo __('by Entity','dashboard'); ?> </a></li>
									<li><a href="./reports/rel_custo_tec.php" target="iframe1" style="color:#000;"> <?php echo __('by Technician','dashboard'); ?> </a></li>
									<li><a href="./reports/rel_custo_req.php" target="iframe1" style="color:#000;"> <?php echo __('by Requester','dashboard'); ?> </a></li>   
									<li><a href="./reports/rel_custo_loc.php" target="iframe1" style="color:#000;"> <?php echo __('by Location','dashboard'); ?> </a></li>
				               </ul>
				             </li> 
							<li class="dropdown-submenu">
							   <a tabindex="-1" href="#"><?php echo __('Summary','dashboard'); ?></a>
				               <ul class="dropdown-menu">
									<li><a href="./reports/rel_sint_all.php" target="iframe1" style="color:#000;"> <?php echo __('Overall','dashboard'); ?> </a></li>
									<li><a href="./reports/rel_sint_ent.php" target="iframe1" style="color:#000;"> <?php echo __('by Entity','dashboard'); ?> </a></li>
									<li><a href="./reports/rel_sint_tec.php" target="iframe1" style="color:#000;"> <?php echo __('by Technician','dashboard'); ?> </a></li>
									<li><a href="./reports/rel_sint_req.php" target="iframe1" style="color:#000;"> <?php echo __('by Requester','dashboard'); ?> </a></li>   
				               </ul>
				             </li> 
                        

				             <?php
	                        // distinguish between 0.90.x and 9.1 version
								if (GLPI_VERSION >= 9.1){
					             echo '<li class="dropdown-submenu">';
	                			 echo	'<a tabindex="-1" href="#">'. __('SLA').'</a>';
					             echo '<ul class="dropdown-menu">
											<li><a href="./reports/rel_sltsas.php?con=1" target="iframe1" style="color:#000;">'. __('Time to own').'</a></li>
											<li><a href="./reports/rel_sltsrs.php?con=1" target="iframe1" style="color:#000;">'. __('Time to resolve').' </a></li>										
									   </ul>
									  </li> ';
					          	} 
					          ?>				             				                                
							<li class="dropdown-submenu">
                			   <a tabindex="-1" href="#"><?php echo _sn('Task','Tasks',2); ?></a>
				               <ul class="dropdown-menu">
									<li><a href="./reports/rel_tarefa.php" target="iframe1" style="color:#000;"> <?php echo __('Technician'); ?> </a></li>
									<li><a href="./reports/rel_task_req.php" target="iframe1" style="color:#000;"> <?php echo  __('Requester'); ?> </a></li>   
									<li><a href="./reports/rel_tarefa_cham.php" target="iframe1" style="color:#000;"> <?php echo  __('Tickets','dashboard'); ?> </a></li>   
									<li><a href="./reports/rel_task_ent.php" target="iframe1" style="color:#000;"> <?php echo  __('Entity','dashboard'); ?> </a></li>   
				               </ul>
				             </li>				             
                      </ul>
                                           

                      <ul class="col-sm-2 list-unstyled menu1" style="width:180px;"> 
                        <li>
                          <!-- <p><strong>Links Title</strong></p> -->                        
                        <li><a href="./reports/rel_categoria.php" target="iframe1" > <?php echo __('by Category','dashboard'); ?> </a></li>                        
                        <li><a href="./reports/rel_data.php" target="iframe1" > <?php echo __('by Date','dashboard'); ?> </a></li> 
                        <li><a href="./reports/rel_entidade.php" target="iframe1" > <?php echo __('by Entity','dashboard'); ?> </a></li>
                        <li><a href="./reports/rel_grupo.php" target="iframe1" > <?php echo __('by Group','dashboard'); ?> </a></li>
                        <li><a href="./reports/rel_localidade.php" target="iframe1" > <?php echo __('by Location','dashboard'); ?> </a></li>
                        <li><a href="./reports/rel_usuario.php" target="iframe1" > <?php echo __('by Requester','dashboard'); ?> </a></li>
                        <li><a href="./reports/rel_tecnico.php" target="iframe1" > <?php echo __('by Technician','dashboard'); ?> </a></li>
                
                        </li>
         	            <?php
	                        // distinguish between 0.90.x and 9.1 version
								if (GLPI_VERSION >= 9.1){
		                        echo '<li class="dropdown-submenu">';
		                			echo '<a tabindex="-1" href="#">'. __('by SLA','dashboard').'</a>';
						            echo '<ul class="dropdown-menu">
											<li><a href="./reports/rel_sltsa.php" target="iframe1" style="color:#000;">'. __('Time to own') .'</a></li>
											<li><a href="./reports/rel_sltsr.php" target="iframe1" style="color:#000;">'. __('Time to resolve').'</a></li>										
										 </ul>
						              </li>';
				             	}
				            ?>
				              	     							
                      </ul>
                    </div>
                  </div>                
              </ul>
            </li>            
            
            <li class="dropdown menu"><a href="#" data-toggle="dropdown" class="dropdown-toggle" style="color:#fff;"><span class="text-nav1"><i class='fa fa-bar-chart-o'></i>&nbsp;<?php echo __('Charts','dashboard');?>&nbsp;<b class="caret"></b></span></a>
              <ul class="dropdown-menu">                
                  <!-- Content container to add padding -->
                  <div class="yamm-content" style="width:400px;">
                    <div class="row">
                      <ul class="col-sm-2 list-unstyled menu1" style="width:180px;">
                        <li><!-- <p><strong>Links Title</strong></p> --></li>
                        <li><a href="./graphs/ativos.php" target="iframe1" > <?php echo __('Assets'); ?> </a></li>
                        <li><a href="./graphs/categorias.php" target="iframe1" > <?php echo __('Category'); ?> </a></li>
                        <li><a href="./graphs/entidades.php" target="iframe1" > <?php echo _n('Entity', 'Entities', 2); ?> </a></li>
                        <li><a href="./graphs/geral.php" target="iframe1" > <?php echo __('Overall','dashboard'); ?></a></li>
                        <li><a href="./graphs/grupos.php" target="iframe1" > <?php echo _sn('Group','Groups',2); ?> </a></li>
                        <li><a href="./graphs/local.php" target="iframe1" > <?php echo _n('Location', 'Locations', 2); ?> </a></li>
                        <li><a href="./graphs/usuarios.php" target="iframe1" > <?php echo __('Requester','dashboard'); ?> </a></li>
                        <li><a href="./graphs/satisfacao.php" target="iframe1" > <?php echo __('Satisfaction','dashboard'); ?> </a></li>
                        <li><a href="./graphs/tecnicos.php" target="iframe1" > <?php echo __('Technician','dashboard'); ?> </a></li>
                        <li><a href="./graphs/times.php" target="iframe1" > <?php echo __('Time range'); ?> </a></li>
                      </ul>

                      <ul class="col-sm-2 list-unstyled menu1" style="width:180px;">
                        <li><!-- <p><strong>Links Title</strong></p> --></li>                        
								<li><a href="./graphs/graf_categoria.php" target="iframe1" > <?php echo __('by Category','dashboard'); ?> </a></li>
                        <li><a href="./graphs/geral_mes.php" target="iframe1" > <?php echo __('by Date','dashboard'); ?> </a></li>
                        <li><a href="./graphs/graf_entidade.php" target="iframe1" > <?php echo __('by Entity','dashboard'); ?> </a></li>
								<li><a href="./graphs/graf_grupo.php" target="iframe1" > <?php echo __('by Group','dashboard'); ?> </a></li>
								<li><a href="./graphs/graf_localidade.php" target="iframe1" > <?php echo __('by Location','dashboard'); ?> </a></li>
                        <li><a href="./graphs/graf_usuario.php" target="iframe1" > <?php echo __('by Requester','dashboard'); ?> </a></li>
                        <li><a href="./graphs/graf_tecnico.php" target="iframe1" > <?php echo __('by Technician','dashboard'); ?> </a></li>
                        <li><a href="./graphs/graf_tipo.php" target="iframe1" > <?php echo __('by Type','dashboard'); ?> </a></li>
								
							<?php
	                        // distinguish between 0.90.x and 9.1 version
									if (GLPI_VERSION >= 9.1){
										echo '<li class="dropdown-submenu">';
										echo '	<a tabindex="-1" href="#">'. __('SLA').'</a>';
										echo '<ul class="dropdown-menu">
												<li><a href="./graphs/sltsa.php" target="iframe1" style="color:#000;">'. __('Time to own').' </a></li>
												<li><a href="./graphs/sltsr.php" target="iframe1" style="color:#000;">'. __('Time to resolve').'</a></li>										
											 </ul>
											</li>'; 
						             }
				             ?>
				             
                      </ul>
                    </div>
                  </div>                
              </ul>
            </li>           
            
            <!-- Classic dropdown -->	            
			<!--<li class="dropdown menu"><a href="#" data-toggle="dropdown" class="dropdown-toggle" style="color:#fff;"><span class="text-nav1" onclick="window.open('./metrics/index.php','self');"><i class='fa fa-line-chart'></i>&nbsp; <?php echo __('Metrics','dashboard');?></span></a>-->
			<li class="dropdown menu"><a href="#" data-toggle="dropdown" class="dropdown-toggle" style="color:#fff;"><span class="text-nav1"><i class='fa fa-line-chart'></i>&nbsp;<?php echo __('Metrics','dashboard');?>&nbsp;<b class="caret"></b></span></a>
              <ul role="menu" class="dropdown-menu">
                <li><a tabindex="-1" href="./metrics/index.php" target="_blank"> <?php echo __('Overall','dashboard'); ?> </a></li>
                <li><a tabindex="-1" href="./metrics/select_ent.php" target="_blank"> <?php echo __('by Entity','dashboard'); ?> </a></li>
                <li><a tabindex="-1" href="./metrics/select_grupo.php" target="_blank">  <?php echo __('by Group','dashboard'); ?> </a></li>                
              </ul>			
			</li>			
						
			<li class="dropdown menu"><a href="#" data-toggle="dropdown" class="dropdown-toggle" style="color:#fff;"><span class="text-nav1" onclick="window.open('./assets/assets.php','iframe1');"><i class='fa fa-desktop'></i>&nbsp; <?php echo __('Assets');?></span></a></li>
 
          </ul>     
			 
			 <!-- Right aligned menu below button -->
			<button id="demo-menu-lower-right" class="mdl-button mdl-js-button mdl-button--icon pull-right" style="margin-top:6px;" ></button>
			
			<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="demo-menu-lower-right" style="color:#888 !important;" >
			  <li class="mdl-menu__item"><span onclick="window.open('./config.php','iframe1');"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class='fa fa-gears' title="<?php echo __('Setup');?>" ></i><?php echo " ". __('Setup');?></a></span></li>
			  <li class="mdl-menu__item"><span onclick="window.open('https://forge.glpi-project.org/projects/dashboard/wiki','_blank');"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class='fa fa fa-question-circle' title="<?php echo __('Help');?>" ></i><?php echo " ". __('Help');?></a></span></li>  
			  <li class="mdl-menu__item"><span onclick="window.open('./info.php','iframe1');"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><i class='fa fa-info-circle' title="<?php echo __('Info','dashboard');?>" ></i><?php echo " ". __('Info','dashboard');?></a></span></li>
			  <li class="mdl-menu__item">    
			    <div id="donate" style="margin-top:0px; margin-left:0px; border: 0px;">
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="3SN6KVC4JSB98">
						<input type="image" src="./img/paypal.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="./img/paypal.png" width="1" height="1">
					</form>
				 </div>
				</li>
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
