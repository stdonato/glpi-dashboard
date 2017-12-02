<?php

global $DB;

$sql_ent = "SELECT COUNT(id) AS id FROM glpi_itilcategories ";

$result_ent = $DB->query($sql_ent) or die('erro');
$num_ent = $DB->fetch_assoc($result_ent);

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
AND glpi_tickets.itilcategories_id = ".$id_cat."  
AND glpi_tickets.date ".$datas."
AND glpi_tickets_users.type = 2
AND glpi_tickets_users.`users_id` NOT IN (SELECT DISTINCT users_id FROM glpi_tickets_users WHERE glpi_tickets_users.type=1)
AND glpi_tickets.is_deleted = 0
AND glpi_users.id = glpi_tickets_users.users_id
". $entidade ."   
GROUP BY `users_id`
ORDER BY conta DESC
LIMIT 20
";

$result4 = $DB->query($query3) or die('erro');

$arr_grf4 = array();
while ($row_result = $DB->fetch_assoc($result4)) { 
	$v_row_result = $row_result['name']. " ".$row_result['sname'];
	$arr_grf4[$v_row_result] = $row_result['conta'];			
} 
	
$grf4 = array_keys($arr_grf4) ;
$quant4 = array_values($arr_grf4) ;
$soma4 = array_sum($arr_grf4);

$grf_3 = json_encode($grf3);
$quant_2 = implode(',',$quant4);

echo "
<script type='text/javascript'>

$(function () {
        $('#grafcat_tec').highcharts({
            chart: {
                type: 'bar',
                height: 650
            },
            title: {
                text: '".__('by Technician','dashboard')."'
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
                    //color: '#000099',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }    
            }]
        });
    });

		</script>";
	//echo '</div>';		
//	}	 
		?>
