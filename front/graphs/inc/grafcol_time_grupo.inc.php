<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";	
}	

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
}

$query_grp = "
SELECT ggt.groups_id AS gid, count( ggt.tickets_id ) AS quant
FROM glpi_groups_tickets ggt, glpi_tickets gt
WHERE ggt.type = 1
AND gt.is_deleted = 0
AND gt.closedate IS NOT NULL
AND ggt.tickets_id = gt.id
AND gt.solvedate ".$datas."
AND gt.entities_id = ".$id_ent."
GROUP BY ggt.groups_id
ORDER BY quant DESC
LIMIT 0, 20 ";

$result_grp = $DB->query($query_grp);

$arr_grft2 = array();

while ($row = $DB->fetch_assoc($result_grp)) {
	
	//tickets by type
	$query2 = "
	SELECT gg.completename AS gname, sum( gt.solve_delay_stat) AS time
	FROM glpi_groups_tickets ggt, glpi_tickets gt, glpi_groups gg
	WHERE ggt.groups_id = ".$row['gid']."
	AND ggt.type = 1
	AND ggt.groups_id = gg.id
	AND gt.is_deleted = 0
	AND closedate IS NOT NULL
	AND gt.id = ggt.tickets_id ";
	
	$result2 = $DB->query($query2) or die('erro');
	
	$row_result = $DB->fetch_assoc($result2);		
		 			
		$v_row_result = $row_result['gname'];
		$arr_grft2[$v_row_result] =  round($row_result['time'], 3);		
		
	$grft2 = array_keys($arr_grft2);	
	$quantt2 = array_values($arr_grft2);
			 		
}
	$conta = count($arr_grft2);


echo "
<script type='text/javascript'>

$(function () {
    $('#graf_time1').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: '".__('Time spent by requester group','dashboard')."'
        },
        xAxis: {
            categories: ['" ._n('Group','Groups',2). "']
        },
        yAxis: {
            min: 0,
            title: {
                text: '" ._n('Hour','Hours',2)."'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },

        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +                    
                    Highcharts.numberFormat(this.y, 2) + ' h<br>' +
                    'Total: ' + Highcharts.numberFormat(this.point.stackTotal, 2) + ' h';
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
						  type: 'datetime',
  		              dateTimeLabelFormats: {
               	  hour: '%H:%M'
            			}, 
            		formatter: function() 
            		{
                  return ''+ Highcharts.numberFormat(this.y, 2) + ' h';
            		},               	
                	  //format: '{point.y} h - ( {point.percentage:.1f}% )',                	  
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black, 0 0 3px black'
                    }
                }
            }
        },
        series: [ ";
          for($i = 0; $i < $conta; $i++) {  
          	if(date('H:i',mktime(0,0,$quantt2[$i])) != 0) {  
						echo "{ name: '". $grft2[$i]."',"	;	
						echo "data: [".date('H',mktime(0,0,$quantt2[$i]))."] },";			
					}
			}				
        
        echo "]
    });
});
    
</script>  
";
	 
		?>
