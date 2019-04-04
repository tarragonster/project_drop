function searchUser()
{
	var query = $('#search_text').val();
 	$.get('user/search/', {query:query}, function(data){
 		$('#user_table').html(data);
 	});
}

function searchFilm()
{
	var query = $('#search_text').val();
	$.get('product/search/', {query:query}, function(data){
 		$('#product_table').html(data);
 	});
}