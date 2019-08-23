<div class="header-genre">
	<div class="row">
		<div class="col-sm-1 col-lg-2" style="padding: 0px"></div>
		<div class="col-sm-2 col-lg-1" style="padding: 0px">
			<div class="title-header">Story ID</div>
		</div>
		<div class="col-sm-3 col-lg-3">
			<div class="title-header">Story Name</div>
		</div>
		<div class="col-sm-2 col-lg-2">
			<div class="title-header">Trending Activity</div>
		</div>
		<div class="col-sm-2 col-lg-2">
			<div class="title-header">Date Added to Trending</div>
		</div>
		<div class="col-sm-1 col-lg-1">
			<div class="title-header">Status</div>
		</div>
		<div class="col-sm-1 col-lg-1">
			<div class="title-header">Actions</div>
		</div>
	</div>
</div>
<div class="card-box explore-mess-box">
	<span>Click <b>Add Story</b> to add a user to the Trending section</span>
</div>

<div class="modal custom-modal below-header fade visiting right" id="add-user-popup" tabindex="-1" role="dialog">
	<form action="<?php echo base_url('explore/addPreviewStory')?>" method="post" id="user-form-add" enctype="multipart/form-data">
		<div class="modal-dialog" role="document">
			<div class="modal-content" id="add-user-form" style="padding: 0px">
				
			</div>
		</div>
	</form>
</div>