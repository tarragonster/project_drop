$('.request-review-btn').click(function() {
	$('input[name="popop_review_id"]').val($(this).attr('data-review-id'));
	var str = $(this).attr('data-message');
	$('#popup-message').html(str);
	$('#sweet-overlay').show();
	$('#sweet-alert').show();
	$('textarea[name="popup_content"]').val('');
	$('select[name="popup_reason"] option').each(function() {
		$(this).removeAttr('selected');
    });
	$('body').addClass('stop-scrolling');
});

$('body').delegate("#alert-close-btn", 'click', function() {
	$('#sweet-overlay').hide();
	$('#sweet-alert').hide();
	$('body').removeClass('stop-scrolling');
});

$('body').delegate('#submit-btn', 'click', function() {
	if ($('select[name="popup_reason"]').val() != '') {
		var ajaxUrl = $('#submit-btn').attr('data-href');
	    $.ajax({
	        type: "POST",
	        dataType: "json",
	        //Relative or absolute path to response.php file
	        url: ajaxUrl, 
	        data: {'review_id' : $('input[name="popop_review_id"]').val(), 'reason_id' : $('select[name="popup_reason"]').val(), 'content' : $('textarea[name="popup_content"]').val()},
	        success: function(data) {
            	$('#sweet-overlay').hide();
            	$('#sweet-alert').hide();
            	$('body').removeClass('stop-scrolling');
            	window.setTimeout('location.reload()', 200)
	        }
	    });
	} else {
		alert('Please select reason first');
	}
});