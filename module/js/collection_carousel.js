// Sorting
$(".sortable").sortable({
	update: function (event, data) {
		$.ajax({
			type: "POST",
			url: $(this).attr('data-url'),
			data: $('#form-data').serialize(),
			success: function (data) {
				if (!data.success) {
					location.reload();
				}
				console.log(JSON.stringify(data)); // show response from the php script.
			}
		});
	},
}).disableSelection();

var carousel_id = null;

function ShowDisableCarousel(event){
	$("#dis-modal").modal("show");
	carousel_id = $(event).data("id");
}

function DisableCarousel(event){
	$.ajax({
		type: "POST",
		url: "/collection/disableCarousel/" + carousel_id,
		// data: $('#form-data').serialize(),
		success: function (data) {
			if (!data.success) {
				location.reload();
			}
		}
	});

}