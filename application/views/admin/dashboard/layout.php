<div class="progress dashboard-progress" style="display: none">
	<div class="dashboard-progress-bar" id='dashboard-progress-bar' style="width: 100%"></div>
</div>
<div class="row">
	<div class="col-md-2 col-xs-6">
		<a href="#">
			<h3 class="header-dashboard header-dark">Total User</h3>
			<h2 class="header-dashboard-num"><?= number_format($top_users, 0) ?></h2>
		</a>
	</div>
	<div class="col-md-6 col-xs-12 col-md-offset-3">

		<div class="pull-right m-l-10" style="padding-top: 8px; padding-bottom: 8px; font-size: 12px">
			compared to <span id="compared_range"></span>
		</div>

		<div id="reportrange" class="pull-right daterange-analytic" style="background: white">
			<i class="icon-calender"></i>
			<span></span>
		</div>
	</div>
	<div class="col-md-1">
		<img class="m-l-10" src="<?= base_url("assets/imgs/spinner.svg") ?>" width="33px" style="display: none" id="dashboard-loading">
	</div>
</div>

<div id="dashboard_box">
	<?php $this->load->view('admin/dashboard/template'); ?>
</div>
<input type="hidden" id="unix_timestamp" value="<?= time() ?>"/>