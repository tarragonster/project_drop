function searchUser()
{
	var query = $('#search_text').val();
 	$.ajax({
        type: 'GET',
        dataType: 'json',
        url: "user/search",
        data: {query:query},
        success: function(data) {
            $('#user_table').html(data);
        }
    });
}

function searchFilm()
{
	var query = $('#search_text').val();
 	$.ajax({
        type: 'GET',
        dataType: 'json',
        url: "product/search",
        data: {query:query},
        success: function(data) {
            $('#product_table').html(data);
        }
    });
}