<script>

	Morris.Line({
		element: 'user-chart',
		data: <?=json_encode($user_chart['data'])?>,
		hideHover: 'auto',
		xkey: 'label',
		ykeys: ['first', 'second'],
		labels: ["<?= $user_chart['first']['label']?>", "<?= $user_chart['second']['label']?>"],
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

	Morris.Line({
		element: 'comment-chart',
		data: <?=json_encode($comment_chart['data'])?>,
		hideHover: 'auto',
		xkey: 'label',
		ykeys: ['first', 'second'],
		labels: ["<?= $comment_chart['first']['label']?>", "<?= $comment_chart['second']['label']?>"],
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

	Morris.Line({
		element: 'review-chart',
		data: <?=json_encode($review_chart['data'])?>,
		hideHover: 'auto',
		xkey: 'label',
		ykeys: ['first', 'second'],
		labels: ["<?= $review_chart['first']['label']?>", "<?= $review_chart['second']['label']?>"],
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



	var randomScalingFactor = function() {
		return Math.round(Math.random() * 100);
	};

	var searchedChartConfig = {
		type: 'pie',
		data: {
			datasets: [{
				data: [
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
				],
				backgroundColor: [
					'#4A81D4',
					'#F4F8FB',
					'#2FBC9B',
					'#28292C',
					'#C7AE6E',
				]
			}],
			labels: [
				'Action',
				'3 Beauties',
				'Marc Lopez',
				'Docuseries',
				'Clip'
			]
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
				data: [
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor(),
					randomScalingFactor()
				],
				backgroundColor: [
					'#4A81D4',
					'#F4F8FB',
					'#2FBC9B',
					'#28292C'
				]
			}],
			labels: [
				'18-24',
				'25-34',
				'35-50',
				'50+'
			]
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

	window.ageChart = new Chart(document.getElementById('chart-searched').getContext('2d'), searchedChartConfig);

	window.searchedChart = new Chart(document.getElementById('chart-age').getContext('2d'), ageChartConfig);

</script>