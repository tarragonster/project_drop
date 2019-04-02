/**
 * Created by ricky on 4/2/19.
 */

$('.button-submit-password').on("click", function(e) {
	e.preventDefault();
	var form = $('#form');
	var password = form.find('input[name="password"]').val();
	var re_password = form.find('input[name="re_password"]').val();
	if (password.length < 6) {
		$('.error-msg').text('Password length at least 6 characters.').show();
		return;
	}
	if (password != re_password) {
		$('.error-msg').text('Password are not match.').show();
		return;
	}
	$('.error-msg').hide();
	form.submit();
});