<div class="progress dashboard-progress" style="display: none">
	<div class="dashboard-progress-bar" id='dashboard-progress-bar' style="width: 100%"></div>
</div>
<div class="row">
	<div class="col-lg-2 col-md-4 col-xs-6">
		<h3 class="header-dashboard">Total Users</h3>
		<h2 class="header-dashboard-num"><?= number_format($top_users, 0) ?></h2>
	</div>
	<div class="col-lg-2 col-md-4 col-xs-6">
		<h3 class="header-dashboard header-dashboard-long">Total Blocked Watched</h3>
		<h2 class="header-dashboard-num"><?= number_format($top_watched, 0) ?></h2>
	</div>
	<div class="col-lg-2 col-md-4 col-xs-6">
		<h3 class="header-dashboard">Total Comments</h3>
		<h2 class="header-dashboard-num"><?= number_format($top_comments, 0) ?></h2>
	</div>
	<div class="col-lg-2 col-md-4 col-xs-6">
		<h3 class="header-dashboard">Total Reviews</h3>
		<h2 class="header-dashboard-num"><?= number_format($top_reviews, 0) ?></h2>
	</div>
	<div class="col-lg-2 col-md-4 col-xs-6">
		<h3 class="header-dashboard">Total Stories</h3>
		<h2 class="header-dashboard-num"><?= number_format($top_stories, 0) ?></h2>
	</div>
	<div class="col-lg-2 col-md-4 col-xs-6">
		<h3 class="header-dashboard">Total Blocks</h3>
		<h2 class="header-dashboard-num"><?= number_format($top_blocks, 0) ?></h2>
	</div>
</div>

<div id="dashboard_box">
	<?php $this->load->view('admin/dashboard/template'); ?>
</div>
<input type="hidden" id="unix_timestamp" value="<?= time() ?>"/>