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



//Action update status of featured user
$('body').delegate('.btnAct', 'click', function(e) {
    e.preventDefault();

    var user_id = $(this).data('id');

    $('.dis-confirm').click(function(){
        $.get('explore/disableFeaturedUser', {user_id:user_id}, function(data){
	        $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });

    $('.en-confirm').click(function(){
        $.get('explore/enableFeaturedUser', {user_id:user_id}, function(data){
	        $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });

    $('.remove-confirm').click(function(){
        $.get('explore/removeFeaturedUser', {user_id:user_id}, function(data){
            $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });
});

//Action update status of preview list
$('body').delegate('.preview-btn', 'click', function(e) {
    e.preventDefault();

    var product_id = $(this).data('id');

    $('.dis-confirm').click(function(){
        $.get('disablePreview', {product_id:product_id}, function(data){
	        $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });

    $('.en-confirm').click(function(){
        $.get('enablePreview', {product_id:product_id}, function(data){
	        $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });

    $('.remove-confirm').click(function(){
        $.get('removePreview', {product_id:product_id}, function(data){
            $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });
});

// Show add featured user popup
$('#add-user').on('click', function (e) {
	e.preventDefault();
	$('#add-user-form').html("");
	$.ajax({
		type: "POST",
		dataType: "html",
		data: {key:'add-user'},
		url: BASE_APP_URL + 'explore/ajaxExplore',
		success: function (data) {
			$('#add-user-form').html(data);
			$('#add-user-popup').modal('show');
		}
	});
});

// Add featured user form
function searchUser() {
	var key = $('#user_key').val()
	var html = ''
	if(key == '') {
		$('#other_user').css('display', 'none')
	}else {
		$.ajax({
			type: "POST",
			dataType: "html",
			data: {key:key},
			url: BASE_APP_URL + 'explore/searchOtherUser',
			success: function (data) {
				$('#other_user').html('')
				$('#user_err').text('')
				if(data != 'null'){
					var obj = JSON.parse(data);
					html += "<ul id='result-search'>"
					obj.forEach(function(item){
						html += "<li class='result-item' data-id='" + item.user_id + "' data-value='" + item.full_name + ", @" + item.user_name + ", " + item.email + "'>"
						html += "<a href='#' class='result-value'>" + item.full_name + ", @" + item.user_name + ", " + item.email + "</a></li>"
					})
					html += "</ul>"
					$('#other_user').css('display', 'block')
					$('#other_user').append(html)
				}else {
					$('#other_user').css('display', 'none')
				}
			}
		});
	}
}

$(document).on('click','.result-item',function(){
	var user_id = $(this).data('id')
	var user_value = $(this).data('value')
	$('#user_key').val(user_value)
	$('#user_id').val(user_id)
	$('#other_user').html('')
});

function addUser() {
	var user_value = $('#user_key').val()
	var user_id = $('#user_id').val()
	if(user_value == '' || user_id == '') {
		$('#user_err').text('User must not be empty')
	}else{
		$('#user-form-add').submit()
	}
}

// Show add preview story popup
$('#add-story').on('click', function (e) {
	e.preventDefault();
	$('#add-story-form').html("");
	$.ajax({
		type: "POST",
		dataType: "html",
		data: {key:'add-story'},
		url: BASE_APP_URL + 'explore/ajaxExplore',
		success: function (data) {
			$('#add-story-form').html(data);
			$('#add-story-popup').modal('show');
		}
	});
});

// Add explore preview
function searchStory() {
	var key = $('#story_key').val();
	var html = ''
	if(key == '') {
		$('#other_story').css('display', 'none')
	}else {
		$.ajax({
			type: "POST",
			dataType: "html",
			data: {key:key},
			url: BASE_APP_URL + 'explore/searchOtherProduct',
			success: function (data) {
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
	}else if(explore_image == BASE_APP_URL + 'assets/images/borders/650x688@3x.png'){
		$('#ex_err1').text('');
        $('#ex_err2').text('');
		$('#img_err').text('Explore preview image must not be empty');
	}else {
		$('#story-form-add').submit()
	}

}


