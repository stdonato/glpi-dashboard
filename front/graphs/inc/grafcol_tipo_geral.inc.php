
<?php

//chamados mensais

$querym = "
SELECT DISTINCT   DATE_FORMAT(date, '%b-%y') as month_l,  COUNT(id) as nb, DATE_FORMAT(date, '%y-%m') as month
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
".$entidade."
GROUP BY month
ORDER BY month
 ";

$resultm = $DB->query($querym) or die('erro');

$arr_grfm = array();
while ($row_result = $DB->fetch_assoc($resultm))
	{
		$v_row_result = $row_result['month_l'];
		$arr_grfm[$v_row_result] = $row_result['nb'];
	}

$grfm = array_keys($arr_grfm) ;
$quantm = array_values($arr_grfm) ;

$grfm3 = json_encode($grfm);
$quantm2 = implode(',',$quantm);

$opened = array_sum($quantm);

//array to compare months
$DB->data_seek($resultm, 0);

$arr_month = array();
while ($row_result = $DB->fetch_assoc($resultm))
	{
		$v_row_result = $row_result['month_l'];
		$arr_month[$v_row_result] = 0;
	}


// incidents
$arr_grfa = array();

$DB->data_seek($resultm, 0);
while ($row_result = $DB->fetch_assoc($resultm))
{

	$querya2 = "
	SELECT DISTINCT DATE_FORMAT( date, '%b-%y' ) AS month_l, DATE_FORMAT( date, '%y-%m' ) AS month, count(id) AS nb
	FROM glpi_tickets
	WHERE is_deleted = 0
	AND type = 1
	AND DATE_FORMAT( date, '%b-%y' ) = '".$row_result['month_l']."'
	".$entidade."
	GROUP BY month
	ORDER BY month";

	$resulta2 = $DB->query($querya2) or die('erronb');
	$row_result2 = $DB->fetch_assoc($resulta2);

		$v_row_result = $row_result['month_l'];
		if($row_result2['nb'] != '') {
			$arr_grfa[$v_row_result] = $row_result2['nb'];
		}
		else {
			$arr_grfa[$v_row_result] = 0;
		}

}

$arr_open = array_merge($arr_month, $arr_grfa);

$grfa = array_keys($arr_open) ;
$quanta = array_values($arr_open) ;

$grfa3 = json_encode($grfa);
$quanta2 = implode(',',$quanta);


// requests
$arr_grfs = array();

$DB->data_seek($resultm, 0);
while ($row_result = $DB->fetch_assoc($resultm))
{

	$querys2 = "
	SELECT DISTINCT DATE_FORMAT( date, '%b-%y' ) AS month_l, DATE_FORMAT( date, '%y-%m' ) AS month, count(id) AS nb
	FROM glpi_tickets
	WHERE is_deleted = 0
	AND type = 2
	AND DATE_FORMAT( date, '%b-%y' ) = '".$row_result['month_l']."'
	".$entidade."
	GROUP BY month
	ORDER BY month";

	$results2 = $DB->query($querys2) or die('erronb');
	$row_result2 = $DB->fetch_assoc($results2);

		$v_row_result = $row_result['month_l'];
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
$arr_grfp = array();

$DB->data_seek($resultm, 0);
while ($row_result = $DB->fetch_assoc($resultm))
{

	$queryp = "
	SELECT DISTINCT DATE_FORMAT(date, '%b-%y') as month_l, DATE_FORMAT(date, '%y-%m') as month, COUNT(id) as nb
	FROM glpi_problems
	WHERE glpi_problems.is_deleted = '0'
	AND DATE_FORMAT( date, '%b-%y' ) = '".$row_result['month_l']."'
	".$problem."
	GROUP BY month
	ORDER BY month ";

	$resultp = $DB->query($queryp) or die('errof');

	$row_resultp = $DB->fetch_assoc($resultp);

	$v_row_result = $row_result['month_l'];
	if($row_resultp['nb'] != '') {
		$arr_grfp[$v_row_result] = $row_resultp['nb'];
	}
	else {
		$arr_grfp[$v_row_result] = 0;
	}
}

$grfp = array_keys($arr_grfp) ;
$quantp = array_values($arr_grfp) ;

$grfp3 = json_encode($grfp);
$quantp2 = implode(',',$quantp);

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
        /*    tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;
                }
            },*/
            tooltip: {
                pointFormat: '<span style=\"color:{series.color}\">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                shared: true
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: false,
                        x:0,
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
