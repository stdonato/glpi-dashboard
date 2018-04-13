<?php

if(isset($_REQUEST['ent'])) {
	$id_ent = $_REQUEST['ent'];
	$indexw = "indexw.php?ent=".$id_ent;
	$indexb = "index.php?ent=".$id_ent;
	include "metrics_ent.inc.php";
}
	
	
elseif(isset($_REQUEST['grp'])) {
	$id_grp = $_REQUEST['grp'];
	$indexw = "indexw.php?grp=".$id_grp;
	$indexb = "index.php?grp=".$id_grp;
	include "metrics_grp.inc.php";
}

else {
	$indexw = "indexw.php";
	$indexb = "index.php";	
	include "metrics.inc.php";
}

?>

<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <title>GLPI  -  <?php echo __('Metrics','dashboard'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="controlfrog.css" rel="stylesheet" media="screen">   
	<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
   <link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
	
	<script src="../js/jquery.js"></script>    
	<script src="moment.js"></script>	
	<script src="jquery.easypiechart.js"></script>
	<script src="gauge.js"></script>	
	<script src="chart.js"></script>
	<script src="jquery-sparkline.js"></script>			
   <script src="../js/bootstrap.min.js"></script>
   <script src="controlfrog-plugins.js"></script>
	<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" /> 
	
	<script src="../js/highcharts.js" type="text/javascript" ></script>
	<!--<script src="../js/highcharts-3d.js" type="text/javascript" ></script>-->
	<script src="../js/themes/dark-unica.js" type="text/javascript" ></script>
	
	<script src="../js/modules/no-data-to-display.js" type="text/javascript" ></script>
	<script src="reload.js"></script>	
	<script src="reload_param.js"></script>	
    
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content= "120"/>  
		<script src="../../js/respond.min.js"></script>
		<script src="../../js/excanvas.min.js"></script>
	<![endif]-->
        
	<script>
		var themeColour = 'white';
	</script>
   <script src="controlfrog.js"></script>
    
<style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style></head>

<body class="white" onload="reloadPage(); initSpark('<?php echo $quantm2; ?>'); initSparkDay('<?php echo $quantd2; ?>'); initGauge('0','100','<?php echo $gauge_val; ?>'); initPie('<?php echo $res_days; ?>'); initFunnel('<?php echo $sta_values; ?>','<?php echo $sta_labels; ?>'); initRag('<?php echo $types; ?>','<?php echo $rag_labels; ?>'); initSingle1('<?php echo $satisf; ?>');">
	
	<div class="cf-nav cf-nav-state-min">
		<a href="" class="cf-nav-toggle">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>
		
		<ul>
			<li class="cf-nav-shortcut">
				<a href="../index.php">
					<span class="cf-nav-min"><i class="fa fa-home"></i></span>
					<span class="cf-nav-max">Home</span>
				</a>
			</li>
			<li class="current cf-nav-shortcut">
				<a href="<?php echo $indexb; ?>" class="current active">
					<span class="cf-nav-min">B</span>
					<span class="cf-nav-max">Black</span>
				</a>
			</li>
			<li class="cf-nav-shortcut">
				<a href="<?php echo $indexw; ?>">
					<span class="cf-nav-min">W</span>
					<span class="cf-nav-max">White</span>
				</a>
			</li>
		</ul>
	</div> 
	
	
<div class="container-fluid">	
<div class="cf-container cf-nav-active">

<div class="row-fluid" style="margin-top: 25px;">
<div class="col-lg-12" role="main">

	<div class="row-status" >
		<div style="min-height: 0px;" class="col-lg-5 cf-item-status tickets new">
			<header>
				<p><span></span><?php echo _x('status','New');?></p>
			</header>
			<div class="content" >
				<div class="metric5"><?php echo $new; ?></div>
				<div class="metric-small5"><?php //if(isset($newy) && $newy != 0) { percent($new,$newy); } else { echo '0'; } ?></div>
			</div>
		</div>
		
		<div style="min-height: 100px;" class="col-lg-5 cf-item-status tickets assign">
			<header>
				<p><span></span><?php echo __('Assigned');?></p>
			</header>
			<div class="content">
				<div class="metric5"><?php echo $assigned;?></div>
				<div class="metric-small5"><?php //percent($assigned,$assignedy); ?></div>
			</div>
		</div>

		<div style="min-height: 100px;" class="col-lg-5 cf-item-status tickets pending">
			<header>
				<p><span></span><?php echo __('Pending'); ?></p>
			</header>
			<div class="content">
				<div class="metric5"><?php echo $pend;?></div>
				<div class="metric-small5"><?php //percent($pend,$pendy); ?></div>
			</div>
		</div>
		
		<?php

		//Solved or closed ticktes	
				
		if($solved > 0) {
			$notopen = $solved;
			$notopeny = $solvedy;
			$tit_notopen = __('Solved','dashboard');
			$count_notop = strlen($notopen);
		}		
		else {
			$notopen = $closed;
			$notopeny = $closedy;
			$tit_notopen = __('Closed','dashboard');
			$count_notop = strlen($notopen);
		}
		
		?>
		<div style="min-height:100px;" class="col-lg-5 cf-item-status tickets closed">
			<header>
				<p><span></span><?php echo $tit_notopen;?></p>
			</header>
			<div class="content">
				<div class="metric5"><?php echo $notopen;?></div>
				<?php 
					if($count_notop < 5) {
						echo "<div class='metric-small5'>";
						//percent($notopen,$notopeny);
						echo  " </div>";
					}
				?>		
			</div>
		</div>

		<div style="min-height: 100px;" class="col-lg-5 cf-item-status tickets all">				
			<header>
				<p><span></span><?php echo __('Total')." (".__('Opened','dashboard').")";?></p>
			</header>
			<div class="content">
				<div class="metric5"><?php echo $total;?></div>
				<div class="metric-small5"><?php //percent($total,$totaly); ?></div>			
			</div>
		</div>
	</div> <!-- fim row1 -->

<div class="row" style="margin-top: 10px;">	
		<div style="" class="col-lg-3 cf-item">
				<!--Display the time and date. For 12hr clock add class 'cf-td-12' to the 'cf-td' div -->
			<header>
				<p><span></span><?php echo __('Time')." &amp; ". __('Date'); ?> </p>
			</header>
				<div class="content">
					<div class="cf-td">
					<!-- <div class="cf-td cf-td-12"> -->
						<div class="cf-version metric-small" style="font-size:30px !important;"><?php echo $actent; ?></div>
						<div class="cf-td-time metric hora"></div>
						<div class="cf-td-dd">
							<!--<p class="cf-td-day metric-small" ></p>
							<p class="cf-td-date metric-small" ></p>						
							-->
							<script type="text/javascript">
								var d_names = <?php echo '"'.$dia.'"' ; ?>;
								var m_names = <?php echo '"'.$mes.'"' ; ?>;
								
								var d = new Date();
								var curr_day = d.getDay();
								var curr_date = d.getDate();
								var curr_month = d.getMonth();
								var curr_year = d.getFullYear();
			
							document.write("<span style='font-size:26px; margin-top: -6px !important;'>" + d_names + "</span><br> <span style='font-size:26px;'>" + curr_date + " " + m_names + " " + curr_year + "</span><br>" );		
							</script>
							<span style="font-size:20px;"><?php echo __('Period'). ": ".$period_name ?></span>
						</div>					
					</div>
				</div>
			</div> <!-- //end cf-item -->


		<div style="" class="col-lg-3 cf-item">
				<header>
					<p><span></span><?php echo __('Tickets Total','dashboard');?></p>
				</header>
				<div class="content">
					<div class="cf-svmc-sparkline">
					<div class="cf-svmc">
						<div class="metric total"></div>
						<div class="change metric-small">
							<div id="arrow"></div>
							<span class="large"></span><!-- <span class="small">.45%</span> -->
						</div>
					</div>
					<div class="cf-sparkline clearfix" style="margin-top:15px;">
						<div id="spark-1" class="sparkline">
							<canvas height="90" width="235" style="display: inline-block; width: 235px; height: 90; vertical-align: top;"></canvas>
						</div>
						<div style="height: 117px;" class="sparkline-value">
							<div class="metric-small"></div>
						</div>
					</div>
					</div>					
				</div>
			</div> <!-- //end cf-item -->
		
			
			<div style="min-height: 0px;" class="col-lg-3 cf-item">
				<header>
					<p><span></span><?php echo __('Today Tickets','dashboard'); ?> </p>
				</header>
				<div class="content">							
				 <div class="cf-svmc-sparkline">
						<div class="cf-svmc">
							<div class="metric total-month"><?php echo $today_tickets; ?></div>
							<div class="change metric-small daily">
								<div id="arrow-2"></div>
								<span class="large large-2"></span><!-- <span class="small">.45%</span> ((V2-V1)/V1 × 100) -->
							</div>
						</div>
						<div class="cf-sparkline clearfix" style="margin-top:15px;">
							<div id="spark-2" class="sparkline">
								<canvas height="90" width="235" style="display: inline-block; width: 235px; height: 90; vertical-align: top;"></canvas>
							</div>
							<div style="height: 117px;" class="sparkline-value">
								<div class="metric-small"></div>
							</div>
						</div>
					</div>					
				</div>
			</div> <!-- //end cf-item -->
								

			<div style="" class="col-lg-3 cf-item">
				<header>
					<p><span></span><?php echo _n('Ticket','Tickets',2)." ".__('Within','dashboard');?> - %</p>
				</header>				
				<div class="content cf-gauge" id="cf-gauge-1">
				
					<div class="val-current">
						<div class="metric" id="cf-gauge-1-m"></div>
					</div>
					<div class="canvas">
						<canvas height="180" width="285" id="cf-gauge-1-g"></canvas>
					</div>
					<div class="val-min">
						<div class="metric-small"></div>
					</div>
					<div class="val-max">
						<div class="metric-small"></div>						
					</div>
					
				</div>
			</div> <!-- //end cf-item -->										
		</div> <!-- //end row 1 -->
	
	<div class="row row-fluid" style="margin-top:40px;">														
						
			<div style="" class="col-lg-3 cf-item">
				<header>
					<p><span></span><?php echo __('Tickets by Source','dashboard') ;?></p>
				</header>
				<div class="content">
					<div id="cf-funnel-1" class="cf-funnelx" style="margin-top: -15px;">
						<?php include ("grafpie_origem.inc.php");  ?>
					</div>
				</div>
			</div> <!-- //end cf-item -->
			
			<div style="" class="col-lg-3 cf-item">
					<header>
						<p><span></span><?php echo _n('Ticket','Tickets',2)." ". __('by Type','dashboard') ;?></p>
					</header>
					<div class="content" >					
						<div id="cf-rag-1" class="cf-rag">
						<?php //include ("grafpie_tipo.inc.php");  ?>
						<div class="cf-bars"></div>
							<div class="cf-figs "></div>
								<div class="cf-txts"></div> 
						</div>
					</div>				
			</div> 	<!-- //end cf-item -->	

			
			<div style="" class="col-lg-3 cf-item">
				<header>
					<p><span></span><?php echo __('Resolution time') ;?></p>
				</header>
				<div class="content cf-piex" id="cf-pie-1" style="margin-left:0px;">					
					<?php include ("grafpie_time_geral.inc.php");  ?>
				</div>
			</div> <!-- //end cf-item -->	
					
			<div style="" class="col-lg-3 cf-item">
				<header>

					<?php 					
						//satisfaction, or not		
						//$sat = 0;										
						if($sat != 0) {
							echo "<p><span></span>" . __('Satisfaction')."</p>";						
						} 
						else {
							echo "<p><span></span>Top 5 " . __('Technician')."</p>";
						}	
					?>						
				</header>
				
				<div class="content cf-svp clearfix" id="svp-1">				
					<?php 
					//satisfaction, or not	
						//$sat = 0;				
						if($sat != 0) {
							echo '<div class="chart" data-percent="' . $satisf .'" > <span class="percent">' . $satisf . '</span><sup></sup> </div>';	
							}
						else {
							echo '<div id="grafsat" class="content cf-piexx" style="margin-left:0px;">';
							include ("grafbar_grupo.inc.php");
							echo ' </div>';
						}							
					 ?>						
				</div>								
			</div> <!-- //end cf-item -->		
			

		<!-- interval selector -->
		<div class="col-xs-3 col-sm-4 col-md-4 col-lg-1 form-group pull-right" style="float: right; width:125px;">
			<select id="reload_selecter" class="form-control pull-right">
				<option value="30">30s</option>						
				<option value="45">45s</option>			
				<option value="60">60s</option>
				<option value="120">120s</option>
				<option value="240">240s</option>
				<option value="300">300s</option>
			</select>
		</div>	
		<div>
			<button id="reload_page" type="button" class="btn btn-default pull-right">
				<i class="glyphicon glyphicon-refresh"></i><text id="countDownTimer"></text>
			</button>
		</div>		
		<!-- interval selector -->			
					
			
	</div> <!-- //end row -->
</div> <!-- //end main --> 
 	
</div> <!-- //end row -->		
		
</div> <!-- //end container -->

</div>

</body>
</html>
