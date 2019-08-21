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

function test() {
	var arr = ['a', 'asdf', 'atryrty', 'body', 'bssfsd']
	// console.log(arr)
	$( "#user" ).autocomplete({
	    source: arr
    });
}