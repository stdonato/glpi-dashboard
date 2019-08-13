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

//get techs list
$sql_tec = "
SELECT count( glpi_tickets.id ) AS conta, glpi_tickets_users.`users_id` AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
FROM `glpi_tickets_users`, glpi_tickets, glpi_users
WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
AND glpi_tickets_users.users_id = glpi_users.id
AND glpi_tickets_users.type = 2
AND glpi_tickets.date ".$datas."
".$entidade."
GROUP BY `users_id`
ORDER BY conta DESC ";

$query_tec = $DB->query($sql_tec) or die('erro t');

//$techs = array();
$arr_techs = array();

while ($row = $DB->fetch_assoc($query_tec)) {
	
	//$techs[] = $row['id'];
	$v_row_result = $row['name']." ".$row['sname'];
	$arr_techs[$v_row_result] = 0;	
}	

	
$DB->data_seek($query_tec, 0);
while ($row_result = $DB->fetch_assoc($query_tec)) {

	$sql_open = "
	SELECT count( glpi_tickets.id ) AS conta, glpi_tickets_users.`users_id` AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
	FROM `glpi_tickets_users`, glpi_tickets, glpi_users
	WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
	AND glpi_tickets.date ".$datas."
	AND glpi_tickets_users.users_id = glpi_users.id
	AND glpi_tickets_users.type = 2
	AND glpi_tickets.is_deleted = 0
	AND glpi_tickets.status <> 6
	".$entidade."
	AND glpi_tickets_users.`users_id` = ".$row_result['id']."
	GROUP BY `users_id`
	ORDER BY name ASC ";
	
	$query_open = $DB->query($sql_open) or die('erro o');
	
	$res_open = $DB->fetch_assoc($query_open);
	
	$v_row_result = $row_result['name']." ".$row_result['sname'];
		if($res_open['conta'] != '') {
			$arr_open[$v_row_result] = $res_open['conta'];
		}
		else {
			$arr_open[$v_row_result] = 0;
		}
}


//closed
$DB->data_seek($query_tec, 0);
while ($row_result = $DB->fetch_assoc($query_tec)) {

	$sql_close = "
	SELECT count( glpi_tickets.id ) AS conta, glpi_tickets_users.`users_id` AS id, glpi_users.firstname AS name, glpi_users.realname AS sname
	FROM `glpi_tickets_users`, glpi_tickets, glpi_users
	WHERE glpi_tickets.id = glpi_tickets_users.`tickets_id`
	AND glpi_tickets.date ".$datas."
	AND glpi_tickets_users.users_id = glpi_users.id
	AND glpi_tickets_users.type = 2
	AND glpi_tickets.is_deleted = 0
	AND glpi_tickets.status = 6
	".$entidade."
	AND glpi_tickets_users.`users_id` = ".$row_result['id']."
	GROUP BY `users_id`
	ORDER BY name ASC ";
	
	$query_close = $DB->query($sql_close) or die('erro o');
	
	$res_close = $DB->fetch_assoc($query_close);
	
	$v_row_result = $row_result['name']." ".$row_result['sname'];
		if($res_close['conta'] != '') {
			$arr_close[$v_row_result] = $res_close['conta'];
		}
		else {
			$arr_close[$v_row_result] = 0;
		}
}

//$contador = $DB->numrows($query_open);
$count = count($arr_techs);

if($count > 0) {
	$contador = $count;
}
else {
	$contador = 0;	
}

//chart height
if($contador > 9) {	
	$height = '1300';	
}
else {
	$height = '800';
}

if($contador > 0) {
	
	$arr_name = array_keys($arr_techs);
	$arr_open2 = array_values($arr_open);
	$arr_close2 = array_values($arr_close);
	
	//create array with values and array for sort names
	for($i=0; $i < count($arr_techs); $i++ ) {	
		$arr_cham[$arr_name[$i]] = $arr_open2[$i].",".$arr_close2[$i];
		$arr_cham_t[$arr_name[$i]] = $arr_open2[$i] + $arr_close2[$i];
	}
	

	//function to array_filter
	function limpa($var)
	{
	   if($var != "0,0") {
	   	 return $var;
	 	}
	}
	
	$arr_cham2 = array_filter($arr_cham, "limpa");
	$arr_cham_t2 = array_filter($arr_cham_t, "limpa");
	
	$arr_val = array_values($arr_cham2);	


	//dividir valores do array com virgulas
	for($i=0; $i < count($arr_cham2) ;$i++) {		
		$vals1 = preg_split("/[\s,]+/",$arr_val[$i]);
		$arr_cham_op2[$i] = $vals1[0];	
		$arr_cham_cl2[$i] = $vals1[1];	
	}	

$categories = json_encode(array_keys($arr_cham2));

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
			        type: 'category',
			        categories: ".$categories."
			    },
	            yAxis: {
	                min: 0,
	                title: {
	                    text: '',
	                    align: 'high'
	                },
	                labels: {
	                    overflow: 'justify'
	                },
	                stackLabels: {
                		enabled: true,                
                	 }
	            },
	            tooltip: {
	                valueSuffix: ''
	            },
	            plotOptions: {
	            bar: {
							  stacking:'normal',
	                    dataLabels: {
	                        enabled: true,
	                    },
	                     borderWidth: 1,
	                		borderColor: 'white',
	                		shadow:true,
	                		showInLegend: true
	                },
	            series: {
	                	  animation: {
	                    duration: 1200,
	                    easing: 'easeOutBounce'	                   
	                	  },
	                	  cursor: 'pointer',
	            }
	
	            },
	            legend: {
	                layout: 'horizontal',
	                align: 'center',
	                verticalAlign: 'bottom',
	                x: -50,
	                y: 5,
	                floating: false,
	                borderWidth: 0,
	                //backgroundColor: '#FFFFFF',
	                shadow: false,
	                enabled: true
	            },
	            credits: {
	                enabled: false
	            },
	
	            series: [
	            {
	                name: '". __('Opened','dashboard')."',
	                data: [";
						 
						 foreach(array_values($arr_cham_op2) as $op){
							echo $op.", ";						 
						 } 
						 		                
	                echo "]
	                }, 
	                {
      		       name: '". __('Closed','dashboard')."',
	                data: [";
						 
						 foreach(array_values($arr_cham_cl2) as $cl){
							echo $cl.", ";						 
						 } 
						 echo "],				 
							 
				   	},
				  ]
	        });
	    });
	
	</script>\n";
	}
		?>
