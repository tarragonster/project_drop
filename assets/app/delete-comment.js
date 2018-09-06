
/**
* Theme: Ubold Admin Template
* Author: Coderthemes
* SweetAlert
*/

!function($) {
    "use strict";
    var SweetAlert = function() {};
    //examples 
    SweetAlert.prototype.init = function() {
	    //Warning Message
	    $('#content-comment').on('click', '.sa-comment' , function() {
	    	var link = $(this).attr('data-href');
	        swal({
	        	title: "Are you sure?",   
	            text: $("#example3").attr('data-alert'),   
	            type: "warning",   
	            showCancelButton: true,   
	            confirmButtonColor: "#ff0000",   
	            confirmButtonText: "Yes, delete it!",   
	            closeOnConfirm: true 
	        }, function(){
	        	$.ajax({
		            type: "GET",
		            dataType: "json",
		            url: link,
		            data: {
			            episode_id: $('#episode_id').val(),
			        },
		            success: function(data) {
		                $('#content-comment').html(data);
		            }
		        });
	        });
	    });
	    $('#content-replies').on('click', '.sa-replies' , function() {
	    	var link = $(this).attr('data-href');
	        swal({
	        	title: "Are you sure?",   
	            text: $("#example3").attr('data-alert'),   
	            type: "warning",   
	            showCancelButton: true,   
	            confirmButtonColor: "#ff0000",   
	            confirmButtonText: "Yes, delete it!",   
	            closeOnConfirm: true 
	        }, function(){
	        	$.ajax({
		            type: "GET",
		            dataType: "json",
		            url: link,
		            data: {
			            comment_id: $('#comment_id').val(),
			        },
		            success: function(data) {
		                $('#content-replies').html(data);
		            }
		        });
	        });
	    });
    },
    //init
    $.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
}(window.jQuery),

//initializing 
function($) {
    "use strict";
    $.SweetAlert.init()
}(window.jQuery);