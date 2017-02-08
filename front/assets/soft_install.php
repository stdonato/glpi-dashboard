<?php

$query_os = "
SELECT glpi_softwares.name AS name, COUNT( glpi_computers_softwareversions.computers_id ) AS conta
FROM glpi_computers_softwareversions, glpi_softwares, glpi_softwareversions
WHERE glpi_softwares.id = glpi_softwareversions.softwares_id
AND glpi_computers_softwareversions.softwareversions_id = glpi_softwareversions.id
".$ent_soft."
GROUP BY name
ORDER BY conta DESC
LIMIT 10 ";

		
$result_os = $DB->query($query_os) or die('erro');

$arr_grf_os = array();


while ($row_result = $DB->fetch_assoc($result_os))		
	{ 
	$v_row_result = $row_result['name'];
	$arr_grf_os[$v_row_result] = $row_result['conta'];			
	} 
	
$grf_os2 = array_keys($arr_grf_os);
$quant_os2 = array_values($arr_grf_os);

$conta_os = count($arr_grf_os);


echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graf_soft2').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '".__('Top 10 - Software Install','dashboard')."'
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
                        name: '" . $grf_os2[0] . "',
                        y: $quant_os2[0],
                        sliced: true,
                        selected: true
                    },";
                    
for($i = 1; $i < $conta_os; $i++) {    
     echo '[ "' . $grf_os2[$i] . '", '.$quant_os2[$i].'],';
        }                    
                                                         
echo "                ]
            }]
        });
    });

		</script>"; 
		?>
