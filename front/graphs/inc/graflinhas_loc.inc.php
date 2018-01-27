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
$arr_months = array();

if($interval <= "31") {
	
	$queryd = "
	SELECT DISTINCT DATE_FORMAT(date, '%b-%d') AS day_l,  COUNT(id) AS nb, DATE_FORMAT(date, '%Y-%m-%d') AS day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND date ".$datas."
	GROUP BY day
	ORDER BY day ";

	$resultd = $DB->query($queryd) or die('erro');
	
	while ($row_result = $DB->fetch_assoc($resultd))
	{
		$v_row_result = $row_result['day'];
		$arr_days[$v_row_result] = 0;		
	}

	$days = array_keys($arr_days) ;
	$quantd = array_values($arr_days) ;

	$DB->data_seek($resultd, 0);
	while ($row_result = $DB->fetch_assoc($resultd))
	{
		$v_row_result = $row_result['day_l'];
		$arr_daysn[$v_row_result] = 0;		
	}

}

else {
	
	$queryd = "
	SELECT DISTINCT DATE_FORMAT(date, '%b-%Y') AS day_l,  COUNT(id) AS nb, DATE_FORMAT(date, '%Y-%m') AS day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND date ".$datas."
	GROUP BY day
	ORDER BY day ";

	$resultd = $DB->query($queryd) or die('erro');
	
	while ($row_result = $DB->fetch_assoc($resultd))
	{
		$v_row_result = $row_result['day'];
		$arr_months[$v_row_result] = 0;		
	}

	$months = array_keys($arr_months) ;
	$monthsq = array_values($arr_months) ;
	
	$DB->data_seek($resultd, 0);	
	while ($row_result = $DB->fetch_assoc($resultd))
	{
		$v_row_result = $row_result['day_l'];
		$arr_monthsn[$v_row_result] = 0;		
	}	
}

//chamados mensais
$arr_grfm = array();
$arr_opened = array();

if($interval >= "31") {

	$DB->data_seek($resultd, 0);
	while ($row_result = $DB->fetch_assoc($resultd)) {
			
		$querym = "
		SELECT DISTINCT DATE_FORMAT(date, '%b-%Y') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%Y-%m') as day
		FROM glpi_tickets
		WHERE glpi_tickets.is_deleted = '0'
		AND glpi_tickets.date ".$datas."
		AND glpi_tickets.locations_id = ".$id_loc."
		AND DATE_FORMAT(glpi_tickets.date, '%Y-%m' ) = '".$row_result['day']."'
		GROUP BY day
		ORDER BY day ";
	
		$resultm = $DB->query($querym) or die('erro m');
		$row_result2 = $DB->fetch_assoc($resultm);

		$v_row_result = $row_result['day'];
		if($row_result2['nb'] != '') {
			$arr_grfm[$v_row_result] = $row_result2['nb'];
		}
		else {
			$arr_grfm[$v_row_result] = 0;
		}
	}

	$arr_opened = $arr_grfm;
	$label = json_encode(array_keys($arr_monthsn));
}

else {
	
	$DB->data_seek($resultd, 0);
	while ($row_result = $DB->fetch_assoc($resultd)) {

		$querym = "
		SELECT DISTINCT DATE_FORMAT(date, '%b-%d') as day_l,  COUNT(id) as nb, DATE_FORMAT(date, '%Y-%m-%d') as day
		FROM glpi_tickets
		WHERE glpi_tickets.is_deleted = '0'
		AND glpi_tickets.date ".$datas."
		AND glpi_tickets.locations_id = ".$id_loc."
		AND DATE_FORMAT(glpi_tickets.date, '%Y-%m-%d') = '".$row_result['day']."'
		GROUP BY day
		ORDER BY day ";
	
		$resultm = $DB->query($querym) or die('erro m');
		$row_result2 = $DB->fetch_assoc($resultm);
	
		$v_row_result = $row_result['day'];
		if($row_result2['nb'] != '') {
			$arr_grfm[$v_row_result] = $row_result2['nb'];
		}
		else {
			$arr_grfm[$v_row_result] = 0;
		}
	}
	
	$arr_opened = $arr_grfm;
	$label = json_encode(array_keys($arr_daysn));
}

//$grfm = array_keys($arr_daysn) ;
$quantm = array_values($arr_opened) ;
$quantm2 = implode(',',$quantm);


// closed
$status = "('5','6')";
$arr_grff = array();

// fechados mensais
if($interval >= "31") {

	// fechados mensais
	$queryf = "
	SELECT DISTINCT DATE_FORMAT(closedate, '%b-%Y') as day_l,  COUNT(id) as nb, DATE_FORMAT(closedate, '%Y-%m') as day
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.closedate ".$datas."	
	AND glpi_tickets.locations_id = ".$id_loc."
	GROUP BY day
	ORDER BY day";
	
	$resultf = $DB->query($queryf) or die('erro f');
	
	while ($row_result = $DB->fetch_assoc($resultf)) {
	
		$v_row_result = $row_result['day'];
		if($row_result['nb'] != '') {
			$arr_grff[$v_row_result] = $row_result['nb'];
		}
		else {
			$arr_grff[$v_row_result] = 0;
		}
	}
	$arr_closed = array_unique(array_merge($arr_months,$arr_grff));
	$label = json_encode(array_keys($arr_monthsn));

 }

 else {
 	
	$DB->data_seek($resultd, 0);
	while ($row_result = $DB->fetch_assoc($resultd))
	{
	
		// fechados mensais
		$queryf = "
		SELECT DISTINCT DATE_FORMAT(closedate, '%b-%d') as day_l,  COUNT(id) as nb, DATE_FORMAT(closedate, '%Y-%m-%d') as day
		FROM glpi_tickets
		WHERE glpi_tickets.is_deleted = '0'
		AND glpi_tickets.closedate ".$datas."
		AND DATE_FORMAT(glpi_tickets.closedate, '%Y-%m-%d' ) = '".$row_result['day']."'
		AND glpi_tickets.locations_id = ".$id_loc."
		GROUP BY day
		ORDER BY day";
	
		$resultf = $DB->query($queryf) or die('erro f');
		$row_result2 = $DB->fetch_assoc($resultf);
	
		$v_row_result = $row_result['day'];
		
		if($row_result2['nb'] != '') {
			$arr_grff[$v_row_result] = $row_result2['nb'];
		}
		else {
			$arr_grff[$v_row_result] = 0;
		}
	}
		$arr_closed = $arr_grff;
		$label = json_encode(array_keys($arr_daysn));
 }

//$grff = array_keys($arr_closed) ;
$quantf = array_values($arr_closed) ;
$quantf2 = implode(',',$quantf);

echo "
<script type='text/javascript'>
$(function ()
{
        $('#graf_linhas').highcharts({
            chart: {
                type: 'column'
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
					 adjustChartSize: true,
                borderWidth: 0,
                //backgroundColor: '#FFFFFF'
            },
            xAxis: {
                categories: ".$label.",
						  labels: {
                    rotation: -55,
                    align: 'right',
                    style: {
                        //fontSize: '11px',
                        //fontFamily: 'Verdana, sans-serif'
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
                column: {
                    fillOpacity: 0.5,
                    borderWidth: 1,
                	  borderColor: 'white',
                	  shadow:true,
                    dataLabels: {
	                 	enabled: true
	                 },
                },
            },
          series: [{
                name: '".__('Opened','dashboard')." (".array_sum($quantm).")',
                data: [$quantm2] },

                {
                name: '".__('Closed','dashboard')." (".array_sum($quantf).")',
                data: [$quantf2]
            }]
        });
    });
  </script>
";

?>
