<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
	<meta name="author" content="Coderthemes">

	<link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon_1.ico'); ?>">

	<title>10 Block cPanel</title>

	<link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/core.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/components.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/icons.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/pages.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/responsive.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/font-awesome.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/app/core-table/coreTable.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('module/css/admin_main_layout.css'); ?>" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/multiselect/css/jquery.multiselect.css') ?>">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.4/css/all.css">

	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<?php
	if (isset($customCss) && is_array($customCss)) {
		foreach ($customCss as $style) {
			echo '<link href="' . (startsWith($style, 'http') ? $style : base_url($style)) . '" rel="stylesheet" type="text/css" />' . "\n";
		}
	}
	?>
	<script src="<?php echo base_url('assets/js/modernizr.min.js'); ?>"></script>
	<script>
		var BASE_APP_URL = "<?= base_url()?>"
	</script>
</head>

<body class="fixed-left">

<div class="animationload">
	<div class="loader"></div>
</div>

<!-- Begin page -->
<div id="wrapper">

	<!-- Top Bar Start -->
	<div class="topbar">

		<!-- LOGO -->
		<div class="topbar-left">
			<div class="text-center">
				<a href="<?php echo base_url('') ?>" class="logo"><span>10 Block</span></a>
			</div>
		</div>

		<!-- Button mobile view to collapse sidebar menu -->
		<div class="navbar navbar-default" role="navigation">
			<div class="container">
				<?php if (isset($sub_id) && $sub_id == 10): ?>
					<div class="date-range-picker">
						<div class="pull-left daterange-analytic button-date-picker">
							<i class="fa fa-filter"></i>
							<span id="first_range"></span>
						</div>
						<div class="compared-box">
							&nbsp;&nbsp;compared to <span id="compared_range"></span><img class="m-l-5" src="<?= base_url("assets/imgs/spinner-v2.svg") ?>" width="27px" style="display: none" id="dashboard-loading">
						</div>
					</div>

					<div class="dropdown-menu opensright filter-dropdown" style="top: 48px; left: 250px;">
						<div class="ranges filter-popup">
							<div class="filter-header">
								<button class="button-cancel">Cancel</button>
								<h3>Filter</h3>
								<button class="button-apply">Apply</button>
							</div>
							<div class="filter-section">Filter Date</div>
							<div class="input-daterange" id="date-range">
								<input type="text" class="form-control" id="filter-from-date" name="start" placeholder="From" autocomplete="off">
								<div class="line"></div>
								<input type="text" class="form-control" id="filter-to-date" name="end" placeholder="To" autocomplete="off">
							</div>
							<div class="filter-section">Presets</div>
							<ul class="preset-list">
								<li data-range="today" class="active">Today</li>
								<li data-range="yesterday">Yesterday</li>
								<li data-range="this-week">This week</li>
								<li data-range="last-week">Last week</li>
								<li data-range="this-month">This month</li>
								<li data-range="last-month">Last month</li>
							</ul>
						</div>
					</div>
				<?php elseif (isset($sub_id) && $sub_id == 31): ?>
					<li class="btn-export">
						<button type="button" class="btn btn-header">
							<a href="<?php echo base_url('product/add') ?>">Add Story</a>
						</button>
					</li>
				<?php elseif (isset($sub_id) && $sub_id == 34): ?>
					<li class="btn-export">
						<button type="button" class="btn btn-header">
							<a href="" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Add New&nbsp;&nbsp;<i class="fa fa-chevron-down"></i></a>
							<ul class="dropdown-menu" role="menu" style="min-width: 87px;left: unset;top: 46px;">
								<li class="add-season-btn" data-product="<?php echo $this->session->userdata('product_id') ?>">
									<a href="">Add season</a>
								</li>
								<li class="add-block-btn" data-product="<?php echo $this->session->userdata('product_id') ?>" data-toggle="modal" data-target="#add-block-popup">
									<a href="">Add block</a>
								</li>
							</ul>
						</button>
					</li>
				<?php elseif (isset($sub_id) && $sub_id == 51): ?>
					<li class="btn-export">
						<button type="button" class="btn btn-header" id="add-genre-btn">Add Genre</button>
					</li>
				<?php elseif (isset($sub_id) && $sub_id == 21): ?>
					<li class="btn-export">
						<div class="dropdown view-status-outer">
							<button type="button" class="btn btn-header dropdown-toggle" id="view-status" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?php if(!empty($conditions['get_status']) && $conditions['get_status'] == 'enabled'){ ?>
                                    <span class="status-text">View Enabled</span>
                                <?php }elseif(!empty($conditions['get_status']) && $conditions['get_status'] == 'disabled'){ ?>
                                    <span class="status-text">View Disabled</span>
                                <?php }else{ ?>
                                    <span class="status-text">View All Statuses</span>
                                <?php } ?>
								<i class="fal fa-chevron-down" style="position: absolute;right: 6px;top: 6px;"></i>
							</button>
							<ul class="dropdown-menu" aria-labelledby="view-status">
                                <?php $get_status = isset($conditions['get_status']) ? $conditions['get_status'] : ''; ?>
								<li class="dropdown-item"><a href="<?php echo base_url('user') ?>">All Statuses</a></li>
								<li class="dropdown-item"><a href="<?php echo base_url('user') . '?get_status=' . $get_status='enabled' ?>">Enabled</a></li>
								<li class="dropdown-item"><a href="<?php echo base_url('user') . '?get_status=' . $get_status='disabled' ?>">Disabled</a></li>
							</ul>
						</div>
					</li>
				<?php elseif (isset($sub_id) && $sub_id == 11): ?>
					<li class="btn-export">
						<button type="button" class="btn btn-header" id="add-user" data-toggle="modal" data-target="#add-user-popup">Add User</button>
					</li>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<!-- Top Bar End -->

	<!-- ========== Left Sidebar Start ========== -->

	<div class="left side-menu">
		<div class="sidebar-inner slimscrollleft">
			<!--- Divider -->
			<div id="sidebar-menu">
				<ul>
					<li>
						<a href="<?php echo base_url(''); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 1 ? ' active' : ''; ?>">
							<?php if (isset($parent_id) && $parent_id == 1) { ?>
								<img src="<?php echo base_url('assets/images/left-sidebar/Dashboard-active.svg') ?>" alt="Dashboard">
							<?php } else { ?>
								<img src="<?php echo base_url('assets/images/left-sidebar/Dashboard-active.svg') ?>" alt="Dashboard">
							<?php } ?>
							<span>Dashboard</span></a>
					</li>
					<li>
						<a href="<?php echo base_url('user'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 2 ? ' active' : ''; ?>">
							<?php if (isset($parent_id) && $parent_id == 2) { ?>
								<img src="<?php echo base_url('assets/images/left-sidebar/User-active.svg') ?>" alt="Users">
							<?php } else { ?>
								<img src=" <?php echo base_url('assets/images/left-sidebar/User.svg') ?>" alt="User">
							<?php } ?>
							<span>Users</span> </a>
					</li>
					<li>
						<a href="<?php echo base_url('product'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 3 ? ' active' : ''; ?>">
							<?php if (isset($parent_id) && $parent_id == 3) { ?>
								<img src=" <?php echo base_url('assets/images/left-sidebar/stories-active.svg') ?>" alt="Dashboard">
							<?php } else { ?>
								<img src="<?php echo base_url('assets/images/left-sidebar/stories.svg') ?> " alt="Stories">
							<?php } ?>
							<span>Stories</span></a>
					</li>
					<li>
						<a href="<?php echo base_url('explore'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 10 ? ' active' : ''; ?>">
							<?php if (isset($parent_id) && $parent_id == 10) { ?>
								<img src="<?php echo base_url('assets/images/left-sidebar/Explore-active.svg') ?> " alt="Explore">
							<?php } else { ?>
								<img src="<?php echo base_url('assets/images/left-sidebar/Explore.svg') ?>" alt="Explore">
							<?php } ?>
							<span>Explore</span></a>
					</li>
					<li>
						<a href="<?php echo base_url('collection/carousel'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 4 ? ' active' : ''; ?>">
							<?php if (isset($parent_id) && $parent_id == 4) { ?>
								<img src="<?php echo base_url('assets/images/left-sidebar/Collections-active.svg') ?> " alt="Collections">
							<?php } else { ?>
								<img src="<?php echo base_url('assets/images/left-sidebar/Collections.svg') ?> " alt="Collections">
							<?php } ?>
							<span>Collections</span></a>
					</li>
					<li>
						<a href="<?php echo base_url('comment/stories'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 9 ? ' active' : ''; ?>">
                            <?php if (isset($parent_id) && $parent_id == 9) { ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Comments-active.svg') ?>" alt="Comments">
                            <?php }else{ ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Comments.svg') ?>" alt="Comments">
                            <?php } ?>
                            <span>Comments</span> </a>
					</li>
					<li>
						<a href="<?php echo base_url('setting'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 5 ? ' active' : ''; ?>">
							<?php if (isset($parent_id) && $parent_id == 5) { ?>
								<img src="<?php echo base_url('assets/images/left-sidebar/Settings-active.svg') ?>" alt="Settings">
							<?php } else { ?>
								<img src="<?php echo base_url('assets/images/left-sidebar/Settings.svg') ?>" alt="Settings">
							<?php } ?>

							<span>Settings</span></a>
					</li>
					<li>
						<a href="<?php echo base_url('logout'); ?>" class="waves-effect">
							<?php if (isset($parent_id) && $parent_id == 1) { ?>
								<img src="<?php echo base_url('assets/images/left-sidebar/Logout-active.svg') ?>" alt="Logout">
							<?php } else { ?>
								<img src="<?php echo base_url('assets/images/left-sidebar/Logout.svg') ?>" alt="Logout">
							<?php } ?>
							<span>Log Out</span></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<!-- Left Sidebar End -->

	<!-- ============================================================== -->
	<!-- Start right Content here -->
	<!-- ============================================================== -->
	<div class="content-page">
		<!-- Start content -->
		<div class="content" ng-app="project">
			<div class="container">
				<?php
				echo isset($content) ? $content : 'Empty page';
				?>
			</div> <!-- container -->
		</div> <!-- content -->
		<!-- <footer class="footer text-right">
			2018 © <a href="http://secondscreentv.us">www.secondscreentv.us</a>
		</footer> -->

	</div>

</div>
<!-- END wrapper -->

<script>
	var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="<?php echo base_url('assets/js/jquery-2.1.4.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/detect.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/fastclick.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.slimscroll.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.blockUI.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/waves.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/wow.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.nicescroll.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.scrollTo.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/multiselect/js/jquery.multiselect.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery-ui.js') ?>"></script>

<script src="https://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>

<?php
if (isset($customJs) && is_array($customJs)) {
	foreach ($customJs as $script) {
		echo '<script type="text/javascript" src="' . (startsWith($script, 'http') ? $script : base_url() . $script) . '"></script>' . "\n";
	}
}
?>
<script src="<?php echo base_url('assets/js/jquery.core.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.app.js'); ?>"></script>
<?= isset($bottom_html) ? $bottom_html : ''; ?>

</body>
</html>
<script>
    function getStatus(){
        $('.status-text').text('View All Statuses')
    }
    function getEnabled(){
        $('.status-text').text('View Enabled')
    }
    function getDisabled(){
        $('.status-text').text('View Disabled')
    }
</script>