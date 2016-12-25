<?php
/*
if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";
}

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}
*/

$data_ini2 = date("Y-m-d");  //hoje

$data_fin2 = date('Y-m-d', strtotime('-1 week'));

//$datas = "BETWEEN '" . $data_fin2 ." 00:00:00' AND '".$data_ini2." 23:59:59'";

//echo $datas;

$sql_tec = "
SELECT DATE_FORMAT(date, '%d-%m') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $data_fin2 ." 00:00:00' AND '".$data_ini2." 23:59:59'
GROUP BY data
ORDER BY data ASC
";


$query_tec = $DB->query($sql_tec);

echo "<script type='text/javascript'>

$(function () {
        $('#graf1').highcharts({
            chart: {
                type: 'bar',
                height: 750
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
            categories: [ ";

while ($entity = $DB->fetch_assoc($query_tec)) {

echo "'". $entity['data']."',";

}

//zerar rows para segundo while

$DB->data_seek($query_tec, 0) ;

echo "    ],
                title: {
                    text: null
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
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 100,
                floating: true,
                borderWidth: 0,
               // backgroundColor: '#FFFFFF',
                shadow: true,
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
            	 dataLabels: {
            	 	//color: '#000099'
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
