<?php

//chamados abertos (opened)
$querym = "
SELECT DISTINCT DATE_FORMAT( date, '%b-%y' ) AS month_l, COUNT( id ) AS nb, DATE_FORMAT( date, '%y-%m' ) AS
MONTH
FROM glpi_tickets
WHERE glpi_tickets.is_deleted = '0'
".$entidade."
GROUP BY MONTH
ORDER BY MONTH ASC ";

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

// late tickets
$arr_grfa = array();

$DB->data_seek($resultm, 0);
while ($row_result = $DB->fetch_assoc($resultm))
{

	$querya2 = "
	SELECT DISTINCT DATE_FORMAT( date, '%b-%y' ) AS month_l, DATE_FORMAT( date, '%y-%m' ) AS month, count(id) AS nb
	FROM glpi_tickets
	WHERE solvedate IS NOT NULL
	AND time_to_resolve IS NOT NULL
	AND solvedate > time_to_resolve
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

$late = array_sum($quanta);

// solved
$arr_grfs = array();

$DB->data_seek($resultm, 0);
while ($row_result = $DB->fetch_assoc($resultm))
{
	$querys2 = "
	SELECT DISTINCT DATE_FORMAT( date, '%b-%y' ) AS month_l, DATE_FORMAT( date, '%y-%m' ) AS month, count(id) AS nb
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.solvedate IS NOT NULL
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

$solved = array_sum($quants);


// fechados mensais
$arr_grff = array();

$DB->data_seek($resultm, 0);
while ($row_result = $DB->fetch_assoc($resultm))
{
	$queryf = "
	SELECT DISTINCT DATE_FORMAT(date, '%b-%y') as month_l, DATE_FORMAT(date, '%y-%m') as month, COUNT(id) as nb
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.closedate IS NOT NULL
	AND DATE_FORMAT( date, '%b-%y' ) = '".$row_result['month_l']."'
	".$entidade."
	GROUP BY month
	ORDER BY month ";

	$resultf = $DB->query($queryf) or die('errof');
	$row_resultf = $DB->fetch_assoc($resultf);

	$v_row_result = $row_result['month_l'];
	if($row_resultf['nb'] != '') {
		$arr_grff[$v_row_result] = $row_resultf['nb'];
	}
	else {
		$arr_grff[$v_row_result] = 0;
	}
}

$grff = array_keys($arr_grff) ;
$quantf = array_values($arr_grff) ;

$grff3 = json_encode($grff);
$quantf2 = implode(',',$quantf);

$closed = array_sum($quantf);


//satisfaction %
$query_sat =
"SELECT DISTINCT DATE_FORMAT( glpi_tickets.date, '%b-%y' ) AS month_l, COUNT( glpi_tickets.id ) AS nb, DATE_FORMAT( glpi_tickets.date, '%y-%m' ) AS month ,
avg( `glpi_ticketsatisfactions`.satisfaction ) AS media
FROM glpi_tickets, `glpi_ticketsatisfactions`
WHERE glpi_tickets.is_deleted = '0'
AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets.id
".$entidade."
GROUP BY month
ORDER BY month";

$result_sat = $DB->query($query_sat) or die('erro');

//array with satisfaction average

$arr_grfsat = array();
while ($row_result1 = $DB->fetch_assoc($result_sat)){
	$v_row_result1 = $row_result1['month_l'];
	$arr_grfsat[$v_row_result1] = round(($row_result1['media']/5)*100,1);
}

$arr_sat = array_merge($arr_month, $arr_grfsat);

$grfsat = array_keys($arr_sat) ;
$quantsat = array_values($arr_sat);

$grfsat3 = json_encode($grfsat);
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
                categories: $grfm3,
                labels: {
                    rotation: -55,
                    align: 'right',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif'
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
                    //color: '#000000',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif',
                        //fontWeight: 'bold'
                    }
                    },
                data: [$quantm2]
                },
                {
                name: '".__('Solved','dashboard')." (".$solved.")',
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
                	data: [$quantf2]
                },
                ]
        });
    });
  </script>
";

		?>
