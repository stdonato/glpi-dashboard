
<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

$data1 = $data_ini;
$data2 = $data_fin;

$unix_data1 = strtotime($data1);
$unix_data2 = strtotime($data2);

$interval = ($unix_data2 - $unix_data1) / 86400;

//$status = "('2','1','3','4')"	;
$status = "('5','6')";

//opened tickets
if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	//chamados mensais
	 $querym = "
	SELECT DISTINCT DATE_FORMAT(glpi_tickets.date, '%b-%Y') as day_l,  COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.date, '%y-%m') as day
	FROM glpi_tickets, glpi_groups_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_groups_tickets.groups_id = ".$id_grp."
	AND glpi_tickets.date ".$datas."
	AND glpi_tickets.id = glpi_groups_tickets.tickets_id
	GROUP BY day
	ORDER BY day ";
}

else {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	//chamados diarios
	 $querym = "
	SELECT DISTINCT DATE_FORMAT(glpi_tickets.date, '%b-%d') as day_l,  COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.date, '%Y-%m-%d') as day
	FROM glpi_tickets, glpi_groups_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	AND glpi_groups_tickets.groups_id = ".$id_grp."
	AND glpi_tickets.id = glpi_groups_tickets.tickets_id
	GROUP BY day
	ORDER BY day ";
}

$resultm = $DB->query($querym) or die('erro');

$contador = $DB->numrows($resultm);

$version = substr($CFG_GLPI["version"],0,5);


//closed tickets

if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	//fechados mensais
	$queryf = "
	SELECT DISTINCT DATE_FORMAT(glpi_tickets.closedate, '%b-%Y') as day_l, COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.closedate, '%y-%m') as day
	FROM glpi_tickets, glpi_groups_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_groups_tickets.groups_id = ".$id_grp."
	AND glpi_tickets.closedate ".$datas."
	AND glpi_tickets.id = glpi_groups_tickets.tickets_id

	GROUP BY day
	ORDER BY day ";
}

else {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	//fechados mensais   	AND glpi_tickets.status IN ". $status ."
	$queryf = "
	SELECT DISTINCT DATE_FORMAT(glpi_tickets.closedate, '%b-%d') as day_l, COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.closedate, '%Y-%m-%d') as day
	FROM glpi_tickets, glpi_groups_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.closedate ".$datas."
	AND glpi_groups_tickets.groups_id = ".$id_grp."
	AND glpi_tickets.id = glpi_groups_tickets.tickets_id

	GROUP BY day
	ORDER BY day ";
}

$resultf = $DB->query($queryf) or die('erro');

$arr_grff = array();

while ($row_result = $DB->fetch_assoc($resultf)) {
	$v_row_result = $row_result['day_l'];
	$arr_grff[$v_row_result] = $row_result['nb'];
}

$grff = array_keys($arr_grff) ;
$quantf = array_values($arr_grff);
$quantf2 = implode(',',$quantf);


//abertos
$arr_grfa = array();

while ($row_result = $DB->fetch_assoc($resultm)){
	$v_row_result = $row_result['day_l'];
	$arr_grfa[$v_row_result] = $row_result['nb'];
}

$grfa = array_keys($arr_grfa) ;
$quanta = array_values($arr_grfa) ;

$grfa3 = json_encode($grfa);
$quanta2 = implode(',',$quanta);

//graph dates
$arr_datas = array_unique(array_merge($grfa, $grff));
$datas1 = array_values($arr_datas);
$datas = json_encode($datas1);

$val_ab = array();
$val_fe = array();
$arr_eq = array();

//igualar itens dos arrays
$contaa = count($arr_grfa);
$contaf = count($arr_grff);

if($contaa > $contaf) {
	for($i=1; $i <= $contaa - $contaf; $i++) {
		array_push($arr_grff,0);
	}
}

if($contaf > $contaa) {
	for($i=1; $i <= $contaf - $contaa; $i++) {
		array_push($arr_grfa,0);
	}
}



for ($i=0; $i < count($arr_datas); $i++) {

	if($arr_datas[$i] != $grfa[$i]) {
		$val_ab[$i] == 0;	
	}
	else {
		$val_ab[$i] = $quanta[$i];	
	}	

}



for ($i=0; $i < count($arr_datas); $i++) {

	if(in_array($grff[$i],$arr_datas[$i])) {
		$val_fe[$i] == 0;	
	}
	else {
		$val_fe[$i] = $quantf[$i];	
	}	

}

/*if($grfa == '') {
	$grfa3 = json_encode($grff);
}*/

//var_dump($grfa);
//var_dump($quanta);
var_dump($arr_grfa);
var_dump($arr_grff);

//$res = array_unique(array_merge($grfa, $grff));
//var_dump($arr_datas);
var_dump($val_ab);
var_dump($val_fe);
//var_dump($arr_grff);



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
                borderWidth: 0,
								adjustChartSize: true
                //backgroundColor: '#FFFFFF'
            },
            xAxis: {
                categories: $datas,
						  labels: {
                    rotation: -45,
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
                name: '".__('Opened','dashboard')."',
                data: [$quanta2] },

                {
                name: '".__('Closed','dashboard')."',
                data: [$quantf2]
            }]
        });
    });
  </script>
";

		?>
