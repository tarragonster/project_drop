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

$('#genre_id').multiselect({
    placeholder: 'Select Genre',
})