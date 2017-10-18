<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/inc/config.php");

global $DB;  

//check if exists google maps api key
$query_key = "SELECT * FROM glpi_plugin_dashboard_config WHERE name = 'map_key'"; 
$res_key = $DB->query($query_key);
$api_key = $DB->result($res_key,0,'value'); 

?>

<html> 
<head>
	<title>GLPI - <?php echo __('Tickets Map','dashboard'); ?></title>
	
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<meta http-equiv="content-language" content="en-us" />	
	
	<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
	<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />
	<script src="../js/jquery.js" type="text/javascript" ></script>
	
	<script src="../js/bootstrap.min.js" type="text/javascript" ></script>  
	
	<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 
	
	<style type="text/css">
		html { margin-top: 3px;}
		a, a:visited { color: #0776cc;}
	</style>

</head>

<body>

<div id='content'>

	<div id='container-fluid' style="margin: 0px 5% 0px 7%;"> 

		<div id="head-tic" class="fluid" >	
			<a href="./index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:5px; margin-top:15px;"></i><span></span></a>
			<div id="titulo" class="tit-config" style="margin-bottom: 25px;"> <a href="config.php" ><?php echo __('Google Maps Key','dashboard'); ?> </a></div> 
		</div>
	
	<div class="well info_box col-md-6 col-sm-6 mapkey">
				
		<div id="js_key"> 
			<?php echo __('To configure Google maps','dashboard'); ?> : <br>
			1 - <a href="https://developers.google.com/maps/documentation/javascript/" target="_blank">  <?php echo __('Create an API key','dashboard'); ?> </a> <br> 
			2 - <?php echo __('Paste your key bellow and save','dashboard'); ?> <br>
		</div>
		<form id="form_key" name="form_key" action="map_key.php?con=1" method="post" class="col-md-8 col-sm-8">
			<br>
			<input type="text" class="form-control" value="<?php echo $api_key; ?>" id="key" name="key" placeholder="Google maps API key">
			<br>
			<button type='submit' class='btn btn-primary'> <?php echo __('Save'); ?> </button>
			
			<button type="button" class='btn btn-primary' onclick='javascript:history.back(-2);'> <?php echo __('Back'); ?> </button>
		
		<?php Html::closeForm(); ?> 								
		
		
		<?php
			
			if(isset($_REQUEST['con']) && $_REQUEST['con'] == 1) {
				if(isset($_POST['key']) && $_POST['key'] != '') {
					
					$key = $_POST['key'];
					
					$insert = "
						INSERT INTO glpi_plugin_dashboard_config (name, value, users_id) 
						VALUES ('map_key', '$key', 'xxx') 
						ON DUPLICATE KEY UPDATE value='$key'";			 
					
					$DB->query($insert) or die ("error inserting API key");
					
					echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=\"map_key.php\"'>";			
				}	
				
				if(isset($_POST['key']) && $_POST['key'] == '') {
					$query = "DELETE FROM glpi_plugin_dashboard_config WHERE name = 'map_key'";
					$result = $DB->query($query);
				}
			}		
		
		?>
	 	
	</div>
	</div>
	
</div>

</body>
</html>