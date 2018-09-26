
<?php

$query_os = "
SELECT CONCAT(glpi_operatingsystems.name,' ',glpi_operatingsystemversions.name) AS so, count( glpi_computers.id ) AS conta
FROM glpi_operatingsystems, glpi_computers, glpi_items_operatingsystems, glpi_operatingsystemversions
WHERE glpi_computers.is_deleted =0
AND glpi_items_operatingsystems.items_id = glpi_computers.id
AND glpi_operatingsystems.id = glpi_items_operatingsystems.operatingsystems_id
AND glpi_operatingsystemversions.id = glpi_items_operatingsystems.operatingsystemversions_id
".$ent_comp."
GROUP BY CONCAT(glpi_operatingsystems.name,' ',glpi_operatingsystemversions.name)
ORDER BY count( glpi_computers.id ) DESC ";
		
$result_os = $DB->query($query_os) or die('erro');

$arr_grf_os = array();


while ($row_result = $DB->fetch_assoc($result_os))	{ 
	$v_row_result = $row_result['so'];
	$arr_grf_os[$v_row_result] = $row_result['conta'];			
} 
	
$grf_os2 = array_keys($arr_grf_os);
$quant_os2 = array_values($arr_grf_os);

$conta_os = count($arr_grf_os);


echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graf_os').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '".__('Computers by Operating System','dashboard')."'
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
