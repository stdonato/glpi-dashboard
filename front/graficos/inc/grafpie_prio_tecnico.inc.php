
<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";	
}	

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}

$query2 = "
SELECT COUNT(glpi_tickets.id) as tick, glpi_tickets.priority AS prio, glpi_tickets_users.users_id AS uid
FROM glpi_tickets_users, glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND glpi_tickets_users.users_id = ".$id_tec."
AND glpi_tickets_users.type = 2
AND glpi_tickets_users.tickets_id = glpi_tickets.id
GROUP BY prio
ORDER BY tick DESC ";
		
$result2 = $DB->query($query2) or die('erro');

$arr_grf2 = array();
while ($row_result = $DB->fetch_assoc($result2))		
	{ 
	
		$priority = $row_result['prio'];
		
		if($priority == 1) {
			$prio_name = _x('priority', 'Very low'); }
		
		if($priority == 2) {
			$prio_name = _x('priority', 'Low'); }
			
		if($priority == 3) {
			$prio_name = _x('priority', 'Medium'); } 		
			
		if($priority == 4) {	
			$prio_name = _x('priority', 'High'); }
			
		if($priority == 5) {
			$prio_name = _x('priority', 'Very high'); } 	
			
		if($priority == 6) {
			$prio_name = _x('priority', 'Major'); } 	
	
		$v_row_result = $prio_name;
		$arr_grf2[$v_row_result] = $row_result['tick'];			
	} 
	
$grf2 = array_keys($arr_grf2);
$quant2 = array_values($arr_grf2);
$conta = count($arr_grf2);

echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graf_prio').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '".__('Tickets by Priority','dashboard')."'
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
                    
for($i = 1; $i < $conta; $i++) {    
     echo '[ "' . $grf2[$i] . '", '.$quant2[$i].'],';
        }                    
                                                         
echo "                ]
            }]
        });
    });

		</script>"; 
		?>
