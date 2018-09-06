$('body').delegate("#select_sizing", 'change', function() {
	var value = 0;
	var count = 0;
	$("#select_sizing option").each(function() {
		str = $(this).text();
		value = $(this).val();
		if($(this).is(':selected')) {
			count += 1;
    		if ($('#group' + value).length == 0) {
    			$('#block_variant_options').append(
    					"<div class='col-md-12' style='margin-top: 6px' id='group" + value + "'>"
							+ "<div class='box box-primary'>"
							+ "<div class='box-body'>"
							+ "<div class='card-box table-responsive' style='background: #F2F4F5!important'>"
							+ "<div class='box-header'>"
							+ "<h3>Modify the variants to be created: <span style='color: #5fbeaa'><strong>" + str + "</strong></span></h3>"
							+ "</div>"
							+ "<table id='example3' class='table table-striped table-bordered'>"
							+ "<thead>"
							+ "<tr>"
							+ "<th align='center'>&nbsp;</th>"
							+ "<th>Variant</th>"
							+ "<th>Price</th>"
							+ "<th>SKU</th>"
							+ "<th>Quantity</th>"
							+ "<th>Action</th>"
							+ "</tr>"
							+ "</thead>"
							+ "<tbody class='options_block' data-group='" + value +"'>"
							+ "</tbody>"
							+ "</table>"
							+ "</div>"
							+ "</div>"
							+ "</div>"
							+ "</div>");
	    		$("#select_strength option").each(function() {
	    			str = $(this).text();
	    			value = $(this).val();
	    			if($(this).is(':selected')) {
	    				console.log('selected');
	    				$(".options_block").each(function() {
	    					group = $(this).attr('data-group');
	    					if ($('#rowid_' + group + "_" + value).length == 0)
	    						$(this).append("<tr id='rowid_" + group + "_" + value
	    								+ "'><td style='vertical-align: middle !important;'>"
	    								+ "<input type='checkbox' name='option_cbs[" + group + "][" + value + "]' class='form-control' value='1' checked/>"
	    								+ "<td style='vertical-align: middle !important;'><span style='color: #5fbeaa'>" + str + "</span></td>"
	    								+ "<td><input type='text' class='form-control' name='option_prices[" + group + "][" + value + "]' placeholder='$0.00' required/></td>"
	    								+ "<td><input type='text' class='form-control' name='option_skus[" + group + "][" + value + "]' required/></td>"
	    								+ "<td><input type='text' class='form-control' name='option_qtys[" + group + "][" + value + "]' required/></td>"
	    								+ "<td style='vertical-align: middle !important;'>"
	    								+ "<span class='delete_variants' data-id='rowid_" + group + "_" + value + "' data-group='" + group + "' data-value='" + value + "' style='cursor: pointer'>"
	    								+ "<i class='glyphicon glyphicon-remove'></i></span></td></tr>");
	    				});
	    	    	}
	    		});
    		}
    	} else {
    		if ($('#group' + value).length > 0) {
    			$('#group' + value).remove();
    		}
		}
	});
	if (count > 0) {
    	$("#strength_box").show();
    	$("#block_variant_options").show();
	} else {
		$("#select_strength option:selected").removeAttr("selected");
		$('#select_strength').selectpicker('refresh');
		
		$("#strength_box").hide();
		$("#block_variant_options").hide();
	}
});

$('body').delegate("#select_strength", 'change', function() {
	var value = 0;
	$("#select_strength option").each(function() {
		str = $(this).text();
		value = $(this).val();
		if($(this).is(':selected')) {
			console.log('selected');
			$(".options_block").each(function() {
				group = $(this).attr('data-group');
				if ($('#rowid_' + group + "_" + value).length == 0)
					$(this).append("<tr id='rowid_" + group + "_" + value
							+ "'><td style='vertical-align: middle !important;'>"
							+ "<input type='checkbox' name='option_cbs[" + group + "][" + value + "]' class='form-control' value='1' checked/>"
							+ "<td style='vertical-align: middle !important;'><span style='color: #5fbeaa'>" + str + "</span></td>"
							+ "<td><input type='text' class='form-control' name='option_prices[" + group + "][" + value + "]' placeholder='$0.00' required/></td>"
							+ "<td><input type='text' class='form-control' name='option_skus[" + group + "][" + value + "]' required/></td>"
							+ "<td><input type='text' class='form-control' name='option_qtys[" + group + "][" + value + "]' required/></td>"
							+ "<td style='vertical-align: middle !important;'>"
							+ "<span class='delete_variants' data-id='rowid_" + group + "_" + value + "' data-group='" + group + "' data-value='" + value + "' style='cursor: pointer'>"
							+ "<i class='glyphicon glyphicon-remove'></i></span></td></tr>");
			});
    	} else {
    		$(".options_block").each(function() {
    			group = $(this).attr('data-group');
    			if ($('#rowid_' + group + "_" + value).length > 0) {
	    			$('#rowid_' + group + "_" + value).remove();
    			}
    		});
    		
		}
	});
});


$('body').delegate(".delete_variants", 'click', function() {
	var id = $(this).attr('data-id');
	var value = $(this).attr('data-value');
	$('#select_strength option[value=' + value + ']').attr('selected', false);
	$('#select_strength').selectpicker('refresh');
	
	$('#' + id).remove();
});