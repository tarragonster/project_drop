$('.th-header').on('click', function () {

	window.location = ($(this).attr('th-header-url'));
});


$('select[name="per_page"]').change(function () {
	this.form.submit();
});
if ($('select[name="app_version"]').length > 0) {
	$('select[name="app_version"]').change(function () {
		this.form.submit();
	});
}

// $('input[name="key"]').keyup(function (event) {
// 	if (event.keyCode == 13) {
// 		this.form.submit();
// 	}
// });

var calledSearch = 0;
$('input[name="key"]').on('input', function (e) {
	if (calledSearch == 0) {
		calledSearch = 1;
		setTimeout(function () {
			calledSearch = 0;
			$('input[name="key"]')[0].form.submit();
		}, 1200);
	}
});
var temp = $('input[name="key"]').val();
$('input[name="key"]').focus().val('').val(temp);