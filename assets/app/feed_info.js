$('#nameFilm').keyup(function() {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: window.location.origin + '/feed/ajaxLoadData/',
        data: {
            query: $('#nameFilm').val()
        },
        success: function(data) {
            $('#bodyFilm').html(data);
        }
    }); 
});

