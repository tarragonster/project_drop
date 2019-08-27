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

// Show add carousel story popup
$('#add-story-carousel').on('click', function (e) {
	e.preventDefault();
	$('#add-story-form').html("");
	$.ajax({
		type: "POST",
		dataType: "html",
		data: {key:'add-carousel'},
		url: BASE_APP_URL + 'collection/ajaxCollection',
		success: function (data) {
			$('#add-story-form').html(data);
			$('#add-carousel-popup').modal('show');
		}
	});
});

// Show add trending story popup
$('#add-story-trending').on('click', function (e) {
	e.preventDefault();
	$('#add-story-form').html("");
	$.ajax({
		type: "POST",
		dataType: "html",
		data: {key:'add-trending'},
		url: BASE_APP_URL + 'collection/ajaxCollection',
		success: function (data) {
			$('#add-story-form').html(data);
			$('#add-trending-popup').modal('show');
		}
	});
});

// Add carousel story
function searchStory() {
	var key = $('#story_key').val();
	var html = ''
	if(key == '') {
		$('#other_story').css('display', 'none')
	}else {
		$.ajax({
			type: "POST",
			dataType: "html",
			data: {key:key, collection_id:5},
			url: BASE_APP_URL + 'collection/searchProductWithoutCollection',
			success: function (data) {
				console.log(data)
				$('#other_story').html('')
				$('#product_err').text('')
				if(data != 'null'){
					var obj = JSON.parse(data);
					html += "<ul id='result-search'>"
					obj.forEach(function(item){
						html += "<li class='result-item' data-id='" + item.product_id + "' data-value='" + item.name + "'>"
						html += "<a href='#' class='result-value'>" + item.name + "</a></li>"
					})
					html += "</ul>"
					$('#other_story').css('display', 'block')
					$('#other_story').append(html)
				}else {
					$('#other_story').css('display', 'none')
				}
			}
		});
	}
}

// Add trending story
function searchStoryTrending() {
	var key = $('#story_key').val();
	var html = ''
	if(key == '') {
		$('#other_story').css('display', 'none')
	}else {
		$.ajax({
			type: "POST",
			dataType: "html",
			data: {key:key, collection_id:1},
			url: BASE_APP_URL + 'collection/searchProductWithoutCollection',
			success: function (data) {
				console.log(data)
				$('#other_story').html('')
				$('#product_err').text('')
				if(data != 'null'){
					var obj = JSON.parse(data);
					html += "<ul id='result-search'>"
					obj.forEach(function(item){
						html += "<li class='result-item' data-id='" + item.product_id + "' data-value='" + item.name + "'>"
						html += "<a href='#' class='result-value'>" + item.name + "</a></li>"
					})
					html += "</ul>"
					$('#other_story').css('display', 'block')
					$('#other_story').append(html)
				}else {
					$('#other_story').css('display', 'none')
				}
			}
		});
	}
}

$(document).on('click','.result-item',function(){
	$('.uploader').css('display', 'block')

	var product_id = $(this).data('id')
	var product_name = $(this).data('value')

	$('#story_key').val(product_name)
	$('#product_id').val(product_id)
	$('#other_story').html('')
});

function addStory() {
	var product_name = $('#story_key').val()
	var product_id = $('#product_id').val()
	var explore_image = $('#explore_image').attr('src')

	if(product_name == '' || product_id == '') {
		$('#product_err').text('Product must not be empty')
	}else if(explore_image == BASE_APP_URL + 'assets/images/borders/667x440@3x.png'){
		$('#ex_err1').text('');
        $('#ex_err2').text('');
		$('#img_err').text('Carousel Banner must not be empty');
	}else {
		$('#story-form-add').submit()
	}
}

function addStoryTrending() {
	var product_name = $('#story_key').val()
	var product_id = $('#product_id').val()
	var explore_image = $('#explore_image').attr('src')

	if(product_name == '' || product_id == '') {
		$('#product_err').text('Product must not be empty')
	}else if(explore_image == BASE_APP_URL + 'assets/images/borders/233x346@3x.png'){
		$('#ex_err1').text('');
        $('#ex_err2').text('');
		$('#img_err').text('Poster image must not be empty');
	}else {
		$('#story-form-add').submit()
	}
	
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