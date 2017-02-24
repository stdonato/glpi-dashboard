
<?php

if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";
}

else {
$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
}

if(isset($_REQUEST['limite'])) {
	$limit = $_REQUEST['limite'];
}
else {
	$limite = 25;
}

# entity
$sql_e = "SELECT value FROM glpi_plugin_dashboard_config WHERE name = 'entity' AND users_id = ".$_SESSION['glpiID']."";
$result_e = $DB->query($sql_e);
$sel_ent = $DB->result($result_e,0,'value');

if($sel_ent == '' || $sel_ent == -1) {
	//get user entities
	$entities = $_SESSION['glpiactiveentities'];
	$ent = implode(",",$entities);

	$entidade = "AND gt.entities_id IN (".$ent.")";
}
else {
	$entidade = "AND gt.entities_id IN (".$sel_ent.")";
}

$sql_grp = "
SELECT gi.id AS id, gi.name AS name, count(gt.id) AS conta
FROM glpi_tickets gt, glpi_". strtolower($type)."s gi, glpi_items_tickets git
WHERE git.itemtype = '".$type."'
AND git.items_id = gi.id
AND gt.is_deleted = 0
AND git.tickets_id = gt.id
AND gt.date ".$datas."
".$entidade."
GROUP BY gi.name
ORDER BY conta DESC
LIMIT ".$limite." ";

$query_grp = $DB->query($sql_grp);

if($DB->fetch_assoc($query_grp) != 0) {

echo "
<script type='text/javascript'>

$(function () {
	var categoryLinks = {
";

$DB->data_seek($query_grp, 0) ;
while ($grupo = $DB->fetch_assoc($query_grp)) {

echo "
        '". $grupo['name']."': '".$CFG_GLPI["url_base"]."/front/".$type.".form.php?id=".$grupo['id']."',
    ";
}

echo "	};
        $('#graf1').highcharts({
            chart: {
                type: 'bar',
                height: 800
            },
            title: {
                text: '". __(ucfirst($type)) ."'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
            categories: ";

$DB->data_seek($query_grp, 0) ;
$categories = array();
while ($grupo = $DB->fetch_assoc($query_grp)) {
    $categories[] = $grupo['name'];
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
                    },
                   // formatter: function() {
                   // return '<a href=\"'+ categoryLinks[this.value] +'\" target=\"_blank\" style=\"color:#606060;\">'+this.value +'</a>';
                	//	},
                	//	useHTML: true
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
                //backgroundColor: '#FFFFFF',
                shadow: true,
                enabled: false
            },
            credits: {
                enabled: false
            },
			 events:{
              click: function (event) {
                  alert(event.point.name);
                  // add your redirect code and u can get data using event.point
              }
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
