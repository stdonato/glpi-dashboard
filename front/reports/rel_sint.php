<?php

include ("../../../../inc/includes.php");
include ("../../../../inc/config.php");
include "../inc/functions.php";

global $DB;

Session::checkLoginUser();
Session::checkRight("profile", READ);
?>

<html>
<head>
<title> GLPI - <?php echo __('Summary Report','dashboard') ?> </title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />
<meta charset="utf-8">

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />

<script language="javascript" src="../js/jquery.js"></script>
<link href="../inc/select2/select2.css" rel="stylesheet" type="text/css">
<script src="../inc/select2/select2.js" type="text/javascript" language="javascript"></script>

<script src="../js/bootstrap.min.js"></script>


<style type="text/css">	
	select { width: 60px; }
	table.dataTable { empty-cells: show; }
   a:link, a:visited, a:active { text-decoration: none;}
</style>

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 

</head>

<body style="background-color: #e5e5e5;">

<div id='content' >
	<div id='container-fluid' style="margin: 0px 5% 0px 5%;">
	  <div id="pad-wrapper" >
		<div id="charts" class="fluid chart">			
				<div id="head-rel" class="fluid">
					<style type="text/css">
					a:link, a:visited, a:active {text-decoration: none;}
					a:hover {color: #000099;}
					</style>
					
					<a href="../index.php"><i class="fa fa-home" style="font-size:14pt; margin-left:20px;"></i><span></span></a>
					
					<div id="titulo"> <?php echo __('Summary Report','dashboard') .'  '. __('','dashboard') ?> </div>		
						<div id="datas" class="col-md-12 fluid" style="margin-left:60px;"> 
						<form id="form1" name="form1" class="form1" method="post" action="rel_sint.php?sel=1"> 
							<table border="0" cellspacing="0" cellpadding="2">
								<tr>										
										<?php
											$url = $_SERVER['REQUEST_URI'];
											$arr_url = explode("?", $url);
											$url2 = $arr_url[0];										
										?>																										
										
										<td style="margin-top:2px;">
										<?php
											echo "
											<select id='sel_rel' name='sel_rel' style='width: 300px; height: 27px;' autofocus onChange=\"javascript: document.form1.submit.focus();\"  >
												<option value='0'> -- " . __('Select','dashboard')." -- </option>
												<option value='1'>".__('Overall','dashboard')."</option>
												<option value='2'>".__('Entity')."</option>
												<option value='3'>".__('Technician')."</option>
												<option value='4'>".__('Requester')."</option>											
											</select> ";										
										?>
										
										</td>
										</tr>
										<tr><td height="15px"></td></tr>
										<tr>
											<td colspan="2" align="center">
												<button class="btn btn-primary btn-sm" type="submit" name="submit" value="Atualizar" ><i class="fa fa-search"></i>&nbsp; <?php echo __('Send'); ?> </button>
												<button class="btn btn-primary btn-sm" type="button" name="Limpar" value="Limpar" onclick="location.href='<?php echo $url2 ?>'" ><i class="fa fa-trash-o"></i>&nbsp; <?php echo __('Clean','dashboard'); ?> </button>
											</td>
										</tr>				
							    </table>
						    <?php Html::closeForm(); ?>						
						</div>
				</div>			
			</div>		
		
		<?php				
		
		if($_REQUEST['sel'] == "1") {
		
			if(isset($_REQUEST["sel_rel"]) AND $_REQUEST["sel_rel"] != 0) {

				$id_rel = $_REQUEST["sel_rel"];	
							
				switch ($id_rel) {
					 case "0": $page = 'rel_sint.php'; break;
				    case "1": $page = 'rel_sint_all.php'; break;
				    case "2": $page = 'rel_sint_ent.php'; break;
				    case "3": $page = 'rel_sint_tec.php'; break;
				    case "4": $page = 'rel_sint_req.php'; break;	
				}
								
				//header("Location:".$page."");
				echo "
				<script type='text/javascript' >
					location.href='".$page."';
				</script>";
		
			}
			
			else {
				echo '<script language="javascript"> alert(" ' . __('Select', 'dashboard') . ' "); </script>';
				echo '<script language="javascript"> location.href="rel_sint.php"; </script>';
			}
		}		
			
   ?>
				
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() { $("#sel_rel").select2({dropdownAutoWidth : true}); });	
	</script>
		</div>
		</div>	
	</div>
</div>
</body>
</html>

