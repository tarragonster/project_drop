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

// Add block
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

function saveEpisode(key) {
    var episode_name = $('#episode_name').val()
    var jw_media_id = $('#jw_media_id').val()
    var description = $('#description').val()
    var block_image = $('#block_image').attr('src')
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
    if(block_image == BASE_APP_URL + 'assets/images/borders/516x295.svg') {
        $('#blockImg_err').text('Block image must not be empty')
    }else {
        $('#blockImg_err').text('')
    }
    if($('#block_err').text() == '' && 
        $('#jw_err').text() == '' && 
        $('#des_err').text() == '' && 
        $('#blockImg_err').text() == '' && 
        $('#block_err1').css('display') == 'none' && $('#block_err2').css('display') == 'none')
    {
        if(key == 'edit') {
            $('#block-form-edit').submit();
        }else {
            $('#block-form-add').submit();
        }
    }
}

// Edit block
$('.edit-block-btn').on('click', function (e) {
    e.preventDefault();

    episode_id = $(this).data('id');
    product_id = $(this).data('product');

    $.ajax({
        type: "POST",
        dataType: "html",
        data: {key:'edit-block', episode_id:episode_id, product_id:product_id},
        url: BASE_APP_URL + 'product/ajaxProduct',
        success: function (data) {
            $('#edit-block-form').html(data);
            $('#edit-block-popup').modal('show');
        }    
    });
});

// Add season
$('.add-season-btn').on('click', function (e) {
    e.preventDefault();

    product_id = $(this).data('product');

    $.ajax({
        type: "POST",
        dataType: "html",
        data: {product_id:product_id},
        url: BASE_APP_URL + 'product/ajaxSeason',
        success: function (data) {
            $('.section-content').html(data)
        }    
    });
});