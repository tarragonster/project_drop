<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
	<meta name="author" content="Coderthemes">

	<link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon_1.ico'); ?>">

	<title>Second Screen cPanel</title>

	<link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/core.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/components.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/icons.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/pages.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/css/responsive.css'); ?>" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url('assets/plugins/datatables/dataTables.bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/>

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
				<a href="<?php echo base_url('') ?>" class="logo"><i class="icon-magnet icon-c-logo"></i><span>Second Screen</span></a>
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
					<ul class="pull-right">
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true">
									<i class="fa fa-cog"></i>
								</a>
								<ul class="dropdown-menu">
									<li><a href="<?php echo base_url('lockscreen'); ?>"><i class="ti-lock m-r-5"></i> Lock screen</a></li>
									<li><a href="<?php echo base_url('logout'); ?>"><i class="ti-power-off m-r-5"></i> Logout</a></li>
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
								<a href="<?php echo base_url('product/add')?>">Add Film</a>
							</button>
						</li>
						<li class="btn-export">
							<button type="button" class="btn">
								<a href="<?php echo base_url('product/exportFilms')?>">Export</a>
							</button>
						</li>
						<?php else:?>
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
						<a href="<?php echo base_url(''); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 1 ? ' active' : ''; ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
					</li>
					<li class="has_sub">
						<a href="#" class="waves-effect<?php echo isset($parent_id) && $parent_id == 2 ? ' active' : ''; ?>"> <i class="fa fa-users"></i> <span>Users</span> </a>
						<ul class="list-unstyled">
							<li <?php echo($sub_id == 21 ? 'class="active"' : ''); ?>>
								<a href="<?php echo base_url('user'); ?>"><span>Active Users</span></a>
							</li>
							<li <?php echo($sub_id == 23 ? 'class="active"' : ''); ?>>
								<a href="<?php echo base_url('user/reports'); ?>">Reported Users</a>
							</li>
							<li <?php echo($sub_id == 24 ? 'class="active"' : ''); ?>>
								<a href="<?php echo base_url('featured'); ?>">Featured Users</a>
							</li>
							<li <?php echo($sub_id == 25 ? 'class="active"' : ''); ?>>
								<a href="<?php echo base_url('user/signups'); ?>">Newsletter Signups</a>
							</li>

						</ul>
					</li>
					<li class="has_sub">
						<a href="#" class="waves-effect<?php echo isset($parent_id) && $parent_id == 3 ? ' active' : ''; ?>">
							<i class="fa fa-sitemap"></i> <span>Films</span>
						</a>
						<ul class="list-unstyled">
							<li <?php echo($sub_id == 31 ? 'class="active"' : ''); ?>><a href="<?php echo base_url('product/add'); ?>">Add Film</a></li>
							<li <?php echo($sub_id == 32 ? 'class="active"' : ''); ?>><a href="<?php echo base_url('product'); ?>">List Films</a></li>
						</ul>
					</li>
					<li>
						<a href="<?php echo base_url('season'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 7 ? ' active' : ''; ?>"><i class="fa fa-ship"></i> <span>Seasons</span></a>
					</li>
					<li>
						<a href="<?php echo base_url('preview'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 10 ? ' active' : ''; ?>"><i class="fa fa-industry"></i>
							<span>Explore Previews</span></a>
					</li>
					<li>
						<a href="<?php echo base_url('collection'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 4 ? ' active' : ''; ?>"><i class="fa fa-industry"></i>
							<span>Collections</span></a>
					</li>
					<li>
						<a href="<?php echo base_url('actor'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 5 ? ' active' : ''; ?>"><i class="fa fa-male"></i> <span>Actors</span></a>
					</li>
					<li>
						<a href="<?php echo base_url('music'); ?>" class="waves-effect<?php echo isset($parent_id) && $parent_id == 6 ? ' active' : ''; ?>"><i class="fa fa-music"></i> <span>Musics</span></a>
					</li>
					<li class="has_sub">
						<a href="#" class="waves-effect<?php echo isset($parent_id) && $parent_id == 9 ? ' active' : ''; ?>"> <i class="ti-comment"></i> <span>Comments</span> </a>
						<ul class="list-unstyled">
							<li <?php echo($sub_id == 91 ? 'class="active"' : ''); ?>>
								<a href="<?php echo base_url('comment'); ?>"><span>Comments</span></a>
							</li>
							<li <?php echo($sub_id == 92 ? 'class="active"' : ''); ?>>
								<a href="<?php echo base_url('comment/reports'); ?>">Reported Comments</a>
							</li>
						</ul>
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
		<footer class="footer text-right">
			2018 © <a href="http://secondscreentv.us">www.secondscreentv.us</a>
		</footer>

	</div>

</div>
<!-- END wrapper -->


<script>
	var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/detect.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/fastclick.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.slimscroll.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.blockUI.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/waves.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/wow.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.nicescroll.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.scrollTo.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>


<script src="https://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>

<script src="<?php echo base_url('assets/js/jquery.core.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.app.js'); ?>"></script>

<?php
if (isset($customJs) && is_array($customJs)) {
	foreach ($customJs as $script) {
		echo '<script type="text/javascript" src="' . base_url() . $script . '"></script>' . "\n";
	}
}
?>
<script type="text/javascript">

	$(document).ready(function(){
	    var stt = $('select option:selected').val();
    	$.get("<?php echo base_url('user/getUsersByStatus')?>", {status:stt}, function(data){
	    	$('#user_table').html(data);
	    });

    	$('.status').change(function(){
		    stt = $(this).val();
			$.get("<?php echo base_url('user/getUsersByStatus')?>", {status:stt}, function(data){
		    	$('#user_table').html(data);
		    });
		});
	});
</script>
</body>
</html>