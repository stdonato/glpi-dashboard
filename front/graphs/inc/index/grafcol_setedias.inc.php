<?php

$data_inis = date("Y-m-d");  //hoje
$data_fins = date('Y-m-d', strtotime('-6 days'));

$sql_tecd = "
SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, COUNT(id) as conta 
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.date BETWEEN '" . $data_fins ." 00:00:00' AND '".$data_inis." 23:59:59'
". $entidade ."
GROUP BY data
ORDER BY data ASC ";

$query_tecd = $DB->query($sql_tecd);

$arr_data = array();
while ($row_result = $DB->fetch_assoc($query_tecd)){ 
	$arr_data[] = $row_result['data'];	
} 

$datas = json_encode($arr_data);	
	

//REQUESTS 
$DB->data_seek($query_tecd, 0);

while ($row = $DB->fetch_assoc($query_tecd)) { 
	
	$sql_tec = "
	SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, COUNT(id) as conta1, SUM(case when glpi_tickets.type = 2 then 1 else 0 end) AS conta
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = 0	
	AND DATE_FORMAT( date, '%Y-%m-%d' ) = '".$row['data']."'
	". $entidade ."
	GROUP BY data ";
	
	$query_tec = $DB->query($sql_tec);	
	
	$row_result = $DB->fetch_assoc($query_tec);	
	$v_row_result = $row_result['data'];
	
	if($row_result['conta'] != '') {
		$arr_grfa[$v_row_result] = $row_result['conta'];
	}
	else {
		$arr_grfa[$v_row_result] = 0;
	}	
}
	
//if(count($arr_grfa) > 0) {		
	$quanta = array_values($arr_grfa) ;
	$quanta2 = implode(',',$quanta);		
//}

//INCIDENTS
$DB->data_seek($query_tecd, 0);
while ($row = $DB->fetch_assoc($query_tecd))	{ 

	$sql_teci = "
	SELECT DATE_FORMAT(date, '%Y-%m-%d') as data, COUNT(id) as conta1, SUM(case when glpi_tickets.type = 1 then 1 else 0 end) AS conta
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = 0	
	AND DATE_FORMAT( date, '%Y-%m-%d' ) = '".$row['data']."'
	". $entidade ."
	GROUP BY data ";
		
	$query_teci = $DB->query($sql_teci);
	
	$row_result = $DB->fetch_assoc($query_teci);	
	$v_row_result = $row_result['data'];
	
	if($row_result['conta'] != '') {
		$arr_grfi[$v_row_result] = $row_result['conta'];
	}
	else {
		$arr_grfi[$v_row_result] = 0;
	}	
}	

$quanti = array_values($arr_grfi);
$quanti2 = implode(',',$quanti);


echo "<script type='text/javascript'>

$(function () {
	
        $('#graf7').highcharts({
            chart: {
                type: 'column',
                height: 330,
                plotBorderColor: '#ffffff',
            	 plotBorderWidth: 0            	             	                 
            },
            title: {
               // text: '". __('Tickets')." - ". __('Last 7 days','dashboard') ."'
               text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: { 
                        	 
            type: 'datetime',
            dateTimeLabelFormats: {
            day: '%e - %b'
            },
            	 
            formatter: function() 
         		{
               return ''+ Highcharts.numberFormat(this.x, 0);
         		},

				categories: $datas,                      

             title: {
                 text: ''
             },
             labels: {
             	style: {
                     fontSize: '11px',
                     fontFamily: 'Verdana, sans-serif'
                 }
             }
            },
            yAxis: {
                min: 0,
                title: {
						  text: '',
                    align: 'middle'
                },
                labels: {
                    overflow: 'justify'
                },
                stackLabels: {
                enabled: true,
                y:-15,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
              }
            },
        
          tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        },
            plotOptions: {
                column: {
                	stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                        textShadow: '0 0 3px black'
                    }                                                
                    },
                  borderWidth: 2,
                	borderColor: 'white',
                	shadow:true,           
                	showInLegend: true
                }
            },
				legend: {
	            layout: 'horizontal',
	            align: 'left',
	            x: 20,
	            y: -10,
	            verticalAlign: 'top',
	            floating: true,
               adjustChartSize: true,
	            borderWidth: 0	            
	        },
            credits: {
                enabled: false
            },
            series: [
                {
                name: '". __('Request') ."',
					 data: [$quanta2] },
					{
                name: '". __('Incident') ."',
                data: [$quanti2] }]
            
        });
    });

</script>
";	
	
?>
