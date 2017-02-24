<?php
/*
if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";	
}	

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}
*/

$data_inis = date("Y-m-d");  //hoje

$data_fins = date('Y-m-d', strtotime('-1 week'));

//$datas = "BETWEEN '" . $data_fin2 ." 00:00:00' AND '".$data_ini2." 23:59:59'";

//echo $datas;

$sql_tec = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $data_fins ." 00:00:00' AND '".$data_inis." 23:59:59'
GROUP BY data
ORDER BY data DESC
";

$query_tec = $DB->query($sql_tec);

echo "<script type='text/javascript'>

$(function () {
        $('#graf1').highcharts({
            chart: {
                type: 'column'                
            },
            title: {
                text: 'Chamados dos últimos 7 dias'
            },
            subtitle: {
                text: ''
            },
            xAxis: { 
            categories: ";

$categories = array();
while ($entity = $DB->fetch_assoc($query_tec)) {
    $categories[] = $entity['data'];
}   
echo json_encode($categories);

//zerar rows para segundo while

$DB->data_seek($query_tec, 0) ;               

echo ",
                title: {
                    text: ''
                },
                labels: {
                	style: {
                        fontSize: '12px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true,                                                
                    },
                     borderWidth: 2,
                	borderColor: 'white',
                	shadow:true,           
                	showInLegend: false
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 100,
                floating: true,
                borderWidth: 1,
                backgroundColor: '#FFFFFF',
                shadow: true,
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{            	
            	                 dataLabels: {
                    enabled: true,                    
                    color: '#000099',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                },
                name: '". $LANG['plugin_dashboard']['1']."',
                data: [ ";
   
$DB->data_seek($query_tec, 0) ;     
             
while ($entity = $DB->fetch_assoc($query_tec)) 

{
	echo $entity['conta'].",";
}    

echo "]
            }]
        });
    });

</script>
";
		
		?>
