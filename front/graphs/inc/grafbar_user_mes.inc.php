<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}

# entity
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

$sql_tec = "
SELECT count( glpi_tickets.id ) AS conta,  glpi_tickets_users.`users_id` AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
FROM glpi_tickets
INNER JOIN glpi_tickets_users
   ON glpi_tickets_users.tickets_id = glpi_tickets.id
   AND glpi_tickets_users.type = 1
INNER JOIN glpi_users
   ON glpi_users.id = glpi_tickets_users.users_id
WHERE glpi_tickets.date ".$datas."
AND glpi_tickets.is_deleted = '0' 
".$entidade."
GROUP BY `users_id`
ORDER BY conta DESC 
LIMIT 40";

/*$sql_tec = "
SELECT count( glpi_tickets.id ) AS conta, glpi_tickets_users.`users_id` AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
FROM `glpi_tickets_users`, glpi_tickets, glpi_users
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets.date ".$datas." 
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets_users.type = 1 
AND glpi_tickets.is_deleted = 0
".$entidade."
GROUP BY `users_id`
ORDER BY conta DESC 
LIMIT 50";*/

$query_tec = $DB->query($sql_tec);

$contador = $DB->numrows($query_tec);

//var_dump($sql_tec);

//chart height
if($contador > 9) {	
	$height = '1000';	
}
else {
	$height = '500';
}


if($DB->fetch_assoc($query_tec) != '') {

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
		        type: 'category'
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
                        enabled: true,
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
                	  },
                	  cursor: 'pointer',
		          		point: {
		                events: {
		                    click: function () {
		                        window.open('../reports/rel_usuario.php?con=1&sel_tec=' + this.options.key + '&date1=$data_ini&date2=$data_fin','_blank');
		                    		}
		                		}
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
                borderWidth: 1,
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

				//zerar rows para segundo while
				$DB->data_seek($query_tec, 0) ;
				
				while ($tecnico = $DB->fetch_assoc($query_tec))
				{
					$user_name = str_replace("'","`",$tecnico['name']." ". $tecnico['sname']);				 	
				 	echo "{y:".$tecnico['conta'].",name:'".$user_name."',key:".$tecnico['id']."},";
				}

				echo "]
            }]
        });
    });

</script>
";
		}
		?>
