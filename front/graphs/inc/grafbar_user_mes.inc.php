
<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}

$sql_usu = "
SELECT count( glpi_tickets.id ) AS conta, glpi_tickets_users.`users_id` AS id
FROM `glpi_tickets_users`, glpi_tickets
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets.date ".$datas."
AND glpi_tickets_users.type = 1

AND glpi_tickets.is_deleted = 0
".$entidade."
GROUP BY `users_id`
ORDER BY conta DESC
LIMIT 40 ";

//AND glpi_tickets_users.`users_id` NOT IN (SELECT DISTINCT users_id FROM glpi_tickets_users WHERE glpi_tickets_users.type=2)

$query_usu = $DB->query($sql_usu);

$contador = $DB->numrows($query_usu);

//chart height
if($contador > 9) {	
	$height = '1000';	
}
else {
	$height = '500';
}


if($DB->fetch_assoc($query_usu) != 0) {

echo "
<script type='text/javascript'>

$(function () {
        $('#graf1').highcharts({
            chart: {
                type: 'bar',
                height: ".$height."
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
            //gridLineWidth: 0,
            //lineWidth:1,
            categories: [ ";

				$DB->data_seek($query_usu,0);
				while ($usuario = $DB->fetch_assoc($query_usu)) {
				
					$sqlC = "SELECT glpi_users.firstname AS name, glpi_users.realname AS sname
					FROM glpi_tickets_users, glpi_users
					WHERE glpi_tickets_users.users_id = glpi_users.id
					AND glpi_tickets_users.users_id = ".$usuario['id']."
					GROUP BY glpi_users.firstname
					";
				
					$queryC = $DB->query($sqlC);
					$chamado = $DB->fetch_assoc($queryC);
				
					$user_name = str_replace("'","`",$chamado['name']." ". $chamado['sname']);
					echo "'". $user_name ."',";
				
				}
				
				//zerar rows para segundo while
				$DB->data_seek($query_usu, 0) ;
				
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
                valueSuffix: ' chamados'
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
                },
                 series: {
			       	  animation: {
			           duration: 2000,
			           easing: 'easeOutBounce'
			       	  }
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
                //backgroundColor: '#FFFFFF',
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
                name: '". __('Tickets','dashboard')."',
                data: [  ";
			
			while ($usuario = $DB->fetch_assoc($query_usu)) {
			
				echo $usuario['conta'].",";
			}
			
			echo "]
            }]
        });
    });

</script>
";
		}
		?>
