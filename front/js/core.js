$(document).ready(function () {

      //set false for non-ajax version
      ajax_version=true;

	$.ajaxSetup({
		cache: false
	});
 
    // After page load, basic jq functions
    $('.options-toggle').on('click', function (e) {
    	e.preventDefault();
    	$('.options-holder').toggleClass('closed');
        //$('.options-holder .col-sm-4').slideToggle('hidden');
      });

$('.close-right-user').click(function(){
  $('.user-details').addClass('user-details-close');
});
    $('.user-menu').on('click', function (e) {
    	e.preventDefault();
    	$('.user-details').toggleClass('user-details-close');
    	//$('.content').toggleClass('user-details-open');
    });



//user-canvas
        $('.user-canvas').easyPieChart({
         animate: 3000,
         lineWidth : 8,
         size : 110,
         barColor: '#a0d269',
         onStep: function(value) {
          this.$el.find('span').text(~~value);
        }
     });

        $('.user-canvas-two').easyPieChart({
         animate: 3000,
         lineWidth : 8,
         size : 110,
         barColor: '#df6c6e'
     });


    // Sidebar Toggle
    $('.toggle-left-sidebar').on('click', function (e) {
    	e.preventDefault();
    	$('.left-sidebar').toggleClass('left-sidebar-open');
    	
    })

    $('.minibar-small').click(function(){
      $('.site-holder').toggleClass('mini-sidebar')
    });
    $('ul.nav-list').accordion();

    $(".refresh-storage").click(function(e){
	e.preventDefault();

      var msg;

msg = Messenger().post({
  message: 'Are you sure to clear changes?',
  type: 'info',
  actions: {
    success: {
      label: 'Hell yes!!',
      action: function() {
      $(this).find('.fa-refresh').addClass('fa-spin');
		window.localStorage.clear();
	$(this).find('.fa-refresh').removeClass('fa-spin');
        return msg.update({
          message: 'Successfully Cleared',
          type: 'success',
          actions: false
        });
      }
    },
    cancel: {
      label: 'Not really ',
      action: function() {
        return msg.update({
          message: 'Storage remains unclear',
          type: 'error',
          actions: false
        });
      }
    }
  }
});

	});
    init();
    var container = $('.main-content');
    var type = window.location.hash.substr(1);
    localStorage.setItem('working', type ) ;


if(ajax_version)
{

      setTimeout(function () {
            loadPage(type, container)
                $('ul.nav-list li:has(a[href="' + type + '"])').addClass('active').closest('.submenu').addClass('current').find('ul').css('display','block');
        },
      2000);

$('.options-holder .fa-links').on('click',function(e){
          e.preventDefault();
               var url = $(this).attr('href');
        var tab = $(this).attr('target');
        if (tab == "_blank") {
          window.open(url,'_blank');
        } else if (url != "#") {
          var container = $('.main-content');
          loadPage(url, container);
        }
  

});
      $('ul.nav-list li a').on('click', function (e) {
        e.preventDefault();
        //$('.main-content').load('index.html');
        var url = $(this).attr('href');
        var tab = $(this).attr('target');
        if (tab == "_blank") {
          window.open(url,'_blank');
        } else if (url != "#") {
          var container = $('.main-content');
          $('ul.nav-list li ').removeClass('active');
          $(this).parent().addClass('active');
          $('.user-details').addClass('user-details-close');
          loadPage(url, container);
              
          if ($('.left-sidebar').hasClass('left-sidebar-open')) 
          {
          $('.left-sidebar').toggleClass('left-sidebar-open');
        }
        if ($('.site-holder').hasClass('mini-sidebar')) 
        {
          $('ul.nav-list li ul ').css('display','none');
      }
        }
        //$('.options-holder .col-sm-4').slideToggle('hidden');
      });


}

//input search function
$('.nav-input-search').typeahead({
     source: [
        { id: 'dashboard', name: 'dashboard' },
        { id: 'dashboard', name: 'Chat' },
        { id: 'gallery', name: 'Gallery' },
        { id: 'gallery', name: 'Images' },
        { id: 'dashboard', name: 'Calendar' },
        { id: 'portlets', name: 'Portlets' },
        { id: 'delighted-layouts', name: 'Delighted Layouts' },
        { id: 'grids', name: 'Grids' },        
        { id: 'template', name: 'Template Page' },
        { id: 'template', name: 'Basic Template' },
        { id: 'inbox', name: 'Inbox' },
        { id: 'invoice', name: 'Invoice' },       
        { id: 'typography', name: 'Typography' },
        { id: 'tasks', name: 'Taks' },
        { id: 'alerts', name: 'Alerts' },
        { id: 'profile', name: 'Profile' },
        { id: 'delighted-wiki', name: 'Delighted Wiki' },
        { id: 'buttons', name: 'Buttons' },
        { id: 'panels', name: 'Panels' },
        { id: 'jquery-ui', name: 'Sliders' },
        { id: 'maps', name: 'Maps' },
        { id: 'timeline', name: 'timeline' },
        { id: 'flot-charts', name: 'Flot Charts' },
        { id: 'morris-charts', name: 'Morris Charts' },
        { id: 'inline-charts', name: 'Inline Charts' },
        { id: 'nvd3charts', name: 'NVD3 Charts' },
        { id: 'chartjs', name: 'Chartjs' },
        { id: 'date-paginator', name: 'Date Paginator' },
        { id: 'portlets', name: 'Draggable Portlets' },
        { id: 'jq-gridplugin', name: 'Jq grid Plugin' },
        { id: 'simple-tables', name: 'Simple tables' },
        { id: 'dynamic-tables', name: 'Dynamic tables' },
        { id: 'dropzone-file-upload', name: 'Dropzone file upload' },
        { id: 'form-input-masks', name: 'Form input masks' },
        { id: 'font-awesome', name: 'Font Awesome' },
        { id: 'input-groups', name: 'Input groups' },
        { id: 'ionicons', name: 'Ion Icons' },
        { id: 'inbox', name: 'Mail' },
        { id: 'jquery-ui', name: 'Color picker' },
        { id: 'social', name: 'Social' },
        { id: 'filetypes', name: 'File Types' },
        { id: 'glyphicons', name: 'Glyphicons' },
        { id: 'editor', name: 'Wysiwyg Editor' },
        { id: 'grid', name: 'Grid' },
        { id: 'file-manager', name: 'File Manager' },
        { id: 'image-crop', name: 'Image Crop' },
        { id: 'dropzone', name: 'Dropzone' },
        { id: 'bootstrap-editors', name: 'Bootstrap Editors' },
        { id: 'wizards', name: 'wizards' },
        { id: 'form-elements', name: 'Form Validations' },
        { id: 'form-layouts', name: 'Form Layouts' },
        { id: 'form-elements', name: 'Form Elements' },
        { id: 'dashboard', name: 'Docs' },
        { id: 'dashboard', name: 'Unlimited Menu' },
        { id: 'general-elements', name: 'General Elements' },
        { id: 'tree-view', name: 'Tree View' },
        { id: 'jquery-ui', name: 'Jquery Ui' },
        { id: 'nestable-lists', name: 'Nestabl Lists' },
        { id: 'extended-zoom', name: 'Extended Zoom' },
        { id: 'dropzone', name: 'Drop Zone' },
        { id: 'editor', name: 'Wysiwyg' },
        { id: 'editor', name: 'Bootstrap Editor' },

      ],
        itemSelected: displayResult
  });


   });
    function init() {
	// PANELS
	  // panel close
        $('.panel-close').on('click', function (e) {
        	e.preventDefault();
        	$(this).parent().parent().parent().parent().addClass(' animated fadeOutDown');
        });

        //Todo List
        $('.finish').click(function(){
          $(this).parent().toggleClass('finished');
          $(this).toggleClass('fa-square-o');
        });

  
        //$('.progress .progress-bar').progressbar(); 
        $('.fa-hover').click(function(e){
          e.preventDefault();
          var valued= $(this).find('i').attr('class');
          $('.modal-title').html(valued);
          $('.icon-show').html('<i class="' + valued + ' fa-5x "></i>&nbsp;&nbsp;<i class="' + valued + ' fa-4x "></i>&nbsp;&nbsp;<i class="' + valued + ' fa-3x "></i>&nbsp;&nbsp;<i class="' + valued + ' fa-2x "></i>&nbsp;&nbsp;<i class="' + valued + ' "></i>&nbsp;&nbsp;');
          $('.modal-footer span.icon-code').html('"' + valued + '"');
          $('#myModal').modal('show');
        });

	$('.panel-settings').click(function(e){
		e.preventDefault();
		var columnId=$(this).closest(".panel").parent().attr("id");
		var title=$(this).closest(".panel-title").text();
		//var valued= $(this).find('i').attr('class');
		//$('.modal-title').html(valued);
		//var vj=$(this).parent().parent().parent().parent().parent().getElementById();
		$('.modal-title').html(title);
		$('#customiseWidget #current-div').val(columnId);
		$('#customiseWidget #column-size').val($('#'+columnId).attr('class'));
		//$('.modal-footer span.icon-code').html('"' + valued + '"');
		$('#customiseWidget').modal('show');
	});

	$('#customiseWidget #submit').on('click',function(e)
      {
    		
    		e.preventDefault();
    		var color=$('#customiseWidget #color').val();
    		var title=$('#customiseWidget #title').val();
    		var targetDiv='#'+$('#customiseWidget #current-div').val();
    		var columnSize=$('#customiseWidget #column-size').val();
	    	$(targetDiv).find('.panel').attr('class','panel '+color);
	    	$(targetDiv).attr('class',columnSize);
		$('#customiseWidget').modal('hide');
	});

        $('.panel-minimize').on('click', function (e) 
        {
        	e.preventDefault();
        	var $target = $(this).parent().parent().parent().next('.panel-body');
        	if ($target.is(':visible')) {
        		$('i', $(this)).removeClass('fa-chevron-up').addClass('fa-chevron-down');
        	} else {
        		$('i', $(this)).removeClass('fa-chevron-down').addClass('fa-chevron-up');
        	}
        	$target.slideToggle();
        });
        
        
        $('.panel-refresh').on('click', function (e) 
        {
        	e.preventDefault();
        	var $target = $(this).parent().parent().parent().next('.panel-body');
        	$target.mask('<i class="fa fa-refresh fa-spin"></i> Loading...');

        	setTimeout(function () {
        		$target.unmask();

        	},
        	1000);
        });
      }

     function loadPage(url, container) {
     /* urlExt = url + '.html';
        //console.log(container)
        $.ajax({
        	type: "GET",
        	url: urlExt,
        	dataType: 'html',
        	cache: false,
        	success: function () {
        		container.mask('<h1><i class="fa  fa-refresh fa-spin"></i> Loading...</h1>');
        		container.load(urlExt, null, function (responseText) {
                  window.location.hash =url;
                  $('.breadcrumb .active').html(url);

    			init();
    			sortablePortlets();
        		}).fadeIn('slow');
        		//console.log("ajax request successful");
        	},
        	error: function (xhr, ajaxOptions, thrownError) {
        		//container.html('<h4 style="margin-top:10px; display:block; text-align:left"><i class="fa fa-warning txt-color-orangeDark"></i> Error 404! Page not found.</h4>');
                        container.load('404.html') ;
                        setTimeout(function () {
                        loadPage('dashboard', container)
                                  $('ul.nav-list li ').removeClass('active');
                                $('ul.nav-list li:has(a[href="dashboard"])').addClass('active').closest('.submenu').addClass('current').find('ul').css('display','block');
                          },
                    3000);
              }, 
              async: false
            });
            */
     }

function randNum() {
	return ((Math.floor(Math.random() * (1 + 40 - 20))) + 20) * 1200;
}

function sortablePortlets()
{
	$(".grid").sortable({
		//tolerance: 'pointer',
		revert: 'invalid',
		cursor: 'move',
		placeholder: ' col-md-2 well placeholder tile',
            handle: ".panel-heading",
            forceHelperSize: true,
            start: function(e, ui){
            	ui.placeholder.height(ui.helper.height())-15;
            	ui.placeholder.width(ui.helper.width()-15);
            },
            stop: function(evt, ui){
            	//console.log($(".grid").sortable('toArray', { attribute: 'data-name' }));
            }
          });

	$( ".grid" ).on("sortupdate",function( event, ui ) {
		var sorted = $( this ).sortable( "serialize");
		//console.log(sorted);
		localStorage.setItem('sorted', sorted) ;

	});
	if(localStorage.getItem("sorted") !== null){
		var arrValuesForOrder = localStorage.getItem('sorted').substring(6).split("&div[]="); 

		var $ul = $(".grid");
		$items = $(".grid").children();

// loop backwards so you can just prepend elements in the list
// instead of trying to place them at a specific position
for (var i = arrValuesForOrder[arrValuesForOrder.length - 1]; i >= 0; i--) {
    // index is zero-based to you have to remove one from the values in your array
    $ul.prepend( $items.get((arrValuesForOrder[i] - 1)));
  		}
	}

}



//left side bar search box
function displayResult(item, val, text) 
{
    var container = $('.main-content');
          if(ajax_version)
          {
            loadPage(val, container);
            $('.nav-input-search').val('')
          }
          else
          {
            window.href.location="val"
          }
} 
