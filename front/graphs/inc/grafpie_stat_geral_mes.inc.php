
<?php

if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";	
}	

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}

$query2 = "
SELECT COUNT(glpi_tickets.id) as tick, glpi_tickets.status as stat
FROM glpi_tickets
WHERE glpi_tickets.date ".$datas."
AND glpi_tickets.is_deleted = 0        
".$entidade." 
GROUP BY glpi_tickets.status
ORDER BY stat  ASC ";

		
$result2 = $DB->query($query2) or die('erro');

$arr_grf2 = array();
while ($row_result = $DB->fetch_assoc($result2))		
	{ 
		$v_row_result = $row_result['stat'];
		$arr_grf2[$v_row_result] = $row_result['tick'];			
	} 
	
$grf2 = array_keys($arr_grf2);
$quant2 = array_values($arr_grf2);

$conta = count($arr_grf2);
	
/*

   	// Radialize the colors
		Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
		    return {
		        radialGradient: { cx: 0.5, cy: 0.3, r: 0.9 },
		        stops: [
		            [0, color],
		            [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
		        ]
		    };
		});

*/	
	

echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graf2').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '".__('Tickets by Status','dashboard')."'
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
                        name: '" . Ticket::getStatus($grf2[0]) . "',
                        y: $quant2[0],
                        sliced: true,
                        selected: true
                    },";
                    
				for($i = 1; $i < $conta; $i++) {    
				     echo '[ "' . Ticket::getStatus($grf2[$i]) . '", '.$quant2[$i].'],';
				        }                    
                                                         
echo "        ]
            }]
        });
    });

		</script>"; 
		?>
