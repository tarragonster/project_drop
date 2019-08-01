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

//Action update status of genre
$('body').delegate('.btnAct', 'click', function(e) {
    e.preventDefault();

    var genre_id = $(this).data('id');

    $('.dis-confirm').click(function(){
        $.get('genre/disable', {genre_id:genre_id}, function(data){
	        $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });

    $('.en-confirm').click(function(){
        $.get('genre/enable', {genre_id:genre_id}, function(data){
	        $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });

    $('.del-confirm').click(function(){
        if ($('#text-confirm').val() == 'DELETE') {
            $.get('genre/delete', {genre_id:genre_id}, function(data){
                $(this).attr('data-dismiss', 'modal');
		        location.reload();
            });
        }
    });
});

