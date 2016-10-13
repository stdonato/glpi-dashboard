
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
	$entities = Profile_User::getUserEntities($_SESSION['glpiID'], true);
	$ent = implode(",",$entities);
	$entidade = "AND glpi_tickets.entities_id IN (".$ent.")";
}

else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
}

$sql_grp = "
SELECT count(glpi_groups_tickets.id) AS conta, glpi_groups.name AS name
FROM `glpi_groups_tickets`, glpi_tickets, glpi_groups
WHERE glpi_groups_tickets.`groups_id` = glpi_groups.id
AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
AND glpi_tickets.is_deleted = 0
AND glpi_tickets.date ".$datas."
".$entidade."
GROUP BY name
ORDER BY conta DESC
LIMIT 30";

$query_grp = $DB->query($sql_grp);

$contador = $DB->numrows($query_grp);

//chart height
if($contador > 5) {	
	$height = '900';	
}
else {
	$height = '500';
}


if($DB->fetch_assoc($query_grp) != 0) {

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
            categories: [ ";

$DB->data_seek($query_grp, 0) ;  
while ($grupo = $DB->fetch_assoc($query_grp)) {

echo "'". $grupo['name']."',";
}              

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
            	 	//color: '#000099'
            	 	},
                name: '". __('Tickets','dashboard')."',
                data: [  
";
             
//zerar rows para segundo while

$DB->data_seek($query_grp, 0) ;  
             
while ($grupo = $DB->fetch_assoc($query_grp)) 
{
	echo $grupo['conta'].",";
}    

echo "]
            }]
        });
    });

</script>
";
		}
		?>
