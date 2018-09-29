!function ($) {
	"use strict";
	var SweetAlert = function () {
	};
	//examples
	SweetAlert.prototype.init = function () {
		// Delete Message
		$('.sa-delete').on('click', function (e) {
			var table = $(this).closest('table');
			var buttonText = table.data('delete-confirm');
			var link = $(this).data('href');
			swal({
				title: "Are you sure?",
				text: table.data('delete-alert'),
				type: "error",
				showCancelButton: true,
				confirmButtonColor: "#F27474",
				confirmButtonText: buttonText ? buttonText : "Yes, delete it!",
				closeOnConfirm: true,
				closeOnCancel: true
			}, function (isConfirm) {
				if (isConfirm) {
					window.location.href = link;
				}
			});
			e.preventDefault();
		});


		//Warning Message
		$('.sa-hide').on('click', function (e) {
			var table = $(this).closest('table');
			var link = $(this).data('href');
			swal({
				title: "Are you sure?",
				text: table.data('hide-alert'),
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#F8BB86",
				confirmButtonText: "Yes, hide it!",
				closeOnConfirm: true,
				closeOnCancel: true
			}, function () {
				window.location.href = link;
			});
			e.preventDefault();
		});
	},
		//init
		$.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
}(window.jQuery),

//initializing 
	function ($) {
		"use strict";
		$.SweetAlert.init()
	}(window.jQuery);