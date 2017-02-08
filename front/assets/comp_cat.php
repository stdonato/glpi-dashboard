
<?php

$query2 = "
SELECT glpi_computertypes.name AS so, count( glpi_computers.id ) AS conta
FROM glpi_computertypes, glpi_computers
WHERE glpi_computers.is_deleted =0
AND glpi_computertypes.id = glpi_computers.computertypes_id
".$ent_comp."
GROUP BY glpi_computertypes.name
ORDER BY count( glpi_computers.id ) DESC ";

		
$result2 = $DB->query($query2) or die('erro');

$arr_grf2 = array();
while ($row_result = $DB->fetch_assoc($result2))		
	{ 
	$v_row_result = $row_result['so'];
	$arr_grf2[$v_row_result] = $row_result['conta'];			
	} 
	
$grf2 = array_keys($arr_grf2);
$quant2 = array_values($arr_grf2);

$conta = count($arr_grf2);


echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graf_cat').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '".__('Computers by Type','dashboard')."'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    //size: '85%',
                    dataLabels: {
								format: '{point.y} - ( {point.percentage:.1f}% )',
                   		style: {
                        	color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        		},
                        //connectorColor: 'black'
                    },
                showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '',
                data: [
                    {
                        name: '" . $grf2[0] . "',
                        y: $quant2[0],
                        sliced: true,
                        selected: true
                    },";
                    
for($i = 1; $i < $conta; $i++) {    
     echo '[ "' . Ticket::getStatus($grf2[$i]) . '", '.$quant2[$i].'],';
        }                    
                                                         
echo "                ]
            }]
        });
    });

		</script>"; 
		?>
