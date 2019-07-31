<div class="header-genre">
	<div class="row">
		<div class="col-sm-1 col-lg-1" style="padding: 0px"></div>
		<div class="col-sm-2 col-lg-2" style="padding: 0px">
			<div class="title-header">Genre ID</div>
		</div>
		<div class="col-sm-3 col-lg-3">
			<div class="title-header">Genre Name</div>
		</div>
		<div class="col-sm-2 col-lg-2">
			<div class="title-header"># of Stories</div>
		</div>
		<div class="col-sm-2 col-lg-2">
			<div class="title-header">Created Date</div>
		</div>
		<div class="col-sm-1 col-lg-1">
			<div class="title-header">Status</div>
		</div>
		<div class="col-sm-1 col-lg-1">
			<div class="title-header">Action</div>
		</div>
	</div>
</div>
<div class="card-box genre-mess-box">
	<span>Click <b>Add Genre</b> to add a new genreto 10 block</span>
</div>

<div class="modal custom-modal below-header fade visiting right" id="add-genre-popup" tabindex="-1" role="dialog">
	<form action="<?php echo base_url('genre/addGenre')?>" method="post" id="genre-form" enctype="multipart/form-data">
		<div class="modal-dialog" role="document">
			<div class="modal-content" id="add-genre-form" style="padding: 0px">
				
			</div>
		</div>
	</form>
</div>