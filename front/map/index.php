<?php

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");

global $DB;  

Session::checkLoginUser();
Session::checkRight("profile", READ);
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

<script src="./js/markerclusterer.js" type="text/javascript" ></script>
<link href="css/google_api.css" rel="stylesheet" type="text/css" />   

<script async defer
	src="https://maps.googleapis.com/maps/api/js?callback=initMap">
</script>  
<!-- src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript" > -->   
  
<script src="../js/bootstrap.min.js" type="text/javascript" ></script>  

<?php echo '<link rel="stylesheet" type="text/css" href="../css/style-'.$_SESSION['style'].'">';  ?> 

<style type="text/css">
	html { margin-top: 3px;}
</style>

</head>

<?php

//Add locations to dashboard map
$query_id =	"
SELECT id, address, town, state, country, name
FROM glpi_entities gt
WHERE id NOT IN (SELECT entities_id FROM glpi_plugin_dashboard_map)
AND gt.address IS NOT NULL
AND gt.town IS NOT NULL
ORDER BY gt.id ";

$result_id = $DB->query($query_id) or die ("erro id");


// check if any entity has address
$query1 = "SELECT entities_id FROM glpi_plugin_dashboard_map";
$result1 = $DB->query($query1);

$teste = $DB->fetch_assoc($result1);

$conta_teste = count($teste);

$DB->data_seek($result_id, 0) ;
while ($row = $DB->fetch_assoc($result_id))
{

	$local = $row['address'].",".$row['town'].",".$row['state'].",".$row['country'] ;
	$url = "http://maps.googleapis.com/maps/api/geocode/xml?sensor=false&address=".$local;    
	$contents1 = file_get_contents($url);
	$xml = simplexml_load_file($url);
	$ent_id = $row['id'];
	$response = $xml->status;
	$lat = $xml->result->geometry->location->lat;
	$lng = $xml->result->geometry->location->lng;
	$location = $row['name'];
	
if($response == 'OK') {

    $insert = "
	INSERT INTO glpi_plugin_dashboard_map (entities_id, location, lat, lng) 
	VALUES ('$ent_id', '$location', '$lat', '$lng')";			 
	
	$DB->query($insert);
	
	echo '<script type="text/javascript">alert("'.$location.' adicionado!");</script>';
}

else {
	echo '<script type="text/javascript">alert("'.$location.' não encontrado!");</script>';
}

}

//delete locations
$query_del = "
SELECT gpdm.entities_id AS id, gpdm.location
FROM glpi_plugin_dashboard_map gpdm, glpi_entities gt
WHERE gpdm.entities_id IN (SELECT id FROM glpi_entities WHERE address = ' ' AND town = ' ')
AND gt.id = gpdm.entities_id ";

$result_del = $DB->query($query_del) or die ("error del");

while ($row = $DB->fetch_assoc($result_del))
{
	$del = "DELETE FROM glpi_plugin_dashboard_map WHERE entities_id = ".$row['id'];
	$DB->query($del);
	
	$up = "UPDATE `glpi`.`glpi_entities` SET `town` = NULL WHERE `glpi_entities`.`id` = ".$row['id'];
	$DB->query($up);
	
	echo '<script type="text/javascript">alert("'.$row['location'].' removido!");</script>';
}

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

$period = $post_date;

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

?>

<!-- google maps - by Stevenes Donato -->

<script type="text/javascript">

var markers=[];	                 
var locations = [
<?php

$icon_red = "http://chart.apis.google.com/chart?chst=d_map_spin&chld=1|0|FF0000|14|_|";
$icon_green = "http://chart.apis.google.com/chart?chst=d_map_spin&chld=1|0|43B53C|14|_|";

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
  $url = $CFG_GLPI['root_doc']."/front/ticket.php?is_deleted=0&field[0]=view&searchtype[0]=contains&contains[0]=notold&link[1]=AND&field[1]=80&searchtype[1]=equals&contains[1]=".$row['entities_id']."&itemtype=Ticket&start=0";   	
  $host = "<a href=". $url ." target=_blank >" . $title . " (".$id.")</a>";  
  $status = $row['conta'];  
  $local = $row['location']; 
  $lat = $row['lat']; 
  $lng = $row['lng']; 
  $quant = $row['conta'];   
  //$num_up = $row['conta'];
  //$num_down = $row['conta'];   


if ($quant == 0) {
	$color = $icon_green.$quant."";
	$num_up = 1;	
	$num_down = 0;
	
}

else {
	$color = $icon_red.$quant."";
	$num_up = 0;	
	$num_down = 1;
}

echo "['$title', $lat, $lng, '$local', '$color', '$host', $id, $quant, $num_up, $num_down, '$url'],";

}
?>
    ];
    
function initialize() {
   
var mapOptions = {
	//mapTypeId: google.maps.MapTypeId.ROADMAP
	mapTypeId: google.maps.MapTypeId.HYBRID
//zoom:9,
//center: new google.maps.LatLng(40,-3)
	};
	
    var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
    var infowindow = new google.maps.InfoWindow();
    var marker, i;

    for (i = 0; i < locations.length; i++) {  

// avoid markers with same location
	 var min = .999999;
	 var max = 1.000001;    
  	 var offsetLat = locations[i][1] * (Math.random() * (max - min) + min);
    var offsetLng = locations[i][2] * (Math.random() * (max - min) + min);      
    
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(offsetLat, offsetLng),
        map: map,
		  title: locations[i][0],	        
        icon: {
        url: locations[i][4],
        scaledSize: new google.maps.Size(36, 52) // pixels
    },     
        host: locations[i][5],
        id: locations[i][6],
        quant: locations[i][7],
        //shadow:'https://chart.googleapis.com/chart?chst=d_map_pin_shadow'
        status: locations[i][7],
        num_up: locations[i][8],
        num_down: locations[i][9],
        url: locations[i][10]
        
      });

//marker animation
marker.setAnimation(google.maps.Animation.DROP);
      	
      google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
        return function() {
          infowindow.setContent('<b>'+locations[i][5] + '</b><br> <?php echo $state; ?>: ' + locations[i][7]);
          infowindow.open(map, marker);
        }
      })(marker, i)); 

// close infowindow when zoom change 
google.maps.event.addListener(map, 'zoom_changed', function() { infowindow.close() }); 
            
	markers.push(marker)			
    }

//center map
    var bounds = new google.maps.LatLngBounds();
    for (i = 0; i < locations.length; i++) {    
    bounds.extend(new google.maps.LatLng(locations[i][1], locations[i][2]));
 }
 map.fitBounds(bounds);

// Define the marker clusterer color

 var styles = [];
   for (var i = 0; i < 4; i++) {
      image_path = "./images/";
      image_ext = ".png";
      styles.push({
        url: image_path + i + image_ext,
        height: 52,
        width: 53
      });
    } 
 
        var mcOptions = { 
        zoomOnClick: true,
        gridSize:30,
        minimumClusterSize: 4,
        styles: styles,  
        maxZoom: 15 
         }
     
	//criar cluster
	var markerClusterer = new MarkerClusterer(map, markers, mcOptions);	
	
var iconCalculator = function(markers, numStyles) {
      var total_up = 0;
      var total_down = 0;
      for (var i = 0; i < markers.length; i++) {
        total_up += markers[i].num_up;
        total_down += markers[i].num_down;
      }

      var ratio_up = total_up / (total_up + total_down);

      //The map clusterer really does seem to use index-1... 
  		  index_ = 1;
  		
      if (ratio_up < 0.9999) {
        index_ = 4; // Could be 2, and then more code to use all 4 images
      }				

      return {
        text: (total_up + total_down),         
        index: index_
      };
    }

    markerClusterer.setCalculator(iconCalculator);	
			
	// Listen for a cluster to be clicked 
	google.maps.event.addListener(markerClusterer, 'mouseover', function(cluster) {
    var content = '';

    // Convert lat/long from cluster object to a usable MVCObject
    var info = new google.maps.MVCObject;
    info.set('position', cluster.center_);

    //----
    //Get markers
    var markers = cluster.getMarkers();
	 var titles = "";
	  
    //Get all the titles
    for(var i = 0; i < markers.length; i++) {
    	
    	if (markers[i].status == 0) 
    	{
    		titles += <?php echo '"<a href='. $url .' target=_blank style=color:#43B53C; "+markers[i].host + "</a><br>"'; ?>;    			
	   }
	
	   if (markers[i].status != 0)
		{
    		titles += <?php echo '"<a href='. $url .' target=_blank style=color:#990000; "+markers[i].host + "</a><br>"'; ?>;	
	   }
   }

 
    var infowindow = new google.maps.InfoWindow();
    infowindow.close();
    infowindow.setContent(titles); //set infowindow content to titles    
    infowindow.open(map, info);

//close infowindow
    google.maps.event.addListener(markerClusterer, 'mouseout', function() { infowindow.close() });

// close infowindow when zoom change
	google.maps.event.addListener(map, 'zoom_changed', function() { infowindow.close() });

});

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
		var estado = localStorage.getItem('statuse');	
		var head = document.getElementById('head-map').style.display;  		
		
		if (estado == 0 ) {
			document.getElementById('head-map').style.display = 'none';
			document.getElementById('head-map2').style.display = 'block';
	      document.getElementById('buttons').style.display = 'none';
	      document.getElementById('map_canvas').style.height = '100%';	      
			document.getElementById('map_canvas').style.marginTop = '5px';

			localStorage.setItem('statuse',0);			
		}
		if (estado == 1 ) {
			document.getElementById('head-map').style.display = 'block';
			document.getElementById('head-map2').style.display = 'none';
	      document.getElementById('buttons').style.display = 'block';
	      document.getElementById('map_canvas').style.height = '90%';	      
	      document.getElementById('map_canvas').style.marginTop = '15px';
	      
			localStorage.setItem('statuse',1);			
		}
	}


	function MudaEstado() {
		
		//localStorage.clear();		
	    var head = document.getElementById('head-map').style.display;
	    var buttons = document.getElementById('buttons').style.display;
	    var estado = localStorage.getItem('statuse');	    
	    	    	    	    	    	    
	    if(head == "block" && buttons == "block") {	       			    	
	        document.getElementById('head-map').style.display = 'none';
	        document.getElementById('head-map2').style.display = 'block';
	        document.getElementById('buttons').style.display = 'none';
	        document.getElementById('map_canvas').style.height = '100%';	           
	        document.getElementById('map_canvas').style.marginTop = '5px';	
	              		      
	        localStorage.setItem('statuse',0);	        
	     }	    	    
	    	    	    			      		        	    		    
		 if(head == "none" && buttons == "none") {		 	
	        document.getElementById('head-map').style.display = 'block';
	        document.getElementById('head-map2').style.display = 'none';
	        document.getElementById('buttons').style.display = 'block';
	        document.getElementById('map_canvas').style.height = '90%';
	        document.getElementById('map_canvas').style.marginTop = '15px';	
	                       
	        localStorage.setItem('statuse',1);	        
	     }	     	    	     
	}
</script>

<body onload="initialize(); ChecaEstado();" style="background:#e5e5e5;">

	<div id='container-fluid' style="margin: 0px 0px 0px 1%;" > 
						
		<button id="hidetop" onclick="MudaEstado();" class="btn btn-primary btn-sm">Show/Hide</button>
		
		<div id="head-map" class="row-fluid" style="z-index:-999; display:block;">
			<div id="titulo"><?php echo __('Tickets','dashboard')." ". __('by Entity','dashboard'); ?></div>				
		</div>	
		
		<div id="head-map2" class="col-md-12 fluid" style="display: none; margin-top:15px;">
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
				    
				    <input type="hidden"  id="period_option" name="period_option" value="<?php echo $period; ?>">   
				</div>	 	       						
			
			<?php
			if($conta_teste == "0") {
			
			echo '
			<div id="teste" class="alert alert-danger" role="alert"  style="margin-top:25px;"><a href='.$CFG_GLPI['root_doc'].'/front/entity.php target=_blank class="alert-link" > '.__('Fill in entities: address, city, state and country.','dashboard').' </a></div>	';
				}
			?>
				<script type="text/javascript">
				function mapa() {
					var stat = document.getElementById('stat_option').value;
					var period = document.getElementById('period_option').value;
					location.href='index.php?period_option=' + period + '&stat_option=' + stat;
				}

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
				</div>	 			
				<div id="map_canvas"></div>
				
	</div>
</body>
</html>
