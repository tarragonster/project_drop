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

var searchedChartConfig = {
	type: 'pie',
	data: {
		datasets: [{
			data: [],
			backgroundColor: [
				'#4A81D4',
				'#F4F8FB',
				'#2FBC9B',
				'#28292C',
				'#C7AE6E'
			]
		}],
		labels: []
	},
	options: {
		responsive: true,
		legend: {
			position: 'right',
			onClick: function (e) {
				e.stopPropagation();
			}
		}
	}
};

var ageChartConfig = {
	type: 'pie',
	data: {
		datasets: [{
			data: [],
			backgroundColor: [
				'#4A81D4',
				'#F4F8FB',
				'#2FBC9B',
				'#28292C',
				'#FF0000'
			]
		}],
		labels: []
	},
	options: {
		responsive: true,
		legend: {
			position: 'right',
			onClick: function (e) {
				e.stopPropagation();
			}
		}
	}
};

window.searchedChart = new Chart(document.getElementById('chart-searched').getContext('2d'), searchedChartConfig);
window.ageChart = new Chart(document.getElementById('chart-age').getContext('2d'), ageChartConfig);

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
	var appendingURL = "";
	if (start != null) {
		appendingURL = "/" + start.tz('America/Los_Angeles').format('YYYY-MM-DD') + "/" + end.tz('America/Los_Angeles').format('YYYY-MM-DD') + "/" + secondStart.tz('America/Los_Angeles').format('YYYY-MM-DD') + "/" + secondEnd.tz('America/Los_Angeles').format('YYYY-MM-DD');
	}
	$.ajax({
		type: "POST",
		dataType: "json",
		url: BASE_APP_URL + "index/dashboard" + appendingURL,
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
		error: function () {
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
		$('.chart-label-first').html(data['first_label']);
		$('.chart-label-second').html(data['second_label']);

		$('#user-chart').empty();
		$('#user_value').html($.number(data['user_chart']['first'], 0));
		$('#user_percent').html(html_percent(data['user_chart']['percent']));
		Morris.Line({
			element: 'user-chart',
			data: data['user_chart']['data'],
			hideHover: 'auto',
			xkey: 'label',
			ykeys: ['first', 'second'],
			labels: [data['first_label'], data['second_label']],
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
		$('#comment_value').html($.number(data['comment_chart']['first'], 0));
		$('#comment_percent').html(html_percent(data['comment_chart']['percent']));
		Morris.Line({
			element: 'comment-chart',
			data: data['comment_chart']['data'],
			hideHover: 'auto',
			xkey: 'label',
			ykeys: ['first', 'second'],
			labels: [data['first_label'], data['second_label']],
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
		$('#review_value').html($.number(data['comment_chart']['first'], 0));
		$('#review_percent').html(html_percent(data['comment_chart']['percent']));

		Morris.Line({
			element: 'review-chart',
			data: data['review_chart']['data'],
			hideHover: 'auto',
			xkey: 'label',
			ykeys: ['first', 'second'],
			labels: [data['first_label'], data['second_label']],
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
		$('#watched-blocks-body').empty().html(data['most_watched_blocks_data']);
		$('#reviewed-stories-body').empty().html(data['most_review_stories_data']);
		$('#liked-blocks-body').empty().html(data['most_liked_blocks_data']);
		$('#shared-stories-body').empty().html(data['most_shared_stories_data']);
		$('#commented-blocks-body').empty().html(data['most_commented_blocks_data']);
		$('#commented-stories-body').empty().html(data['most_commented_stories_data']);

		searchedChartConfig.data.datasets[0].data = data['top_searched']['data'];
		searchedChartConfig.data.labels = data['top_searched']['labels'];
		searchedChart.update();

		ageChartConfig.data.datasets[0].data = data['age_demographic'];
		ageChartConfig.data.labels = [
			'18-24',
			'25-34',
			'35-50',
			'50+',
//				'Undefined'
		];
		ageChart.update();

		$('#most-followed-body').empty().html(data['most_followers_data']);
	}
}

$('.chart-label-first').html(currentStartRange.tz('America/Los_Angeles').format('MMM DD, YYYY'));
$('.chart-label-second').html(currentSecondStartRange.tz('America/Los_Angeles').format('MMM DD, YYYY'));
reloadDashboard(currentStartRange, currentEndRange, currentSecondStartRange, currentSecondEndRange);