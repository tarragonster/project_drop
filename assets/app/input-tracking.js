$('.add-tracking-btn').click(function() {
	$('input[name="order_id"]').val($(this).attr('data-order-id'));
	$('#sweet-overlay').show();
	$('#sweet-alert').show();
	$('input[name="tracking_number"]').val('');
	$('select[name="carriers"] option').each(function() {
		$(this).removeAttr('selected');
    });
	$('#message').html("");
	$('#tracking_message').html("");
	
	$('#enter-btn').show();
	$('#submit-btn').hide();
	$('body').addClass('stop-scrolling');
});

$('body').delegate("#alert-close-btn", 'click', function() {
	$('#sweet-overlay').hide();
	$('#sweet-alert').hide();
	$('body').removeClass('stop-scrolling');
});
$('body').delegate('#enter-btn', 'click', function() {
	var ajaxUrl = $('#enter-btn').attr('data-href');
	$('#tracking_message').html("");
	$('#message').html("");
    $.ajax({
        type: "GET",
        dataType: "json",
        //Relative or absolute path to response.php file
        url: ajaxUrl + $('input[name="tracking_number"]').val() + '&c=' + $('select[name="carriers"]').val(), 
        data: {},
        success: function(data) {
            if (data.success){
                $('#submit-btn').show();
                $('select[name="carriers"] option').each(function() {
                	if ($(this).val() == data.carrier) {
                		$(this).prop('selected', true);
                		$('#message').html("auto detected");
                	}
                });
            } else {
            	$('#tracking_message').html(data.message);
            }            
        }
    });
});

$('body').delegate('#submit-btn', 'click', function() {
	if ($('select[name="carriers"]').val() != '') {
		var ajaxUrl = $('#submit-btn').attr('data-href');
	    $.ajax({
	        type: "POST",
	        dataType: "json",
	        //Relative or absolute path to response.php file
	        url: ajaxUrl, 
	        data: {'order_id' : $('input[name="order_id"]').val(), 'tracking_number' : $('input[name="tracking_number"]').val(), 'carrier' : $('select[name="carriers"]').val()},
	        success: function(data) {
	            if (data.success) {
	            	$('#sweet-overlay').hide();
	            	$('#sweet-alert').hide();
	            	$('body').removeClass('stop-scrolling');
	            	window.setTimeout('location.reload()', 200)
	            } else {
	            	$('#message').html(data.message);
	            }
	        }
	    });
	} else {
		alert('Please select carrier first');
	}
});