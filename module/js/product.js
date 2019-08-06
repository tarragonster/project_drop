$('body').delegate('.btnAct', 'click', function(e) {
    e.preventDefault();

    var product_id = $(this).data('id');

    $('.dis-confirm').click(function(){
        $.get('disable', {product_id:product_id}, function(data){
	        $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });

    $('.en-confirm').click(function(){
        $.get('enable', {product_id:product_id}, function(data){
	        $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });

    $('.del-confirm').click(function(){
        if ($('#text-confirm').val() == 'DELETE') {
            $.get('delete', {product_id:product_id}, function(data){
                $(this).attr('data-dismiss', 'modal');
		        location.reload();
            });
        }
    });
});