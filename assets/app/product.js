$("a.restore").each(function() {
    $(this).click(function(e){
        e.preventDefault();      
        $('#confirm_restore').modal('show');
        $('#ajax_url').val($(this).attr('data-href'));
        $('#return_url').val($(this).attr('data-callback'));
        $('#restore_confirmation').on('click', function() {
            $('#confirm_restore').modal('hide');
            var ajaxUrl = $('#ajax_url').val();
            var return_url = $('#return_url').val();
            $.ajax({
                type: "GET",
                dataType: "json",
                //Relative or absolute path to response.php file
                url: ajaxUrl, 
                data: {},
                success: function(data) {
                    if (data.status == 'OK'){
                        $('#msg').html(data.message);
                        $('#confirm_message').modal('show');
                        setTimeout(function(){ 
                            window.location = return_url; 
                        }, 2000);
                    }
                }
            });           
        });
    });
});

$("#comment_block a.btn_close").each(function() {
    $(this).click(function(e){
        e.preventDefault();   
        var clicked = $(this);
        $('#confirm_close').modal('show');
        $('#ajax_url').val($(this).attr('data-href'));
        $('#close_confirmation').on('click', function() {
            $('#confirm_close').modal('hide');
            var ajaxUrl = $('#ajax_url').val();       
            $.ajax({
                type: "GET",
                dataType: "json",
                url: ajaxUrl, 
                data: {},
                success: function(data) {
                    if (data.status == 'OK'){
                        $('#msg').html(data.message);                                       
                        clicked.closest("tr").remove();    
                        $('#confirm_message').modal('show'); 
                    }
                }
            });           
        });
    });
});

$('.block_image img').each(function() {
    $(this).hover(function(){  
        var btnRemove = $(this).parent().next('.btn_delete');
        var top = Math.floor(($(this).height() - btnRemove.height())/2);
        var left = Math.floor(($(this).width() - btnRemove.width())/2);   
        btnRemove.css('top',top);
        btnRemove.css('left',left);
       
    },function() {
       
    })
})
$("a.btn_delete").each(function() {
    $(this).click(function(e){
        e.preventDefault();   
        var clicked = $(this);
        $('#confirm_close').modal('show');
        $('#ajax_url').val($(this).attr('data-href'));
        $('#close_confirmation').on('click', function() {
            $('#confirm_close').modal('hide');
            var ajaxUrl = $('#ajax_url').val();       
            $.ajax({
                type: "GET",
                dataType: "json",
                url: ajaxUrl, 
                data: {},
                success: function(data) {
                    if (data.status == 'OK'){
                        $('#msg').html(data.message);   
                        console.log(clicked);
                        clicked.parent().parent().remove();    
                        $('#confirm_message').modal('show'); 
                    }
                }
            });           
        });
    });
});

var count = 1;
$("#btn_add_more").click(function () {
	if (count < 5) {
		$('<input size=50 type="file" name="file[]" accept="image/*"/> ').appendTo($('#upload_block'));
		count++;
		if (count >= 5)
			$('#btn_add_more').remove();
	} else {
		$('#btn_add_more').remove();
	}
    return false;
});

$('body').delegate("#attr_id", 'change', function() {
//$("#attr_id").change(function() {
	var attr_id = $('#attr_id').val();
	$("#block_options").hide();
	$("#option_types").empty();
	$('#select_variants').empty();
	$('#select_variants').selectpicker('refresh');
	if (attr_id > 0) {
		$('#quantity').prop('disabled', true);
		$('#quantity').removeAttr('required');
		var ajaxUrl = $('#attr_id').attr('data-href'); 
		$.ajax({
	        type: "GET",
	        dataType: "html",
	        url: ajaxUrl + '/' + attr_id, 
	        data: {},
	        success: function(data) {
	        	console.log(data);
	        	$('#select_variants').html(data);
	        	$('#select_variants').selectpicker('refresh');
	        }
	    });
	} else {
		$('#quantity').prop('required', true);
		$('#quantity').removeAttr('disabled');
	}
});

$('body').delegate("#select_variants", 'change', function() {
//$('#select_variants').change( function() {
	var text = "";
	var value = 0;
	var count = 0;
	var prd;
	$("#select_variants option" ).each(function() {
		str = $(this).text();
		value = $(this).val();
		prd = $(this).attr('data-href');
		if($(this).is(':selected')) {
			count += 1;
    		if ($('#rowid' + value).length == 0)
    			$('#option_types').append("<tr id='rowid" + value + "'><td style='vertical-align: middle !important;'><input type='checkbox' name='option_cb[" + value + "]' class='form-control' value='1' checked/><input type='hidden' name='option_prid[" + value + "]' value='" + prd + "' /></td><td style='vertical-align: middle !important;'><span style='color: #5fbeaa'>" + str + "</span></td><td><input type='text' class='form-control' name='option_price[" + value + "]' placeholder='$0.00' required/></td><td><input type='text' class='form-control' name='option_sku[" + value + "]' required/></td><td><input type='text' class='form-control' name='option_qty[" + value + "]' required/></td><td style='vertical-align: middle !important;'><span class='delete_variant' data-href='"  + value + "' href='#' style='cursor: pointer'><i class='glyphicon glyphicon-remove'></i></span></td></tr>")
    	} else {
    		if ($('#rowid' + value).length > 0) {
    			$('#rowid' + value).remove();
    			console.log('remove');
    		}
		}
	});
	if (count > 0) {
    	$("#block_options").show();
	} else {
		$("#block_options").hide();
	}
});

$('body').delegate(".delete_variant", 'click', function() {
	var value = $(this).attr('data-href');
	$('#select_variants option[value=' + value + ']').attr('selected', false);
	$('#select_variants').selectpicker('refresh');
	$('#rowid' + value).remove();
	var count = 0;
	$("#select_variants option" ).each(function() {
		if($(this).is(':selected')) {
			count += 1;
    	}
	});
	if (count > 0) {
    	$("#block_options").show();
	} else {
		$("#block_options").hide();
	}
});

$('textarea#text-area-des').maxlength({
    alwaysShow: true
});

$('textarea#text-area-detail').maxlength({
    alwaysShow: true
});

$("#parent_id").change(function() {
	var parent_id = $('#parent_id').val();
	$('#cat1_id').empty();
	$('#cat2_id').empty();
	$('#cat1_id').html('<option value="">Main Category</optiopn>');
	$('#cat2_id').html('<option value="">Optional Category</optiopn>');
	$('#cat1_id').removeAttr('required');
	$('#cat1_id').prop('disabled', true);
	$('#cat2_id').prop('disabled', true);
	
	$('#quantity').prop('disabled', true);
	$('#quantity').removeAttr('required');
	$('#quantity').val('');
	$('#product_variations').html();
	if (parent_id <= 0)
		return;
	if ($("#parent_id option:selected").attr('data-has-sub') == 1) {
		$('#cat1_id').show();
		$('#cat2_id').show();
		var ajaxUrl = $('#parent_id').attr('data-href'); 
		$.ajax({
	        type: "GET",
	        dataType: "html",
	        url: ajaxUrl + '/' + parent_id, 
	        data: {},
	        success: function(data) {
	        	console.log(data);
	        	$('#cat1_id').prop('required', true);
	        	$('#cat1_id').removeAttr('disabled');
	        	$('#cat2_id').removeAttr('disabled');
	        	$('#cat1_id').html('<option value="">Main Category</optiopn>' + data);
	        	$('#cat2_id').html('<option value="">Optional Category</optiopn>' + data);
	        }
	    });
	}
	
	var variantUrl = $('#parent_id').attr('data-variant-href');
	var multiple = $("#parent_id option:selected").attr('data-multiple');
	if (multiple == 0) {
		$('#quantity').prop('required', true);
		$('#quantity').removeAttr('disabled');
	}
	$.ajax({
        type: "GET",
        dataType: "html",
        url: variantUrl + '/' + multiple, 
        data: {},
        success: function(data) {
        	console.log(data);
        	$('#product_variations').html(data);
        	
        	$('.selectpicker').selectpicker('refresh');
        }
    });
});

$('#is_sale_checkbox').change(function() {
    if($(this).is(":checked")) {
    	$('#selling_price').removeAttr('disabled');
    } else {
    	$('#selling_price').prop('disabled', true);
    }        
});