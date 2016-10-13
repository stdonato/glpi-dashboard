$(document).ready(function() {
    // keep widgets ordered
    keepWidgetOrdered();

    // hide localstored hidden widgets
    keepWidgetHidden();
});

/**
 * Adds jQuery UI sortable portlet functionality to widgets
 *
 *
 */

$( "#widgets" ).sortable({
      handle: ".widget-header",
      cancel: "#filter-ps",
      cursor: "move",
      opacity: 0.7,
      scrollSensitivity:10,
      tolerance: 'pointer',
      stop: function(event, ui) {
            // save widget order in localStorage
            var newOrder = new Array();
            $('.widget').each(function() {
                newOrder.push($(this).attr("id"));
            });
            localStorage.setItem('positions', JSON.stringify(newOrder));
        }
 });


//localStorage.setItem('hidden', 0);

/**
 *
 * Widget hide functionality
 *
**/

// general cached DOM objects
var closedWidgetCount = $('#closed-widget-count');
var closedWidgets = $('#closed-widget-list');
var allWidgets = $('.widget');

// Close all widgets
$('#close-all-widgets').click(function(){
    allWidgets.each(function(index){
        if ($(this).is(":visible")){
	       hideWidget($(this), 400);
        }
    });
});

// Open all widgets
$('#open-all-widgets').click(function(){
    allWidgets.each(function(index){
	   openWidget($(this), $(this).attr('id'), 500);
	   closedWidgets.empty();
    });
    localStorage.removeItem('hidden');	
});

// attach a close button to all widget headers
//$('.widget-header').append('<div class="btn btn-icon-only icon-remove hide-widget"></div>');
//$('.widget-header').append('<div class="glyphicon glyphicon-remove-circle hide-widget"></div>');   
//$('.widget1').removeClass("hide-widget");

// hide / close widget function
$('.hide-widget').on('click',function(){
    var widget = $(this).parent().parent();
    hideWidget(widget, 300);
});

// unhide closed widget
$(document).on('click','#open',function(){
		
    // cache DOM objects/data used in this function
    var widgetIdentifier = $(this).data('id');
    var widget =  $("#" + widgetIdentifier);
    var navItem = $(this).parent();

    openWidget(widget,widgetIdentifier,500);

    // remove item from closed-widget-list
    navItem.remove();

});


function openWidget(widget, widgetIdentifier, speed){

    // decrement closed-widget-count 
    if(widget.is(":hidden")) {
        closedWidgetCount.text( Number(closedWidgetCount.text()) - 1);
    }

    // unhide widget
    widget.show(500);

     // remove widget from localStorage
    var localData = JSON.parse(window.localStorage.getItem('hidden'));
    for(var i = localData.length; i--;){
        if (localData[i] == widgetIdentifier) {
            localData.splice(i, 1);
        }
    }
    localStorage.setItem('hidden', JSON.stringify(localData));
}


function hideWidget(widget, speed){
    // cache DOM objects/data used in this function
    var widgetName = widget.find('.widget-header h3').text();
    var widgetIdentifier = widget.attr('id'); 

    // update count
    if(!widget.is(":hidden")) {
        closedWidgetCount.text( Number(closedWidgetCount.text()) + 1);
    }

    // hide widget from DOM
    widget.hide(speed);

    // add to hidden list
    closedWidgets.append('<li><a href="#" id="open" class="open-widget" style="color:#000;" data-id="'+widgetIdentifier+'"><i class="fa fa-plus"></i> '+widgetName+'</a></li>');

    // add widget to localStorage (and create item if needed)
    var localData = JSON.parse(window.localStorage.getItem('hidden'));
    if(localData == null) {
        hidden = new Array();
        hidden.push(widgetIdentifier);
        localStorage.setItem('hidden', JSON.stringify(hidden));
    }
    else{
        if (!isInArray(localData, widgetIdentifier)) {
            localData.push(widgetIdentifier);
            localStorage.setItem('hidden', JSON.stringify(localData));
        }
    }
}

function keepWidgetHidden(){
    var localData = JSON.parse(window.localStorage.getItem('hidden'));
    if(localData!=null) {
        $.each(localData, function(i,value){
             hideWidget( $("#" + value), 0 );
        });
    }
}

function keepWidgetOrdered(){
    var localData = JSON.parse(window.localStorage.getItem('positions'));
    if(localData!=null) {
        $.each(localData, function(i,value){
            var widgetId ="#" + value;
            $("#widgets").append($(widgetId).parent());
        });
    }
}

function isInArray(array, search)
{
    return (array.indexOf(search) >= 0) ? true : false; 
}

		   
	//$(window).scroll.(function () {
	$('#theme-setting').click(function() { 
	
		var setting = $('#theme-setting');
		var setting2 = $('#theme-setting2');
		
		if (setting.hasClass("show-setting")) {
			 				
			setting.addClass("hide-setting");
			setting.removeClass("show-setting");
			
			setting2.addClass("show-setting");
			setting2.removeClass("hide-setting");								 
		} 
		
		//else { 				
		//	setting.removeClass("top-50");				
		//} 
	});
 	  

   
	//var setting = $('#close-setting');   
	//$(window).scroll.(function () {
	$('#close-setting').click(function() {
		
		var setting = $('#theme-setting');
		var setting2 = $('#theme-setting2'); 
	
		if (setting2.hasClass("show-setting")) { 
										
			setting2.addClass("hide-setting");
			setting2.removeClass("show-setting");	
			
			setting.addClass("show-setting");	
			setting.removeClass("hide-setting");			 
		} 
		
		//else { 				
		//	setting.removeClass("top-50");				
		//} 
	}); 
 	
    
    
    
    
