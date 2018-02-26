<?php

if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";	
}	

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}

$query3 = "
SELECT DISTINCT DATE_FORMAT( glpi_tickets.date, '%w' ) AS day_l, COUNT( glpi_tickets.id ) AS conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND (glpi_tickets.date ".$datas." OR glpi_tickets.closedate ".$datas." )
". $entidade ."
GROUP BY day_l
ORDER BY `day_l` ASC
LIMIT 0 , 30 ";

$result3 = $DB->query($query3) or die('erro');

$arr_grf3 = array();
while ($row_result = $DB->fetch_assoc($result3))		
	{ 
	
	    switch ($row_result['day_l']) {
	    case "0": $day = __('Sunday'); break;
	    case "1": $day = __('Monday'); break;
	    case "2": $day = __('Tuesday'); break;
	    case "3": $day = __('Wednesday'); break;
	    case "4": $day = __('Thursday'); break;
	    case "5": $day = __('Friday'); break;
	    case "6": $day = __('Saturday'); break;    
	    }	
	
		$v_row_result = $day;
		$arr_grf3[$v_row_result] = $row_result['conta'];			
	} 
	
$grf3 = array_keys($arr_grf3) ;
$quant3 = array_values($arr_grf3) ;
$soma3 = array_sum($arr_grf3);

$grf_3 = json_encode($grf3);
$quant_2 = implode(',',$quant3);


echo "
<script type='text/javascript'>

$(function () {
        $('#grafday').highcharts({
            chart: {
                type: 'areaspline',
                height: 450
            },
            title: {
                text: '".__('Tickets by week day','dashboard')."'
            },
           
             xAxis: {
                categories: $grf_3,
                labels: {                    
                    align: 'center',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif'
                    }, 
                    overflow: 'justify'                                    
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
         tooltip: {
                valueSuffix: ' '
            },
            plotOptions: {
                column: {
                    dataLabels: {
                      enabled: true,
                      x:5,
                      y:0,                                                
                    },
                  borderWidth: 2,
                	borderColor: 'white',
                	shadow:true,           
                	showInLegend: false,
                }
            },
            series: [{
                name: '".__('Tickets','dashboard')."',
                data: [$quant_2],
                dataLabels: {
                    enabled: true,                    
                    ///color: '#000099',
                    style: {
                       // fontSize: '13px',
                       // fontFamily: 'Verdana, sans-serif'
                    }
                }    
            }]
        });
    });

		</script>";
	echo '</div>';
		 
?>
