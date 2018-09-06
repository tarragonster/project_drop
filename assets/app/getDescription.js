$('#getDescription').click(function() {
	var query = $("#nameActor").val();
	$.ajax({
		type: "GET",
		url: window.location.origin + '/admin/actor/ajaxLoadActor/', 
        data: {
        	query: encodeURI('http://www.imdb.com/find?q=' + query +'&s=nm&ref_=fn_nm'), 
        },
        dataType: "text",
        success: function(rData) {
        	if(rData != ''){
        		$.ajax({
    				headers: { 
				        Accept : "text/json; charset=utf-8",
				        "Content-Type": "text/json; charset=utf-8"
				    },
			        url: "http://imdb.wemakesites.net/api/" + rData,
			        crossDomain: true,
			        dataType: "jsonp",
			        success: function(data) {
			        	$('#link_imdb').val('http://www.imdb.com/name/' + data.data.id);
			        	var text = data.data.description.substring(0, data.data.description.indexOf('See full bio'));
			        	text = text.trim();
			        	if(text.length > 176){
			        		text = text.substring(0, 176);
			        		text += "...";
			        	}
			        	$('#descriptionActor').html('<textarea  name="description" class="form-control" placeholder="" rows="3" required="">' + unescape(encodeURIComponent(text) + '</textarea>'));
			        }
			    });
	        }else{
	        	alert('No result');
	        }
            
        }
    });
});