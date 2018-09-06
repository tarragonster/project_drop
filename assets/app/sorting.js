$(".m-sorting").click(function() {
	$("#order_by").val($(this).attr('data-order'));
	$("#sort_by").val($(this).attr('data-sort'));
	$("#form-premium").submit();
});