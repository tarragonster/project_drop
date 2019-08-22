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
	
	$.ajax({
		type: "POST",
		dataType: "html",
		data: {key:key},
		url: BASE_APP_URL + 'explore/searchOtherUser',
		success: function (data) {
			console.log(data)
			var obj = JSON.parse(data);
			html += "<ul id='result-search'>"
			obj.forEach(function(item){
				html += "<li class='result-item' data-id='" + item.user_id + "' data-value='" + item.full_name + ", @" + item.user_name + ", " + item.email + "'>"
				html += "<a href='#' class='result-value'>" + item.full_name + ", @" + item.user_name + ", " + item.email + "</a></li>"
			})
			html += "</ul>"
			$('#other_user').append(html)
		}
	});
	$('#other_user').html('')
}

$(document).on('click','.result-item',function(){
	var user_id = $(this).data('id')
	var user_value = $(this).data('value')
	$('#user_key').val(user_value)
	$('#user_key').attr('data-id', user_id)
	$('#other_user').html('')
});

function addUser() {
	var user_value = $('#user_key').val()
	if(user_value != '') {
		var user_id = $('#user_key').data('id')
		$('#user_key').val(user_id)
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

	$.ajax({
		type: "POST",
		dataType: "html",
		data: {key:key},
		url: BASE_APP_URL + 'explore/searchOtherProduct',
		success: function (data) {
			console.log(data)
			var obj = JSON.parse(data);
			html += "<ul id='result-search'>"
			obj.forEach(function(item){
				html += "<li class='result-item' data-id='" + item.user_id + "' data-value='" + item.full_name + ", @" + item.user_name + ", " + item.email + "'>"
				html += "<a href='#' class='result-value'>" + item.full_name + ", @" + item.user_name + ", " + item.email + "</a></li>"
			})
			html += "</ul>"
			$('#other_user').append(html)
		}
	});
	$('#other_user').html('')
}