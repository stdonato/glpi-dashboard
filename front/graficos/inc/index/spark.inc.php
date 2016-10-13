<?php


echo "
<script type='text/javascript'>

 $(function() {
        /** This code runs when everything has been loaded on the page */
        /* Inline sparklines take their values from the contents of the tag */
       // $('.inlinesparkline').sparkline(); 

        /* Sparklines can also take their values from the first argument 
        passed to the sparkline() function */
       // var myvalues = [10,8,5,7,4,4,1];
       // $('.dynamicsparkline').sparkline(myvalues);

        /* The second argument gives options such as chart type */
       // $('.dynamicbar0').sparkline(myvalues, {type: 'bar', barColor: 'green'} );

        /* Use 'html' instead of an array of values to pass options 
       // to a sparkline with data in the tag */
       // $('.inlinebar').sparkline('html', {type: 'bar', barColor: 'red'} );
        
        
        $('.dynamicbar').sparkline([6,5,7,2,0,4,3,4,6,7,5,5,6,7,0,4,3,4,6,7,2,5,4,3], {
		    type: 'bar',
		    height: '50',
		    barWidth: 8,
		    barSpacing: 3
		    //tooltipFormat: '{{value:levels}} - {{value}}' 
		    });		  
		   
        $('.dynamicbar1').sparkline([4,8,4,5,5,6,7,2,0,4,3,4,6,7,2,5,6,7,2,0,4,3,4,6,7,2,5,6,7,2,0], {
			type: 'line',
    		width: '250',
    		height: '50',
    		fillColor: undefined});
		    
		   $('.dynamicbar2').sparkline([5,6,7,2,0,4,3,4,6,7,5,6], {
		    type: 'bar',
		    height: '50',
		    barWidth: 8,
		    barSpacing: 4,
		    barColor: 'purple'});
		    
	    $('#sparkline').sparkline([5,6,7,9,9,5,3,2,2,4,6,7], {
    		type: 'line',
    		width: '250',
    		fillColor: undefined});
		    
		    
		   });

		</script>";

echo "		
<script type='text/javascript'>
$(function () {
    Highcharts.setOptions({                                          
        global : {
            useUTC : false
        }
    });
    
    var chart = new Highcharts.Chart({
     
        chart:{
            renderTo: 'chart',
            margin:[0, 0, 0, 0],
            backgroundColor:'white',
            height: '80'
        },
        title:{
            text:'" . __('Tickets/hour','dashboard') ."',
            y:0,
            verticalAlign: 'top',
		  style: {
            fontSize: '10px',
            fontFamily: 'Verdana, sans-serif'
        }
        },
        credits:{
            enabled:false
        },
        exporting: {
            enabled: false
        },
        xAxis:{
            labels:{
                enabled:false
            },                title: {
				  text: '',
              align: 'middle'
           }
        },
        yAxis:{
            maxPadding:0,
            minPadding:0,
            gridLineWidth: 0,
            endOnTick:false,
            labels:{
                enabled:false
            }
        },
        legend:{
            enabled:false
        },
         tooltip: {
          enabled: true,	
          backgroundColor: 'white',
          borderWidth: 1,
          shadow: false,
          useHTML: true,
          hideDelay: 0,
          shared: true,
          padding: 0         
          //positioner: function (w, h, point) {
          //    return { x: point.plotX - w / 2, y: point.plotY - h};
         // }
        },
        plotOptions:{
            series:{
                enableMouseTracking:true,
                lineWidth:1,
                shadow:false,
                states:{
                    hover:{
                        lineWidth:1
                    }
                },
                marker:{
                    //enabled:false,
                    radius:0,
                    states:{
                        hover:{
                            radius:2
                        }
                    }
                }
            },
                column: {
                    negativeColor: '#910000',
                    borderColor: 'silver'
                }
            
        },
        series: [{type:'column',
        		name: '" .__('Tickets')."',
            data: [6,5,7,2,4,4,3,4,6,7,5,5,6,7,0,4,3,4,6,7,2,5,4,6]
        }]
    
    });
    
    
    
});
</script> ";

//chart1
echo "		
<script type='text/javascript'>
$(function () {
    Highcharts.setOptions({                                          
        global : {
            useUTC : false
        }
    });
    
    var chart = new Highcharts.Chart({
     
        chart:{
            renderTo: 'chart1',
            margin:[0, 0, 0, 0],
            backgroundColor:'white',
            height: '80'
        },
        title:{
            text:'" . __('Tickets/day','dashboard') ."',
            y:-2,
            verticalAlign: 'top',
		  style: {
            fontSize: '10px',
            fontFamily: 'Verdana, sans-serif'
        }
        },
        credits:{
            enabled:false
        },
        exporting: {
            enabled: false
        },
        xAxis:{
            labels:{
                enabled:false
            },                title: {
				  text: '',
              align: 'middle'
           }
        },
        yAxis:{
            maxPadding:0,
            minPadding:0,
            gridLineWidth: 0,
            endOnTick:false,
            labels:{
                enabled:false
            }
        },
        legend:{
            enabled:false
        },
         tooltip: {
          enabled: true,	
          backgroundColor: 'white',
          borderWidth: 1,
          shadow: false,
          useHTML: true,
          hideDelay: 0,
          shared: true,
          padding: 0         
          //positioner: function (w, h, point) {
          //    return { x: point.plotX - w / 2, y: point.plotY - h};
         // }
        },
        plotOptions:{
            series:{
                enableMouseTracking:true,
                lineWidth:1,
                shadow:false,
                states:{
                    hover:{
                        lineWidth:1
                    }
                },
                marker:{
                    //enabled:false,
                    radius:0,
                    states:{
                        hover:{
                            radius:2
                        }
                    }
                }
            },
                column: {
                    negativeColor: '#910000',
                    borderColor: 'silver'
                }
            
        },
        series: [{type:'areaspline',
        		name: '" .__('Tickets')."',
            data: [4,8,4,5,5,6,7,2,0,4,3,4,6,7,2,5,6,7,2,0,4,3,4,6,7,2,5,6,7,2,4]
        }]
    
    });
    
    
    
});
</script> ";	


//chart2

echo "		
<script type='text/javascript'>
$(function () {
    Highcharts.setOptions({                                          
        global : {
            useUTC : false
        }
    });
    
    var chart = new Highcharts.Chart({
     
        chart:{
            renderTo: 'chart2',
            margin:[0, 0, 0, 0],
            backgroundColor:'white',
            height: '80'
        },
        title:{
            text:'" . __('Tickets/month','dashboard') ."',
            y:-2,
            verticalAlign: 'top',
		  style: {
            fontSize: '10px',
            fontFamily: 'Verdana, sans-serif'
        }
        },
        credits:{
            enabled:false
        },
        exporting: {
            enabled: false
        },
        xAxis:{
            labels:{
                enabled:false
            },                title: {
				  text: '',
              align: 'middle'
           }
        },
        yAxis:{
            maxPadding:0,
            minPadding:0,
            gridLineWidth: 0,
            endOnTick:false,
            labels:{
                enabled:false
            }
        },
        legend:{
            enabled:false
        },
         tooltip: {
          enabled: true,	
          backgroundColor: 'white',
          borderWidth: 1,
          shadow: false,
          useHTML: true,
          hideDelay: 0,
          shared: true,
          padding: 0         
          //positioner: function (w, h, point) {
          //    return { x: point.plotX - w / 2, y: point.plotY - h};
         // }
        },
        plotOptions:{
            series:{
                enableMouseTracking:true,
                lineWidth:1,
                shadow:false,
                states:{
                    hover:{
                        lineWidth:1
                    }
                },
                marker:{
                    //enabled:false,
                    radius:0,
                    states:{
                        hover:{
                            radius:2
                        }
                    }
                }
            },
                column: {
                    negativeColor: '#910000',
                    borderColor: 'silver'
                }
            
        },
        series: [{type:'column',
        		name: '" .__('Tickets')."',
            data: [6,5,7,2,4,4,3,4,6,7,5,5]
        }]
    
    });
    
    
    
});
</script> ";				
		
		 
	   echo '</div>';
		
		?>
