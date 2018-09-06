$('#typeahead').autocomplete({
    delay: 500,
    source: function(request, response) {
    	var hrefUrl = $('#typeahead').attr('data-href');
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
    	$('#typeahead').val(ui.item.label);
        $('#' + $('#typeahead').attr('data-linked-id')).val(ui.item.value);
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