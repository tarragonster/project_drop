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
	<div class="col-md-4">
		<div class="card-box chart-card-box">
			<div class="chart-content">
				<span class="chart-title">Comments</span>
				<div class="chart-number-box">
					<span id="user_value" class="number-value"><?php echo number_format($user_chart['first']['value']); ?></span>

					<div id="user_percent" class="percent-value">
						<?= html_percent($user_chart['percent']) ?>
					</div>
					<div style="clear:both"></div>
				</div>
				<span class="chart-information">Comments Over Time</span>
			</div>
			<div class="widget-chart text-center">
				<div id="comment-chart" style="height:200px;width:100%;"></div>
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
	<div class="col-md-4">
		<div class="card-box chart-card-box">
			<div class="chart-content">
				<span class="chart-title">Reviews</span>
				<div class="chart-number-box">
					<span id="user_value" class="number-value"><?php echo number_format($user_chart['first']['value']); ?></span>

					<div id="user_percent" class="percent-value">
						<?= html_percent($user_chart['percent']) ?>
					</div>
					<div style="clear:both"></div>
				</div>
				<span class="chart-information">Reviews Over Time</span>
			</div>
			<div class="widget-chart text-center">
				<div id="review-chart" style="height:200px;width:100%;"></div>
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
<div class="row">
	<?php for ($i = 0; $i < 6; $i++) : ?>
		<div class="col-md-6">
			<div class="card-box table-card-box">
				<div class="chart-content">
					<span class="chart-title">
						<?php
						switch ($i) {
							case 0;
								echo 'Most-Watched Blocks';
								break;
							case 1;
								echo 'Most-Reviewed Stories';
								break;
							case 2;
								echo 'Most-Liked Blocks';
								break;
							case 3;
								echo 'Most-Shared Stories';
								break;
							case 4;
								echo 'Most-Commented Blocks';
								break;
							case 5;
								echo 'Most-Commented Stories';
								break;
						}
						?>
					</span>
				</div>
				<table class="table table-responsive">
					<thead>
					<tr>
						<th>Block</th>
						<th>Story</th>
						<th>Watched</th>
					</tr>
					</thead>
					<tbody>
					<?php for ($j = 0; $j < 5; $j++) : ?>
						<tr>
							<td><?= $j + 1 ?>. Block</td>
							<td>Story</td>
							<td>1</td>
						</tr>
					<?php endfor; ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endfor; ?>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="card-box chart-card-box">
			<div class="chart-content">
				<span class="chart-title">Top Searched Terms</span>
			</div>
			<canvas id="chart-searched" style="max-width: 450px; margin: 25px auto 10px"></canvas>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card-box chart-card-box">
			<div class="chart-content">
				<span class="chart-title">Age Demographic</span>
			</div>
			<canvas id="chart-age"  style="max-width: 450px; margin: 25px auto 10px"></canvas>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Followed User</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Username</th>
					<th>Total Followers</th>
					<th>New Followers</th>
				</tr>
				</thead>
				<tbody>
				<?php for ($j = 0; $j < 10; $j++) : ?>
					<tr>
						<td><?= $j + 1 ?>. Full Name - @username</td>
						<td><?=rand(0, 1000)?></td>
						<td><?=rand(0, 100)?></td>
					</tr>
				<?php endfor; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>