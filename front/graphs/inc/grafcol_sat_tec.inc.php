<?php

if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";
}

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}


//satisfaction %
$query_sat = "
SELECT DATE_FORMAT(date, '%b-%y') as month_l,  DATE_FORMAT(date, '%y-%m') as month, avg( `glpi_ticketsatisfactions`.satisfaction ) AS media
FROM glpi_tickets, `glpi_ticketsatisfactions` , glpi_tickets_users, glpi_users
WHERE glpi_tickets.is_deleted = '0'
AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets.id
AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets_users.tickets_id
AND `glpi_users`.id = glpi_tickets_users.users_id
AND glpi_tickets_users.type = 2
AND glpi_users.id = ".$id_tec."
AND glpi_tickets.date ".$datas."
GROUP BY month
ORDER BY `month` ASC
";

$result = $DB->query($query_sat) or die('erro');

$contador = $DB->numrows($result);

//array with satisfaction average
$arr_grfsat = array();

while ($row_result = $DB->fetch_assoc($result))
	{
		$v_row_result = $row_result['month_l'];
		$arr_grfsat[$v_row_result] = round(($row_result['media']/5)*100,1);
	}


$grfsat = array_keys($arr_grfsat) ;
$quantsat = array_values($arr_grfsat);

$grfsat3 = json_encode($grfsat);
$quantsat2 = implode(',',$quantsat);


if($contador >= 1) {

echo "
<script type='text/javascript'>
$(function () {

        $('#graf_sat').highcharts({
            chart: {
            type: 'column'

            },
            title: {
                text: '".__('Satisfaction','dashboard')."'
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                x: 0,
                y: 0,
                //floating: true,
                borderWidth: 0,
                //backgroundColor: '#FFFFFF',
                adjustChartSize: true
            },
            xAxis: {
                categories: $grfsat3,
                labels: {
                    rotation: -55,
                    align: 'right',
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }

            },

            yAxis: {
	 						//minPadding: 0,
   	 					//maxPadding: 0,
    						min: 0,
    						//max:1,
   						showLastLabel:false,
    						//tickInterval:1,

                title: {
                    text: '',
                    style: {
                       // color: '#4572A7'
                    }
                },
                labels: {
                    format: '{value} %',
                    style: {
                        //color: '#4572A7'
                    }
                },
                opposite: false
              },

				plotOptions: {
                column: {
                    pointPadding: 0.2,
  		              borderWidth: 2,
      	           borderColor: 'white',
         	        shadow:true,
                	  showInLegend: false
                },
                areaspline: {
                    fillOpacity: 0.5
                }
                },

            tooltip: {
                shared: true
            },
            credits: {
                enabled: false
            },

          series: [

					{ // satisfacao
                name: '".__('Satisfaction','dashboard')."',
                //color: '#C4D9F1',
                type: 'column',
               // yAxis: 1,

          		data: [".$quantsat2."],

                tooltip: {
                    valueSuffix: ' %'
                },
                    dataLabels: {
                    enabled: true,
                    //color: '#000099',
                    align: 'center',
                    x: 0,
                    y: 0,
                    format: '{y} %',
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    },
                    formatter: function () {
                    return Highcharts.numberFormat(this.y, 0, '','');
                }
                }

                }]

        });
    });
  </script>
";

}
		?>
