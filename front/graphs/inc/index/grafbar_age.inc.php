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
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
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
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
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
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
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
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
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
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
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
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
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
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
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
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
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
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
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
AND DATE_FORMAT( date, '%Y' ) IN (".$years.")  
". $entidade ."
AND status NOT IN (5,6) ";

$query_m2i = $DB->query($sql_m2i);

$month2i = $DB->result($query_m2i,0,'conta');


echo "<script type='text/javascript'>

$(function () {
        $('#graf8').highcharts({
            chart: {
                type: 'column',
					 height: 330,
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
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif'
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
                    //fontWeight: 'bold',
                    //color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
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
             },
             series: {
             cursor: 'pointer',
				 colorByPoint: true, 
             point: {
                    events: {
                        click: function () {
                            location.href = this.options.url;
                        }
                    }
                }
            }
        
            },
            series: [
            	{
                name: '" .__('Request')."',
                data: [{ y:$week, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_s."&date2=".$datai_s."' }, {y:$quinz, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_q."&date2=".$datai_q."'}, {y:$month, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m."&date2=".$datai_m."'}, {y:$month1, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m1."&date2=".$datai_m1."'}, {y:$month2, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m2."&date2=".$datai_m2."' }]},
 					 {
                name: '" .__('Incident')."',
                data: [{ y:$weeki, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_s."&date2=".$datai_s."'}, {y:$quinzi, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_q."&date2=".$datai_q."'}, {y:$monthi, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m."&date2=".$datai_m."'}, {y:$month1i, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m1."&date2=".$datai_m1."'}, {y:$month2i, url:'reports/rel_data.php?con=1&stat=open&date1=".$dataf_m2."&date2=".$datai_m2."'}],

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
