
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
	    $('.sa-warning').click(function(){
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
	        	window.location.href = link;
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