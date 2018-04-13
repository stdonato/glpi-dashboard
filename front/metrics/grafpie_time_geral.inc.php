<?php

if($id_grp != '') {

	$query2 = "
	SELECT count( glpi_tickets.id ) AS chamados , DATEDIFF( glpi_tickets.solvedate, date ) AS days
	FROM `glpi_groups_tickets`, glpi_tickets, glpi_groups, glpi_requesttypes
	WHERE glpi_groups_tickets.`groups_id` = ".$id_grp."
	AND glpi_groups_tickets.`groups_id` = glpi_groups.id
	AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
	AND glpi_tickets.is_deleted = 0
	AND glpi_tickets.`requesttypes_id` = glpi_requesttypes.id
	AND glpi_tickets.solvedate IS NOT NULL
	$period
	$entidade
	GROUP BY days ";
}

else {

	$query2 = "
	SELECT count( glpi_tickets.id ) AS chamados , DATEDIFF( glpi_tickets.solvedate, date ) AS days
	FROM glpi_tickets
	WHERE glpi_tickets.solvedate IS NOT NULL
	AND glpi_tickets.is_deleted = 0
	$period
	$entidade
	GROUP BY days ";
}
		
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


for($i=0; $i < 8; $i++) {

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
        $('#cf-pie-1').highcharts({
            chart: {
            type: 'pie',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                height:260,
                backgroundColor:'transparent'                
                //backgroundColor: '#2b2b2b'
            },
            title: {
                text: ''
            },
             legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                //floating: true,
                borderWidth: 0,
                //backgroundColor: '#FFFFFF',
                adjustChartSize: true,
                format: '{series.name}: <b>{point.percentage:.1f}%</b>',
                 itemStyle: {
	                 font: '9pt Trebuchet MS, Verdana, sans-serif',                 
   	              color: '#A0A0A0'                 
               } 
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.y} - ( {point.percentage:.1f}% )</b>'
            },
            credits: {
   	         enabled: false
	   	     },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    size: '105%',

                    dataLabels: {
									//format: '{point.y} - ( {point.percentage:.1f}% )',
									format: '{point.percentage:.1f}% ',
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

					dataLabels: {
                   color:'black',
                   distance: -25,
                   style: {fontWeight: 'bold'},
                   formatter: function () {
                       if(this.percentage!=0)  return Math.round(this.percentage)  + '%';

                   }
                },                
                
                data: [  {
                        name: '< 1 " .__('day','dashboard')."',
                        y: ".$arr_days[0].",
                        sliced: false,
                        selected: false
                    }, ['1 - 2 " .__('days','dashboard')."',  ".$arr_days[1]." ], ['2 - 3 " .__('days','dashboard')."',  ".$arr_days[2]." ],
                			['3 - 4 " .__('days','dashboard')."', ".$arr_days[3]." ], ['4 - 5 " .__('days','dashboard')."',  ".$arr_days[4]." ], 
                			['5 - 6 " .__('days','dashboard')."',  ".$arr_days[5]." ], ['6 - 7 " .__('days','dashboard')."',  ".$arr_days[6]." ]		]
            }]
        });
    });

		</script>"; 
		?>
