$('#nameActor').keyup(function() {
    console.log($('#nameActor').val());
    $.ajax({
        type: "GET",
        dataType: "json",
        url: window.location.origin + '/actor/ajaxLoadData/',
        data: {
            query: $('#nameActor').val(),
            product_id: $('#product_id').val()
        },
        success: function(data) {
            console.log(data);
            $('#bodyActor').html(data);
        }
    }); 
});

