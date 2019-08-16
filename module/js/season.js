// Actions of Season
$('body').delegate('.btn_season', 'click', function(e) {
    e.preventDefault();

    var episode_id = $(this).data('id');
    var product_id = $(this).data('product');
    
    $('.dis-confirm').click(function(){
        $.get(BASE_APP_URL + 'product/disableEpisode', {episode_id:episode_id, product_id:product_id}, function(data){
            $(this).attr('data-dismiss', 'modal');
            location.reload();
        });
    });

    $('.en-confirm').click(function(){
        $.get(BASE_APP_URL + 'product/enableEpisode', {episode_id:episode_id, product_id:product_id}, function(data){
            $(this).attr('data-dismiss', 'modal');
            location.reload();
        });
    });

    $('.del-confirm').click(function(){
        if ($('#text-confirm').val() == 'DELETE') {
            $.get(BASE_APP_URL + 'product/deleteEpisode', {episode_id:episode_id, product_id:product_id}, function(data){
                $(this).attr('data-dismiss', 'modal');
                location.reload();
            });
        }
    });
});


$(".sortable").sortable({
    update: function (event, data) {
        console.log($('#' + $(this).attr('data-form-id')).serialize())
        $.ajax({
            type: "POST",
            url: $(this).attr('data-url'),
            data: $('#' + $(this).attr('data-form-id')).serialize(),
            success: function (data) {
                if (data.success) {
                    location.reload();
                }
                console.log(JSON.stringify(data)); // show response from the php script.
            }
        });
    },
    start: function (event, ui) {
        $('#dragging-item-' + $(this).attr('season-id')).val(ui.item.attr('data-id'));
    }
}).disableSelection();