<div class="row">
	<div class="col-md-4">
		<div class="card-box chart-card-box">
			<div class="chart-content">
				<span class="chart-title">Users</span>
				<div class="chart-number-box">
					<span id="user_value" class="number-value"><?= number_format($user_chart['first']['value']); ?></span>

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
					<span class="chart-mini-label chart-label-second"><?= $user_chart['second']['label'] ?></span>
				</div>
				<div class="chart-label pull-right">
					<div class="chart-mini-box" style="background-color: #996cd9"></div>
					<span class="chart-mini-label chart-label-first"><?= $user_chart['first']['label'] ?></span>
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
					<span id="comment_value" class="number-value"><?= number_format($comment_chart['first']['value']); ?></span>

					<div id="comment_percent" class="percent-value">
						<?= html_percent($comment_chart['percent']) ?>
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
					<span class="chart-mini-label chart-label-second"><?= $comment_chart['second']['label'] ?></span>
				</div>
				<div class="chart-label pull-right">
					<div class="chart-mini-box" style="background-color: #996cd9"></div>
					<span class="chart-mini-label chart-label-first"><?= $comment_chart['first']['label'] ?></span>
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
					<span id="review_value" class="number-value"><?= number_format($review_chart['first']['value']); ?></span>

					<div id="review_percent" class="percent-value">
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
					<span class="chart-mini-label chart-label-second"><?= $review_chart['second']['label'] ?></span>
				</div>
				<div class="chart-label pull-right">
					<div class="chart-mini-box" style="background-color: #996cd9"></div>
					<span class="chart-mini-label chart-label-first"><?= $review_chart['first']['label'] ?></span>
				</div>
				<div style="clear:both"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Watched Blocks</span>
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
				<?php foreach ($most_watched_blocks as $key => $row) : ?>
					<tr>
						<td><?= $key + 1 ?>. <?= $row['name']?></td>
						<td><?= empty($row['story_name']) ? 'Deleted' : $row['story_name']?></td>
						<td><?= number_format($row['watched'], 0)?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Reviewed Stories</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Story</th>
					<th>Reviews</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($most_review_stories as $key => $row) : ?>
					<tr>
						<td><?= $key + 1 ?>. <?= $row['name']?></td>
						<td><?= number_format($row['num_of_reviewed'], 0)?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Liked Blocks</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Block</th>
					<th>Story</th>
					<th>Likes</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($most_liked_blocks as $key => $row) : ?>
					<tr>
						<td><?= $key + 1 ?>. <?= $row['name']?></td>
						<td><?= empty($row['story_name']) ? 'Deleted' : $row['story_name']?></td>
						<td><?= number_format($row['num_of_like'], 0)?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Shared Stories</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Story</th>
					<th>Shares</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($most_shared_stories as $key => $row) : ?>
					<tr>
						<td><?= $key + 1 ?>. <?= $row['name']?></td>
						<td><?= number_format($row['num_of_shared'], 0)?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Commented Blocks</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Block</th>
					<th>Story</th>
					<th>Comments</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($most_commented_blocks as $key => $row) : ?>
					<tr>
						<td><?= $key + 1 ?>. <?= $row['name']?></td>
						<td><?= empty($row['story_name']) ? 'Deleted' : $row['story_name']?></td>
						<td><?= number_format($row['num_of_comments'], 0)?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card-box table-card-box">
			<div class="chart-content">
				<span class="chart-title">Most-Commented Stories</span>
			</div>
			<table class="table table-responsive">
				<thead>
				<tr>
					<th>Story</th>
					<th>Reviews</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($most_commented_stories as $key => $row) : ?>
					<tr>
						<td><?= $key + 1 ?>. <?= $row['name']?></td>
						<td><?= number_format($row['num_of_comments'], 0)?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
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
			<canvas id="chart-age" style="max-width: 450px; margin: 25px auto 10px"></canvas>
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
				<?php foreach ($most_followers as $key => $row) : ?>
				<tr>
					<td><?= $key + 1 ?>. <?= $row['full_name'] ?> - @<?= $row['user_name'] ?></td>
					<td><?= number_format(empty($row['num_of_followers']) ? 0 : $row['num_of_followers'], 0) ?></td>
					<td><?= number_format(empty($row['new_followers']) ? 0 : $row['new_followers'], 0) ?></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>