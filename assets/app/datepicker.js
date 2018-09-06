if ($('.input-daterange-datepicker').length) {
	$('.input-daterange-datepicker').daterangepicker({
		buttonClasses: ['btn', 'btn-sm'],
	    applyClass: 'btn-default',
	    cancelClass: 'btn-white'
	});
}

$(function() {
    $( "#from" ).datepicker({
      defaultDate: "+1w",
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#to" ).datepicker({
      defaultDate: "+1w",
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
  });