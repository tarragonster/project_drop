$("#ad_type").change(function() {
	var ad_type = $('#ad_type').val();
	$('#select_placement').empty();
	$('#select_placement').selectpicker('refresh');
	if (ad_type > 0) {
		var ajaxUrl = $('#ad_type').attr('data-href'); 
		$.ajax({
	        type: "GET",
	        dataType: "html",
	        url: ajaxUrl + '/' + ad_type, 
	        data: {},
	        success: function(data) {
	        	$('#select_placement').html(data);
	        }
	    });
	}
});