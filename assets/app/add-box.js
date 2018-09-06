$('.add-button').on('click', function() {
	var ajaxUrl = $('#example3').attr('data-href');
	var id = $(this).attr('data');
	console.log(ajaxUrl);
	$.ajax({
        type: "GET",
        dataType: "html",
        url: ajaxUrl + '/' + id, 
        data: {},
        success: function(data) {
        	console.log(data);
        	var reponse = JSON.parse(data);
        	if (reponse.done == 1) {
        		$("#add_box_" + id).html('Added');
        	}
        }
    });
});