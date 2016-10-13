$(document).ready(function(){

	// //Theme options , this is only for demo 
	// $('#colr').colorpicker().on('changeColor', function(ev) {
	// 	var vj = ev.color.toHex();
	// 	$('#primaryColor').val(vj);
	// 	$(this).find('i').css('background-color',ev.color.toHex());
	// 	applyLess();
	// 	less.modifyVars({
	// 		'@primary':ev.color.toHex(),
	// 		'@leftSidebarBackground':$('#secondaryColor').val(),
	// 		'@leftSidebarLinkColor':$('#linkColor').val(),
	// 		'@rightSidebarBackground':$('#rightsidebarColor').val()

	// 	});

	// });

	// $('#Scolr').colorpicker().on('changeColor', function(ev) {
	// 	var vj = ev.color.toHex();
	// 	$('#secondaryColor').val(vj);
	// 	$(this).find('i').css('background-color',ev.color.toHex());
	// 	applyLess();
	// 	less.modifyVars({
	// 		'@primary':$('#primaryColor').val(),
	// 		'@leftSidebarBackground':ev.color.toHex(),
	// 		'@leftSidebarLinkColor':$('#linkColor').val(),
	// 		'@rightSidebarBackground':$('#rightsidebarColor').val()

	// 	});

	// });

	// $('#Rcolr').colorpicker().on('changeColor', function(ev) {
	// 	var vj = ev.color.toHex();
	// 	$('#rightsidebarColor').val(vj);
	// 	$(this).find('i').css('background-color',ev.color.toHex());
	// 	applyLess();
	// 	less.modifyVars({
	// 		'@primary':$('#primaryColor').val(),
	// 		'@leftSidebarBackground':$('#secondaryColor').val(),
	// 		'@leftSidebarLinkColor':$('#linkColor').val(),
	// 		'@rightSidebarBackground':ev.color.toHex()

	// 	});

	// });

	// $('#Lcolr').colorpicker().on('changeColor', function(ev) {
	// 	var vj = ev.color.toHex();
	// 	$('#linkColor').val(vj);
	// 	$(this).find('i').css('background-color',ev.color.toHex());
	// 	applyLess();
	// 	less.modifyVars({

	// 		'@primary':$('#primaryColor').val(),
	// 		'@leftSidebarBackground':$('#secondaryColor').val(),
	// 		'@leftSidebarLinkColor':ev.color.toHex(),
	// 		'@rightSidebarBackground':$('#rightsidebarColor').val()

	// 	});

	// });

	$('#fixedTopSide').click(function(){
		$('.site-holder').toggleClass('top-side-fixed');
		$('.navbar').toggleClass('navbar-fixed-top');
        });

	$('#miniSidebar').click(function(){
		$('.site-holder').toggleClass('mini-sidebar');
	
	        });

	
	$('.theme-panel-close').click(function(){
		$(this).parent().fadeOut();
		$('.theme-options').fadeOut();
	});



	$('.predefined-themes li a').click(function(){
		var primaryCol=$(this).data('color-primary');
		var secondaryCol=$(this).data('color-secondary');
		var linkCol=$(this).data('color-link');

		applyLess();
		less.modifyVars({

			'@primary':primaryCol,
			'@leftSidebarBackground':secondaryCol,
			'@leftSidebarLinkColor':linkCol

		});
	});



});

function applyLess() 
{	
	if($('#lessCss').attr("rel")=="stylesheet")
	{
		$('#lessCss').attr("rel", "stylesheet/less");
		less.sheets.push($('link[title=lessCss]')[0]);
		less.refresh();

	}

}