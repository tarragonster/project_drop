$("#ad_type").change(function() {
	var ad_type = $('#ad_type').val();
	$('#select_placement').empty();
	$('#block_brand').hide();
	$('#block_product').hide();
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

$("#select_placement").change(function() {
	var type = $(this).find(":selected").attr('data-linked');
	$('#block_brand').hide();
	$('#block_product').hide();
	if (type == 1) {
		$('#block_brand').show();
	} else if (type == 2) {
//		$('#select_product').val('');
//		$('#' + $('#select_product').attr('data-linked-id')).val('');
		$('#block_product').show();
	}
});

$('#select_product').autocomplete({
    delay: 500,
    source: function(request, response) {
    	var hrefUrl = $('#select_product').attr('data-href');
        $.ajax({
                url: hrefUrl + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item.label,
                            value: item.value
                        }
                    }));
                }
        	});
    	},
    select: function(event, ui) {
    	$('#select_product').val(ui.item.label);
        $('#' + $('#select_product').attr('data-linked-id')).val(ui.item.value);
        return false;
    },
    focus: function(event, ui) {
        return false;
    }
});

jQuery(document).ready(function() {
	jQuery('.timepicker2').timepicker({
		showMeridian : false
	});
	
	jQuery('.datepicker-autoclose').datepicker({
    	autoclose: true,
    	todayHighlight: true
    });
});