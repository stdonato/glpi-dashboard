<?php

$arr_days = array();

$query2 = "
SELECT count( id ) AS chamados , DATEDIFF( solvedate, date ) AS days
FROM glpi_tickets
WHERE solvedate IS NOT NULL
AND is_deleted = 0
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")     
".$entidade."
GROUP BY days ";

$result2 = $DB->query($query2) or die('erro');

$arr_keys = array();

while ($row_result = $DB->fetch_assoc($result2)) {
	$v_row_result = $row_result['days'];
	$arr_keys[$v_row_result] = $row_result['chamados'];
}

$grf2 = array_keys($arr_keys);
$quant2 = array_values($arr_keys);

$conta = count($arr_keys);

/*for($i=0; $i <= $conta; $i++) {

	if($quant2[$i] != 0) {
		$till[$i] = $quant2[$i];
	}
	else {
		$till[$i] = 0;
	}

	$arr_days[] += $till[$i];
}*/

$arr_more8 = array_slice($arr_keys,8);
$more8 = array_sum($arr_more8);

echo "
<script type='text/javascript'>

$(function () {

		// Build the chart
        $('#graf9').highcharts({
            chart: {
            type: 'pie',
            options3d: {
				enabled: false,
                alpha: 45,
                beta: 0
            },
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                //text: '".__('Ticket Solving Period','dashboard')."'
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
                adjustChartSize: true,
                format: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            credits: {
                enabled: false
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
                        y: ".$arr_keys[0].",
                        sliced: true,
                        selected: true
                    }, ['1 " .__('day','dashboard')."',  ".$arr_keys[1]." ], ['2 " .__('days','dashboard')."',  ".$arr_keys[2]." ],
                			['3 " .__('days','dashboard')."', ".$arr_keys[3]." ], ['4 " .__('days','dashboard')."',  ".$arr_keys[4]." ],
                			['5 " .__('days','dashboard')."',  ".$arr_keys[5]." ], ['6 " .__('days','dashboard')."',  ".$arr_keys[6]." ],
                			['7 " .__('days','dashboard')."',  ".$arr_keys[7]." ], ['8+ " .__('days','dashboard')."',  ".$more8." ]		]
            }]
        });
    });

		</script>";
		?>
