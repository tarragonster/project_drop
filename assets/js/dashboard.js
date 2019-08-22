moment.tz.add('America/Los_Angeles|PST PDT|80 70|0101|1Lzm0 1zb0 Op0');
const DATE_PICKER_OPTIONS = {toggleActive: true, format: 'MM dd, yyyy', autoclose: true}

var currentStartRange = moment().tz('America/Los_Angeles');
var currentEndRange = currentStartRange;
var currentSecondStartRange = currentStartRange.clone().subtract(1, 'days');

var currentSecondEndRange = currentSecondStartRange;
var $firstRangeLabel = $('#first_range');
var $comparedRangeLabel = $('#compared_range');
var $dashboardLoading = $('#dashboard-loading');
var $filterFromDate = $('#filter-from-date');
var $filterToDate = $('#filter-to-date');
var $dateRange = $('#date-range');

$firstRangeLabel.html('Today');
$comparedRangeLabel.html(currentSecondStartRange.format('MMM DD'));
$dateRange.datepicker(DATE_PICKER_OPTIONS);

function showDateRange(secondStart, secondEnd) {
	if (secondStart.year() == secondEnd.year()) {
		if (secondStart.month() == secondEnd.month() && secondStart.date() == secondEnd.date())
			return secondEnd.format('MMM DD, YYYY');
		else {
			return secondStart.format('MMM DD') + ' - ' + secondEnd.format('MMM DD, YYYY');
		}
	} else {
		return secondStart.format('MMM DD, YYYY') + ' - ' + secondEnd.format('MMM DD, YYYY');
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

$('.button-date-picker').on('click', function(e) {
	$('.filter-dropdown').toggle();
});

$('.button-cancel').on('click', function(e) {
	$('.filter-dropdown').toggle();
});

$('.button-apply').on('click', function(e) {
	if ($filterFromDate.val() == '' || $filterToDate.val() == '') {
		return;
	}
	$('.filter-dropdown').toggle();
	$('.preset-list li').removeClass('active');
	var start = moment($filterFromDate.val(), 'MMMM DD, YYYY');
	var end = moment($filterToDate.val(), 'MMMM DD, YYYY');
	var diff = end.diff(start, 'days') + 1;

	var secondStart = start.clone().subtract(diff, 'day');
	var secondEnd = end.clone().subtract(diff, 'day');

	$firstRangeLabel.html(showDateRange(start, end));
	$comparedRangeLabel.html(showDateRange(secondStart, secondEnd))
	reloadDashboard(start, end, secondStart, secondEnd);
});

$('.preset-list li').on('click', function(e) {
	$('.preset-list li').removeClass('active');
	$(this).addClass('active');
	var start = null;
	var end = null;
	var secondStart = null;
	var secondEnd = null;
	switch ($(this).data('range')) {
		case 'yesterday':
			start = moment().tz('America/Los_Angeles').subtract(1, 'days');
			end = start;
			secondStart = start.clone().subtract(1, 'days');
			secondEnd = secondStart;
			break;
		case 'this-week':
			start = moment().tz('America/Los_Angeles').startOf('isoweek');
			end = start.clone().add(6, 'day').endOf('day');
			secondStart = start.clone().subtract(1, 'week');
			secondEnd = secondStart.clone().add(6, 'day').endOf('day')
			break;
		case 'last-week':
			start = moment().tz('America/Los_Angeles').startOf('isoweek').subtract(1, 'week');
			end = start.clone().add(6, 'day').endOf('day');
			secondStart = start.clone().subtract(1, 'week');
			secondEnd = secondStart.clone().add(6, 'day').endOf('day')
			break;
		case 'this-month':
			start = moment().tz('America/Los_Angeles').startOf('month');
			end = start.clone().endOf('month');
			secondStart = start.clone().subtract(1, 'month').startOf('month');
			secondEnd = secondStart.clone().endOf('month')
			break;
		case 'last-month':
			start = moment().tz('America/Los_Angeles').subtract(1, 'month').startOf('month');
			end = start.clone().endOf('month');
			secondStart = start.clone().subtract(1, 'month').startOf('month');;
			secondEnd = secondStart.clone().endOf('month')
			break;
		default:
			start = moment().tz('America/Los_Angeles');
			end = start;
			secondStart = start.clone().subtract(1, 'days');
			secondEnd = secondStart;
			break;
	}
	// const DATE_FORMAT = 'MMMM DD, YYYY'; // 'YYYY-MM-DD'; //
	// console.log(start.format(DATE_FORMAT))
	// console.log(end.format(DATE_FORMAT))

	// $dateRange.datepicker('update', start.format(DATE_FORMAT), end.format(DATE_FORMAT));

	// $dateRange.datepicker('setStartDate', start.format(DATE_FORMAT));
	// $dateRange.datepicker('setEndDate', end.format(DATE_FORMAT));

	$dateRange.datepicker('remove');
	$filterFromDate.val(''); // start.format(DATE_FORMAT)
	$filterToDate.val(''); // end.format(DATE_FORMAT)
	$dateRange.datepicker(DATE_PICKER_OPTIONS);

	$firstRangeLabel.html($(this).text());
	$comparedRangeLabel.html(showDateRange(secondStart, secondEnd))
	reloadDashboard(start, end, secondStart, secondEnd);
	$('.filter-dropdown').toggle();
});

function reloadDashboard(start, end, secondStart, secondEnd) {
	var appendingURL = "";
	if (start != null) {
		appendingURL = "/" + start.format('YYYY-MM-DD') + "/" + end.format('YYYY-MM-DD') + "/" + secondStart.format('YYYY-MM-DD') + "/" + secondEnd.format('YYYY-MM-DD');
	}
	$dashboardLoading.show();
	$.ajax({
		type: "POST",
		dataType: "json",
		url: BASE_APP_URL + "index/dashboard" + appendingURL,
		data: {},

		success: function (data) {
			$dashboardLoading.hide();
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
			$dashboardLoading.hide();
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

$('.chart-label-first').html(currentStartRange.format('MMM DD, YYYY'));
$('.chart-label-second').html(currentSecondStartRange.format('MMM DD, YYYY'));
reloadDashboard(currentStartRange, currentEndRange, currentSecondStartRange, currentSecondEndRange);