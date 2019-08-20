moment.tz.add('America/Los_Angeles|PST PDT|80 70|0101|1Lzm0 1zb0 Op0');
var currentStartRange = moment();
var currentEndRange = moment();
var currentSecondStartRange = moment().subtract(1, 'days');
var currentSecondEndRange = moment().subtract(1, 'days');

$('#reportrange span').html('Today');
$('#compared_range').html(moment().subtract(1, 'days').tz('America/Los_Angeles').format('MMM DD'));
var ranges = {
	'Today': [moment(), moment(), moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days'), moment().subtract(2, 'days'), moment().subtract(2, 'days')],
	'Last 7 Days': [moment().subtract(6, 'days'), moment(), moment().subtract(13, 'days'), moment().subtract(7, 'days')],
	'Last 30 Days': [moment().subtract(29, 'days'), moment(), moment().subtract(59, 'days'), moment().subtract(30, 'days')],
	'Last 90 days': [moment().subtract(89, 'days'), moment(), moment().subtract(179, 'days'), moment().subtract(90, 'days')],
	'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month'), moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
	'Last year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year'), moment().subtract(2, 'year').startOf('year'), moment().subtract(2, 'year').endOf('year')],
	'Week to date': [moment().startOf('isoweek'), moment(), moment().startOf('isoweek').subtract(1, 'week'), moment().subtract(1, 'week')],
	'Month to date': [moment().startOf('month'), moment(), moment().startOf('month').subtract(1, 'month'), moment().subtract(1, 'month')],
	'Quarter to date': [moment().startOf('quarter'), moment(), moment().startOf('quarter').subtract(1, 'quarter'), moment().subtract(1, 'quarter')],
	'Year to date': [moment().startOf('year'), moment(), moment().startOf('year').subtract(1, 'year'), moment().subtract(1, 'year')]
};

for (var i = 1; i <= 4; i++) {
	var firstQuarter = moment().subtract(i, 'quarter').startOf('quarter');
	var label = '';
	if (firstQuarter.quarter() == 1) {
		label = '1st Quarter (' + firstQuarter.year() + ')';
	} else if (firstQuarter.quarter() == 2) {
		label = '2nd Quarter (' + firstQuarter.year() + ')';
	} else if (firstQuarter.quarter() == 3) {
		label = '3th Quarter (' + firstQuarter.year() + ')';
	} else {
		label = '4th Quarter (' + firstQuarter.year() + ')';
	}
	ranges[label] = [firstQuarter, moment().subtract(i, 'quarter').endOf('quarter'), moment().subtract(i + 1, 'quarter').startOf('quarter'), moment().subtract(i + 1, 'quarter').endOf('quarter')];
}

// Remove because now show all time
function update_actived_day() {
	if (active_days.length > 0) {
		$('#day_in_week option').prop('disabled', true);
		$('#day_in_week option').prop('selected', false);
		$.each(active_days, function (index, value) {
			$('#day_in_week_' + value).prop('disabled', false);
		});
		current_actived_day = active_days[0];
		$('#day_in_week_' + active_days[0]).attr('selected', true);
		$('#day_in_week').val(active_days[0])
	}
}

function showDateRange(secondStart, secondEnd) {
	if (secondStart.year() == secondEnd.year()) {
		if (secondStart.month() == secondEnd.month() && secondStart.date() == secondEnd.date())
			return secondEnd.tz('America/Los_Angeles').format('MMM DD, YYYY');
		else
			return secondStart.tz('America/Los_Angeles').format('MMM DD') + ' - ' + secondEnd.tz('America/Los_Angeles').format('MMM DD, YYYY');
	} else {
		return secondStart.tz('America/Los_Angeles').format('MMM DD, YYYY') + ' - ' + secondEnd.tz('America/Los_Angeles').format('MMM DD, YYYY');
	}
}

$('#reportrange').daterangepicker({
	format: 'MM/DD/YYYY',
	startDate: moment(),
	endDate: moment(),
	minDate: '01/01/2017',
	maxDate: moment(),
	dateLimit: {
		days: 6000000
	},
	showDropdowns: true,
	showWeekNumbers: true,
	timePicker: false,
	timePickerIncrement: 1,
	timePicker12Hour: true,
	ranges: ranges,
	opens: 'left',
	drops: 'down',
	buttonClasses: ['btn', 'btn-sm'],
	applyClass: 'btn-default',
	cancelClass: 'btn-white',
	separator: ' to ',
	locale: {
		applyLabel: 'Submit',
		cancelLabel: 'Cancel',
		fromLabel: 'From',
		toLabel: 'To',
		customRangeLabel: 'Custom',
		daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
		monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
		firstDay: 1
	}
}, function (start, end, label) {
	var secondStart = null;
	var secondEnd = null;
	if (label == 'Custom') {
		$('#reportrange span').html(showDateRange(start, end));
		var diff = end.diff(start, 'days') + 1;
		start.second
		secondStart = start.clone().subtract(diff, 'day');
		secondEnd = end.clone().subtract(diff, 'day');
	} else {
		$('#reportrange span').html(label);
		secondStart = ranges[label][2];
		secondEnd = ranges[label][3];
	}
	$('#compared_range').html(showDateRange(secondStart, secondEnd));

	reloadDashboard(start, end, secondStart, secondEnd);
});

function reloadDashboard(start, end, secondStart, secondEnd) {
	$('#dashboard-loading').show();
	$.ajax({
		type: "POST",
		dataType: "json",
		url: BASE_APP_URL + "index/dashboard/" + start.tz('America/Los_Angeles').format('YYYY-MM-DD') + "/" + end.tz('America/Los_Angeles').format('YYYY-MM-DD') + "/" + secondStart.tz('America/Los_Angeles').format('YYYY-MM-DD') + "/" + secondEnd.tz('America/Los_Angeles').format('YYYY-MM-DD'),
		data: {},

		success: function (data) {
			$('#dashboard-loading').hide();
			if (!data['success']) {
				location.reload()
				return;
			}
			currentStartRange = start;
			currentEndRange = end;
			currentSecondStartRange = secondStart;
			currentSecondEndRange = secondEnd;

			dataSetChanged(data);
		},
		error: function() {
			$('#dashboard-loading').hide();
		}
	});
}

function html_percent(percent) {
	if (percent > 0) {
		return '<span class="text-success"><i class="fa fa-arrow-up"></i> ' + percent + '%</span>';
	} else if (percent == 0) {
		return '<span> 0%</span>';
	} else {
		return '<span class="text-danger"><i class="fa fa-arrow-down"></i> ' + Math.abs(percent) + '%</span>';
	}
}

function dataSetChanged(data) {
	if (data['unix_timestamp'] != null) {
		$('#unix_timestamp').val(data['unix_timestamp']);
	}
	if (data['user_chart'] != null) {
		$('#user-chart').empty();
		$('#user_label_second').html(data['user_chart']['second']['label']);
		$('#user_label_first').html(data['user_chart']['first']['label']);
		$('#user_value').html($.number(data['user_chart']['first']['value'], 0));
		$('#user_percent').html(html_percent(data['user_chart']['percent']));

		Morris.Line({
			element: 'user-chart',
			data: data['user_chart']['data'],
			hideHover: 'auto',
			xkey: 'label',
			ykeys: ['first', 'second'],
			labels: [data['user_chart']['first']['label'], data['user_chart']['second']['label']],
			lineColors: ['#996cd9', '#c3cdd4'],
			parseTime: false,
			yLabelFormat: function (y) {
				if (y >= 10000) {
					return $.number(y / 1000, 0) + 'k';
				} else if (y >= 100) {
					return $.number(y, 0);
				} else {
					return y != Math.round(y) ? '' : y;
				}
			},
			resize: true,
		});


		$('#comment-chart').empty();
		Morris.Line({
			element: 'comment-chart',
			data: data['user_chart']['data'],
			hideHover: 'auto',
			xkey: 'label',
			ykeys: ['first', 'second'],
			labels: [data['user_chart']['first']['label'], data['user_chart']['second']['label']],
			lineColors: ['#996cd9', '#c3cdd4'],
			parseTime: false,
			yLabelFormat: function (y) {
				if (y >= 10000) {
					return $.number(y / 1000, 0) + 'k';
				} else if (y >= 100) {
					return $.number(y, 0);
				} else {
					return y != Math.round(y) ? '' : y;
				}
			},
			resize: true,
		});

		$('#review-chart').empty();
		Morris.Line({
			element: 'review-chart',
			data: data['user_chart']['data'],
			hideHover: 'auto',
			xkey: 'label',
			ykeys: ['first', 'second'],
			labels: [data['user_chart']['first']['label'], data['user_chart']['second']['label']],
			lineColors: ['#996cd9', '#c3cdd4'],
			parseTime: false,
			yLabelFormat: function (y) {
				if (y >= 10000) {
					return $.number(y / 1000, 0) + 'k';
				} else if (y >= 100) {
					return $.number(y, 0);
				} else {
					return y != Math.round(y) ? '' : y;
				}
			},
			resize: true,
		});

		searchedChartConfig.data.datasets[0].data = [
			randomScalingFactor(),
			randomScalingFactor(),
			randomScalingFactor(),
			randomScalingFactor(),
			randomScalingFactor()
		];
		searchedChart.update();

		ageChartConfig.data.datasets[0].data = [
			randomScalingFactor(),
			randomScalingFactor(),
			randomScalingFactor(),
			randomScalingFactor()
		];
		ageChart.update();
	}
}