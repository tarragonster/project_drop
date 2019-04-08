<div class="row">
	<div class="col-md-4">
		<div class="card-box chart-card-box">
			<div class="chart-content">
				<span class="chart-title">Users</span>
				<div class="chart-number-box">
					<span id="user_value" class="number-value"><?php echo number_format($user_chart['first']['value']); ?></span>

					<div id="user_percent" class="percent-value">
						<?= html_percent($user_chart['percent']) ?>
					</div>
					<div style="clear:both"></div>
				</div>
				<span class="chart-information">Users Over Time</span>
			</div>
			<div class="widget-chart text-center">
				<div id="user-chart" style="height:200px;width:100%;"></div>
			</div>
			<div class="chart-content">
				<div class="chart-label pull-right m-l-15">
					<div class="chart-mini-box" style="background-color: #c3cdd4"></div>
					<span class="chart-mini-label" id="user_label_second"><?= $user_chart['second']['label'] ?></span>
				</div>
				<div class="chart-label pull-right">
					<div class="chart-mini-box" style="background-color: #996cd9"></div>
					<span class="chart-mini-label" id="user_label_first"><?= $user_chart['first']['label'] ?></span>
				</div>
				<div style="clear:both"></div>
			</div>
		</div>
	</div>
</div>