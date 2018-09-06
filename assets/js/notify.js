
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
    	$("#button-send").click(function(){
    	    $.ajax({url: "notify/ajaxSend", type: 'POST', 
    	    		data: $( "#thisForm" ).serialize(), 
    	    		success: function(data) {
    	    			if (data.success) {
    	    				swal("Send notify success!", "", "success")
    	    			} else {
    	    				swal({   
    	    		            title: "Send notify error",   
    	    		            text: data.error,   
    	    		            timer: 4000,   
    	    		            showConfirmButton: false 
    	    		        });
    	    			}
    	    		}
    	    	});
    	    return false;
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