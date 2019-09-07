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

// Sorting
$(".sortable").sortable({
	update: function (event, data) {
		$.ajax({
			type: "POST",
			url: $(this).attr('data-url'),
			data: $('#form-data').serialize(),
			success: function (data) {
				if (!data.success) {
					location.reload();
				}
				console.log(JSON.stringify(data)); // show response from the php script.
			}
		});
	},
}).disableSelection();

// function saveGenre(key) {
// 	var genre_name = $('#genre_name').val()
// 	var genre_image = $('#genre_image').attr('src')
// 	if(genre_name == '') {
//         $('#name_err').text('Genre name must not be empty')
//     }else {
//         $('#name_err').text('')
//     }
//     if(genre_image == BASE_APP_URL + 'assets/images/borders/369x214.svg') {
//         $('#genre_err').text('Genre image must not be empty')
//     }else {
//         $('#genre_err').text('')
//     }
//     if($('#name_err').text() == '' && $('#genre_err').text() == '' && $('#genre_err1').text() == '' && $('#genre_err2').text() == '') {
//     	if(key == 'add') {
// 			$('#genre-form-add').submit();
//     	}else {
// 			$('#genre-form-edit').submit();
//     	}
// 	}
// }