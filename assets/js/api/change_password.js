$('#form').submit(function(){
	var new_pass = $('#pwd').val();
    var re_pass = $('#re-pwd').val();
    if (new_pass == '' || re_pass == '') {
    	$('.error-msg').text('Enter your password.');
    	return false;
    }else if (new_pass.length < 6 || re_pass.length < 6) {
    	$('.error-msg').text('Password length at least 6 characters.');
    	return false;
    }else if(new_pass != re_pass) {
    	$('.error-msg').text('Error. Passwords do not match.');
    	return false;
    }else {
    	return true;
    }
});

   
