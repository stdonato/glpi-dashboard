<?php
	
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
global $DB;
	
$num_years = 0;

$theme = 'material.css';
$style = $theme;
$_SESSION['theme'] = $theme;
$_SESSION['style'] = $theme;

$colors = 'grid-light.js';	
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>GLPI - Dashboard - Home</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	 <meta http-equiv="Pragma" content="public">
    
    <?php     		 
    	$redir = '<meta http-equiv="refresh" content= "120"/>'; 
    	echo $redir;
    ?>        
    
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
 	 
 	<!-- gauge -->
	<script src="js/raphael.2.1.0.min.js"></script>
	<script src="js/justgage.1.0.1.min.js"></script>	

</head>

	<?php
	if($theme == 'trans.css') {		
   	echo "<body style=\"background: url('./img/".$back."') no-repeat top center fixed; \">";
   	}
   else {
   	echo "<body>";
   	}	 
   ?>

<div class="site-holder">
<!-- top -->
                                               
<?php     
$ano = date("Y");
$month = date("Y-m");
$hoje = date("Y-m-d");


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

<!-- .box-holder -->
<!-- .content -->

<!-- <div id='intra'><a href='http://www.mpro.mp.br' target="_self"><img src="./indexmp/intra.png" alt="" style="width:100%;" /></a></div> -->

<div id='intra' style="background-image:url('./indexmp/intra.png'); width:100%; height:110px;">
	<button class="btn btn-default pull-right" style="margin-top:40px; margin-right: 10%;" onclick="javascript: history.back();">Voltar</button>
</div>

<div class="content animated fadeInBig corpo" style="margin-left: -60px;">

    <!-- main-content -->
   <div class="main-content masked-relative masked">
      
						<div id="panels" class="row" style="margin-left: 3%;">
							<!-- COLUMN 1 -->															
								  <div class="col-sm-3 col-md-3 stat">
									 <div class="dashbox shad panel panel-default db-red">
										<div class="panel-body">
										   <div class="panel-left red redbg">
												<i class="fa fa-calendar-o fa-3x"></i>
										   </div>
										   <div class="panel-right right">
										     <div id="odometer1" class="odometer" style="font-size: 25px;">   </div><p></p>
                        				<span class="chamado"><?php echo __('Tickets','dashboard'); ?></span><br>
                        				<span class="date"><b><?php echo __('Today','dashboard'); ?></b></span>												
										   </div>
										</div>
									 </div>
								  </div>
								  
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox shad panel panel-default db-blue">
										<div class="panel-body">
										   <div class="panel-left blue bluebg">
												<i class="fa fa-calendar fa-3x fa-calendar-index"></i>
										   </div>
										   <div class="panel-right right">										 
											<div id="odometer2" class="odometer" style="font-size: 25px;">   </div><p></p>
                        				<span class="chamado"><?php echo __('Tickets','dashboard'); ?></span><br>
                        				<span class="date"><b><?php echo $mes ?></b></span>
										   </div>
										</div>
									 </div>
								  </div>																		
                     								
								  <div class="col-sm-3 col-md-3">
									 <div class="dashbox shad panel panel-default db-yellow">
										<div class="panel-body">
										   <div class="panel-left yellow yellowbg">
												<i class="fa fa-plus-square fa-3x"></i>
										   </div>
										   <div class="panel-right right">
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
										   <div class="panel-left green orangebg">
												<i class="fa fa-users fa-3x"></i>
										   </div>
								   		<div class="panel-right right">
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

   <div id="tickets_total" class="widget2 widget-table action-table striped" style="margin-top: 30px; ">
      <div class="widget-header2" style="width:98.5%;">                
      	<h3><i class="fa fa-area-chart" style="margin-left:7px;">&nbsp;&nbsp;&nbsp;</i><?php echo __('Tickets Evolution','dashboard'); ?></h3>
      	 <!--  -->               
      </div> 
      <!-- /widget-header -->
	<div id="grfrow" class="row-fluid col-sm-12 col-md-12 card" style="width:98.5%;">	
      <h4> <?php //echo __('Tickets Evolution','dashboard'); ?> </h4>
      <p id="choices" style=" margin-right: 20px; margin-top: 5px; text-align:right; width:<?php echo $width_os; ?>;"></p>	  	
		<div class="demo-container" style="margin-bottom:10px;">						
			<div id="graflinhas1" class="demo-placeholder" style="float:left; width: <?php echo $width_os; ?> ;"></div>									
		</div>
	</div>
	   <?php 
			include ("indexmp/graflinhas_index_sel.inc.php");
		?>						

<div id="widgets" class="row" style="margin-top: 50px;">	
		
	<div class="col-sm-6 col-md-6"> 	 				              
	   <div id="tickets_status" class="widget widget-table action-table striped card1" style="height:387px !important;">
	      <div class="widget-header wred">                 
	      	<h3><i class="fa fa-pie-chart" style="margin-left:7px;">&nbsp;&nbsp;&nbsp;</i><?php echo __('Opened Tickets by Status','dashboard'); ?></h3>
	      	                
	      </div> 
	      <!-- /widget-header -->      
	      <div id="pie1">	 			
					<?php
						include ("indexmp/grafpie_index.inc.php");
					?> 	 						            
			</div> 
		</div>
	</div>
	
	<div class="col-sm-6 col-md-6" > 	 				              
	   <div id="last_week" class="widget widget-table action-table striped card1" >
	      <div class="widget-header wred">                
	      	<h3><i class="fa fa-bar-chart-o" style="margin-left:7px;">&nbsp;&nbsp;&nbsp;</i><?php echo __('Tickets')." - ". __('Last 7 days','dashboard') ; ?></h3>
	      	              
	      </div> 
	      <!-- /widget-header -->      
				<div id="seven" style="margin-top:-2px;"> 
					<?php
						include ("indexmp/grafcol_setedias.inc.php");
					?> 	 				              
				</div> 
	  		</div>      
	  </div>     
	  	
	<div class="col-sm-6 col-md-6" > 	 				              
	   <div id="tickets_time" class="widget widget-table action-table striped card1" >
	      <div class="widget-header wpurple">                
	      	<h3><i class="fa fa-pie-chart" style="margin-left:7px;">&nbsp;&nbsp;&nbsp;</i><?php echo __('Ticket Solving Period','dashboard'); ?></h3>
	      	              
	      </div>    
	      <!-- /widget-header -->  
				<div id="time" style="margin-top:-2px;"> 			
					<?php
						include ("indexmp/grafpie_time.inc.php");
					?> 	 						            
				</div> 
		</div>
	</div>		
		
	<div class="col-sm-6 col-md-6" > 	 				              
	   <div id="tickets_source" class="widget widget-table action-table striped card1" >
	      <div class="widget-header wpurple">                
	      	<h3><i class="fa fa-bar-chart-o" style="margin-left:7px;">&nbsp;&nbsp;&nbsp;</i><?php echo __('Tickets by Source','dashboard'); ?></h3>
	      	              
	      </div> 
	      <!-- /widget-header -->
				<div id="source" style="margin-top:-2px;"> 				
						<?php include ("indexmp/grafpie_origem.inc.php");  ?>	 				              
				</div> 
	  		</div>      
	</div> 	 

	<div class="col-sm-12 col-md-12" > 	 				              
	   <div id="tickets_type" class="widget widget-table action-table striped card1" >
	      <div class="widget-header wpurple">                
	      	<h3><i class="fa fa-bar-chart-o" style="margin-left:7px;">&nbsp;&nbsp;&nbsp;</i><?php echo __('Tickets','dashboard')." ".__('by Type','dashboard') ?></h3>
	      	              
	      </div> 
	      
				<div id="graf_tipo" style="margin-top:-2px;"> 
					<?php
						include ("indexmp/grafcol_tipo_geral.inc.php");
					?> 	 				              
				</div> 
	  		</div>      
	</div>
	
	<div class="col-sm-12 col-md-12" > 	 				              
	   <div id="tickets_ent" class="widget widget-table action-table striped card1" >
	      <div class="widget-header wpurple">                
	      	<h3><i class="fa fa-bar-chart-o" style="margin-left:7px;">&nbsp;&nbsp;&nbsp;</i><?php echo __('Tickets by Entity','dashboard'); ?></h3>
	      	              
	      </div> 	      
				<div id="graf_ent" style="margin-top:-2px;"> 
					<?php
						include ("indexmp/grafent_geral.inc.php");
					?> 	 				              
				</div> 
	  		</div>      
	</div>
	
	<div class="col-sm-12 col-md-12" > 	 				              
	   <div id="tickets_sat" class="widget widget-table action-table striped card1" style=" margin-left: -15px;">
	      <div class="widget-header wpurple">                
	      	<h3><i class="fa fa-bar-chart-o" style="margin-left:7px;">&nbsp;&nbsp;&nbsp;</i><?php echo __('Satisfaction','dashboard')." - ".__('Tickets','dashboard'); ?></h3>
	      	              
	      </div> 	      
				<div id="graf_sat" style="margin-top:-2px;"> 
					<?php
						include ("indexmp/graflinhas_sat_cham.inc.php");
					?> 	 				              
				</div> 
	  		</div>      
	</div>
	
<!--	
	<div class="col-sm-6 col-md-6" > 	 				              
	   <div id="tickets_age" class="widget widget-table action-table striped card1" >
	      <div class="widget-header wpurple">                
	      	<h3><i class="fa fa-bar-chart-o" style="margin-left:7px;">&nbsp;&nbsp;&nbsp;</i><?php echo __('Open Tickets Age','dashboard'); ?></h3>
	      	              
	      </div> 
	      
				<div id="age" style="margin-top:-2px;"> 
					<?php
						include ("indexmp/grafbar_age.inc.php");
					?> 	 				              
				</div> 
	  		</div>      
	</div>		
-->	
	
</div>                    		                  
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
	<!-- end main-content 	
	</div>-->

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
		#time {
			border: 0px none !important;	
			}
	</style>
	<?php
	if($theme == 'trans.css') {		 
		echo '<div id="footer-bar" class="footer-bar row-fluid" style="overflow: hidden; height:70px; width:100%; background-color: #000; opacity:0.7; float:left; bottom:0px; margin-top: -70px; position:relative; clear: both; margin-left: 220px; " ></div>';
	}  
	?>
	
<!--     		
<div id="" style="display:block; color:#000;"><button class="btn" id="close-all-widgets">CLOSE</button></div>
<div id="" style="display:block; color:#000;"><button class="btn" id="open-all-widgets">OPEN</button></div> 

<div id="closed-widget-list" style="display:block;"></div>
<div id="closed-widget-count" style="display:block;"></div> 	
-->
	
</div>
<!-- /.site-holder -->
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
 <!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- <script src="js/jquery-ui-1.10.2.custom.min.js"></script> -->

<script src="js/jquery-ui.min.js" type="text/javascript"></script> 
<script src="js/jquery.accordion.js"></script>            
<script src="js/bootstrap-dropdown.js"></script>
<script src="js/jquery.easy-pie-chart.js"></script> 
<script src="js/jquery.address-1.6.min.js"></script>

<script src="js/bootstrap-switch.js"></script> 
<script src="js/highcharts.js" type="text/javascript" ></script>
<script src="js/highcharts-3d.js" type="text/javascript" ></script>
<!--<script src="js/modules/exporting.js" type="text/javascript" ></script>-->
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

<!-- <script src="js/widgets.js"></script> -->

 <!-- Remove below two lines in production --> 
 
 <script src="js/theme-options.js"></script>       
 <script src="js/core.js"></script>
</body>
</html>
