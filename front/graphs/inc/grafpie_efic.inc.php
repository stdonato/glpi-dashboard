
<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";	
}	

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}

//problems
$query_p = "
SELECT COUNT(id) as id
FROM glpi_problems
WHERE glpi_problems.is_deleted = 0     
AND glpi_problems.date ".$datas."
AND glpi_problems.entities_id = ".$id_ent." 
 ";		
 
$result_p = $DB->query($query_p) or die('erro');
$problems = $DB->fetch_assoc($result_p);

//tickets by type
$query2 = "
SELECT COUNT(glpi_tickets.id) as tick, glpi_tickets.type AS tipo
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0     
AND glpi_tickets.date ".$datas."
AND glpi_tickets.entities_id = ".$id_ent." 
GROUP BY glpi_tickets.type
ORDER BY type  ASC ";
		
$result2 = $DB->query($query2) or die('erro');

$arr_grf2 = array();
while ($row_result = $DB->fetch_assoc($result2))		
{ 
	$v_row_result = $row_result['tipo'];
	$arr_grf2[$v_row_result] = $row_result['tick'];			
} 
	
//$grf2 = array_keys($arr_grf2);
$grf2 = array(__('Incident'),__('Request'),__('Problem'));

$quant2 = array_values($arr_grf2);
$quant2[2] = $problems['id'];

$conta = count($arr_grf2);

	
echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graf2').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '".__('Tickets by Type','dashboard')."'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    size: '85%',
 					dataLabels: {
								format: '{point.y} - ( {point.percentage:.1f}% )',
                   		style: {
                        	color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        		},
                        connectorColor: 'black'
                    },
                showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '".__('Tickets','dashboard')."',
                data: [
                   {
                        name: '" . $grf2[0] . "',
                        y: $quant2[0],
                        sliced: true,
                        selected: true
                    },";
                                      
for($i = 1; $i <= $conta; $i++) {    
     echo '[ "' . $grf2[$i] . '", '.$quant2[$i].'],';
        }                    
                                                         
echo "                ],
            }]
        });
    });

		</script>"; 
		
/*

SELECT COUNT(glpi_tickets.id) as tick, glpi_tickets.type
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0     
AND glpi_tickets.date ".$datas." 
GROUP BY glpi_tickets.type
ORDER BY type  ASC

*/
		?>
