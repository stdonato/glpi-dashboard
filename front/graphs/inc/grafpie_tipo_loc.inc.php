
<?php

if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";	
}	

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}

//problems
$query_p = "
SELECT COUNT(id) AS total
FROM glpi_problems
WHERE glpi_problems.is_deleted = 0     
AND glpi_problems.date ".$datas."
AND glpi_problems.entities_id = ".$id_loc." 
 ";		
 
$result_p = $DB->query($query_p) or die('erro');
$problems = $DB->fetch_assoc($result_p);

//tickets by type
$query2 = "
SELECT COUNT(glpi_tickets.id) AS tick, glpi_tickets.type AS tipo
FROM glpi_tickets
WHERE glpi_tickets.date ".$datas."
AND glpi_tickets.is_deleted = 0     
AND glpi_tickets.locations_id = ".$id_loc."    
GROUP BY glpi_tickets.type
ORDER BY tipo  ASC ";
		
$result2 = $DB->query($query2) or die('erro');

$arr_grft2 = array();
while ($row_result = $DB->fetch_assoc($result2))		
	{ 
		$v_row_result = $row_result['tipo'];
		$arr_grft2[$v_row_result] = $row_result['tick'];			
	} 
	
$grft2 = array_keys($arr_grft2);

$quantt2 = array_values($arr_grft2);

$conta = count($arr_grft2);

if($conta == 1) {

	if($grft2[0] == 1) {		
		$grft2[0] = __('Incident'); 
		}
		
	if($grft2[0] == 2) {		
		$grft2[0] = __('Request'); 
		}	
	if($problems['total'] != 0) {	
		$grft2[1] = __('Problem'); 
		$quantt2[1] = $problems['total'];
	}	
}


if($conta > 1) {
	$grft2[0] = __('Incident'); 
	$grft2[1] = __('Request');
	
//	if($problems['total'] != 0) {	
		$grft2[2] = __('Problem'); 
		$quantt2[2] = $problems['total']; 		
//	}	
}

	
echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graf_tipo').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '".__('Tickets','dashboard')." ".__('by Type','dashboard')."'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    size: '85%',
 					dataLabels: {
								format: '{point.y} - ( {point.percentage:.1f}% )',
                   		style: {
                        	color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        		},
                        connectorColor: 'black'
                    },
                showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '".__('Tickets','dashboard')."',
                data: [
                   {
                        name: '" . $grft2[0] . "',
                        y: $quantt2[0],
                        sliced: true,
                        selected: true
                    },";
if($conta == 1) {                                      
	for($i = 1; $i < $conta; $i++) {    
	     echo '[ "' . $grft2[$i] . '", '.$quantt2[$i].'],';
	        }
        }  
        
if($conta > 1) {                                      
	for($i = 1; $i <= $conta; $i++) {    
	     echo '[ "' . $grft2[$i] . '", '.$quantt2[$i].'],';
	        }
        }                           
                                                         
echo "                ],
            }]
        });
    });

		</script>"; 
		
		?>
