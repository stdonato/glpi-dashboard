<?php

$querydays = "
SELECT count(glpi_tickets.id) AS chamados , DATEDIFF( glpi_tickets.solvedate, glpi_tickets.date ) AS days
FROM glpi_tickets
WHERE glpi_tickets.solvedate IS NOT NULL
AND glpi_tickets.is_deleted = 0
GROUP BY days ";
		
$resultdays = $DB->query($querydays) or die('erro');

$arr_keys = array();
$arr_days = array();

while ($row_result = $DB->fetch_assoc($resultdays)) { 
	$v_row_result = $row_result['days'];
	$arr_days[$v_row_result] = 0;						
}

$conta = count($arr_days);

if( $conta < 9) {
	for($i=$conta; $i < 9; $i++) {		
		$arr_days[$i] = 0;			
	}	
}	


$query2 = "
SELECT count(glpi_tickets.id) AS chamados , DATEDIFF( glpi_tickets.solvedate, glpi_tickets.date ) AS days
FROM glpi_tickets
WHERE glpi_tickets.solvedate IS NOT NULL
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.type = ".$id_tip."
AND glpi_tickets.date ".$datas."
".$entidade_a."
GROUP BY days ";
		
$result2 = $DB->query($query2) or die('erro');

while ($row_result = $DB->fetch_assoc($result2)){ 	
	$v_row_result = $row_result['days'];
	$arr_keys[$v_row_result] = $row_result['chamados'];			
}

$arr_tick = array_merge($arr_keys,$arr_days);
	
$days = array_keys($arr_tick);
$keys = array_keys($arr_tick);

$arr_more8 = array_slice($arr_keys,8);
$more8 = array_sum($arr_more8);

$quant2 = array_values($arr_tick);

array_push($quant2,$more8);

$conta_q = count($quant2)-1;


echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graf_time1').highcharts({
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
                text: '".__('Ticket Solving Period','dashboard')."'
            },

            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.y} - ({point.percentage:.1f}%)</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    size: '85%',
                    innerSize: 90,
                    depth: 40,
                    dataLabels: {
									format: '{point.y} - ({point.percentage:.1f}%)',
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
 					 data: [  ['< 1 " .__('day','dashboard')."',  ".$quant2[0]." ], ['1 " .__('day','dashboard')."',  ".$quant2[1]." ], ['2 " .__('days','dashboard')."',  ".$quant2[2]." ],
                			['3 " .__('days','dashboard')."', ".$quant2[3]." ], ['4 " .__('days','dashboard')."',  ".$quant2[4]." ],
                			['5 " .__('days','dashboard')."',  ".$quant2[5]." ], ['6 " .__('days','dashboard')."',  ".$quant2[6]." ],
                			['7 " .__('days','dashboard')."',  ".$quant2[7]." ], ['8+ " .__('days','dashboard')."',  ".$quant2[$conta_q]." ]	
                			].filter(function(d) {return d[1] > 0})
            }]
        });
    });
		</script>"; 
?>
