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

// 
$('.add-block-btn').on('click', function (e) {
    e.preventDefault();

    $('#add-block-form').html("");
    product_id = $(this).data('product');

    $.ajax({
        type: "POST",
        dataType: "html",
        data: {key:'add-block', product_id:product_id},
        url: BASE_APP_URL + 'product/ajaxProduct',
        success: function (data) {
            $('#add-block-form').html(data);
            $('#add-block-popup').modal('show');
        }
    });
});

function saveEpisode() {
    var episode_name = $('#episode_name').val()
    var story_name = $('#story_name').val()
    var season_id = $('#season_id').val()
    var jw_media_id = $('#jw_media_id').val()
    var description = $('#description').val()

    if(episode_name == '') {
        $('#block_err').text('Block name must not be empty')
    }else {
        $('#block_err').text('')
    }
    if(jw_media_id == '') {
        $('#jw_err').text('JW Media ID must not be empty')
    }else {
        $('#jw_err').text('')
    }
    if(description == '') {
        $('#des_err').text('Description must not be empty')
    }else {
        $('#des_err').text('')
    }
    if($('#block_err').text() == '' && 
        $('#jw_err').text() == '' && 
        $('#des_err').text() == '' && 
        $('#block_image').attr('src') != BASE_APP_URL + 'assets/images/borders/516x295.svg') 
    {
        $('#block-form-add').submit();
    }
}