var t = 0;
var lastTime = 0;
// function searchUser()
// {
//
//     // var date = new Date();
//     var time1 = ++t;
//     // console.log('timeSend: ' + time1);
//     var query = $('#search_text').val();
//  	$.ajax({
//         type: 'GET',
//         dataType: 'json',
//         url: "user/search",
//         data: {query:query},
//         success: function(data) {
//             fillData(time1, data);
//         }
//     });
// }

function fillData(time, data) {
    console.log('   time:  ' + time);
    if (lastTime <= time) {
        lastTime = time;
        $('#user_table').html(data);
    }
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