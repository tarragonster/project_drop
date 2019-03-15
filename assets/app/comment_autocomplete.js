jQuery(document).ready(function() {
$('#select_episode').autocomplete({
    delay: 100,
    source: function(request, response) {
        var hrefUrl = $('#select_episode').attr('data-href');
        $.ajax({
                url: hrefUrl + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item.label,
                            value: item.value
                        }
                    }));
                }
            });
        },
    select: function(event, ui) {
        $('#select_episode').val(ui.item.label);
        $('#episode_id').val(ui.item.value);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: window.location.origin + '/comment/ajaxComment/',
            data: {
                episode_id: ui.item.value
            },
            success: function(data) {
                console.log(data);
                $('#content-comment').html(data);
            }
        });
        return false;
    },
    focus: function(event, ui) {
        return false;
    }
});

$('#content-comment').on('click', '.btn-comment' , function() {
    $('#comment_id').val($(this).attr('id'));
    $.ajax({
        type: "GET",
        dataType: "json",
        url: window.location.origin + '/comment/ajaxReplies/',
        data: {
            comment_id: $(this).attr('id')
        },
        success: function(data) {
            $('#comment-box').hide();
            $('#replies-box').show();
            $('#content-replies').html(data);
        }
    }); 
});
$('.btn-back-comment').on('click', function() {
    $('#comment-box').show();
    $('#replies-box').hide();
});

});