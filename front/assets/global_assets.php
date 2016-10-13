<?php

$arr_assets =  array('Computer', 'Monitor', 'Printer', 'Networkequipment', 'Phone', 'Peripheral');
$global = 0;

foreach($arr_assets as $asset) {

	$query = "
	SELECT count(id) AS id
	FROM glpi_". strtolower($asset)."s
	WHERE is_deleted = 0
	AND is_template = 0
	".$ent_global." ";
	
	
	$result = $DB->query($query);
	$total = $DB->result($result,0,'id');
	
	$arr_totals[$asset] = $total;

}

$grf_os2 = array_keys($arr_totals);
$quant_os2 = array_values($arr_totals);
$conta_os = count($arr_totals);

echo "
<script type='text/javascript'>

$(function () {		
    	   		
		// Build the chart
        $('#graf_global1').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '".__('Assets by Type','dashboard')."'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    //size: '85%',
 					dataLabels: {
								format: '{point.y} - ( {point.percentage:.1f}% )',
                   		style: {
                        	color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        		},
                        //connectorColor: 'black'
                    },
                showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '',
                data: [
                    {
                        name: '" . __($grf_os2[0],'dashboard') . "',
                        y: $quant_os2[0],
                        sliced: true,
                        selected: true
                    },";
                    
for($i = 1; $i < $conta_os; $i++) {    
     echo '[ "' . __($grf_os2[$i],'dashboard'). '", '.$quant_os2[$i].'],';
        }                    
                                                         
echo "                ]
            }]
        });
    });

		</script>"; 

?>
