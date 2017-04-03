<?php

$query2 = "
SELECT count( id ) AS chamados , DATEDIFF( solvedate, date ) AS days
FROM glpi_tickets
WHERE solvedate IS NOT NULL
AND is_deleted = 0
".$entidade."
GROUP BY days ";
		
$result2 = $DB->query($query2) or die('erro');


$arr_grf2 = array();
while ($row_result = $DB->fetch_assoc($result2))		
	{ 
		$v_row_result = $row_result['days'];
		$arr_grf2[$v_row_result] = $row_result['chamados'];			
	} 
	
$grf2 = array_keys($arr_grf2);
$quant2 = array_values($arr_grf2);

$conta = count($arr_grf2);


for($i=0; $i < 7; $i++) {

	if($quant2[$i] != 0) {
		$till[$i] = $quant2[$i];
	}
	else {
		$till[$i] = 0;
	}	
	
	$arr_days[] += $till[$i];

}

echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#time').highcharts({
            chart: {
            type: 'pie', 
            height: 350,          
            options3d: {
				enabled: true,
                alpha: 45,
                beta: 0
            },
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                //text: ''
                text: ''
            },
             legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                x: 0,
                y: 0,
                floating: true,
                borderWidth: 0,
                backgroundColor: '#FFFFFF',
                adjustChartSize: true,
                format: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.y} - ( {point.percentage:.1f}% )</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    size: '90%',
                    innerSize: 90,
                    depth: 40,
                    dataLabels: {
									format: '{point.y} - ( {point.percentage:.1f}% )',
                   		   style: {
                        			color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        				}
                    },
                showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '".__('Tickets','dashboard')."',
                data: [  {
                        name: '< 1 " .__('day','dashboard')."',
                        y: ".$arr_days[0].",
                        sliced: true,
                        selected: true
                    }, ['1 - 2 " .__('days','dashboard')."',  ".$arr_days[1]." ], ['2 - 3 " .__('days','dashboard')."',  ".$arr_days[2]." ],
                			['3 - 4 " .__('days','dashboard')."', ".$arr_days[3]." ], ['4 - 5 " .__('days','dashboard')."',  ".$arr_days[4]." ], 
                			['5 - 6 " .__('days','dashboard')."',  ".$arr_days[5]." ], ['6 - 7 " .__('days','dashboard')."',  ".$arr_days[6]." ]		]
            }]
        });
    });

		</script>"; 
		?>
