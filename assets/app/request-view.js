$('.request-view-btn').click(function() {
	$('#fill-user').text($(this).attr('data-user'));
	$('#star_rating').attr('data-rating', $(this).attr('data-star'));
	$('#fill-review').html($(this).attr('data-review'));
	$('#fill-time').text($(this).attr('data-time'));

	$('#sweet-overlay').show();
	$('#sweet-alert').show();

	$('#star_rating').rating();
	$('body').addClass('stop-scrolling');
});

$('body').delegate("#alert-close-btn", 'click', function() {
	$('#sweet-overlay').hide();
	$('#sweet-alert').hide();
	$('body').removeClass('stop-scrolling');
});