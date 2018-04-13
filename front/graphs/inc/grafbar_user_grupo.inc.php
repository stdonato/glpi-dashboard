<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}

$sql_grpb = "
SELECT `glpi_groups_users`.`users_id` AS uid, `glpi_users`.`firstname` AS name ,`glpi_users`.`realname` AS sname, count(glpi_tickets_users.id) AS conta
FROM `glpi_groups_users`, glpi_tickets_users, glpi_users, glpi_tickets, glpi_groups_tickets
WHERE glpi_groups_tickets.groups_id = ".$id_grp."
AND glpi_tickets_users.users_id = glpi_groups_users.users_id
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets.id = glpi_tickets_users.tickets_id
AND glpi_tickets.id = glpi_groups_tickets.tickets_id
AND glpi_groups_users.groups_id = glpi_groups_tickets.groups_id
AND glpi_tickets.date ".$datas."
AND glpi_tickets.is_deleted = 0
AND glpi_tickets_users.type = 2
". $entidade_and ."
GROUP BY uid
ORDER BY conta DESC
LIMIT 10 ";

$query_grp_b = $DB->query($sql_grpb);

echo "
<script type='text/javascript'>

$(function () {
        $('#graf_user').highcharts({
            chart: {
                type: 'bar',
                height: 550
            },
            title: {
                text: '".__('Tickets','dashboard')." ".__('by Technician','dashboard')."'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
            categories: ";

				$categories = array();
				while ($grupo = $DB->fetch_assoc($query_grp_b)) {
				    $categories[] = $grupo['name']." ".$grupo['sname'];
				}
				echo json_encode($categories);
				
				echo ",
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
                name: '". __('Tickets','dashboard')."',
                data: [
";

//zerar rows para segundo while

$DB->data_seek($query_grp_b, 0) ;

while ($grupo = $DB->fetch_assoc($query_grp_b))
{
	echo $grupo['conta'].",";
}

echo "]
            }]
        });
    });

</script>
";

		?>
