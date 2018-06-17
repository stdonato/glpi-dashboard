<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}


# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '' || $sel_ent == -1) {
	//$sel_ent = 0;
	$entities = $_SESSION['glpiactiveentities'];
	$ent = implode(",",$entities);

	$entidade = "AND glpi_tickets.entities_id IN (".$ent.") ";
}

else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.") ";  //OR glpi_locations.is_recursive = 1
}
	

$sql_tec = "
SELECT count( glpi_tickets.id ) AS conta, glpi_locations.id AS loc_id, glpi_locations.completename AS name
FROM glpi_locations, glpi_tickets
WHERE glpi_tickets.locations_id = glpi_locations.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas."
".$entidade."
GROUP BY loc_id
ORDER BY conta DESC ";

$query_tec = $DB->query($sql_tec);

$contador = $DB->numrows($query_tec);

//chart height
if($contador > 9) {	
	$height = '1100';	
}
else {
	$height = '500';
}


if($DB->fetch_assoc($query_tec) != '') {

echo "
<script type='text/javascript'>

$(function () {

        $('#graf1').highcharts({
            chart: {
                type: 'bar',
                height: ".$height."
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
            categories: ";

				$DB->data_seek($query_tec, 0) ;
				$categories = array();
				while ($tecnico = $DB->fetch_assoc($query_tec)) {
				    $categories[] = $tecnico['name'];
				}
				echo json_encode($categories);
				
				echo ",
                title: {
                    text: null
                },
                labels: {
                	style: {
                        fontSize: '12px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    },
                     borderWidth: 1,
                		borderColor: 'white',
                		shadow:true,
                		showInLegend: false
                },
                 series: {
			       	  animation: {
			           duration: 2000,
			           easing: 'easeOutBounce'
			       	  }
			  			 }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 100,
                floating: true,
                borderWidth: 0,
                //backgroundColor: '#FFFFFF',
                shadow: true,
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
            	 dataLabels: {
            	 	//color: '#000099'
            	 	},
                name: '". __('Tickets','dashboard')."',
                data: [
					";
					
									
					//zerar rows para segundo while
					$DB->data_seek($query_tec, 0) ;
					while ($tecnico = $DB->fetch_assoc($query_tec)) {
					 
					 echo $tecnico['conta'].",";
					}
					
					echo "]
            }]
        });
    });

</script> ";
	}
		?>
