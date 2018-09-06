if ($('#type_message').length) {
	$('#type_message').on('change', function() {
		this.form.submit();
	});
}