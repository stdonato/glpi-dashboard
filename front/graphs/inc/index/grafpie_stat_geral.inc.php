<?php

if(!empty($_POST['submit']))
{	
	$data_ini =  $_POST['date1'];	
	$data_fin = $_POST['date2'];
}

else {
	$data_ini = date("Y-m-01");
	$data_fin = date("Y-m-d");
}    

$month = date("Y-m");
$datahoje = date("Y-m-d"); 

$query2 = "
SELECT COUNT(glpi_tickets.id) as tick, glpi_tickets.status as stat
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0  
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")             
GROUP BY glpi_tickets.status
ORDER BY stat  ASC ";
		
$result2 = $DB->query($query2) or die('erro');

$arr_grf2 = array();
while ($row_result = $DB->fetchAssoc($result2))		
{ 
   $v_row_result = $row_result['stat'];
   $arr_grf2[$v_row_result] = $row_result['tick'];			
} 
	
$grf2 = array_keys($arr_grf2);
$quant2 = array_values($arr_grf2);

$conta = count($arr_grf2);


echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#pie2').highcharts({
            chart: {
            type: 'pie',
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
                text: '".__('Tickets by Status','dashboard')."'
            },
             legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                x: -30,
                y: 0,
                floating: false,
                borderWidth: 0,
               // backgroundColor: '#FFFFFF',
                adjustChartSize: true,
                format: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
             credits: {
                enabled: false
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                data: [
                    {
                        name: '" . Ticket::getStatus($grf2[0]) . "',
                        y: ".$quant2[0].",
                        sliced: true,
                        selected: true
                    },";
                    
				for($i = 1; $i < $conta; $i++) {    
				     echo '[ "' . Ticket::getStatus($grf2[$i]) . '", '.$quant2[$i].'],';
				        }                    
				                                                         
				echo "  ]
            }]
        });
    });

		</script>"; 
		?>
