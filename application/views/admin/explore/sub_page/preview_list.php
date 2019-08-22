<div class="table-responsive " style="width: 100%; border: 0;overflow: unset;">
	<table class="table dataTable preview_table">
		<thead>
			<tr class="display-flex">
				<th class="flex-item"></th>
				<th class="flex-item"></th>
				<th class="flex-item">Story ID</th>
				<th class="flex-item">Story Name</th>
				<th class="flex-item">Preview Activity</th>
				<th class="flex-item">Date Added to Preview List</th>
				<th class="flex-item">Status</th>
				<th class="flex-item">Actions</th>
			</tr>
		</thead>
		<tbody>
			<tr><td colspan="8">
				<form action="" method="post" id="form-data">
					<ul data-url="<?= base_url('explore/sortPreviewStory')?>" class="sortable">
						<input type="hidden" name="dragging" value="0"/>
						<?php foreach ($previews as $key => $row):?>
						<li id="user_<?php echo $row['product_id']; ?>" data-id="<?php echo $row['product_id']; ?>">
		                    <input type="hidden" name="positions[<?php echo $row['product_id']?>]" value="1">
		                    <div class="card-box card-sorting row">
		                        <div class="col-sm-3 col-lg-1 sortable-box width-5" style="padding: 0px">
		                            <div class="sortable-move"><i class="icon-menu"></i></div>
		                        </div>
		                        <div class="col-sm-3 col-lg-2 sortable-box width-13" style="padding: 0px;width: 80px">
	                               <?php echo image_mask($row['image'], 'assets/images/genre_mark.png', 50, 'height:50px;border-radius:10%')?>
		                        </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-7" style="padding: 0px">
	                                <?php echo $row['product_id']?>
		                        </div>
		                        <div class="col-sm-3 col-lg-2 sortable-box width-23" style="font-weight: 600">
	                                <?php echo $row['name'] ?>
	                            </div>
	                            <div class="col-sm-3 col-lg-1 sortable-box width-18">
	                            	Reviews:&nbsp;<?= $row['total_pick']?> <br>
	                            	Comments:&nbsp;<?= $row['total_cmt']?> <br>
	                            	Likes:&nbsp;<?= $row['total_like']?>
		                        </div>
		                        <div class="col-sm-3 col-lg-2 sortable-box width-20">
	                                <?= date('m/d/Y h:iA', $row['added_at']) ?>
			                    </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-13 action-column">
		                        	<?php if($row['status'] == 0): ?>
		                                <i class="fa fa-circle text-danger icon-size"></i>&nbsp;&nbsp;
		                                <span>DISABLED</span>
	                                <?php else: ?>
	                                	<i class="fa fa-circle text-success icon-size"></i>&nbsp;&nbsp;
		                                <span>ENABLE</span>
	                                <?php endif;?>
	                            </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-5" style="width: 60px">
		                            <div class="btn-group">
										<i type="button" style="font-size: 36px; color: #c7c7c7; line-height: 25px;" class="md md-more-horiz m-r-5 dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></i>
										<ul class="dropdown-menu" role="menu" style="min-width: 90px">
											<div class="dropdown-arrow"></div>
											<li class="edit-genre-btn" data-id="<?php echo $row['product_id']; ?>">
												<a href="#">
													View
													<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/edit.svg')?>"></span>
												</a>
											</li>
											<?php if($row['status'] == 1):?>
											<li class="dis-genre-btn preview-btn" data-id="<?php echo $row['product_id']; ?>" data-toggle="modal" data-target="#dis-modal">
												<a href="#">
													Disable
													<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/block.svg')?>"></span>
												</a>
											</li>
											<?php else: ?>
											<li class="en-genre-btn preview-btn" data-id="<?php echo $row['product_id']; ?>" data-toggle="modal" data-target="#en-modal">
												<a href="#">
													Enable
													<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/block.svg')?>"></span>
												</a>
											</li>
											<?php endif; ?>
											<li class="delete-genre-btn preview-btn" data-id="<?php echo $row['product_id']; ?>" data-toggle="modal" data-target="#del-modal">
												<a href="#">
													Remove
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

<div class="modal custom-modal below-header fade visiting right" id="add-user-popup" tabindex="-1" role="dialog">
	<form action="<?php echo base_url('explore/addFeaturedUser')?>" method="post" id="user-form-add" enctype="multipart/form-data">
		<div class="modal-dialog" role="document">
			<div class="modal-content" id="add-user-form" style="padding: 0px">
				
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
				<p>Are you sure you want to disable this story from the explore preview list? You will be able to undo this in the actions section.</p>
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
				<p>Are you sure you want to enable this this story from the explore preview list? You will be able to undo this in the actions section.</p>
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
				<p>Are you sure you want to remove this this story from the explore preview list? You will not be able to undo this.</p>
			</div>
			<div class="modal-button">
				<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>
				<button class="btn btn-danger remove-confirm" data-dismiss="modal" >Remove</button>
			</div>	
		</div>
	</div>
</div>
