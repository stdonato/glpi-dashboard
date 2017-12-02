
<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}


$sql_ent = "SELECT COUNT(id) AS ids FROM `glpi_entities` ";
$result_ent = $DB->query($sql_ent) or die('erro');
$num_ent = $DB->fetch_assoc($result_ent);

if($num_ent['ids'] > 2) {

echo '<div id="grafent" class="span12" style="height: 450px; margin-top:35px; margin-left: -5px;">';

$query3 = "
SELECT glpi_entities.name AS name, COUNT( glpi_tickets.id ) AS tick
FROM glpi_tickets
LEFT JOIN glpi_entities ON glpi_tickets.entities_id = glpi_entities.id
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
".$entidade."
GROUP BY glpi_entities.name
ORDER BY tick DESC ";

$result3 = $DB->query($query3) or die('erro');

$arr_grf3 = array();
while ($row_result = $DB->fetch_assoc($result3))
{
	$v_row_result = $row_result['name'];
	$arr_grf3[$v_row_result] = $row_result['tick'];
}

$grf3 = array_keys($arr_grf3) ;
$quant3 = array_values($arr_grf3) ;
$soma3 = array_sum($arr_grf3);

$grf_3 = json_encode($grf3);
$quant_2 = implode(',',$quant3);

echo "
<script type='text/javascript'>

$(function () {
        $('#grafent').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '".__('Tickets by Entity','dashboard')."'
            },

            xAxis: {
                categories: $grf_3,
                labels: {
                    rotation: -55,
                    align: 'right',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
          /*  tooltip: {
                headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
                pointFormat: '<tr><td style=\"color:{series.color};padding:0;\"font-size:10px\">{series.name}: </td>' +
                    '<td style=\"padding:0;\"font-size:10px\"><b>{point.y:.1f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },*/
            plotOptions: {
                column: {
                  pointPadding: 0.2,
                  borderWidth: 2,
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
                    align: 'center',
                    x: 1,
                    y: 1,
                    style: {
                       // fontSize: '13px',
                       // fontFamily: 'Verdana, sans-serif'
                    },
                    formatter: function () {
                    return Highcharts.numberFormat(this.y, 0, '', ''); // Remove the thousands sep?
                }
                }
            }]
        });
    });

		</script>";

	echo '</div>';

	}
		?>
