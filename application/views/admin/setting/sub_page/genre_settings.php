<div class="table-responsive " style="width: 100%; border: 0;overflow-y: hidden;">
	<table class="table dataTable">
		
			<?php $this->load->view('admin/base/table_header') ?>
		<tbody>
			<tr><td colspan="8">
				<form action="" method="post" id="form-data">
					<ul data-url="<?= base_url('genre/sortable')?>" class="sortable">
						<input type="hidden" name="dragging" value="0"/>
						<?php foreach ($genres as $key => $row):?>
						<li id="genre_<?php echo $row['id']; ?>" data-id="<?php echo $row['id']; ?>">
		                    <input type="hidden" name="positions[<?php echo $row['id']?>]" value="1">
		                    <div class="card-box card-sorting row">
		                        <div class="col-sm-3 col-lg-1 sortable-box width-5" style="padding: 0px">
		                            <div class="sortable-move"><i class="icon-menu"></i></div>
		                        </div>
		                        <div class="col-sm-3 col-lg-2 sortable-box width-13" style="padding: 0px">
		                               <img src="<?php echo base_url($row['image'])?>" alt='genre-image' width='102' height='60'>
		                        </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-9" style="padding: 0px">
	                                <?php echo $row['id']?>
		                        </div>
		                        <div class="col-sm-3 col-lg-2 sortable-box width-18">
	                                <?= $row['name'] ?>
	                            </div>
	                            <div class="col-sm-3 col-lg-1 sortable-box width-18">
	                                <?= $row['num_stories'] ?>
		                        </div>
		                        <div class="col-sm-3 col-lg-2 sortable-box width-18">
	                                <?= date('m/d/Y h:iA', $row['created_at']) ?>
			                    </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-13">
		                        	<?php if($row['status'] == 0): ?>
		                                <i class="fa fa-circle text-danger" style="font-size: 60%;"></i>&nbsp;&nbsp;
		                                <span>DISABLED</span>
	                                <?php else: ?>
	                                	<i class="fa fa-circle text-success" style="font-size: 60%;"></i>&nbsp;&nbsp;
		                                <span>ENABLE</span>
	                                <?php endif;?>
	                            </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-5">
		                            <div class="btn-group">
										<i type="button" style="font-size: 36px; color: #c7c7c7; line-height: 25px;" class="md md-more-horiz m-r-5 dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></i>
										<ul class="dropdown-menu" role="menu" style="min-width: 87px">
											<div class="dropdown-arrow"></div>
											<li class="edit-genre-btn" data-id="<?php echo $row['id']; ?>">
												<a href="#">
													Edit
													<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/edit.svg')?>"></span>
												</a>
											</li>
											<?php if($row['status'] == 1):?>
											<li class="dis-genre-btn btnAct" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#dis-modal">
												<a href="#">
													Disable
													<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/block.svg')?>"></span>
												</a>
											</li>
											<?php else: ?>
											<li class="en-genre-btn btnAct" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#en-modal">
												<a href="#">
													Enable
													<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/block.svg')?>"></span>
												</a>
											</li>
											<?php endif; ?>
											<li class="delete-genre-btn btnAct" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#del-modal">
												<a href="#">
													Delete
													<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/trash.svg')?>"></span>
												</a>
											</li>
										</ul>
									</div>
		                        </div>
		                    </div>
		                </li>
					<?php endforeach; ?>
					</ul>
				</form>
			</td></tr>
		</tbody>
	</table>
</div>

<div class="modal custom-modal below-header fade visiting right" id="add-genre-popup" tabindex="-1" role="dialog">
	<form action="<?php echo base_url('genre/addGenre')?>" method="post" id="genre-form-add" enctype="multipart/form-data">
		<div class="modal-dialog" role="document">
			<div class="modal-content" id="add-genre-form" style="padding: 0px">
				
			</div>
		</div>
	</form>
</div>
<div class="modal custom-modal below-header fade visiting right" id="edit-genre-popup" tabindex="-1" role="dialog">
	<form action="<?php echo base_url('genre/editGenre')?>" method="post" id="genre-form-edit" enctype="multipart/form-data">
		<div class="modal-dialog" role="document">
			<div class="modal-content" id="edit-genre-form" style="padding: 0px">
				
			</div>
		</div>
	</form>
</div>
<div class="modal fade" id="dis-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content modal-content-popup">
			<div class="modal-img">
				<img src="<?php echo base_url('assets/images/exclamation.jpg')?>">
			</div>
			<div class="modal-description">
				<h2>Are you sure?</h2>
				<p>Are you sure you want to disable this genre? You will be able to undo this in the actions section.</p>
			</div>
			<div class="modal-button">
				<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-warning dis-confirm">Disable</button>
			</div>	
		</div>
	</div>
</div>
<div class="modal fade" id="en-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content modal-content-popup">
			<div class="modal-img">
				<img src="<?php echo base_url('assets/images/exclamation.jpg')?>">
			</div>
			<div class="modal-description">
				<h2>Are you sure?</h2>
				<p>Are you sure you want to enable this genre? You will be able to undo this in the actions section.</p>
			</div>
			<div class="modal-button">
				<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-warning en-confirm">Enable</button>
			</div>	
		</div>
	</div>
</div>
<div class="modal fade" id="del-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content modal-content-popup">
			<div class="modal-img">
				<img src="<?php echo base_url('assets/images/quit.jpg')?>">
			</div>
			<div class="modal-description">
				<h2>Are you sure?</h2>
				<p>Are you sure you want to delete this genre? You will not be able to undo this.</p>
			</div>
			<div class="modal-button">
				<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>
				<button class="btn btn-danger" data-toggle="modal" data-target="#confirm-del-modal" data-dismiss="modal" >Delete</button>
			</div>	
		</div>
	</div>
</div>
<div class="modal fade" id="confirm-del-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content modal-content-popup">
			<div class="modal-img">
				<img src="<?php echo base_url('assets/images/quit.jpg')?>">
			</div>
			<div class="modal-description" style="margin-top: 10px">
				<p>Type "DELETE" in the box below to proceed. You will not be able to undo this.</p>
				<input type="text" id="text-confirm">
			</div>
			<div class="modal-button">
				<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-danger del-confirm">Delete</button>
			</div>  
		</div>
	</div>
</div>