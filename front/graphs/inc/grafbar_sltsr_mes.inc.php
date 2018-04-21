<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";	
}	

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}


#entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '' || $sel_ent == -1) {
	//get user entities
	$entities = $_SESSION['glpiactiveentities'];
	$ent = implode(",",$entities);

	$entidade = "AND glpi_tickets.entities_id IN (".$ent.")";
}
else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
}


$slaid = "slas_ttr_id";		

$query3 = "
SELECT count( glpi_tickets.id ) AS conta, glpi_tickets.".$slaid." AS id, glpi_slas.name
FROM glpi_tickets, glpi_slas
WHERE glpi_tickets.".$slaid." = glpi_slas.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas."
".$entidade."
GROUP BY ".$slaid."
ORDER BY conta DESC ";

$result3 = $DB->query($query3) or die('erro');

$arr_grf3 = array();
while ($row_result = $DB->fetch_assoc($result3))		
	{ 
	$v_row_result = $row_result['name'];
	$arr_grf3[$v_row_result] = $row_result['conta'];			
	} 
	
$grf3 = array_keys($arr_grf3) ;
$quant3 = array_values($arr_grf3) ;
$soma3 = array_sum($arr_grf3);


$grf_3 = json_encode($grf3);
$quant_2 = implode(',',$quant3);


if($soma3 != 0) {

	echo "
	<script type='text/javascript'>
		$(function () {
        $('#graf1').highcharts({
            chart: {
                type: 'bar',
                height: 1100
            },
            title: {
                text: ''
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
		
}	 
		?>
