<?php

include ("../../../inc/includes.php");
include ("../../../inc/config.php");

Session::checkLoginUser();
//Session::checkRight("profile", READ);

$ver = explode(" ",implode(" ",plugin_version_dashboard()));
              						                         	            
?>

<html>
  <head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>GLPI - Dashboard - Info</title>
  <link rel="icon" href="img/dash.ico" type="image/x-icon" />
  <link rel="shortcut icon" href="img/dash.ico" type="image/x-icon" />
  <link href="css/styles.css" rel="stylesheet" type="text/css" />
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
  <link href="css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />      
	<style type="text/css">
		video#bgvid { 		
			position: fixed; right: 0; bottom: 0;		
			min-width: 100%; min-height: 100%;		
			width: auto; height: auto; z-index: -900;		
			background: url(cloud.png) no-repeat;		
			background-size: cover; 		
		}
	</style>    
    
  </head>
<body style="background-color: #fff;" background="./img/backx.jpg" >

<div id="content" class="col-md-12" >
	<video autoplay loop poster="cloud.png" id="bgvid" style="z-index:-999; position:absolute;">
		<source src="./img/cloud.mp4" type="video/mp4">
	</video>
	  
	<div class="well info_box col-md-6" style="opacity: 0.8; height:460px; margin:auto; margin-top:100px; margin-bottom: 400px; float:none; text-align:center; font-size:14pt;">    
	    <br>
	    <span style="font-weight: bold;">GLPI Dashboard</span><p>
	    <br>
	    <?php echo __('Tickets Statistics','dashboard'); ?><br>
	    <br>
		 <?php echo __('Version')." ". $ver['1']; ?><br>
	    <br><p>
	    <?php echo __('Developed by','dashboard'); ?>:
	    <br>
	    <b>Stevenes Donato
	    <br>
	     <a href="mailto:stevenesdonato@gmail.com"> stevenesdonato@gmail.com </b> </a>
	    <br>
	    <br>
	     <a href="https://forge.glpi-project.org/projects/dashboard/files" target="_blank" >https://forge.glpi-project.org/projects/dashboard/files</a>    
	    <br>
	    <a href="https://sourceforge.net/projects/glpidashboard" target="_blank" >https://sourceforge.net/projects/glpidashboard</a>
	    <br><p></p>
	    
	    <div id="donate" style="margin-top:25px; margin-left:0px;">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="3SN6KVC4JSB98">
			<input type="image" src="./img/paypal.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="./img/paypal.png" width="1" height="1">
			</form>
		 </div>	          
	</div>
</div>
</body>
</html>
