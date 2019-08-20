<div class="progress dashboard-progress" style="display: none">
	<div class="dashboard-progress-bar" id='dashboard-progress-bar' style="width: 100%"></div>
</div>
<div class="row">
	<div class="col-md-2 col-xs-6">
		<a href="#">
			<h3 class="header-dashboard header-dark">Total Users</h3>
			<h2 class="header-dashboard-num"><?= number_format($top_users, 0) ?></h2>
		</a>
	</div>
	<div class="col-md-2 col-xs-6">
		<a href="#">
			<h3 class="header-dashboard header-dark">Total Blocked Watched</h3>
			<h2 class="header-dashboard-num"><?= number_format($top_users, 0) ?></h2>
		</a>
	</div>
	<div class="col-md-2 col-xs-6">
		<a href="#">
			<h3 class="header-dashboard header-dark">Total Comments</h3>
			<h2 class="header-dashboard-num"><?= number_format($top_users, 0) ?></h2>
		</a>
	</div>
	<div class="col-md-2 col-xs-6">
		<a href="#">
			<h3 class="header-dashboard header-dark">Total Reviews</h3>
			<h2 class="header-dashboard-num"><?= number_format($top_users, 0) ?></h2>
		</a>
	</div>
	<div class="col-md-2 col-xs-6">
		<a href="#">
			<h3 class="header-dashboard header-dark">Total Stories</h3>
			<h2 class="header-dashboard-num"><?= number_format($top_users, 0) ?></h2>
		</a>
	</div>
	<div class="col-md-2 col-xs-6">
		<a href="#">
			<h3 class="header-dashboard header-dark">Total Blocks</h3>
			<h2 class="header-dashboard-num"><?= number_format($top_users, 0) ?></h2>
		</a>
	</div>
</div>

<div id="dashboard_box">
	<?php $this->load->view('admin/dashboard/template'); ?>
</div>
<input type="hidden" id="unix_timestamp" value="<?= time() ?>"/>