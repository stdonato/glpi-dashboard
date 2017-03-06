<?php

echo ' <script type="text/javascript">

$(function() {

		var datasets = {';
		

for($i=0; $i < $conta_y; $i++) {	

	$query_m = "
	SELECT DISTINCT DATE_FORMAT( date, '%Y' ) AS year, COUNT( id ) AS nb, DATE_FORMAT( date, '%m' ) AS month
	FROM glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND DATE_FORMAT( date, '%Y' ) = ". $arr_years[$i] ."
	".$entidade."
	GROUP BY month
	ORDER BY month";
	
	$resultm = $DB->query($query_m);
	
	echo '
	"'.$arr_years[$i].'": {label: "'.$arr_years[$i].'", ';
	
	echo 'data: [';
	
	while ($row_m = $DB->fetch_assoc($resultm)) {
		//echo '['.$row_m['month'].', '.$row_m['nb'].'],'; 
		echo "['".$row_m["month"]."', '".$row_m["nb"]."'],"; 
	}
	
	echo '] }, ';
}		
	echo '}; ';

?>

	// hard-code color indices to prevent them from shifting as countries are turned on/off
		var i = 1;
		$.each(datasets, function(key, val) {
			val.color = i;
			++i;
		});

		// insert checkboxes 
		var choiceContainer = $("#choices");
		$.each(datasets, function(key, val) {
			choiceContainer.append("&nbsp;&nbsp;<input type='checkbox' name='" + key + "' checked='checked' id='id" + key + "' > " + val.label + "</input>&nbsp;" );
		});

		choiceContainer.find("input").click(plotAccordingToChoices);

function plotAccordingToChoices() {

			var data = [];

			choiceContainer.find("input:checked").each(function () {
				var key = $(this).attr("name");
				if (key && datasets[key]) {
					data.push(datasets[key]);
				}
			});

			if (data.length > 0) {
				$.plot('#graflinhas1', data, {
					
					lines: { show: true,
                                lineWidth: 1,
                                fill: true, 
                                fillColor: { colors: [ { opacity: 0.08 }, { opacity: 0.27 } ] },
                                label: {show:true}
                             },

                     points: { show: true, 
                              lineWidth: 2,
                              radius: 4
                          },
              
                    grid: { hoverable: true, 
                           clickable: true, 
                           tickColor: "#f9f9f9",
                           borderWidth: 0,
                           backgroundColor: "#fff",
                        },
                    legend: {
                            show: true,
                            labelBoxBorderColor: "#fff"
                        },  
				
					yaxis: {
						min: 0,
						tickLength:0
					},
					xaxis: {
						ticks: [[1,"Jan"], [2,"Feb"], [3,"Mar"], [4,"Apr"], [5,"May"], [6,"Jun"], [7,"Jul"], [8,"Aug"], [9,"Sep"], [10,"Oct"], [11,"Nov"], [12,"Dec"] ],
						tickDecimals: 0,
						tickLength:0
					}
			
				});
			}
		

function showTooltip(x, y, contents) {
                $('<div id="tooltip">' + contents + '</div>').css( {
                    position: 'absolute',
                    display: 'none',
                    top: y - 30,
                    left: x - 50,
                    color: "#fff",
                    padding: '2px 5px',
                    'border-radius': '6px',
                    'background-color': '#000',
                    opacity: 0.80
                }).appendTo("body").fadeIn(200);
            }

            var previousPoint = null;
            $("#graflinhas1").bind("plothover", function (event, pos, item) {
                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;

                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(0),
                            y = item.datapoint[1].toFixed(0);

                   //var month = item.series.xaxis.ticks[item.dataIndex].label;

                        showTooltip(item.pageX, item.pageY,
                                    item.datapoint[0] + " - " + item.series.label + ": " + y);
                    }
                }
                else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });		
		
		}


		plotAccordingToChoices();

		// Add the Flot version string to the footer

		$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
	});

	</script> 	
	