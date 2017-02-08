<?php

$query_os = "
SELECT glpi_manufacturers.name AS name, count( glpi_softwares.id ) AS conta
FROM glpi_manufacturers, glpi_softwares
WHERE glpi_softwares.is_deleted = 0
AND glpi_manufacturers.id = glpi_softwares.manufacturers_id
".$ent_soft."
GROUP BY glpi_manufacturers.name
ORDER BY count( glpi_softwares.id ) DESC
LIMIT 10 ";

		
$result_os = $DB->query($query_os) or die('erro');

$arr_grf_os = array();

if($unk != 0) {
$arr_grf_os[__('Unknown','dashboard')] = $unk;
}


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
        $('#graf_soft1').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '".__('Top 10 - Software Vendors','dashboard')."'
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
