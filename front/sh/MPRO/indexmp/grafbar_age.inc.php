<?php


$datai_s = date("Y-m-d");  //hoje
$dataf_s = date('Y-m-d', strtotime('-6 days'));

$datai_q = date('Y-m-d', strtotime('-6 days'));
$dataf_q = date('Y-m-d', strtotime('-14 days'));

$datai_m = date('Y-m-d', strtotime('-15 days'));
$dataf_m = date('Y-m-d', strtotime('-29 days'));

$datai_m1 = date('Y-m-d', strtotime('-30 days'));
$dataf_m1 = date('Y-m-d', strtotime('-59 days'));

$datai_m2 = date('Y-m-d', strtotime('-60 days'));
$dataf_m2 = date('Y-m-d', strtotime('-365 days'));


//REQUESTS
//semana
$sql_s = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.type = 2
AND glpi_tickets.date BETWEEN '" . $dataf_s ." 00:00:00' AND '".$datai_s." 23:59:59'
". $entidade ."
AND status NOT IN (5,6) ";

$query_s = $DB->query($sql_s);

$week = $DB->result($query_s,0,'conta');

//quinzena
$sql_q = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.type = 2
AND glpi_tickets.date BETWEEN '" . $dataf_q ." 00:00:00' AND '".$datai_q." 23:59:59'
". $entidade ."
AND status NOT IN (5,6) ";

$query_q = $DB->query($sql_q);

$quinz = $DB->result($query_q,0,'conta');


//mes
$sql_m = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.type = 2
AND glpi_tickets.date BETWEEN '" . $dataf_m ." 00:00:00' AND '".$datai_m." 23:59:59'
". $entidade ."
AND status NOT IN (5,6) ";

$query_m = $DB->query($sql_m);

$month = $DB->result($query_m,0,'conta');


// > 30 e <60
$sql_m1 = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.type = 2
AND glpi_tickets.date BETWEEN '" . $dataf_m1 ." 00:00:00' AND '".$datai_m1." 23:59:59'
". $entidade ."
AND status NOT IN (5,6) ";

$query_m1 = $DB->query($sql_m1);

$month1 = $DB->result($query_m1,0,'conta');


// > 60
$sql_m2 = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.type = 2
AND glpi_tickets.date BETWEEN '" . $dataf_m2 ." 00:00:00' AND '".$datai_m2." 23:59:59' 
". $entidade ."
AND status NOT IN (5,6) ";

$query_m2 = $DB->query($sql_m2);

$month2 = $DB->result($query_m2,0,'conta');



//INCIDENTS
//semana
$sql_si = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.type = 1
AND glpi_tickets.date BETWEEN '" . $dataf_s ." 00:00:00' AND '".$datai_s." 23:59:59'
". $entidade ."
AND status NOT IN (5,6) ";

$query_si = $DB->query($sql_si);

$weeki = $DB->result($query_si,0,'conta');

//quinzena
$sql_qi = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.type = 1
AND glpi_tickets.date BETWEEN '" . $dataf_q ." 00:00:00' AND '".$datai_q." 23:59:59'
". $entidade ."
AND status NOT IN (5,6) ";

$query_qi = $DB->query($sql_qi);

$quinzi = $DB->result($query_qi,0,'conta');


//mes
$sql_mi = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.type = 1
AND glpi_tickets.date BETWEEN '" . $dataf_m ." 00:00:00' AND '".$datai_m." 23:59:59'
". $entidade ."
AND status NOT IN (5,6) ";

$query_mi = $DB->query($sql_mi);

$monthi = $DB->result($query_mi,0,'conta');


// > 30 e <60
$sql_m1i = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.type = 1
AND glpi_tickets.date BETWEEN '" . $dataf_m1 ." 00:00:00' AND '".$datai_m1." 23:59:59'
". $entidade ."
AND status NOT IN (5,6) ";

$query_m1i = $DB->query($sql_m1i);

$month1i = $DB->result($query_m1i,0,'conta');


// > 60
$sql_m2i = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(id) as conta
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_tickets.type = 1
AND glpi_tickets.date BETWEEN '" . $dataf_m2 ." 00:00:00' AND '".$datai_m2." 23:59:59' 
". $entidade ."
AND status NOT IN (5,6) ";

$query_m2i = $DB->query($sql_m2i);

$month2i = $DB->result($query_m2i,0,'conta');



echo "<script type='text/javascript'>

$(function () {
        $('#age').highcharts({
            chart: {
                type: 'column',
					 height: 350,
                plotBorderColor: '#ffffff',
            	 plotBorderWidth: 0 
            },
            title: {
                //text: '" .__('Open Tickets Age','dashboard')."'
                text: ''
            },
           
            xAxis: {
                categories: [ '1-7','7-15','15-30','> 30','> 60' ], 
                labels: { 
                	  text: '',	                    
                    align: 'center',
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    }, 
                    overflow: 'justify'                
                     },
//                     crosshair:true,
                    title: {
						  	text: '" .__('days','dashboard')."',
                    	align: 'middle'
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
  /*      tooltip: {
            headerFormat: '<span style=\"font-size:10px\">{point.key}</span><table>',
            pointFormat: '<tr><td style=\"color:{series.color};padding:0\">{series.name}: </td>' +
                '<td style=\"padding:0\"><b>{point.y:.0f} </b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        }, */
        
          tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        },
			legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
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
             		borderColor: '#fff',
             		shadow:true,           
             		showInLegend: true,
             }
            },
            series: [
            	{
                name: '" .__('Request')."',
                data: [ $week, $quinz, $month, $month1, $month2 ]},
 					 {
                name: '" .__('Incident')."',
                data: [ $weeki, $quinzi, $monthi, $month1i, $month2i ],
                
                dataLabels: {
                    enabled: true,                                        
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
                    
            }]
        });
    });

		</script>";

		?>
