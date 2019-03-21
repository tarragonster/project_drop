$('#nameFilm').keyup(function() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: window.location.origin + '/product/ajaxLoadData/',
        data: {
            query: $('#nameFilm').val(),
            cast_id: $('#cast_id').val()
        },
        success: function(data) {
            $('#bodyFilm').html(data);
        }
    }); 
});

