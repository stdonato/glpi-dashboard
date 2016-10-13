	
function reloadPage() {
	
		$("#reload_page").click(function() {			
			window.location.reload();
		});
		
		
		var reloadTimer = function(flag, interval) {
		if (flag === true) {
			clearInterval(metric.reloadId);
			var counter = interval;
			$("#countDownTimer").text(interval);
	
			metric.reloadId = setInterval(function() {
				counter--;
				$("#countDownTimer").text(counter);
	
				if (counter === 0) {					
					window.location.reload();
					counter = interval;
				}
	
			}, 1000);
		} else {
			clearInterval(metric.reloadId);
			$("#countDownTimer").text("");
		}
   	};			


	$(function($){
		
		var inter = localStorage.getItem('relInt');
		document.getElementById('reload_selecter').value = inter;
		
		if (inter > 0) {
			$("#reload_page").attr({
				"disabled" : "disabled"
			});

			reloadTimer(true, inter);

		} else {
			$("#reload_page").removeAttr("disabled");

			reloadTimer(false);
		}							
});

		
		$(function($) {
			$('#reload_selecter').change(function() {
								
				var selectVal = $(this).val();
				
				localStorage.setItem('relInt',selectVal);
				var inter = localStorage.getItem('relInt');
				
				window.location.reload();								

				if (selectVal != 0) {
					$("#reload_page").attr({
						"disabled" : "disabled"
					});

					reloadTimer(true, selectVal);

				} else {
					$("#reload_page").removeAttr("disabled");

					reloadTimer(false);
					window.location.reload();
				}
			});
		});			

}		 
	    
	    