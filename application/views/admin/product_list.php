<?php
if ($this->session->flashdata('alert')) {
	echo '<div class="row"><div class="col-xs-12 alert alert-success">';
	echo $this->session->flashdata('alert');
	echo '</div></div>';
}
if($this->session->flashdata('error')){
	echo '<div class="col-xs-12"><div class="alert alert-danger">';
	echo $this->session->flashdata('error');
	echo '</div></div>';
}
?>
<div class="search-container">
	<i class="fa fa-search"></i>
	<input type="search" id="search_text" class="form-control" placeholder="Search Films" onkeyup="searchFilm();">
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="card-box table-responsive">
				<div class="filter-container">
					<label>Status:</label>
					<select class="form-control status-film">
						<option value="2" selected>All</option>
						<option value="1">Enable Films</option>
						<option value="0">Disable Films</option>
					</select>
				</div>
				<div id="product_table">
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="dis-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-img">
					<img src="<?php echo base_url('assets/images/exclamation.jpg')?>">
				</div>
				<div class="modal-description">
					<h2>Are You Sure?</h2>
					<p>Are you sure you want to disable this film? You will be able to undo this in the actions section.</p>
				</div>
				<div class="modal-button">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;color: #FFFFFF;">Cancel</button>
					<button type="button" class="btn btn-warning dis-confirm">Disable</button>
				</div>	
			</div>
		</div>
	</div>
	<div class="modal fade" id="en-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-img">
					<img src="<?php echo base_url('assets/images/exclamation.jpg')?>">
				</div>
				<div class="modal-description">
					<h2>Are You Sure?</h2>
					<p>Are you sure you want to enable this film? You will be able to undo this in the actions section.</p>
				</div>
				<div class="modal-button">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;color: #FFFFFF;">Cancel</button>
					<button type="submit" class="btn btn-warning en-confirm">Enable</button>
				</div>	
			</div>
		</div>
	</div>
	<div class="modal fade" id="del-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-img">
					<img src="<?php echo base_url('assets/images/quit.jpg')?>">
				</div>
				<div class="modal-description">
					<h2>Are You Sure?</h2>
					<p>Are you sure you want to delete this film? You will not be able to undo this.</p>
				</div>
				<div class="modal-button">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;color: #FFFFFF;">Cancel</button>
					<button class="btn btn-danger" data-toggle="modal" data-target="#confirm-del-modal" data-dismiss="modal" >Delete</button>
				</div>	
			</div>
		</div>
	</div>
	<div class="modal fade" id="confirm-del-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-img">
					<img src="<?php echo base_url('assets/images/quit.jpg')?>">
				</div>
				<div class="modal-description">
					<h2>Are You Sure?</h2>
					<p>Type "DELETE" in the box below to proceed. <br> You will not be able to undo this.</p>
					<input type="text" id="text-confirm">
				</div>
				<div class="modal-button">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right:10px;color: #FFFFFF;">Cancel</button>
					<button type="submit" class="btn btn-danger del-confirm">Confirm</button>
				</div>  
			</div>
		</div>
	</div>