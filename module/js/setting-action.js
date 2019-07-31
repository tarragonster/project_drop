$('#add-genre-btn').on('click', function (e) {
	e.preventDefault();
	$('#add-genre-form').html("");
	$.ajax({
		type: "GET",
		dataType: "html",
		url: BASE_APP_URL + 'genre/ajaxAddGenre',
		success: function (data) {
			$('#add-genre-form').html(data);
			$('#add-genre-popup').modal('show');
		}
	});
});


