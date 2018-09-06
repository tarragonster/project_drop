
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
	    $('.table-alert').click(function(){
	    	var link = $(this).attr('data-href');
	    	var target = $(this).attr('target');
	        swal({
	        	title: "Are you sure?",   
	            text: $("#example3").attr('data-alert'),   
	            type: "warning",
	            showCancelButton: true,   
	            confirmButtonColor: "#ff0000",
	            confirmButtonText: $("#example3").attr('data-confirm'),   
	            closeOnConfirm: true
	        }, function(){
	        	if (target == '_blank') {
	        		window.open(link, '_blank');
	        	} else {
	        		window.location.href = link;
	        	}
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