
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


$sql_gid = "SELECT DISTINCT glpi_groups_tickets.`groups_id` AS gid, count(glpi_groups_tickets.id) AS conta
FROM `glpi_groups_tickets` , glpi_tickets, glpi_groups
WHERE glpi_groups_tickets.`groups_id` = glpi_groups.id
AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
AND glpi_tickets.is_deleted = 0
AND (glpi_tickets.date ".$datas." OR glpi_tickets.closedate ".$datas." )
".$entidade."
GROUP BY gid
ORDER BY conta DESC ";

$query_gid = $DB->query($sql_gid);

$contador = $DB->numrows($query_gid);

//chart height
if($contador > 5) {	
	$height = '900';	
}
else {
	$height = '500';
}


echo "
<script type='text/javascript'>

$(function () {
	// Create the chart
	$('#graf1').highcharts({
    	chart: {
        	type: 'bar',
        	height: ".$height."
    	},
    	title: {
        	text: ''
    	},
    	subtitle: {
        	text: 'Click the bars to view details. '
    	},
    	xAxis: {
        	type: 'category'
    	},
    	yAxis: {
        	title: {
            	text: '". __('Tickets','dashboard')."'
        	}

    	},
    	legend: {
        	enabled: false
    	},
    	plotOptions: {
        	series: {
            	borderWidth: 0,
            	dataLabels: {
                	enabled: true,
                	format: '{point.y}'
            	}
        	}
    	},

    	tooltip: {
        	headerFormat: '<span style=\"font-size:11px\">{series.name}</span><br>',
        	pointFormat: '<span style=\"color:{point.color}\">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
    	},

	series: [{
        	name: 'Groups',
        	colorByPoint: true,
        	data: [ ";
    
    			//$DB->data_seek($query_gid, 0) ;  
				while ($row = $DB->fetch_assoc($query_gid)) {				
						
					$query = "SELECT count( glpi_groups_tickets.id ) AS conta, glpi_groups.name AS name
					FROM `glpi_groups_tickets` , glpi_tickets, glpi_groups
					WHERE glpi_groups_tickets.`groups_id` = glpi_groups.id
					AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
					AND glpi_tickets.is_deleted = 0
					AND glpi_groups.id = ".$row['gid']."
					AND glpi_tickets.date ".$datas."
					".$entidade." ";
					
					$result = $DB->query($query);
					$grupos = $DB->fetch_assoc($result);
					
					echo "
					{
						name: '".$grupos['name']."',
						y: ".$grupos['conta'].",
						drilldown: '".$grupos['name']."'
					},
					";
				}     		
        		
echo "
		]
    	}],
    	drilldown: {
        	series: [ ";


				$DB->data_seek($query_gid, 0) ;  
				while ($row = $DB->fetch_assoc($query_gid)) {					
					
					$query = "SELECT 
					SUM(case when glpi_groups_tickets.type = 1 then 1 else 0 end) AS req,
					SUM(case when glpi_groups_tickets.type = 2 then 1 else 0 end) AS tec,
					SUM(case when glpi_groups_tickets.type = 3 then 1 else 0 end) AS obs,
					count(glpi_groups_tickets.id) AS conta,
					glpi_groups.name AS name
					FROM `glpi_groups_tickets`, glpi_tickets, glpi_groups
					WHERE glpi_groups_tickets.`groups_id` = glpi_groups.id
					AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
					AND glpi_tickets.is_deleted = 0
					AND glpi_groups.id = ".$row['gid']."
					AND glpi_tickets.date ".$datas."
					".$entidade."	 ";
					
					$result = $DB->query($query);
					$grupos = $DB->fetch_assoc($result);
					
					echo "
					{
						name: '".$grupos['name']."',
						id: '".$grupos['name']."',
						data: [
							[
								'". __('Requester')."',
								".$grupos['req']."
							
							],
												[
								'". __('Technician')."',
								".$grupos['tec']."
							
							],
												[
								'". __('Watcher')."',
								".$grupos['obs']."
							
							]							
							
						]
						},
					";
								
				}  
        
echo "        	
]
    	}
	});
});

</script> ";

		//}
		?>