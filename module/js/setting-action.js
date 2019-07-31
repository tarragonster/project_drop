$('#add-genre-btn').on('click', function (e) {
	e.preventDefault();
	$('#add-genre-form').html("");
	$.ajax({
		type: "POST",
		dataType: "html",
		data: {key:'add'},
		url: BASE_APP_URL + 'genre/ajaxGenre',
		success: function (data) {
			$('#add-genre-form').html(data);
			$('#add-genre-popup').modal('show');
		}
	});
});


$('.edit-genre-btn').on('click', function (e) {
	e.preventDefault();
	var genre_id = $(this).data('id')
	console.log(genre_id)
	// $('#edit-genre-form').html("");
	$.ajax({
		type: "POST",
		dataType: "html",
		data: {key:'edit', genre_id:genre_id},
		url: BASE_APP_URL + 'genre/ajaxGenre',
		success: function (data) {
			$('#edit-genre-form').html(data);
			$('#edit-genre-popup').modal('show');
		}
	});
});


