<?php

if($data_ini == $data_fin) 
	{
		$datas = "LIKE '".$data_ini."%'";	
	}	

else 
	{
		$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";	
	}
	
	//tickets by tech
	$query2 = "	
	SELECT `glpi_groups_users`.`users_id` AS uid, `glpi_users`.`firstname` AS name ,`glpi_users`.`realname` AS sname, sum( glpi_tickets.solve_delay_stat) AS time1,
	AVG(glpi_tickets.solve_delay_stat) AS time2, AVG(TIME_TO_SEC(TIMEDIFF(glpi_tickets.solvedate,glpi_tickets.date))) AS time
	FROM `glpi_groups_users`, glpi_tickets_users, glpi_users, glpi_tickets
	WHERE `glpi_groups_users`.`groups_id` = ".$id_grp."
	AND glpi_tickets_users.users_id = glpi_groups_users.users_id
	AND glpi_tickets_users.users_id = glpi_users.id
	AND glpi_tickets.id = glpi_tickets_users.tickets_id
	AND glpi_tickets.date ".$datas."
	AND glpi_tickets.is_deleted = 0
	AND glpi_tickets_users.type = 2
	AND solvedate IS NOT NULL	
	GROUP BY uid
	ORDER BY time DESC
	LIMIT 30 ";
	
	$result2 = $DB->query($query2) or die('erro');
	
	$arr_grft2 = array();
	
	while($row_result = $DB->fetch_assoc($result2))	 
		{				 			
			$v_row_result = $row_result['name']." ".$row_result['sname'];
			$arr_grft2[$v_row_result] =  round($row_result['time']/3600, 2);		
		}
		
	$grft2 = array_keys($arr_grft2);	
	$quantt2 = array_values($arr_grft2);
			 		
	$conta = count($arr_grft2);
	

echo "
<script type='text/javascript'>

$(function () {
        $('#graf_time').highcharts({
            chart: {
                type: 'bar',
                height: 550               
            },
            title: {
                text: '".__('Time spent by technician','dashboard')."'
            },
            subtitle: {
                text: ''
            },
            xAxis: { 
            categories: [ ";
            
for($i = 0; $i < $conta; $i++) { 
	echo "'".$grft2[$i]."',";
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
                valueSuffix: '',
                formatter: function () {
                return '<b>' + this.x + '</b><br/>' +                    
                    Highcharts.numberFormat(this.y, 2) + ' h' ;
                    //'Total: ' + Highcharts.numberFormat(this.point.stackTotal, 2) + ' h';
            }
            },
            plotOptions: {
          		bar: {
               // stacking: 'normal',
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
                    //color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'gray',
                    style: {
                        //textShadow: '0 0 3px black, 0 0 3px black'
                    }
                },
                 borderWidth: 1,
                 borderColor: 'white',
                 shadow:true,
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
                name: '". __('Users')."',
                data: [  
";
             
//zerar rows para segundo while
          for($i = 0; $i < $conta; $i++) {  
          	/*if(date('H',mktime(0,0,$quantt2[$i])) != 0) {  
						//echo "{ name: '". $grft2[$i]."',"	;	
						echo date('H',mktime(0,0,$quantt2[$i])).",";			
					} */
					echo $quantt2[$i].",";
			}  

echo "]
            }]
        });
    });

</script>
";
	 
		?>
