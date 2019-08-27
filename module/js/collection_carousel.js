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
var trending_id = null;

function ShowDisableCarousel(event){
	$("#dis-modal").modal("show");
	carousel_id = $(event).data("id");
}

function DisableCarousel(){
	$.ajax({
		type: "POST",
		url: "/collection/disableCarousel/" + carousel_id,
		success: function (data) {
				location.reload();
		}
	});
}

function ShowEnableCarousel(event){
	$("#en-modal").modal("show");
	carousel_id = $(event).data("id");
}

function EnableCarousel(){
	$.ajax({
		type: "POST",
		url: "/collection/enableCarousel/" + carousel_id,
		success: function (data) {
				location.reload();
		}
	});
}

function ShowDeleteCarousel(event){
	$("#del-modal").modal("show");
	carousel_id = $(event).data("id");
}

function DeleteCarousel(){
	$.ajax({
		type: "POST",
		url: "/collection/deleteCarousel/" + carousel_id,
		success: function (data) {
			location.reload();
		}
	});
}