<?php

if($data_ini == $data_fin) {
$datas = "LIKE '".$data_ini."%'";
}

$data1 = $data_ini;
$data2 = $data_fin;

$unix_data1 = strtotime($data1);
$unix_data2 = strtotime($data2);

$interval = ($unix_data2 - $unix_data1) / 86400;

$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

if($interval <= "31") {
	$queryd = "
	SELECT DISTINCT   DATE_FORMAT(date, '%b-%d') AS day_l,  COUNT(id) AS nb, DATE_FORMAT(date, '%Y-%m-%d') AS day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND date ".$datas."
	".$entidade."
	GROUP BY day
	ORDER BY day ";

	$resultd = $DB->query($queryd) or die('erro');

	$arr_days = array();
	while ($row_result = $DB->fetch_assoc($resultd))
		{
			$v_row_result = $row_result['day'];
			$arr_days[$v_row_result] = $row_result['nb'];
		}

	$days = array_keys($arr_days) ;
	$quantd = array_values($arr_days) ;

}

//chamados mensais
$arr_grfm = array();

if($interval >= "31") {

	 $querym = "
	SELECT DISTINCT DATE_FORMAT(date, '%b-%Y') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%y-%m') as day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY day
	ORDER BY day ";

	$resultm = $DB->query($querym) or die('erro');

	while ($row_result = $DB->fetch_assoc($resultm))
		{
			$v_row_result = $row_result['day_l'];
			$arr_grfm[$v_row_result] = $row_result['nb'];
		}

}

else {

		$DB->data_seek($resultd, 0);
		while ($row_result = $DB->fetch_assoc($resultd))
		{
			$querym = "
			SELECT DISTINCT DATE_FORMAT(date, '%b-%d') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%Y-%m-%d') as day
			FROM glpi_tickets
			WHERE glpi_tickets.is_deleted = '0'
			AND DATE_FORMAT( date, '%Y-%m-%d' ) = '".$row_result['day']."'
			".$entidade."
			GROUP BY day
			ORDER BY day ";

			$resultm = $DB->query($querym) or die('erro');
			$row_result2 = $DB->fetch_assoc($resultm);

				$v_row_result = $row_result['day'];
					if($row_result2['nb'] != '') {
						$arr_grfm[$v_row_result] = $row_result2['nb'];
					}
				else {
						$arr_grfm[$v_row_result] = 0;
					}
		}
}

$grfm = array_keys($arr_grfm) ;
$quantm = array_values($arr_grfm) ;

$grfm2 = implode("','",$grfm);
$grfm3 = "'$grfm2'";
$quantm2 = implode(',',$quantm);

$opened = array_sum($quantm);


//array to compare months
$DB->data_seek($resultm, 0);

$arr_month = array();

while ($row_result = $DB->fetch_assoc($resultm))
	{
		$v_row_result = $row_result['day_l'];
		$arr_month[$v_row_result] = 0;
	}

$arr_grfa = array();

if($interval >= "31") {

	$querya = "
	SELECT DISTINCT DATE_FORMAT( date, '%b-%y' ) AS day_l, DATE_FORMAT( date, '%y-%m' ) AS day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY day
	ORDER BY day  ";

	$resulta = $DB->query($querya) or die('erro');
}
else {

	$querya = "
	SELECT DISTINCT DATE_FORMAT( date, '%b-%d' ) AS day_l, DATE_FORMAT( date, '%Y-%m-%d' ) AS day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY day
	ORDER BY day  ";

	$resulta = $DB->query($querya) or die('erro');
}

	if($interval >= "31") {

		while ($row_result = $DB->fetch_assoc($resulta))
		{
			$querya2 = "
			SELECT DISTINCT DATE_FORMAT( date, '%b-%y' ) AS day_l, DATE_FORMAT( date, '%y-%m' ) AS day, count(id) AS nb
			FROM glpi_tickets
			WHERE solvedate IS NOT NULL
			AND due_date IS NOT NULL
			AND solvedate > due_date
			AND is_deleted = 0
			AND glpi_tickets.date ".$datas."
			AND DATE_FORMAT( date, '%b-%y' ) = '".$row_result['day_l']."'
			".$entidade."
			GROUP BY day
			ORDER BY day";

			$resulta2 = $DB->query($querya2) or die('erro a');
			$row_result2 = $DB->fetch_assoc($resulta2);

				$v_row_result = $row_result['day_l'];
			if($row_result2['nb'] != '') {
				$arr_grfa[$v_row_result] = $row_result2['nb'];
				}
		   else {
				$arr_grfa[$v_row_result] = 0;
					}
			}
	}

	else {

		$DB->data_seek($resultd, 0);
		while ($row_result = $DB->fetch_assoc($resulta))
		{

		$querya2 = "
		SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`solvedate`),'%Y-%m-%d') AS day, COUNT(id) AS nb
		FROM glpi_tickets
		WHERE solvedate IS NOT NULL
		AND due_date IS NOT NULL
		AND solvedate > due_date
		AND glpi_tickets.date ".$datas."
		AND DATE_FORMAT( solvedate, '%Y-%m-%d' ) = '".$row_result['day']."'
		".$entidade."
		GROUP BY day
		ORDER BY day";

		$resulta2 = $DB->query($querya2) or die('erro a');
		$row_result2 = $DB->fetch_assoc($resulta2);

		$v_row_result = $row_result['day'];
		if($row_result2['nb'] != '') {
			$arr_grfa[$v_row_result] = $row_result2['nb'];
			}
		else {
			$arr_grfa[$v_row_result] = 0;
				}
	}
	}

//$arr_open = array_merge($arr_month, $arr_grfa);

$grfa = array_keys($arr_grfa) ;
$quanta = array_values($arr_grfa) ;

$grfa2 = implode("','",$grfa);
$grfa3 = "'$grfa2'";
$quanta2 = implode(',',$quanta);

$late = array_sum($quanta);


// solucionados
$arr_grfs = array();

if($interval >= "31") {
	$querys = "
	SELECT DISTINCT DATE_FORMAT( solvedate, '%b-%y' ) AS day_l, DATE_FORMAT( solvedate, '%y-%m' ) AS day, COUNT(id) as nb
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.solvedate ".$datas."
	AND glpi_tickets.solvedate IS NOT NULL
	".$entidade."
	GROUP BY day
	ORDER BY day ";

	$results = $DB->query($querys) or die('erro');

while ($row_result = $DB->fetch_assoc($results))
	{

	$v_row_result = $row_result['day_l'];
	if($row_result['nb'] != '') {
		$arr_grfs[$v_row_result] = $row_result['nb'];
		}
	else {
		$arr_grfs[$v_row_result] = 0;
		}
	}

}

else {

$DB->data_seek($resultd, 0);
while ($row_result = $DB->fetch_assoc($resultd))
{

$querys = "
SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`solvedate`),'%Y-%m-%d') AS day, COUNT(id) AS nb
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.solvedate ".$datas."
AND DATE_FORMAT( solvedate, '%Y-%m-%d' ) = '".$row_result['day']."'
AND glpi_tickets.solvedate IS NOT NULL
".$entidade."
GROUP BY day
ORDER BY day  ";

$results = $DB->query($querys) or die('erro s' . $DB->error());
$row_result2 = $DB->fetch_assoc($results);

	$v_row_result = $row_result['day'];
	if($row_result2['nb'] != '') {
		$arr_grfs[$v_row_result] = $row_result2['nb'];
		}
	else {
		$arr_grfs[$v_row_result] = 0;
		}
	}
}

$grfs = array_keys($arr_grfs) ;
$quants = array_values($arr_grfs) ;

$grfs2 = implode("','",$grfs);
$grfs3 = "'$grfs2'";
$quants2 = implode(',',$quants);

$solved = array_sum($quants);


// fechados mensais

$arr_grff = array();

if($interval >= "31") {

$queryf = "
SELECT DISTINCT DATE_FORMAT( closedate, '%b-%y' ) AS day_l, DATE_FORMAT( closedate, '%y-%m' ) AS day, COUNT(id) AS nb
FROM glpi_tickets
WHERE glpi_tickets.closedate ".$datas."
AND glpi_tickets.closedate IS NOT NULL
".$entidade."
GROUP BY day
ORDER BY day ";

$resultf = $DB->query($queryf) or die('erro');

while ($row_result = $DB->fetch_assoc($resultf))
	{

	$v_row_result = $row_result['day_l'];
	if($row_result['nb'] != '') {
		$arr_grff[$v_row_result] = $row_result['nb'];
		}
	else {
		$arr_grff[$v_row_result] = 0;
		}
	}

}

else {

$DB->data_seek($resultd, 0);
while ($row_result = $DB->fetch_assoc($resultd))
{

$queryf = "
SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`closedate`),'%Y-%m-%d') AS day, COUNT(id) AS nb
FROM glpi_tickets
WHERE glpi_tickets.closedate ".$datas."
AND DATE_FORMAT( closedate, '%Y-%m-%d' ) = '".$row_result['day']."'
AND glpi_tickets.closedate IS NOT NULL
".$entidade."
GROUP BY day
ORDER BY day  ";

$resultf = $DB->query($queryf) or die('erro f' . $DB->error());
$row_result2 = $DB->fetch_assoc($resultf);

	$v_row_result = $row_result['day'];
	if($row_result2['nb'] != '') {
		$arr_grff[$v_row_result] = $row_result2['nb'];
		}
	else {
		$arr_grff[$v_row_result] = 0;
		}
}
}

$grff = array_keys($arr_grff) ;
$quantf = array_values($arr_grff) ;

$grff = implode("','",$grff);
//$grff3 = "'$grff2'";
$quantf2 = implode(',',$quantf);

$closed = array_sum($quantf);

//satisfaction %
if($interval >= "31") {

$query_sat =
"SELECT DISTINCT DATE_FORMAT( glpi_tickets.date, '%b-%Y' ) AS day_l, COUNT( glpi_tickets.id ) AS nb, DATE_FORMAT( glpi_tickets.date, '%y-%m' ) AS day ,
avg( `glpi_ticketsatisfactions`.satisfaction ) AS media
FROM glpi_tickets, `glpi_ticketsatisfactions`
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets.id
".$entidade."
GROUP BY day
ORDER BY day";
}

else {
//$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

$query_sat =
"SELECT DISTINCT DATE_FORMAT( glpi_tickets.date, '%b-%d' ) AS day_l, COUNT( glpi_tickets.id ) AS nb, DATE_FORMAT( glpi_tickets.date, '%Y-%m-%d' ) AS day ,
avg( `glpi_ticketsatisfactions`.satisfaction ) AS media
FROM glpi_tickets, `glpi_ticketsatisfactions`
WHERE glpi_tickets.is_deleted = '0'
AND glpi_tickets.date ".$datas."
AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets.id
".$entidade."
GROUP BY day
ORDER BY day";

}

$result_sat = $DB->query($query_sat) or die('erro');

//array with satisfaction average

$arr_grfsat = array();
while ($row_result1 = $DB->fetch_assoc($result_sat))
	{
	$v_row_result1 = $row_result1['day_l'];
	$arr_grfsat[$v_row_result1] = round(($row_result1['media']/5)*100,1);
	}


$arr_sat = array_merge($arr_month, $arr_grfsat);

$grfsat = array_keys($arr_sat) ;
$quantsat = array_values($arr_sat);

$grfsat2 = implode("','",$grfsat);
$grfsat3 = "'$grfsat2'";
$quantsat2 = implode(',',$quantsat);

$satisf = round(array_sum($quantsat),0);


echo "
<script type='text/javascript'>
$(function () {

        $('#graf_linhas').highcharts({
            chart: {";

if(array_sum($quantsat) != 0) {
	echo        "type: 'line', \n";
}
else {
	echo        "type: 'areaspline',";
}

echo           "height: 460

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
                //backgroundColor: '#FFFFFF',
                adjustChartSize: true
            },
            xAxis: {
                categories: [$grfm3],
                labels: {
                    rotation: -55,
                    align: 'right',
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }

            },
          		 ";

if(array_sum($quantsat) != 0) {

     echo  "
            yAxis: [{
	 						minPadding: 0,
   	 					maxPadding: 0,
    						min: 0,
    						//max:1,
   						showLastLabel:false,
    						//tickInterval:1,

                title: { // Primary yAxis
                    text: '".__('Tickets','dashboard')."'
                }
             },

          		{ // Secondary yAxis
                title: {
                    text: '".__('Satisfaction','dashboard')."',
                    style: {
                       // color: '#4572A7'
                    }
                },
                labels: {
                    format: '{value} %',
                    style: {
                       // color: '#4572A7'
                    }
                },
                opposite: true
            }],
            ";

         }

else {

echo "      yAxis: {
	 						minPadding: 0,
   	 					maxPadding: 0,
    						min: 0,
    						//max:1,
   						showLastLabel:false,
    						//tickInterval:1,

                title: { // Primary yAxis
                    text: '".__('Tickets','dashboard')."'
                }
             },  ";
      }

         echo  "plotOptions: {
                column: {
                    pointPadding: 0.2,
  		              borderWidth: 2,
      	           borderColor: 'white',
         	        shadow:true,
                	  showInLegend: true
                },
                areaspline: {
                    fillOpacity: 0.5
                }
                },

            tooltip: {
                shared: true
            },
            credits: {
                enabled: false
            },

          series: [";

if(array_sum($quantsat) != 0) {

          echo  "
					{ // satisfacao
                name: '".__('Satisfaction','dashboard')." (".$satisf.")',

                type: 'column',
                yAxis: 1,

          		data: [$quantsat2],
                tooltip: {
                    valueSuffix: ' %'
                },
                    dataLabels: {
                    enabled: true,
                    //color: '#000099',
                    align: 'center',
                    x: 1,
                    y: 1,
                    format: '{y} %',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif'
                    },
                    formatter: function () {
                    return Highcharts.numberFormat(this.y, 0, '','');
                }
                },

                },";
            }

echo "
          		 {
                name: '".__('Opened','dashboard')." (".$opened.")',

                 dataLabels: {
                    enabled: true,
                    //color: '#000',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif',
                        //fontWeight: 'bold'
                    },
                    },
                data: [$quantm2]
                },

    				{
                name: '" . __('Solved','dashboard')." (".$solved.")',
                dataLabels: {
                    enabled: false,
                    //color: '#000',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif',
                        //fontWeight: 'bold'
                    },
                    },
                data: [$quants2]
                },

                {
                name: '".__('Late','dashboard')." (".$late.")',

                dataLabels: {
                    enabled: true,
                    //color: '#800000',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif',
                        //fontWeight: 'bold'
                    },
                    },
                data: [$quanta2]
                },

                {
                name: '".__('Closed','dashboard')." (".$closed.")',
                dataLabels: {
                    enabled: false,
                    //color: '#000',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif',
                        //fontWeight: 'bold'
                    },
                    },
                data: [$quantf2]
                },
                ]
        });
    });
  </script>
";

		?>
