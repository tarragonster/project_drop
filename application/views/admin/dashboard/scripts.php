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

</script>