
<?php

if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";
}

$data1 = $data_ini;
$data2 = $data_fin;

$unix_data1 = strtotime($data1);
$unix_data2 = strtotime($data2);

$interval = ($unix_data2 - $unix_data1) / 86400;


if($interval >= "31") {

$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

 $querym = "
SELECT DISTINCT DATE_FORMAT(date, '%b-%Y') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%y-%m') as day
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND glpi_tickets.entities_id = ".$id_ent."
GROUP BY day
ORDER BY day ";
}

else {

$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

 $querym = "
SELECT DISTINCT DATE_FORMAT(date, '%b-%d') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%Y-%m-%d') as day
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND glpi_tickets.entities_id = ".$id_ent."
GROUP BY day
ORDER BY day ";
}

$resultm = $DB->query($querym) or die('errol');

$contador = $DB->numrows($resultm);

$arr_grfm = array();
while ($row_result = $DB->fetch_assoc($resultm))
	{
		$v_row_result = $row_result['day_l'];
		$arr_grfm[$v_row_result] = $row_result['nb'];
	}

$grfm = array_keys($arr_grfm) ;
$quantm = array_values($arr_grfm) ;

$grfm2 = implode("','",$grfm);
$grfm3 = "'$grfm2'";

$quantm2 = implode(',',$quantm);

$version = substr($CFG_GLPI["version"],0,5);

$status = "('5','6')"	;

if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	// fechados mensais
	$queryf = "
	SELECT DISTINCT DATE_FORMAT(date, '%b-%Y') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%y-%m') as day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	AND glpi_tickets.status IN ". $status ."
	AND glpi_tickets.entities_id = ".$id_ent."
	GROUP BY day
	ORDER BY day ";
 }

 else {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	// fechados mensais
	$queryf = "
	SELECT DISTINCT DATE_FORMAT(date, '%b-%d') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%Y-%m-%d') as day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	AND glpi_tickets.status IN ". $status ."
	AND glpi_tickets.entities_id = ".$id_ent."
	GROUP BY day
	ORDER BY day ";

 }

$resultf = $DB->query($queryf) or die('erro');

$arr_grff = array();
while ($row_result = $DB->fetch_assoc($resultf))
	{
	$v_row_result = $row_result['day_l'];
	$arr_grff[$v_row_result] = $row_result['nb'];
	}

$grff = array_keys($arr_grff) ;
$quantf = array_values($arr_grff) ;

$quantf2 = implode(',',$quantf);


echo "
<script type='text/javascript'>
$(function ()
{
        $('#graf_linhas').highcharts({
            chart: {
                type: 'areaspline'
            },
            title: {
                text: '".__('Tickets','dashboard')."'
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                x: 0,
                y: 0,
                //floating: true,
                borderWidth: 0,
                adjustChartSize: true
                //backgroundColor: '#FFFFFF'
            },
            xAxis: {
                categories: [$grfm3],
						  labels: {
                    rotation: -45,
                    align: 'right',
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }

            },
            yAxis: {
                title: {
                    text: ''
                }
            },
            tooltip: {
                shared: true
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                areaspline: {
                    fillOpacity: 0.5
                }
            },
          series: [{
       	         dataLabels: {
                 enabled: true,
                 //color: '#000000',
                 style: {
                     fontSize: '11px',
                     fontFamily: 'Verdana, sans-serif',
                     fontWeight: 'bold'
                 }
                 },
                name: '".__('Opened','dashboard')."',
                data: [$quantm2] },

                {
                name: '".__('Closed','dashboard')."',
                data: [$quantf2]
            }]
        });
    });
  </script>
";

		?>
