<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/inc/config.php");

global $DB;  

Session::checkLoginUser();
Session::checkRight("profile", READ);

//check if exists google maps api key
/*$query_key = "SELECT * FROM glpi_plugin_dashboard_config WHERE name = 'map_key'"; 
$res_key = $DB->query($query_key);
$api_key = $DB->result($res_key,0,'value'); 

if($api_key != '') {
	$key = $api_key;
}
else {
	$key = '';
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=\"map_key.php\"'>";
}
*/
?>

<html> 
<head>
<title>GLPI - <?php echo __('Tickets Map','dashboard'); ?></title>

<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="en-us" />
<meta http-equiv="refresh" content= "180"/> 

<link rel="icon" href="../img/dash.ico" type="image/x-icon" />
<link rel="shortcut icon" href="../img/dash.ico" type="image/x-icon" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="../css/font-awesome.css" type="text/css" rel="stylesheet" />
<script src="../js/jquery.js" type="text/javascript" ></script>

<!--<script src="./js/markerclusterer.js" type="text/javascript" ></script>
<link href="css/google_api.css" rel="stylesheet" type="text/css" />  -->   

<?php 
//echo '<script async defer src="https://maps.googleapis.com/maps/api/js?sensor=false&key='.$key.'">'; 
//echo "</script>\n" 
?>  

<script src="../js/bootstrap.min.js" type="text/javascript" ></script>  

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 

  <link rel="stylesheet" href="css/leaflet.css" />
  <script src="js/leaflet.js"></script>

	<link rel="stylesheet" href="css/MarkerCluster.css" />
   <link rel="stylesheet" href="css/MarkerCluster.Default.css" />
	<script src="js/leaflet.markercluster-src.js"></script>

	<link rel="stylesheet" href="css/leaflet-beautify-marker-icon.css">
	<script src="js/leaflet-beautify-marker-icon.js"></script>	

	<style type="text/css">
		html { margin-top: 3px;}
		a, a:visited, a:focus, a:hover { color: #0776cc;}	
		#map_canvas {
			margin-left: auto;
			margin-right: auto;
			float: none;
			margin-top: 25px;
			width: 93%;
			height: 100%;
		}
		.mycluster-green {
			width: 32px;
			height: 32px;
			line-height: 32px;
			background-image: url('images/0-32.png');
			text-align: center;		
		}
		
		.mycluster-red {
			width: 32px;
			height: 32px;
			line-height: 32px;
			background-image: url('images/3-32.png');
			text-align: center;		
		}
	</style>

</head>

<?php

// check if any entity has address
$query1 = "SELECT entities_id FROM glpi_plugin_dashboard_map";
$result1 = $DB->query($query1);
$teste = $DB->fetch_assoc($result1);

$conta_teste = count($teste);

$status = "";
$status_open = "('1','2','3','4','13','14')";
$status_close = "('5','6')";	
$status_all = "('1','2','3','4','5','6','13','14')";

if(isset($_GET['stat_option'])) {
	
	if($_GET['stat_option'] == "open") {		
		$status = $status_open;
		$stat = "open";
		$state = __('Opened','dashboard');
	}
	if($_GET['stat_option'] == "closed") {
		$status = $status_close;
		$stat = "closed";
		$state = __('Closed','dashboard');
	}
	if($_GET['stat_option'] == "all") {
		$status = $status_all;
		$stat = "all";	
		$state = __('Overall','dashboard');
	}
	if($_GET['stat_option'] == "") {		
		$status = $status_open;
		$stat = "open";
		$state = __('Opened','dashboard');
	}
}

else {
		$status = $status_open;
		$stat = "open";
		$state = __('Opened','dashboard');
	}

if(isset($_GET['period_option'])) {

	$post_date = $_GET['period_option'];
	$period = $_GET['period_option'];
	
	switch($post_date) {
	
		case ("today") :
		   $data_ini2 = date('Y-m-d');
		   $data_fin2 = date('Y-m-d');														   
		   $sel_date = "AND gt.date LIKE '".$data_ini2."%'";	
		break;
		case ("week") :
		   $data_ini2 = date('Y-m-d', strtotime('-1 week'));
		   $data_fin2 = date('Y-m-d');
			$sel_date = "AND gt.date BETWEEN '" . $data_ini2 ." 00:00:00' AND '".$data_fin2." 23:59:59'";
		break;
		case ("month") :
		   $data_ini2 = date('Y-m-d', strtotime('-1 month'));
		   $data_fin2 = date('Y-m-d');					
			$sel_date = "AND gt.date BETWEEN '" . $data_ini2 ." 00:00:00' AND '".$data_fin2." 23:59:59'";
		break;				
		case ("all") :
		   $data_ini2 = date('Y-m-d', strtotime('-1 year'));
		   $data_fin2 = date('Y-m-d');								
			$sel_date = "";
		break;	
		default:
			$sel_date = "";
	} 
}

else {
	$period = "all";
	$data_ini2 = date('Y-m-d', strtotime('-1 year'));
   $data_fin2 = date('Y-m-d');								
	$sel_date = "";
}

?>

<!-- maps - by Stevenes Donato -->
<script type="text/javascript">	 
                
var locations = 
<?php

$locations = [];

/*
$icon_red = "http://chart.apis.google.com/chart?chst=d_map_spin&chld=1|0|FF0000|14|_|";
$icon_green = "http://chart.apis.google.com/chart?chst=d_map_spin&chld=1|0|43B53C|14|_|";
*/

//select not closed tickets
$query_loc = "
	SELECT gpdm.entities_id, gpdm.location, gpdm.lat, gpdm.lng, count( gt.id ) AS conta
	FROM glpi_plugin_dashboard_map gpdm
	LEFT JOIN glpi_tickets gt ON gpdm.entities_id = gt.entities_id
	AND gt.status IN ".$status."
	".$sel_date."
	AND gt.is_deleted = 0
	GROUP BY gpdm.id
	ORDER BY gpdm.entities_id ";

$result_loc = $DB->query($query_loc) or die ("erro");

while ($row = $DB->fetch_assoc($result_loc))
{
 
  $id = $row['entities_id'];
  $title = $row['location'];       	
  $url = $CFG_GLPI['root_doc']."/front/ticket.php?is_deleted=0&criteria[0][field]=12&criteria[0][searchtype]=equals&criteria[0][value]=notold&criteria[1][link]=AND&criteria[1][field]=80&criteria[1][searchtype]=equals&criteria[1][value]=".$row['entities_id']."&itemtype=Ticket&start=0";   	
  $host = "<a href=". $url ." target=_blank >" . $title . " </a>";  
  $status = $row['conta'];  
  $local = $row['location']; 
  $lat = $row['lat']; 
  $lng = $row['lng']; 
  $quant = $row['conta'];  

	if ($quant == 0) {
		//$color = $icon_green.$quant."";
		$color = "";
		$num_up = 1;	
		$num_down = 0;
		
	}
	
	else {
		//$color = $icon_red.$quant."";
		$color = "";
		$num_up = 0;	
		$num_down = 1;
	}

$locations[] = [
        $title,
        $lat,
        $lng,
        $local,
        $color,
        $host,
        $id,
        $quant,
        $num_up,
        $num_down,
        $url
    ];
}

echo json_encode($locations);
?>
;
    
function initialize() {
   
	latlng = L.latLng(-9.95126,-63.9059);
	var map = L.map('map_canvas').setView([-9.95126,-63.9059], 13);
	    
		var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);
		
		for(var i = 0; i < locations.length; i++) {
			var markers = new L.MarkerClusterGroup({
        		iconCreateFunction: function(cl) {
            var layer = cl.getAllChildMarkers()[0].l;
            var cor = layer === 1 ? 'green' : 'red';            
            return L.divIcon({ html: '<b>' + cl.getChildCount() + '</b>', className: 'mycluster-' + cor, iconSize: L.point(32, 32) });
        	},        			
			maxClusterRadius: 50, spiderfyOnMaxZoom: false, showCoverageOnHover: true, zoomToBoundsOnClick: false 
			});
		}		
		
		//var markers = L.markerClusterGroup();

	  //marcadores individuais			
		var arr_markers = [];
		
		for (var i = 0; i < locations.length; i++) {
			
			var a = locations[i];

			var cor = a[8] === 1 ? '#43B53C' : '#FF0000';
			//var tipo = a[8] === 1 ? 'green' : 'red';
										 	 		 	 			
			var options = { isAlphaNumericIcon: true, text: a[7], iconShape: 'marker', borderColor: cor, textColor: cor};
		   var marker = L.marker([a[1], a[2]], {icon: L.BeautifyIcon.icon(options), draggable: true}, {title: a[3]});
		   marker.l = a[8];
		   
		   //show popup on mouse over
/*		   marker.on('mouseover', function (e) {
            this.openPopup();
         });*/
/*         marker.on('mouseout', function (e) {
            this.closePopup();
         });	*/	   

			marker.bindPopup(a[5]);
			markers.addLayer(marker);
			
			//array to center
			arr_markers.push([a[1], a[2]]);
		}

		map.addLayer(markers);
		
		//center map		
		var bounds = L.latLngBounds(arr_markers);
		map.fitBounds(bounds);

/*		
  var popup = markers[i].name +
              '<br/>' + markers[i].city +
              '<br/><b>IATA/FAA:</b> ' + markers[i].iata_faa +
              '<br/><b>ICAO:</b> ' + markers[i].icao +
              '<br/><b>Altitude:</b> ' + Math.round( markers[i].alt * 0.3048 ) + ' m' +
              '<br/><b>Timezone:</b> ' + markers[i].tz;		
*/
}

</script> 

<script type="text/javascript" >
	$(document).ready(function(){
		var a = document.getElementById('stat_option').value;	
			
		if ( a === 'open')
		  { $( ".btn0" ).addClass( "active" ); }
		  
		else if ( a === 'closed')
		  {  $( ".btn1" ).addClass( "active" ); }
		
		else if ( a === 'all')
		  {  $( ".btn2" ).addClass( "active" ); }
		  
		else 
		  {  $( ".btn0" ).addClass( "active" ); }      
	
	});
	
	
	$(document).ready(function(){
		var b = document.getElementById('period_option').value;
			
		if ( b === 'today')
		  { $( ".btna" ).addClass( "active" ); }
		  
		else if ( b === 'week')
		  {  $( ".btnb" ).addClass( "active" ); }
		
		else if ( b === 'month')
		  {  $( ".btnc" ).addClass( "active" ); }
		  
		else if ( b === 'all')
		  {  $( ".btnd" ).addClass( "active" ); }
		  
		else 
		  {  $( ".btnd" ).addClass( "active" ); }      
		
	});
</script>

<script type="text/javascript">

		function ChecaEstado() {
		
		//localStorage.clear();	
		var estado = localStorage.getItem('status');	
		var head = document.getElementById('head-map').style.display;  		
		
		if (estado == 0 ) {
			document.getElementById('head-map').style.display = 'none';
			document.getElementById('head-map2').style.display = 'block';
	      document.getElementById('buttons').style.display = 'none';
	      document.getElementById('map_canvas').style.height = '100%';
	      //document.getElementById('charts').style.marginTop = '20px';
			document.getElementById('map_canvas').style.marginTop = '5px';

			localStorage.setItem('status',0);			
		}
		if (estado == 1 ) {
			document.getElementById('head-map').style.display = 'block';
			document.getElementById('head-map2').style.display = 'none';
	      document.getElementById('buttons').style.display = 'block';
	      document.getElementById('map_canvas').style.height = '90%';
	      //document.getElementById('charts').style.marginTop = '20px';
	      document.getElementById('map_canvas').style.marginTop = '15px';
	      
			localStorage.setItem('status',1);			
		}
	}


	function MudaEstado() {
		
		//localStorage.clear();		
	    var head = document.getElementById('head-map').style.display;
	    var buttons = document.getElementById('buttons').style.display;
	    var estado = localStorage.getItem('status');	    
	    	    	    	    	    	    
	    if(head == "block" && buttons == "block") {	       			    	
	        document.getElementById('head-map').style.display = 'none';
	        document.getElementById('head-map2').style.display = 'block';
	        document.getElementById('buttons').style.display = 'none';
	        document.getElementById('map_canvas').style.height = '100%';
	        //document.getElementById('charts').style.marginTop = '15px';	   
	        document.getElementById('map_canvas').style.marginTop = '5px';	
	              		      
	        localStorage.setItem('status',0);	        
	     }	    	    
	    	    	    			      		        	    		    
		 if(head == "none" && buttons == "none") {		 	
	        document.getElementById('head-map').style.display = 'block';
	        document.getElementById('head-map2').style.display = 'none';
	        document.getElementById('buttons').style.display = 'block';
	        document.getElementById('map_canvas').style.height = '90%';
	        document.getElementById('map_canvas').style.marginTop = '15px';	
	                       
	        localStorage.setItem('status',1);	        
	     }	     	    	     
	}
</script>

<body onload="initialize(); ChecaEstado();" style="background:#e5e5e5;">


	<div id='container-fluid' style="margin: 0px 0px 0px 2%;" > 		

		<button id="hidetop" onclick="MudaEstado();" class="btn btn-primary btn-sm">Show/Hide</button>

		<div id="head-map" class="row-fluid" style="z-index:-999; display:block;">
			<div id="titulo_map"><?php echo __('Tickets','dashboard')." ". __('by Entity','dashboard'); ?></div>	
		</div>	
		
		<div id="head-map2" class="col-md-12 col-sm-12 fluid" style="display: none; margin-top:15px;">
			<div id="titulo2"><h3><?php echo __('Tickets','dashboard')." ". __('by Entity','dashboard'); ?></h3></div>				
		</div>
		
			<div id="charts" class="row-fluid chart" > 		      
				<div id="buttons" class="btn-toolbar" role="toolbar" class='center' style="margin-left:1%; margin-right:auto; display:block;" >				          
				    <div class="btn-group" data-toggle-name="radius_options" data-toggle="buttons-radio">		            
				        <button type="button" value="open" 	data-toggle="button" name="stat" class="btn btn-default btn0" onclick="document.getElementById('stat_option').value='open'; mapa();"><?php echo __('Opened','dashboard'); ?></button>
				        <button type="button" value="closed" data-toggle="button" name="stat" class="btn btn-default btn1" onclick="document.getElementById('stat_option').value='closed'; mapa();" ><?php echo __('Closed'); ?></button>
				        <button type="button" value="all" 	data-toggle="button" name="stat" class="btn btn-default btn2" onclick="document.getElementById('stat_option').value='all'; mapa();" ><?php echo __('All','dashboard'); ?></button>        
				    </div>
				    
				    <input type="hidden" id="stat_option" name="stat_option" value="<?php echo $stat; ?>">
				    
				    <div class="btn-group" data-toggle-name="sort_options" data-toggle="buttons-radio" style="margin-left: 25px;;">
				        <button type="button" value="today" 	data-toggle="button" name="period" class="btn btn-default btna" onclick="document.getElementById('period_option').value='today'; mapa();"><?php echo __('Today'); ?></button>
				        <button type="button" value="week" 	data-toggle="button" name="period" class="btn btn-default btnb" onclick="document.getElementById('period_option').value='week'; mapa();"><?php echo __('Last 7 days','dashboard'); ?></button>
				        <button type="button" value="month"  data-toggle="button" name="period" class="btn btn-default btnc" onclick="document.getElementById('period_option').value='month'; mapa();"><?php echo __('Last 30 days','dashboard'); ?></button>
				        <button type="button" value="all" 	data-toggle="button" name="period" class="btn btn-default btnd" onclick="document.getElementById('period_option').value='all'; mapa();"><?php echo __('All', 'dashboard'); ?></button>
				    </div>
				    
				    <input type="hidden" id="period_option" name="period_option" value="<?php echo $period; ?>">    
				</div>	
				
				<?php				
					if($conta_teste == "0") {				
						echo '<div id="teste" class="alert alert-danger" role="alert"  style="margin-top:25px;"><a href='.$CFG_GLPI['root_doc'].'/front/entity.php target=_blank class="alert-link" ><b> '.__('Enter the latitude and longitude in Administration -> Entities -> Dashboard Map','dashboard').' </b></a></div>	';
					}
				?> 	       

				<script type="text/javascript">
				function mapa() {
					var stat = document.getElementById('stat_option').value;
					var period = document.getElementById('period_option').value;
					location.href='index.php?period_option=' + period + '&stat_option=' + stat;
				}
				</script>

				<script type="text/javascript" >
				$(function () {
				    $('div.btn-group[data-toggle-name]').each(function () {
				        var group = $(this);
				        var form = group.parents('form').eq(0);
				        var name = group.attr('data-toggle-name');
				        var hidden = $('input[name="' + name + '"]', form);
				        $('button', group).each(function () {
				            var button = $(this);
				            button.on('click',  function () {
				                hidden.val($(this).val());
				               // alert(hidden.val());
				            });
				            if (button.val() == hidden.val()) {
				                button.addClass('active');                
				            }
				        });
				    });
				});				
				</script> 			
				<div id="map_canvas"></div>
			</div>
	</div>
</body>
</html>
