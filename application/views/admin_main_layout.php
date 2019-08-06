<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
	<meta name="author" content="Coderthemes">

	<link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon_1.ico'); ?>">

	<title>10 Block cPanel</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">
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
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.4/css/all.css" >>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/node_modules/multiple-select/dist/multiple-select.min.css'); ?>">


	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<?php
	if (isset($customCss) && is_array($customCss)) {
		foreach ($customCss as $style) {
			echo '<link href="' . base_url($style) . '" rel="stylesheet" type="text/css" />' . "\n";
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
				<a href="<?php echo base_url('') ?>" class="logo"><i class="icon-magnet icon-c-logo"></i><span>10 Block</span></a>
			</div>
		</div>

		<!-- Button mobile view to collapse sidebar menu -->
		<div class="navbar navbar-default" role="navigation">
			<div class="container">
				<div class="">
					<div class="pull-left">
						<button class="button-menu-mobile open-left">
							<i class="ion-navicon"></i>
						</button>
						<span class="clearfix"></span>
					</div>
					<ul class="pull-right" style="margin-bottom: 0">
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true">
									<i class="fa fa-cog" style="font-size: 28px; line-height: 60px"></i>
								</a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo base_url('lockscreen'); ?>"><i class="ti-lock m-r-5"></i> Lock screen</a></li>
<!--									<li><a href="--><?php //echo base_url('logout'); ?><!--"><i class="ti-power-off m-r-5"></i> Logout</a></li>-->
								</ul>
							</li>
							
						</ul>
						<?php if(isset($sub_id) && $sub_id == 21):?>
							<li class="btn-export">
								<button type="button" class="btn">
									<a href="<?php echo base_url('user/exportUsers')?>">Export</a>
								</button>
							</li>
						<?php elseif(isset($sub_id) && $sub_id == 32):?>
							<li class="btn-export">
								<button type="button" class="btn">
									<a href="<?php echo base_url('product/exportFilms')?>">Export</a>
								</button>
							</li>
							<li class="btn-export">
								<button type="button" class="btn btn-header">
									<a href="<?php echo base_url('product/add')?>">Add Story</a>
								</button>
							</li>
						<?php elseif(isset($sub_id) && $sub_id == 51):?>
							<li class="btn-export">
								<button type="button" class="btn btn-header" id="add-genre-btn">Add Genre</button>
							</li>
						<?php endif;?>
					</ul>
				</div>
				<!--/.nav-collapse -->
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
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Dashboard-active.svg') ?>" alt="Dashboard">
                            <?php }else{ ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Dashboard-active.svg') ?>" alt="Dashboard">
                            <?php } ?>
                            <span>Dashboard</span></a>
					</li>
					<li>
						<a href="<?php echo base_url('user'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 2 ? ' active' : ''; ?>">
                            <?php if (isset($parent_id) && $parent_id == 2) { ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/User-active.svg') ?>" alt="Users">
                            <?php }else{ ?>
                                <img src= " <?php echo base_url('assets/images/left-sidebar/User.svg') ?>" alt="User">
                            <?php } ?>
                            <span>Users</span> </a>
					</li>
					<li>
						<a href="<?php echo base_url('product'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 3 ? ' active' : ''; ?>">
                            <?php if (isset($parent_id) && $parent_id == 3) { ?>
                                <img src= " <?php echo base_url('assets/images/left-sidebar/stories-active.svg') ?>" alt="Dashboard">
                            <?php }else{ ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/stories.svg') ?> " alt="Stories">
                            <?php } ?>
                            <span>Stories</span></a>
					</li>
					<li>
						<a href="<?php echo base_url('preview'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 10 ? ' active' : ''; ?>">
                            <?php if (isset($parent_id) && $parent_id == 10) { ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Explore-active.svg') ?> " alt="Explore">
                            <?php }else{ ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Explore.svg') ?>" alt="Explore">
                            <?php } ?>
                            <span>Explore</span></a>
					</li>
					<li>
						<a href="<?php echo base_url('collection'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 4 ? ' active' : ''; ?>">
                            <?php if (isset($parent_id) && $parent_id == 4) { ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Collections-active.svg') ?> " alt="Collections">
                            <?php }else{ ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Collections.svg') ?> " alt="Collections">
                            <?php } ?>
                            <span>Collections</span></a>
					</li>
					<li class="has_sub">
						<a href="#" class="waves-effect<?php echo isset($parent_id) && $parent_id == 9 ? ' active' : ''; ?>">
                            <?php if (isset($parent_id) && $parent_id == 9) { ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Comments-active.svg') ?>" alt="Comments">
                            <?php }else{ ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Comments.svg') ?>" alt="Comments">
                            <?php } ?>
                            <span>Comments</span> </a>
						<ul class="list-unstyled">
							<li <?php echo($sub_id == 91 ? 'class="active"' : ''); ?>>
								<a href="<?php echo base_url('comment'); ?>"><span>Comments</span></a>
							</li>
							<li <?php echo($sub_id == 92 ? 'class="active"' : ''); ?>>
								<a href="<?php echo base_url('comment/reports'); ?>">Reported Comments</a>
							</li>
						</ul>
					</li>
                    <li>
                        <a href="<?php echo base_url('setting'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 5 ? ' active' : ''; ?>">
                            <?php if (isset($parent_id) && $parent_id == 5) { ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Settings-active.svg') ?>" alt="Settings">
                            <?php }else{ ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Settings.svg') ?>" alt="Settings">
                            <?php } ?>

                            <span>Settings</span></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('logout'); ?>" class="waves-effect">
                            <?php if (isset($parent_id) && $parent_id == 1) { ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Logout-active.svg') ?>" alt="Logout">
                            <?php }else{ ?>
                                <img src= "<?php echo base_url('assets/images/left-sidebar/Logout.svg') ?>" alt="Logout">
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
<script src="<?php echo base_url('assets/js/jquery-ui.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.nicescroll.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.scrollTo.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap.min.js'); ?>"></script>

<script src="https://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>
<script src="<?php echo base_url('assets/css/node_modules/multiple-select/dist/multiple-select.min.js'); ?>"></script>

<?php
if (isset($customJs) && is_array($customJs)) {
	foreach ($customJs as $script) {
		echo '<script type="text/javascript" src="' . base_url() . $script . '"></script>' . "\n";
	}
}
?>
<script src="<?php echo base_url('assets/js/jquery.core.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.app.js'); ?>"></script>
<?= isset($bottom_html) ? $bottom_html : ''; ?>
</body>
</html>