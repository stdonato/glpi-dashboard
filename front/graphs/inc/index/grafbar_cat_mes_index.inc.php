
<?php

if(!empty($_POST['submit']))
{	
	$data_ini =  $_POST['date1'];	
	$data_fin = $_POST['date2'];
}

else {
	$data_ini = date("Y-01-01");
	$data_fin = date("Y-m-d");
}    

$month = date("Y-m");
$datahoje = date("Y-m-d"); 

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}


// entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '' || $sel_ent == -1) {
	//$sel_ent = 0;
	$entities = $_SESSION['glpiactiveentities'];
	$ent = implode(",",$entities);
	$entidade = "AND glpi_tickets.entities_id IN (".$ent.")";
}
else {
	$entidade = "AND glpi_tickets.entities_id IN (".$sel_ent.")";
}

//limits
if($sel_ent == 0) { $limit = "LIMIT 25"; }
else { $limit = "";}


$sql_cat = "
SELECT glpi_itilcategories.id, glpi_itilcategories.completename AS name, count(glpi_tickets.id) AS total
FROM `glpi_itilcategories`, glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.`itilcategories_id` = glpi_itilcategories.id
AND glpi_tickets.date ".$datas."
". $entidade ."
GROUP BY id
ORDER BY total DESC
LIMIT 10";

$query_cat = $DB->query($sql_cat);

$contador = $DB->numrows($query_cat);

//chart height
if($contador > 9) {	
	$height = '900';	
}
else {
	$height = '500';
}


echo "
<script type='text/javascript'>

$(function () {
        $('#grafcat').highcharts({
            chart: {
                type: 'bar',
                height: '400'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
            categories: ";

				$categories = array();
				while ($entity = $DB->fetch_assoc($query_cat)) {
				    $categories[] = $entity['name'];
				}
				echo json_encode($categories);
				
				//zerar rows para segundo while
				$DB->data_seek($query_cat, 0) ;
				
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
                name: '". __('Tickets','dashboard') ."',
                data: [
";

while ($entity = $DB->fetch_assoc($query_cat)) {
	echo $entity['total'].",";
}

echo "]
            }]
        });
    });

</script>
";

		?>
