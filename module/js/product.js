// Action of Product
$('body').delegate('.btnAct', 'click', function(e) {
    e.preventDefault();

    var product_id = $(this).data('id');

    $('.dis-confirm').click(function(){
        $.get(BASE_APP_URL + 'product/disable', {product_id:product_id}, function(data){
	        $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });

    $('.en-confirm').click(function(){
        $.get(BASE_APP_URL + 'product/enable', {product_id:product_id}, function(data){
	        $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });

    $('.del-confirm').click(function(){
        if ($('#text-confirm').val() == 'DELETE') {
            $.get(BASE_APP_URL + 'product/delete', {product_id:product_id}, function(data){
                $(this).attr('data-dismiss', 'modal');
		        location.reload();
            });
        }
    });
});

// Multi select
$('#genre_id').multiselect({
    placeholder: 'Select Genre',
});

// Sorting
$(".sortable").sortable({
    update: function (event, data) {
        console.log($('#form-data').serialize())
        $.ajax({
            type: "POST",
            url: $(this).attr('data-url'),
            data: $('#form-data').serialize(),
            success: function (data) {
                if (!data.success) {
                    // location.reload();
                }
                console.log(JSON.stringify(data)); // show response from the php script.
            }
        });
    },
}).disableSelection();

// Actions of Review
$('body').delegate('.btn_action', 'click', function(e) {
    e.preventDefault();

    var pick_id = $(this).data('id');
    var product_id = $(this).data('product');
    
    $('.dis-confirm').click(function(){
        $.get(BASE_APP_URL + 'product/disableReview', {pick_id:pick_id, product_id:product_id}, function(data){
            $(this).attr('data-dismiss', 'modal');
            location.reload();
        });
    });

    $('.en-confirm').click(function(){
        $.get(BASE_APP_URL + 'product/enableReview', {pick_id:pick_id, product_id:product_id}, function(data){
            $(this).attr('data-dismiss', 'modal');
            location.reload();
        });
    });

    $('.del-confirm').click(function(){
        if ($('#text-confirm').val() == 'DELETE') {
            $.get(BASE_APP_URL + 'product/deleteReview', {pick_id:pick_id, product_id:product_id}, function(data){
                $(this).attr('data-dismiss', 'modal');
                location.reload();
            });
        }
    });
});


