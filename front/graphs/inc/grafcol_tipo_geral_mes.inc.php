<?php

if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";
}

$data1 = $data_ini;
$data2 = $data_fin;

$unix_data1 = strtotime($data1);
$unix_data2 = strtotime($data2);

$interval = ($unix_data2 - $unix_data1) / 86400;

//incidents
if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	$querya = "
	SELECT DISTINCT DATE_FORMAT( date, '%b-%y' ) AS day_l, DATE_FORMAT( date, '%y-%m' ) AS day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY day
	ORDER BY day
	";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	$querya = "
	SELECT DISTINCT DATE_FORMAT(date, '%b-%d') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%Y-%m-%d') as day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY day
	ORDER BY day  ";
}
// all months
$months = $DB->query($querya) or die('erro');


$arr_grfa = array();

while ($row_result = $DB->fetch_assoc($months))
	{
	if($interval >= "31") {

		$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
		$querya2 = "
		SELECT DISTINCT DATE_FORMAT( date, '%b-%y' ) AS day_l, DATE_FORMAT( date, '%y-%m' ) AS day, count(id) AS nb
		FROM glpi_tickets
		WHERE is_deleted = 0
		AND type = 1
		AND glpi_tickets.date ".$datas."
		AND DATE_FORMAT( date, '%b-%y' ) = '".$row_result['day_l']."'
		".$entidade."
		GROUP BY day
		ORDER BY day";
		}
	else {

		$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
		$querya2 = "
		SELECT DISTINCT DATE_FORMAT(date, '%b-%d') AS day_l,  COUNT(id) AS nb, DATE_FORMAT(date, '%Y-%m-%d') AS day
		FROM glpi_tickets
		WHERE is_deleted = 0
		AND type = 1
		AND glpi_tickets.date ".$datas."
		AND DATE_FORMAT( date, '%b-%d' ) = '".$row_result['day_l']."'
		".$entidade."
		GROUP BY day
		ORDER BY day";
		}

$months2 = $DB->query($querya2) or die('erro a');
$row_result2 = $DB->fetch_assoc($months2);

	$v_row_result = $row_result['day_l'];
		if($row_result2['nb'] != '') {
		$arr_grfa[$v_row_result] = $row_result2['nb'];
		}
	else {
		$arr_grfa[$v_row_result] = 0;
		}

	}

$grfa = array_keys($arr_grfa) ;
$quanta = array_values($arr_grfa) ;

$grfa3 = json_encode($grfa);
$quanta2 = implode(',',$quanta);


// requests

if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	$querys = "
	SELECT DISTINCT DATE_FORMAT( date, '%b-%y' ) AS day_l, DATE_FORMAT( date, '%y-%m' ) AS day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY day
	ORDER BY day
	";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	$querys = "
	SELECT DISTINCT DATE_FORMAT(date, '%b-%d') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%Y-%m-%d') as day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY day
	ORDER BY day  ";
}

$months = $DB->query($querys) or die('erro');

$arr_grfs = array();

while ($row_result = $DB->fetch_assoc($months))
	{
	if($interval >= "31") {

		$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
		$querys2 = "
		SELECT DISTINCT DATE_FORMAT( date, '%b-%y' ) AS day_l, DATE_FORMAT( date, '%y-%m' ) AS day, COUNT(id) AS nb
		FROM glpi_tickets
		WHERE is_deleted = 0
		AND type = 2
		AND glpi_tickets.date ".$datas."
		AND DATE_FORMAT( date, '%b-%y' ) = '".$row_result['day_l']."'
		".$entidade."
		GROUP BY day
		ORDER BY day";
		}
	else {

		$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
		$querys2 = "
		SELECT DISTINCT DATE_FORMAT(date, '%b-%d') AS day_l, DATE_FORMAT(date, '%Y-%m-%d') AS day, COUNT(id) AS nb
		FROM glpi_tickets
		WHERE is_deleted = 0
		AND type = 2
		AND glpi_tickets.date ".$datas."
		AND DATE_FORMAT( date, '%b-%d' ) = '".$row_result['day_l']."'
		".$entidade."
		GROUP BY day
		ORDER BY day";
		}

$months2 = $DB->query($querys2) or die('erro a');
$row_result2 = $DB->fetch_assoc($months2);

	$v_row_result = $row_result['day_l'];
		if($row_result2['nb'] != '') {
		$arr_grfs[$v_row_result] = $row_result2['nb'];
		}
	else {
		$arr_grfs[$v_row_result] = 0;
		}

	}

$grfs = array_keys($arr_grfs) ;
$quants = array_values($arr_grfs) ;

$grfs3 = json_encode($grfs);
$quants2 = implode(',',$quants);

// problems
if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	$queryp = "
	SELECT DISTINCT DATE_FORMAT( date, '%b-%y' ) AS day_l, DATE_FORMAT( date, '%y-%m' ) AS day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY day
	ORDER BY day
	";
}

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	$queryp = "
	SELECT DISTINCT DATE_FORMAT(date, '%b-%d') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%Y-%m-%d') as day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY day
	ORDER BY day  ";
}

$months = $DB->query($queryp) or die('erro');

$arr_grfp = array();

while ($row_result = $DB->fetch_assoc($months))
	{
	if($interval >= "31") {

		$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
		$queryp2 = "
		SELECT DISTINCT DATE_FORMAT(date, '%b-%y') AS day_l, DATE_FORMAT(date, '%y-%m') AS day, COUNT(id) AS nb
		FROM glpi_problems
		WHERE is_deleted = 0
		AND glpi_problems.date ".$datas."
		AND DATE_FORMAT( date, '%b-%y' ) = '".$row_result['day_l']."'
		".$problem."
		GROUP BY day
		ORDER BY day  ";
		}
	else {

		$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";
		$queryp2 = "
		SELECT DISTINCT DATE_FORMAT(date, '%b-%d') AS day_l, DATE_FORMAT(date, '%Y-%m-%d') AS day, COUNT(id) AS nb
		FROM glpi_problems
		WHERE is_deleted = 0
		AND glpi_problems.date ".$datas."
		AND DATE_FORMAT( date, '%b-%d' ) = '".$row_result['day_l']."'
		".$problem."
		GROUP BY day
		ORDER BY day  ";
		}

$months2 = $DB->query($queryp2) or die('erro a');
$row_result2 = $DB->fetch_assoc($months2);

	$v_row_result = $row_result['day_l'];
		if($row_result2['nb'] != '') {
		$arr_grfp[$v_row_result] = $row_result2['nb'];
		}
	else {
		$arr_grfp[$v_row_result] = 0;
		}
	}

$grfp = array_keys($arr_grfp) ;
$quantp = array_values($arr_grfp) ;

$grfp3 = json_encode($grfp);
$quantp2 = implode(',',$quantp);

echo "problemas";
print_r($quantp2);

echo "
<script type='text/javascript'>
$(function () {
        $('#graf_tipo').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '".__('Tickets','dashboard')." ".__('by Type','dashboard')."'
            },
            xAxis: {
                categories: $grfp3
            },
            yAxis: {
                min: 0,
                title: {
                    text: '".__('Tickets','dashboard')."'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        //fontWeight: 'bold',
                        //color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
     			legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                x: 0,
                y: 0,
                //floating: true,
                borderWidth: 0,
                //backgroundColor: '#FFFFFF',
                adjustChartSize: true
            },
                tooltip: {
                pointFormat: '<span style=\"color:{series.color}\">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                shared: true
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: false,
                        x: 5,
                        y:0,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black, 0 0 3px black'
                        }
                    }
                }
            },
            series: [{
                name: '".__('Incident')."',
                data: [$quanta2]
            }, {
                name: '".__('Request')."',
                data: [$quants2]
            }, {
                name: '".__('Problem')."',
                data: [$quantp2]
            }]
        });
    });

  </script>
";

		?>
