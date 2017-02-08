	$(function(){   
		var nav = $('#menuHeader');   
		$(window).scroll(function () { 
			if ($(this).scrollTop() > 50) { 
				nav.addClass("menu-fixo");				 
			} else { 
				nav.removeClass("menu-fixo"); 				
			} 
		});  
	});
	
	$(function(){   
		var collapse = $('#navbar-collapse-1');   
		$(window).scroll(function () { 
			if ($(this).scrollTop() > 50 && $(this).width() < 600) { 		
				collapse.addClass("black-bg");				 
			} else { 				
				collapse.removeClass("black-bg");				
			} 
		});  
	});
	
	$(function(){   
		var collapse = $('#navbar-collapse-1');   
		//$(window).scroll.(function () {
		$('#bnt-collapse').click(function() { 
			if ($(this).scrollTop() == 0) { 		
				collapse.addClass("top-50");				 
			} else { 				
				collapse.removeClass("top-50");				
			} 
		});  
	});	

$(document).on('click', '.yamm .dropdown-menu', function(e) {
  //e.stopPropagation()
})
	
/*
//menu hover
$(document).ready(function(){	

//$('#menu').addClass('dropdown'); 	 

$('.menu').hover(function(){ 
  $('.dropdown-toggle', this).trigger('click'); 
});

});	
*/
	