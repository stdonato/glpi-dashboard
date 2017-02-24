<?php

global $DB;

$sql_loc = "SELECT COUNT(id) AS id FROM `glpi_locations` ";

$result_loc = $DB->query($sql_loc) or die('erro');
$num_loc = $DB->fetch_assoc($result_loc);


//echo '<div id="graf3" class="span12" >';

if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";	
}	

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}

$query3 = "
SELECT count( glpi_tickets.id ) AS conta, glpi_tickets_users.`users_id` AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
FROM `glpi_tickets_users`, glpi_tickets , glpi_users
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets.locations_id = ".$id_loc."  
AND glpi_tickets.date ".$datas."
AND glpi_tickets_users.type = 1
AND glpi_tickets_users.`users_id` NOT IN (SELECT DISTINCT users_id FROM glpi_tickets_users WHERE glpi_tickets_users.type=2)
AND glpi_tickets.is_deleted = 0
AND glpi_users.id = glpi_tickets_users.`users_id`
GROUP BY `users_id`
ORDER BY conta DESC
LIMIT 10
";

$result3 = $DB->query($query3) or die('erro');

$arr_grf3 = array();
while ($row_result = $DB->fetch_assoc($result3))		
	{ 
	$v_row_result = $row_result['name']. " ".$row_result['sname'];
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
        $('#graf3').highcharts({
            chart: {
                type: 'bar',
                height: 450
            },
            title: {
                text: '".__('TOP 10 - Requester','dashboard')."'
            },
           
            xAxis: {
                categories: $grf_3,
                labels: {                    
                    align: 'right',
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
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
                valueSuffix: ' ".__('Tickets','dashboard')."'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true                                                
                    },
                     borderWidth: 1,
                	borderColor: 'white',
                	shadow:true,           
                	showInLegend: false
                }
            },
            series: [{
                name: '".__('Tickets','dashboard')."',
                data: [$quant_2],
                dataLabels: {
                    enabled: true,                    
                   // color: '#000099',
                    style: {
                       // fontSize: '13px',
                       // fontFamily: 'Verdana, sans-serif'
                    }
                }    
            }]
        });
    });

		</script>";
	//echo '</div>';
		
//	}	 
		?>
