$('.th-header').on('click', function () {
	window.location = ($(this).attr('th-header-url'));
});

$('select[name="per_page"]').change(function () {
	this.form.submit();
});

var calledSearch = 0;
$('input[name="search_key"]').on('input', function (e) {
	if (calledSearch == 0) {
		calledSearch = 1;
		setTimeout(function () {
			calledSearch = 0;
			$('#filter_form input[name="key"]').val($('input[name="search_key"]').val())
			$('#filter_form').submit();
		}, 1200);
	}
});
var temp = $('input[name="search_key"]').val();
$('input[name="search_key"]').focus().val('').val(temp);