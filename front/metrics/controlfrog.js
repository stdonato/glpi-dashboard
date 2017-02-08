// Colour settings
if(themeColour == 'white'){
	var metric = '#a9a9a9';
	var backColor = '#7d7d7d';
	var pointerColor = '#898989'; 	
	var pageBackgorund = '#fff';
	var pieTrack = metric;
	var pieBar = backColor;
	var gaugeTrackColor = metric;
	var gaugeBarColor = backColor;
	var gaugePointerColor = '#ccc';
	//var pieSegColors = [metric,'#868686','#636363','#404040','#1d1d1d'];
	var pieSegColors = [metric,'#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
	var bbarColor = '#7d7d7d';
   var btrackColor = '#ccc';	      
}

else {
	//default to black
	var backColor = '#4f4f4f';
	var metric = '#f2f2f2';	
	var pointerColor = '#898989'; 
	var pageBackgorund = '#2b2b2b';	
	//var pieSegColors = [metric,'#c0c0c0','#8e8e8e','#5b5b5b','#292929'];
	var pieSegColors = [metric,'#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
	var pieTrack = backColor;
	var pieBar = metric;
	var gaugeTrackColor = '#4f4f4f';
	var gaugeBarColor = '#898989';
	var gaugePointerColor = metric;
	var bbarColor = '#f9f9f9';
   var btrackColor = '#4f4f4f';
}

// Stores
var cf_rSVPs = [];
var cf_rGs = [];
var cf_rLs = [];
var cf_rPs = [];
var cf_rRags = [];
var cf_rFunnels = [];

$(document).ready(function(){

	// Make items square
	cfSizeItems();
	
	// Navigation 
	$('.cf-nav-toggle').click(function(e){

		if( $('.cf-nav').hasClass('cf-nav-state-min') ){
			$('.cf-nav').removeClass('cf-nav-state-min').addClass('cf-nav-state-max');
			$('.cf-container').addClass('cf-nav-state-max');
		}
		else{
			$('.cf-nav').removeClass('cf-nav-state-max').addClass('cf-nav-state-min');		
			$('.cf-container').removeClass('cf-nav-state-max');			
		}
		
		e.preventDefault();
	});
		
	// Time and date display 
	(function updateTime(){
		var now = moment();
		
		$('.cf-td').each(function(){
			if($(this).hasClass('cf-td-12')){
				$('.cf-td-time', $(this)).html(now.format('h:mm'));
				ampm = now.format('a');
				$('.cf-td-time', $(this)).append('<span>'+ampm+'</span>');
			}
			else{
				$('.cf-td-time', $(this)).html(now.format('HH:mm:ss'));
			}

			$('.cf-td-day', $(this)).html(now.format('dddd'));   			
			$('.cf-td-date', $(this)).html(now.format('DD MMMM YYYY'));   
		});
	
		setTimeout(updateTime, 1000);
	})();
	
	// Open links from RSS Module in a new window
	$('.cf-rss a').each(function(){
		$(this).prop('target', '=_blank');
	});
	
}); // end doc ready


/*
*
* Pie charts (cf-pie)
*
*/

function initPie(pie_values) {

$(document).ready(function(){

	// Default Pie chart options
	window.cf_DefaultPieOpts = {};
	cf_DefaultPieOpts.segmentShowStroke = false;
	
	// Initialise chart
	/*
	*	Copy the each() function for each line chart you have
	* 	e.g. $('#pie-1').each(function(){.....}
	*/
	$('.cf-pie').each(function(){
		
		array_res = pie_values.split(",");
		pie = array_res;			
		
		// Data for pie chart
var pdata = [
			{
				value : +pie[0],
				color : pieSegColors[1],
				label: '< 1 day'
			},
			{
				value : +pie[1],
				color : pieSegColors[2],
				label: '1 - 2'			
			},
			{
				value : +pie[2],
				color: pieSegColors[3],
				label: '2 - 3'			
			},
			{
				value : +pie[3],
				color: pieSegColors[8],
				label: '3 - 4'			
			},
			{
				value : +pie[4],
				color : pieSegColors[9],
				label: '4 - 5'			
			},
			{
				value : +pie[5],
				color: pieSegColors[6],
				label: '5 - 6'			
			},
			{
				value : +pie[6],
				color: pieSegColors[7],
				label: '6 - 7'			
			}
		]
	
		var $container = $(this);
		var pId = $container.prop('id');
		
		// Store chart information
		cf_rPs[pId] = {};
		cf_rPs[pId].data = pdata;
		
		/*
		// Set options per chart		
		customOptions = {};
		customOptions.animation = false;
		cf_rPs[pId].options = customOptions;
		*/
		
		// Create chart
		createPieChart($container);
	});
	
}); // end doc ready

}

function createPieChart(obj){
	$(window).resize(generatePieChart);

	function generatePieChart(){
		$container = obj;
		pId = $container.prop('id');
		var $canvas = $('canvas', $container);
		var cWidth = $container.width()*0.50;
		var cHeight = $container.height();		
		
		// Safari 5.1.10 .height() bug
		if(cHeight == 0){
			cHeight = cWidth - 28;
		}

		//Set canvas size
		$canvas.prop({width:cWidth,height:cHeight});
	
		// Get canvas context		
		var ctx = $canvas.get(0).getContext('2d');
		
		// Check for custom options
		var pieOptions;
		if(cf_rPs[pId].options){
			var pieOptions = $.extend({}, cf_DefaultPieOpts, cf_rPs[pId].options);
		}
		else{
			pieOptions = cf_DefaultPieOpts;
		}

		// Create chart		
		new Chart(ctx).Pie(cf_rPs[pId].data,pieOptions);
		createPieLegend(pId);
	}
	
	function createPieLegend(pId){
		// Check if we've already generated the legend
		if(cf_rPs[pId].legend){
			$('#'+pId).append(pieLegendHtml);			
		}
		else{
			// generate legend
			var pieLegendRow = '';
			var pieLegendHtml = '';
			
			for(i in cf_rPs[pId].data){
				pieLegendRow += '<li><div class="cf-pie-legend-color" style="background-color:'+cf_rPs[pId].data[i].color+'"></div>'+cf_rPs[pId].data[i].label+'</li>';
			}
			pieLegendHtml += '<div class="cf-pie-legend" style="margin-left:40px;"><ul>'+pieLegendRow+'</ul></div>';
			$('#'+pId).append(pieLegendHtml);
			cf_rPs[pId].legend = pieLegendHtml;
		}
	}
	
	// Call once on page load
	generatePieChart();
}

/*
*
* Line charts (cf-line)
*
*/
$(document).ready(function(){

	// Default line chart options
	window.cf_lineDefaultOpts = {};
	cf_lineDefaultOpts.datasetFill = false;
	cf_lineDefaultOpts.scaleMaxMinLabels = true;
	cf_lineDefaultOpts.scaleShowGridLines = false;
	cf_lineDefaultOpts.pointDot = false;
	cf_lineDefaultOpts.scaleLineColor = 'transparent';
	cf_lineDefaultOpts.bezierCurve = false;
	cf_lineDefaultOpts.scaleFontSize = 10;


	// Initialise chart
	/*
	*	Copy the each() function for each line chart you have
	* 	e.g. $('#line-1').each(function(){.....}
	*/	
	$('.cf-line').each(function(){
		// Dummy data for line chart
		var ldata = {
			labels : ["5/13","","","","","","11/13"],
			datasets : [
				{
					strokeColor : metric,
					data : [65,59,40,81,56,55,90]
				}
			]
		}
	
		var $container = $(this);
		var lId = $container.prop('id');
		
		// Store chart information
		cf_rLs[lId] = {};
		cf_rLs[lId].data = ldata;
		
		/*
		// Set options per chart
		customOptions = {};
		customOptions.scaleMaxMinLabels = false;
		cf_rLs[lId].options = customOptions;
		*/
		
		// Create chart
		createLineChart($container);
	});
	
}); // end doc ready

function createLineChart(obj){
	$(window).resize(generateLineChart);

	function generateLineChart(){
		$container = obj;
		lId = $container.prop('id');

		var $canvas = $('canvas', $container);
		var cWidth = $container.width();
		var cHeight = $container.height();		
		
		console.log(cWidth, cHeight);

		// Get canvas context		
		var ctx = $canvas.get(0).getContext('2d');

		//Set canvas size
		$canvas.prop({width:cWidth,height:cHeight});
		
		// Check for custom options
		var lineOptions;
		if(cf_rLs[lId].options){
			var lineOptions = $.extend({}, cf_lineDefaultOpts, cf_rLs[lId].options);
		}
		else{
			lineOptions = cf_lineDefaultOpts;
		}

		// Create chart		
		new Chart(ctx).Line(cf_rLs[lId].data,lineOptions);
	}
	
	// Call once on page load
	generateLineChart();
}


/*
*
* Sparklines (cf-svmc-sparkline)
*
*/

function initSpark(data1) {
	
$(document).ready(function(){
	
	// Set up default options	
	window.cf_defaultSparkOpts = {};
	cf_defaultSparkOpts.fillColor = false;
	cf_defaultSparkOpts.lineColor = metric;
	cf_defaultSparkOpts.lineWidth = 1.5;
	cf_defaultSparkOpts.minSpotColor = false;
	cf_defaultSparkOpts.maxSpotColor = false;
	cf_defaultSparkOpts.spotRadius = 2.5;
	cf_defaultSparkOpts.highlightLineColor = metric;
	cf_defaultSparkOpts.spotColor = '#f8f77d';
	
	// Initialise sparklines
	/*
	*	Copy the each() function for each sparkline you have
	* 	e.g. $('#spark-1').each(function(){.....}
	*/	
	$('#spark-1').each(function(){
		
		/*
		// Set custom options and merge with default
		customSparkOptions = {};
		customSparkOptions.minSpotColor = true;
		var sparkOptions = cf_defaultSparkOpts;
		var sparkOptions = $.extend({}, cf_defaultSparkOpts, customSparkOptions);
		*/
		
		// No custom options
		var sparkOptions = cf_defaultSparkOpts;
			
		//data = 	[2343,1765,2000,2453,2122,2333,2666,3000,2654,2322,2500,2700,2654,2456,2192,3894];
		array_mes = data1.split(",");
		data = array_mes;
		createSparkline($(this), data, sparkOptions);
		

	});		
	
});

}

function createSparkline(obj, data, sparkOptions){
	
	$(window).resize(generateSparkline);
	
	function generateSparkline(){
		var ww = $(window).width();
		var $obj = obj;			
		var $parent = $obj.parent().parent();
		
		//sum array data - total tickets		
		//var arr = ["20.0","40.1","80.2","400.3"],
    	sum = 0;
		$.each(data,function(){sum+=parseFloat(this) || 0;});
		
			
		// Current value
		$('.sparkline-value .metric-small', $parent).html(data[data.length-1]);
		
		//$('.total-month').html(data[data.length-1]);
		$('.total').html(sum);		
		$('.today-tickets').html(data[data.length-1]);

		//percent values
		var large;		
		
		if (data == 0) {
			large = 1;
		}
		else {
			large = (data[data.length-1]/data[data.length-2]);
		}
		
		if (large > 1) {
			
			large1 = (large - 1) * 100;
			$('.large').html(Math.round(large1*10)/10 + '%');
			$('.large').addClass('m-green');
			$('.change').addClass('m-green');
			$('#arrow').addClass('arrow-up');	
			
		}	
		
		if (large < 1) {
			
			large1 = (1 - large) * 100;
			$('.large').html(Math.round(large1*10)/10 + '%');
			$('.large').addClass('m-red');
			$('.change').addClass('m-red');
			$('#arrow').addClass('arrow-down');
		 
		}
			
		if (large == 1) {
			
			large2 = 0;
			$('.large').html(large2 + '%');						
			$('.large').addClass('m-white');
			$('.change').addClass('m-white');
						 
		}			
		
//		$('.small').html('.' + data[data.length-1] + '%');
	
		// Sizing
		if(ww < 768){
			var cWidth = $parent.width();
			var slWidth = Math.floor(cWidth/3);
		}
		else{
			var svWidth = $('.sparkline-value', $parent).width();
			var cWidth = $parent.width();
			var slWidth = cWidth - svWidth - 20;
			var cHeight = $parent.parent().outerHeight() - 35;
			var svmHeight = $('.cf-svmc', $parent).height();
			var slHeight = cHeight - svmHeight;
			$('.sparkline-value', $parent).css({height:slHeight});
		}	
	
		// Options
		sparkOptions.width = slWidth;
		sparkOptions.height = slHeight;		
	
		// Create sparkline
		$obj.sparkline(data, sparkOptions);
	}
	
		// Call once on page load
		generateSparkline();
}



//spark days
function initSparkDay(data1) {
	
$(document).ready(function(){
	
	// Set up default options	
	window.cf_defaultSparkOpts = {};
	cf_defaultSparkOpts.fillColor = false;
	cf_defaultSparkOpts.lineColor = metric;
	cf_defaultSparkOpts.lineWidth = 1.5;
	cf_defaultSparkOpts.minSpotColor = false;
	cf_defaultSparkOpts.maxSpotColor = false;
	cf_defaultSparkOpts.spotRadius = 2.5;
	cf_defaultSparkOpts.highlightLineColor = metric;
	cf_defaultSparkOpts.spotColor = '#f8f77d';
	
	// Initialise sparklines
	/*
	*	Copy the each() function for each sparkline you have
	* 	e.g. $('#spark-1').each(function(){.....}
	*/	
	$('#spark-2').each(function(){
		
		/*
		// Set custom options and merge with default
		customSparkOptions = {};
		customSparkOptions.minSpotColor = true;
		var sparkOptions = cf_defaultSparkOpts;
		var sparkOptions = $.extend({}, cf_defaultSparkOpts, customSparkOptions);
		*/
		
		// No custom options
		var sparkOptions = cf_defaultSparkOpts;
			
		//data = 	[2343,1765,2000,2453,2122,2333,2666,3000,2654,2322,2500,2700,2654,2456,2192,3894];
		array_mes = data1.split(",");
		data = array_mes;
		createSparkline2($(this), data, sparkOptions);
		
	});		
	
});

}

function createSparkline2(obj, data, sparkOptions){
	
	$(window).resize(generateSparkline);
	
	function generateSparkline(){
		var ww = $(window).width();
		var $obj = obj;			
		var $parent = $obj.parent().parent();
		
		//sum array data - total tickets		
		//var arr = ["20.0","40.1","80.2","400.3"],
    	sum = 0;
		$.each(data,function(){sum+=parseFloat(this) || 0;});
					
		// Current value
		//$('.sparkline-value .metric-small', $parent).html(data[data.length-1]);
		
		//$('.total-month').html(data[data.length-1]);
		//$('.total').html(sum);		
		//$('.today-tickets').html(data[data.length-1]);

		//percent values

		var large;		
		
		if (data == 0) {
			large = 1;
		}
		else {
			large = (data[data.length-1]/data[data.length-2]);
		}
		
//large = 1; ((V2-V1)/V1 × 100)		
		
		if (large > 1) {
			
			large1 = (large - 1) * 100;
			$('.large-2').html(Math.round(large1*10)/10 + '%');
			$('.large-2').addClass('m-green');
			$('.daily').addClass('m-green');
			$('#arrow-2').addClass('arrow-up');				
		}	
		
		if (large < 1) {
			
			large1 = (1 - large) * 100;
			$('.large-2').html(Math.round(large1*10)/10 + '%');

			$('.large-2').removeClass('m-green');
			$('.daily').removeClass('m-green');
			$('#arrow-2').removeClass('arrow-up');
				
			$('.large-2').addClass('m-red');
			$('.daily').addClass('m-red');
			$('#arrow-2').addClass('arrow-down');		 
		}
			
		if (large == 1) {
			
			large2 = 0;
			$('.large-2').html(large2 + '%');						
			$('.large-2').addClass('m-white');
			$('.change-2').addClass('m-white');
			//$('#arrow-2').addClass('arrow-up');			 
		}	
		
//		$('.small').html('.' + data[data.length-1] + '%');
	
		// Sizing
		if(ww < 768){
			var cWidth = $parent.width();
			var slWidth = Math.floor(cWidth/3);
		}
		else{
			var svWidth = $('.sparkline-value', $parent).width();
			var cWidth = $parent.width();
			var slWidth = cWidth - svWidth - 20;
			var cHeight = $parent.parent().outerHeight() - 35;
			var svmHeight = $('.cf-svmc', $parent).height();
			var slHeight = cHeight - svmHeight;
			$('.sparkline-value', $parent).css({height:slHeight});
		}	
	
		// Options
		sparkOptions.width = slWidth;
		sparkOptions.height = slHeight;		
	
		// Create sparkline
		$obj.sparkline(data, sparkOptions);
	}
	
		// Call once on page load
		generateSparkline();
}




/*
*
*	Gauge (cf-gauge)
*
*/

function initGauge(minVal1,maxVal1,newVal1) {
	
$(document).ready(function(){
	//Initialise gauges to default 
	$('.cf-gauge').each(function(){

		// Gather IDs 
		var gId = $(this).prop('id');					// Gauge container id e.g. cf-gauge-1
		var gcId = $('canvas', $(this)).prop('id');		// Gauge canvas id e.g. cf-gauge-1-g
		var gmId = $('.metric', $(this)).prop('id');  	// Gauge metric id e.g. cf-gauge-1-m

		//Create a canvas
		var ratio = 2.1;
		var width = $('.canvas',$(this)).width();
		var height = Math.round(width/ratio);
		$('canvas', $(this)).prop('width', width).prop('height', height);

		// Set options  	
		rGopts = {};
		rGopts.lineWidth = 0.30;
		rGopts.strokeColor = gaugeTrackColor;
		rGopts.limitMax = true;
		rGopts.colorStart = gaugeBarColor;
		rGopts.percentColors = void 0;	
		rGopts.pointer = {
			length: 0.7,
			strokeWidth: 0.035,
			color: gaugePointerColor
		};

		// Create gauge
		cf_rGs[gId] = new Gauge(document.getElementById(gcId)).setOptions(rGopts);
		cf_rGs[gId].setTextField(document.getElementById(gmId));

		// Set up values for gauge (in reality it'll likely be done one by one calling the function, not from here)

		updateOpts = {'minVal':minVal1,'maxVal':maxVal1,'newVal':newVal1};
		gaugeUpdate(gId, updateOpts);


		// Responsiveness	
		$(window).resize(function(){
		
			//Get canvas measurements
			var ratio = 1.8;
			var width = $('.canvas', $('#'+gId)).width();
			var height = Math.round(width/ratio);

			cf_rGs[gId].ctx.clearRect(0, 0, width, height);
			$('canvas', $('#'+gId)).width(width).height(height);
			cf_rGs[gId].render();
		});

	});
});

}

/*
*	Set or update a Gauge
*	@param gauge 	string 		ID of gauge container
*	@param opts 	object		JSON object of options
*/
function gaugeUpdate(gauge, opts){
	if(opts.minVal){
		$('.val-min .metric-small', $('#'+gauge)).html(opts.minVal);		
		cf_rGs[gauge].minValue = opts.minVal;
	}
	if(opts.maxVal){
		cf_rGs[gauge].maxValue = opts.maxVal;
		$('.val-max .metric-small', $('#'+gauge)).html(opts.maxVal);
	}
	if(opts.newVal){
		cf_rGs[gauge].set(parseInt(opts.newVal));
	}
}


/*
*
* R.A.G
*
*/

function initRag(data1,labels) {

$(document).ready(function(){
	/*
	*	Copy the each() function for each R.A.G chart you have
	* 	e.g. $('#cf-rag-1').each(function(){.....}
	*/								
	$('.cf-rag').each(function(){
		// Dummy data for RAG

		array_data = data1.split(",");						
		data = array_data;		
		
		array_label = labels.split(",");				
		dataLabels = array_label;
		
		//ragData = [40,50,10];
		ragData = [+array_data[0],+array_data[1],+array_data[2]];
		ragLabels = [array_label[0],array_label[1],array_label[2]];
		ragOpts = {postfix:''}

		cf_rRags[$(this).prop('id')] = new RagChart($(this).prop('id'), ragData, ragLabels, ragOpts);
	});
}); // end doc ready

}

/*
*
* Funnel charts
*
*/

function initFunnel(funData1,funLabels1) {
	
$(document).ready(function(){
	/*
	*	Copy the each() function for each Funnel chart you have
	* 	e.g. $('#cf-funnel-1').each(function(){.....}
	*/								

	$('.cf-funnel').each(function(){
	
		// Dummy data for Funnel chart
		//funData = [3000,1500,500,250,10];
		
		array_data = funData1.split(",");
		array_labels = funLabels1.split(",");
		
		funData = array_data;
		funLabels = array_labels;

		//funLabels = ['Visits','Cart','Checkout','Purchase','Refund'];
		funOptions = {barOpacity:true };
		
		cf_rFunnels[$(this).prop('id')] = new FunnelChart($(this).prop('id'), funData, funLabels, funOptions);
	});
	
}); // end doc ready

}


/*
*
* Single Value Pie Charts (cf-svp)
*
*/


//Donut chart
$(document).ready(function(){

$('.chart').easyPieChart({
    easing: 'easeOutCirc',
    barColor: bbarColor,
    trackColor: btrackColor,
    scaleColor:false,
    scaleLength: 5,
    percent: 89,
    lineCap: 'square',
    lineWidth: 15, //12
    size: 200,
    onStep: function(from, to, percent) {
$(this.el).find('.percent').text(Math.round(percent));
}
});

});



function initSingle(data1) {

$(document).ready(function(){

var sat = Math.round(data1*100)/100;
var perSat = (sat*100)/5;

//alert(perSat);

data = perSat;

	// Initialise single value pie charts
	$('.cf-svp').each(function(){
		cf_rSVPs[$(this).prop('id')] = {};
		rSVP($(this));
	});
	
	// Update a single value pie chart
	// -- Example of how to update a chart, can be used in other cases than from a button click
	$('.svp-update').click( function(){
		var element = $(this).data('update');
		
		// Call EasyPieChart update function
		cf_rSVPs[element].chart.update(12);
		
		// Update the data-percent so it redraws on resize properly
		$('#svp-1 .chart').data('percent', 12);
		// Update the UI metric
		$('.metric', $('#'+element)).html('12');
	});
});

}

/*
*	Create single value pie charts
*/
function rSVP(element, options){
	// Call the chart generation on window resize
	$(window).resize(generateChart);
	
	var container = $(element);
	var chart = '#'+$(element).attr('id')+' .chart';
	

	// Create the chart
	function generateChart(){
		
		// Resize when width is 768 or greater 
		// Remove any existing canvas                
		if($('canvas', $(container)).length){
			$.when($('canvas', $(container)).remove()).then(addChart());
		}
		else{
			addChart();
		}
		
		function addChart(){
			//Setup options
			var rsvpOpt = {
				barColor: pieBar,
				trackColor: pieTrack,
				scaleColor: false,
				lineWidth: 15,			
				lineCap: 'butt',
				size: 80
			};
			
			//Alter settings depending on layout and screen width
			var ww = $(window).width();
			
			if(ww > 765 && ww < 992){
				rsvpOpt.size = container.width()-10;
										
				switch($(chart).data('layout')){
					case 'l-6':
						rsvpOpt.lineWidth = 30;
						break;
					
					case 'l-6-i':
						rsvpOpt.lineWidth = 20;
						rsvpOpt.size = parseFloat((container.width()*0.7)-10);
						break;					
					
					case 'l-6-12-6':
						break;
					
					case 'l-6-4':
						rsvpOpt.lineWidth = 5;	
						break;
				}
			}
			else if(ww > 991 && ww < 1200 ){
				rsvpOpt.size = container.width()-10;
										
				switch($(chart).data('layout')){
					case 'l-6':
						rsvpOpt.lineWidth = 30;
						break;
					
					case 'l-6-i':
						rsvpOpt.lineWidth = 30;
						rsvpOpt.size = parseFloat((container.width()*0.75)-10);
						break;					
					
					case 'l-6-12-6':
						rsvpOpt.lineWidth = 20;
						break;
					
					case 'l-6-4':
						rsvpOpt.lineWidth = 5;	
						break;
				}
			}
			else if(ww > 1199 && ww < 1399){
				rsvpOpt.size = container.width()-10;
										
				switch($(chart).data('layout')){
					case 'l-6':
						rsvpOpt.lineWidth = 40;
						break;
					
					case 'l-6-i':
						rsvpOpt.lineWidth = 30;
						rsvpOpt.size = parseFloat((container.width()*0.75)-10);
						break;					
					
					case 'l-6-12-6':
						rsvpOpt.lineWidth = 20;					
						break;
					
					case 'l-6-4':
						rsvpOpt.lineWidth = 10;	
						break;
				}
			}
			else if(ww > 1399){
				rsvpOpt.size = container.width()-10;
										
				switch($(chart).data('layout')){
					case 'l-6':
						rsvpOpt.lineWidth = 50;
						break;
					
					case 'l-6-i':
						rsvpOpt.lineWidth = 40;
						rsvpOpt.size = parseFloat((container.width()*0.75)-10);
						break;					
					
					case 'l-6-12-6':
						rsvpOpt.lineWidth = 30;
						break;
					
					case 'l-6-4':
						rsvpOpt.lineWidth = 15;	
						break;
				}
			}
			// Create and store the chart
			cf_rSVPs[$(element).attr('id')].chart = new EasyPieChart(document.querySelector(chart), rsvpOpt);
		}
	};

	// Run once on first load
	generateChart();
}

/*
*	Size modules 
*/
function cfSizeItems(){
	var width = $(window).height();

$('.cf-item').each(function(){
		if(width > 750 ){
			//$(this).height($(this).width());
			//$(this).height(430);
			$(this).height((width-130)/2.5);
			//$('.donut').css('margin-bottom','20% !important');		
		}
		else{
			//$(this).height('auto');
			$(this).height((width-60)/2.5);	
		}
	});
	
	$('.row').each(function(){
		if(width > 750 ){
			//$(this).height($(this).width());
			//$(this).height(400);
			$(this).height((width-100)/2.5);
		}
		else{
			//$(this).height('auto');
			$(this).height((width-70)/2.5);
		}
	});
	
	$('.cf-item-status').each(function(){
		if(width > 750 ){
			//$(this).height($(this).width());
			//$(this).height(430);
			$(this).height((width-250)/4);
			//$('.donut').css('margin-bottom','20% !important');		
		}
		else{
			//$(this).height('auto');
			$(this).height((width-350)/3);	
		}
	});
	
	$('.row-status').each(function(){
		if(width > 750 ){
			//$(this).height($(this).width());
			//$(this).height(400);
			$(this).height((width-250)/4);
		}
		else{
			//$(this).height('auto');
			$(this).height((width-350)/3);
		}
	});
}
// Call the resize function on window resize
$(window).resize(function(){
	cfSizeItems();
});

/*
*	Shorten large numbers
*/
function prettyNumber (number) {
    var prettyNumberSuffixes = ["", "K", "M", "bn", "tr"];
	var addCommas = function (nStr){
		var x = '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x)) {
			x = x.replace(rgx, '$1' + ',' + '$2');
		}
		return x;
	}
	var prettyNumber_rec = function (number, i) {
		if (i == prettyNumberSuffixes.length) {
			return addCommas(Math.round(number*1000)) + prettyNumberSuffixes[i-1];
		}
		if (number / 1000 >= 1) { // 1000+
			return prettyNumber_rec(number / 1000, ++i);
		}
		else {
			var decimals = number - Math.floor(number);
			if (decimals != 0) {
				if (number >= 10) { // 10 - 100
					number = Math.floor(number) + Math.round(decimals*10) / 10 + '';
					number = number.replace(/(.*\..).*$/, '$1');
				}
				else { // 0 - 10
					number = Math.floor(number) + Math.round(decimals*100) / 100 + '';
					number = number.replace(/(.*\...).*$/, '$1');
				}
				return number + prettyNumberSuffixes[i];
			}
			else {
				return Math.floor(number) + prettyNumberSuffixes[i];
			}
		}
	}
	return prettyNumber_rec(number, 0);
}


