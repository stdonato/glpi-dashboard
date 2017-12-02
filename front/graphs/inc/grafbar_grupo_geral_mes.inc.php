
<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}

$query3 = "
SELECT count(glpi_groups_tickets.id) AS conta, glpi_groups.name AS name
FROM `glpi_groups_tickets`, glpi_tickets, glpi_groups
WHERE glpi_groups_tickets.`groups_id` = glpi_groups.id
AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas."
".$entidade."
GROUP BY name
ORDER BY conta DESC
LIMIT 10 ";

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

if($soma3 > 1) {

echo "
<script type='text/javascript'>

$(function () {
        $('#grafgrp').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'TOP 10 - ". __('Tickets','dashboard') ." ".__('by Group','dashboard')."'
            },

            xAxis: {
                categories: $grf_3,
                labels: {
                	  //rotation: -55,
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
                },

            },
            /*tooltip: {
                headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
                pointFormat: '<tr><td style=\"color:{series.color};padding:0;\"font-size:10px\">{series.name}: </td>' +
                    '<td style=\"padding:0;\"font-size:10px\"><b>{point.y:.1f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },*/
            plotOptions: {
                bar: {
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
                   // color: '#000099',
                    align: 'center',
                    x: 20,
                    y: 0,
                    style: {
                       // fontSize: '11px',
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

}
?>
