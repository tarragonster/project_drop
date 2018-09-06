jQuery(document).ready(function() {
	jQuery('.timepicker2').timepicker({
		showMeridian : false
	});
	
	jQuery('.datepicker-autoclose').datepicker({
    	autoclose: true,
    	todayHighlight: true
    });
});

$('#select_brand').autocomplete({
    delay: 500,
    source: function(request, response) {
    	var hrefUrl = $('#select_brand').attr('data-href');
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
    	$('#select_brand').val(ui.item.label);
        $('#' + $('#select_brand').attr('data-linked-id')).val(ui.item.value);
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

$("#type").change(function() {
	var type = $('#type').val();
	if (type == 1) {
		$("#block_brand").show();
		$("#block_product").hide();
	} else {
		$("#block_brand").hide();
		$("#block_product").show();
	}
});