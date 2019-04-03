function ajaxSearch()
{
	var query = $('#search_text').val();
 	$.get('user/search/', {query:query}, function(data){
 		$('#user_table').html(data);
 	});
}