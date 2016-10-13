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


//semana
$sql_s = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(glpi_tickets.id) as conta
FROM glpi_tickets, glpi_groups_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_groups_tickets.tickets_id = glpi_tickets.id
AND glpi_groups_tickets.groups_id = ".$id_grp."
AND glpi_tickets.date BETWEEN '" . $dataf_s ." 00:00:00' AND '".$datai_s." 23:59:59'
". $entidade_age ."
AND status NOT IN ('5','6') ";

$query_s = $DB->query($sql_s);

$week = $DB->result($query_s,0,'conta');

//quinzena
$sql_q = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(glpi_tickets.id) as conta
FROM glpi_tickets, glpi_groups_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_groups_tickets.tickets_id = glpi_tickets.id
AND glpi_groups_tickets.groups_id = ".$id_grp."
AND glpi_tickets.date BETWEEN '" . $dataf_q ." 00:00:00' AND '".$datai_q." 23:59:59'
". $entidade_age ."
AND status NOT IN ('5','6') ";

$query_q = $DB->query($sql_q);

$quinz = $DB->result($query_q,0,'conta');


//mes
$sql_m = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(glpi_tickets.id) as conta
FROM glpi_tickets, glpi_groups_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_groups_tickets.tickets_id = glpi_tickets.id
AND glpi_groups_tickets.groups_id = ".$id_grp."
AND glpi_tickets.date BETWEEN '" . $dataf_m ." 00:00:00' AND '".$datai_m." 23:59:59'
". $entidade_age ."
AND status NOT IN ('5','6') ";

$query_m = $DB->query($sql_m);

$month = $DB->result($query_m,0,'conta');


// > 30 e <60
$sql_m1 = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(glpi_tickets.id) as conta
FROM glpi_tickets, glpi_groups_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_groups_tickets.tickets_id = glpi_tickets.id
AND glpi_groups_tickets.groups_id = ".$id_grp."
AND glpi_tickets.date BETWEEN '" . $dataf_m1 ." 00:00:00' AND '".$datai_m1." 23:59:59'
". $entidade_age ."
AND status NOT IN ('5','6') ";

$query_m1 = $DB->query($sql_m1);

$month1 = $DB->result($query_m1,0,'conta');


// > 60 AND
//AND glpi_tickets.date < '" . $dataf_m2 ." 00:00:00'

$sql_m2 = "
SELECT DATE_FORMAT(date, '%b-%d') as data, COUNT(glpi_tickets.id) as conta
FROM glpi_tickets, glpi_groups_tickets
WHERE glpi_tickets.is_deleted = 0
AND glpi_groups_tickets.tickets_id = glpi_tickets.id
AND glpi_groups_tickets.groups_id = ".$id_grp."
AND glpi_tickets.date BETWEEN '" . $dataf_m2 ." 00:00:00' AND '".$datai_m2." 23:59:59'
". $entidade_age ."
AND status NOT IN ('5','6') ";

$query_m2 = $DB->query($sql_m2);

$month2 = $DB->result($query_m2,0,'conta');


echo "<script type='text/javascript'>

$(function () {
        $('#graf_time').highcharts({
            chart: {
                type: 'column',
					 height: 450,
                plotBorderColor: '#ffffff',
            	 plotBorderWidth: 0
            },
            title: {
                text: '" .__('Open Tickets Age','dashboard')."'
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
                     title: {
						  text: ' " .__('days','dashboard')."',
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
                }
            },
         tooltip: {
                valueSuffix: ' '
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true
                    },
                    borderWidth: 2,
                		borderColor: '#fff',
                		shadow:true,
                		showInLegend: false,
                }
            },
            series: [{
                name: ' " .__('Tickets')."',
                data: [ $week, $quinz, $month, $month1, $month2 ],
                dataLabels: {
                    enabled: true,
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
        });
    });

		</script>";

		?>
