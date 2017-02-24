<?php

if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";	
}	

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}

$query3 = "
SELECT DISTINCT DATE_FORMAT( glpi_tickets.date, '%H' ) AS day_l, COUNT( glpi_tickets.id ) AS conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
". $entidade ."
GROUP BY day_l
ORDER BY `day_l` ASC
LIMIT 0 , 30 ";

$result3 = $DB->query($query3) or die('erro');

$arr_grf3 = array();
while ($row_result = $DB->fetch_assoc($result3))		
	{ 
	$v_row_result = $row_result['day_l'];
	$arr_grf3[$v_row_result] = $row_result['conta'];			
	} 
	
$grf3 = array_keys($arr_grf3) ;
$quant3 = array_values($arr_grf3) ;
$soma3 = array_sum($arr_grf3);


$grf_3 = json_encode($grf3);
$quant_2 = implode(',',$quant3);


//if($soma3 != 0) {

echo "
<script type='text/javascript'>

$(function () {
        $('#graf_hour').highcharts({
            chart: {
                type: 'column',
                height: 450
            },
            title: {
                text: '".__('Tickets by hour','dashboard')."'
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
                	showInLegend: false
                },
                series: {
		       	  animation: {
		           duration: 2000,
		           easing: 'easeOutBounce'
		       	  }
		  			 }
		            },
            series: [{
                name: '".__('Tickets','dashboard')."',
                data: [$quant_2],
                dataLabels: {
                    enabled: true, 
						  align: 'center',                   
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
		
//}	 
		?>
