<?php

if($id_grp != '') {

	$query2 = "
	SELECT glpi_requesttypes.name AS request, count( glpi_tickets.id ) AS total
	FROM `glpi_groups_tickets`, glpi_tickets, glpi_groups, glpi_requesttypes
	WHERE glpi_groups_tickets.`groups_id` = ".$id_grp."
	AND glpi_groups_tickets.`groups_id` = glpi_groups.id
	AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
	AND glpi_tickets.is_deleted = 0
	AND glpi_tickets.`requesttypes_id` = glpi_requesttypes.id
	$period
	$entidade
	GROUP BY request
	ORDER BY total DESC ";
}

else {
	$query2 = "
	SELECT glpi_requesttypes.name AS request, count( glpi_tickets.id ) AS total
	FROM `glpi_tickets`, glpi_requesttypes
	WHERE glpi_tickets.is_deleted =0
	AND glpi_tickets.`requesttypes_id` = glpi_requesttypes.id
	$period
	$entidade
	GROUP BY request
	ORDER BY total DESC ";
}
		
$result2 = $DB->query($query2) or die('erro');

$arr_grf2 = array();
while ($row_result = $DB->fetch_assoc($result2))		
{ 
	$v_row_result = $row_result['request'];
	$arr_grf2[$v_row_result] = $row_result['total'];			
} 
	
$grf2 = array_keys($arr_grf2);
$quant2 = array_values($arr_grf2);

$conta = count($arr_grf2);


echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#cf-funnel-1').highcharts({
            chart: {
            type: 'pie',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,                
                height:290,
                backgroundColor:'transparent',
                marginTop: -35               
                //backgroundColor: '#2b2b2b'
            },
            title: {
                text: ''
            },
             legend: {
                layout: 'horizontal',
                align: 'top',
                verticalAlign: 'bottom',
                floating: false,
                borderWidth: 0,
                x: 0,
                y: 10,
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
                    size: '65%',
                    x:0,
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
                data: [
                    {
                        name: '" .$grf2[0]."',
                        y: $quant2[0],
                        sliced: false,
                        selected: false
                    },";
                    
for($i = 1; $i < $conta; $i++) {    
     echo '[ "'.$grf2[$i].'", '.$quant2[$i].'],';
        }                    
                                                         
echo "                ]
            }]
        });
    });

		</script>"; 
		?>
