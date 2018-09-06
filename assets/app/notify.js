$('#notify_type').change(function() {
	if ($( "#notify_type" ).val() == 1) {
		$('#brand_id').show();
	} else {
		$('#brand_id').hide();
	}
});