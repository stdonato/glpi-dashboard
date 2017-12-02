<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

$data1 = $data_ini;
$data2 = $data_fin;

$unix_data1 = strtotime($data1);
$unix_data2 = strtotime($data2);

$interval = ($unix_data2 - $unix_data1) / 86400;


// if exists satisfaction
$query_if = "
SELECT COUNT(id) AS id
FROM glpi_ticketsatisfactions ";

$result_if = $DB->query($query_if) or die('erro');
$sat_if = $DB->fetch_assoc($result_if);


if($sat_if['id'] != 0) {


if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	//chamados mensais
	$querym = "
	SELECT DISTINCT   DATE_FORMAT(date, '%b-%y') as month_l,  COUNT(id) as nb, DATE_FORMAT(date, '%y-%m') as month
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY month
	ORDER BY month ";

	$resultm = $DB->query($querym) or die('erro');

	$contador = $DB->numrows($resultm);

	$arr_grfm = array();
	while ($row_result = $DB->fetch_assoc($resultm)){
		$v_row_result = $row_result['month_l'];
		$arr_grfm[$v_row_result] = $row_result['nb'];
	}

}

else {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	$querym = "
	SELECT DISTINCT DATE_FORMAT(date, '%b-%d') as month_l, COUNT(id) as nb, DATE_FORMAT(date, '%y-%m-%d') as month
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY month
	ORDER BY month ";

	$resultm = $DB->query($querym) or die('erro');

	$contador = $DB->numrows($resultm);

	$arr_grfm = array();
	while ($row_result = $DB->fetch_assoc($resultm)){
		$v_row_result = $row_result['month'];
		$arr_grfm[$v_row_result] = $row_result['nb'];
	}

}

$grfm = array_keys($arr_grfm) ;
$quantm = array_values($arr_grfm) ;

$grfm3 = json_encode($grfm);
$quantm2 = implode(',',$quantm);

$status = "('5','6')"	;

//array to compare months
$DB->data_seek($resultm, 0);

if($interval >= "31") {

	$arr_month = array();

	while ($row_result = $DB->fetch_assoc($resultm))
		{
			$v_row_result = $row_result['month_l'];
			$arr_month[$v_row_result] = 0;
		}
}
else {
	$arr_month = array();

	while ($row_result = $DB->fetch_assoc($resultm))
		{
			$v_row_result = $row_result['month'];
			$arr_month[$v_row_result] = 0;
		}
}


if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	//chamados abertos mensais
	$querya = "
	SELECT DISTINCT   DATE_FORMAT(date, '%b-%y') as month_l,  COUNT(id) as nb, DATE_FORMAT(date, '%y-%m') as month
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.status NOT IN ". $status ."
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY month
	ORDER BY month ";

	$resulta = $DB->query($querya) or die('erro');

	$arr_grfa = array();
	while ($row_result = $DB->fetch_assoc($resulta)){
		$v_row_result = $row_result['month_l'];
		$arr_grfa[$v_row_result] = $row_result['nb'];
	}

}

else {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	$querya = "
	SELECT DISTINCT DATE_FORMAT(date, '%b-%d') as month_l,  COUNT(id) as nb, DATE_FORMAT(date, '%y-%m-%d') as month
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.status NOT IN ". $status ."
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY month
	ORDER BY month ";

	$resulta = $DB->query($querya) or die('erro');

	$arr_grfa = array();
	while ($row_result = $DB->fetch_assoc($resulta)){
		$v_row_result = $row_result['month'];
		$arr_grfa[$v_row_result] = $row_result['nb'];
	}
}

$arr_open = array_merge($arr_month, $arr_grfa);

$grfa = array_keys($arr_open) ;
$quanta = array_values($arr_open) ;

$grfa3 = json_encode($grfa);
$quanta2 = implode(',',$quanta);


if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	// fechados mensais
	$queryf = "
	SELECT DISTINCT   DATE_FORMAT(date, '%b-%y') as month_l,  COUNT(id) as nb, DATE_FORMAT(date, '%y-%m') as month
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.status IN ". $status ."
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY month
	ORDER BY month ";

	$resultf = $DB->query($queryf) or die('erro');

	$arr_grff = array();
	while ($row_result = $DB->fetch_assoc($resultf)){
		$v_row_result = $row_result['month_l'];
		$arr_grff[$v_row_result] = $row_result['nb'];
	}

}
else {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	// fechados mensais
	$queryf = "
	SELECT DISTINCT   DATE_FORMAT(date, '%b-%y-%d') as month_l,  COUNT(id) as nb, DATE_FORMAT(date, '%y-%m-%d') as month
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.status IN ". $status ."
	AND glpi_tickets.date ".$datas."
	".$entidade."
	GROUP BY month
	ORDER BY month ";

	$resultf = $DB->query($queryf) or die('erro');

	$arr_grff = array();
	while ($row_result = $DB->fetch_assoc($resultf)) {
		$v_row_result = $row_result['month'];
		$arr_grff[$v_row_result] = $row_result['nb'];
	}

}

$grff = array_keys($arr_grff) ;
$quantf = array_values($arr_grff) ;

$grff3 = json_encode($grff);
$quantf2 = implode(',',$quantf);



if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	//satisfaction %
	$query_sat = "SELECT DISTINCT DATE_FORMAT( glpi_tickets.date, '%b-%y' ) AS month_l, COUNT( glpi_tickets.id ) AS nb, DATE_FORMAT( glpi_tickets.date, '%y-%m' ) AS month ,
	avg( `glpi_ticketsatisfactions`.satisfaction ) AS media
	FROM glpi_tickets, `glpi_ticketsatisfactions`
	WHERE glpi_tickets.is_deleted = '0'
	AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets.id
	AND glpi_tickets.date ".$datas."
	AND glpi_ticketsatisfactions.satisfaction <> 'NULL'
	".$entidade."
	GROUP BY month
	ORDER BY month";

	$result_sat = $DB->query($query_sat) or die('erro');

//array with satisfaction average
$arr_grfsat = array();
while ($row_result1 = $DB->fetch_assoc($result_sat))
	{
		$v_row_result1 = $row_result1['month_l'];
		$arr_grfsat[$v_row_result1] = round(($row_result1['media']/5)*100,1);
	}

}
else {

	//satisfaction %
	//$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	$query_sat = "SELECT DISTINCT DATE_FORMAT( glpi_tickets.date, '%b-%y-%d' ) AS month_l, COUNT( glpi_tickets.id ) AS nb, DATE_FORMAT( glpi_tickets.date, '%y-%m-%d' ) AS month,
	avg( `glpi_ticketsatisfactions`.satisfaction ) AS media
	FROM glpi_tickets, `glpi_ticketsatisfactions`
	WHERE glpi_tickets.is_deleted = '0'
	AND `glpi_ticketsatisfactions`.tickets_id = glpi_tickets.id
	AND glpi_tickets.date ".$datas."
	AND glpi_ticketsatisfactions.satisfaction <> 'NULL'
	".$entidade."
	GROUP BY month
	ORDER BY month";

	$result_sat = $DB->query($query_sat) or die('erro');

//array with satisfaction average
$arr_grfsat = array();
while ($row_result1 = $DB->fetch_assoc($result_sat))
	{
		$v_row_result1 = $row_result1['month'];
		$arr_grfsat[$v_row_result1] = round(($row_result1['media']/5)*100,1);
	}
}

$arr_sat = array_merge($arr_month, $arr_grfsat); //$arr_grfsat

$grfsat = array_keys($arr_sat) ;
$quantsat = array_values($arr_sat);

$grfsat3 = json_encode($grfsat);
$quantsat2 = implode(',',$quantsat);


if($contador >= 1) {

echo "
<script type='text/javascript'>
$(function () {

//Highcharts.setOptions({
//    colors: [
//   '#4572A7', '#000099', '#89A54E', '#80699B', '#3D96AE', '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92']
//    });

        $('#graf_sat').highcharts({
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
                text: '".__('Satisfaction','dashboard')." - ".__('Tickets','dashboard')."'
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
                        color: '#4572A7'
                    }
                },
                labels: {
                    format: '{value} %',
                    style: {
                        color: '#4572A7'
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
                name: '".__('Satisfaction','dashboard')."',
                color: '#C4D9F1',
                type: 'column',
                yAxis: 1,

          		data: [".$quantsat2."],
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
                name: '".__('Opened','dashboard')."',
                color: '#4572A7',
                 dataLabels: {
                    enabled: true,
                   // color: '#000000',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif',
                        //fontWeight: 'bold'
                    },
                    },
                data: [$quantm2]
                },

                {
                name: '".__('Closed','dashboard')."',
                color: '#89A54E',
                data: [$quantf2]
                },

                {
                name: '".__('Late','dashboard')."',
                color: '#800000',
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
                }]
        });
    });
  </script>
";
}
}

		?>
