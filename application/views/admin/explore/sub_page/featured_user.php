<div class="table-responsive " style="width: 100%; border: 0;overflow: unset;">
	<table class="table dataTable">
		<thead>
			<tr class="display-flex">
				<th class="flex-item"></th>
				<th class="flex-item"></th>
				<th class="flex-item">User ID</th>
				<th class="flex-item">Username</th>
				<th class="flex-item">Activity</th>
				<th class="flex-item">Followers | Following</th>
				<th class="flex-item">Date Added</th>
				<th class="flex-item">Status</th>
				<th class="flex-item">Actions</th>
			</tr>
		</thead>
		<tbody>
			<tr><td colspan="8">
				<form action="" method="post" id="form-data">
					<ul data-url="<?= base_url('explore/sortFeaturedUser')?>" class="sortable">
						<input type="hidden" name="dragging" value="0"/>
						<?php foreach ($featured_users as $key => $row):?>
						<li id="user_<?php echo $row['user_id']; ?>" data-id="<?php echo $row['user_id']; ?>">
		                    <input type="hidden" name="positions[<?php echo $row['user_id']?>]" value="1">
		                    <div class="card-box card-sorting row">
		                        <div class="col-sm-3 col-lg-1 sortable-box width-5" style="padding: 0px">
		                            <div class="sortable-move"><i class="icon-menu"></i></div>
		                        </div>
		                        <div class="col-sm-3 col-lg-2 sortable-box width-13" style="padding: 0px;width: 100px">
	                               <?php echo image_mask($row['avatar'], 'assets/images/genre_mark.png', 50, 'height:50px;border-radius:50%')?>
		                        </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-9" style="padding: 0px">
	                                <?php echo $row['user_id']?>
		                        </div>
		                        <div class="col-sm-3 col-lg-2 sortable-box width-18" style="font-weight: 600">
	                                <?php echo $row['full_name'] ?><br>
				                    <span style="font-weight: 500!important;display: contents;">@<?php echo $row['user_name'] ?></span>
	                            </div>
	                            <div class="col-sm-3 col-lg-1 sortable-box width-13">
	                            	Reviews:&nbsp;<?= $row['total_pick']?> <br>
	                            	Comments:&nbsp;<?= $row['total_cmt']?> <br>
	                            	Likes:&nbsp;<?= $row['total_like']?>
		                        </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-15">
	                                <?= $row['followers']?> | <?= $row['following']?>
		                        </div>
		                        <div class="col-sm-3 col-lg-2 sortable-box width-18">
	                                <?= date('m/d/Y h:iA', $row['added_at']) ?>
			                    </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-13 action-column">
		                        	<?php if($row['status'] == 0): ?>
		                                <i class="fa fa-circle text-danger icon-size"></i>&nbsp;&nbsp;
		                                <span>DISABLED</span>
	                                <?php else: ?>
	                                	<i class="fa fa-circle text-success icon-size"></i>&nbsp;&nbsp;
		                                <span>ENABLED</span>
	                                <?php endif;?>
	                            </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-5" style="width: 70px">
		                            <div class="btn-group">
										<i type="button" style="font-size: 36px; color: #c7c7c7; line-height: 25px;" class="md md-more-horiz m-r-5 dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></i>
										<ul class="dropdown-menu" role="menu" style="min-width: 90px">
											<div class="dropdown-arrow"></div>
											<li class="view-user-btn" data-user_id="<?php echo $row['user_id']; ?>" onclick="ShowUserProfile(this)">
												<a href="#">
													View
													<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/edit.svg')?>"></span>
												</a>
											</li>
											<?php if($row['status'] == 1):?>
											<li class="dis-genre-btn btnAct" data-id="<?php echo $row['user_id']; ?>" data-toggle="modal" data-target="#dis-modal">
												<a href="#">
													Disable
													<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/block.svg')?>"></span>
												</a>
											</li>
											<?php else: ?>
											<li class="en-genre-btn btnAct" data-id="<?php echo $row['user_id']; ?>" data-toggle="modal" data-target="#en-modal">
												<a href="#">
													Enable
													<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/block.svg')?>"></span>
												</a>
											</li>
											<?php endif; ?>
											<li class="delete-genre-btn btnAct" data-id="<?php echo $row['user_id']; ?>" data-toggle="modal" data-target="#del-modal">
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

<div class="modal fade right" id="view-user-popup" tabindex="-1" role="dialog">
    <div class="modal-dialog style-modal" role="document">
        <div class="modal-content group-popup" style="padding: 0px" id="view-user-content">

        </div>
    </div>
</div>

<div class="modal fade" id="dis-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-action">
		<div class="modal-content modal-content-popup">
			<div class="modal-img">
				<img src="<?php echo base_url('assets/images/exclamation.jpg')?>">
			</div>
			<div class="modal-description">
				<h2>Are you sure?</h2>
				<p>Are you sure you want to disable this user from the featured users list? You will be able to undo this in the actions section.</p>
			</div>
			<div class="modal-button">
				<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-warning dis-confirm">Disable</button>
			</div>	
		</div>
	</div>
</div>
<div class="modal fade" id="en-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-action">
		<div class="modal-content modal-content-popup">
			<div class="modal-img">
				<img src="<?php echo base_url('assets/images/exclamation.jpg')?>">
			</div>
			<div class="modal-description">
				<h2>Are you sure?</h2>
				<p>Are you sure you want to enable this user from the featured users list? You will be able to undo this in the actions section.</p>
			</div>
			<div class="modal-button">
				<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-warning en-confirm">Enable</button>
			</div>	
		</div>
	</div>
</div>
<div class="modal fade" id="del-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-action">
		<div class="modal-content modal-content-popup">
			<div class="modal-img">
				<img src="<?php echo base_url('assets/images/quit.jpg')?>">
			</div>
			<div class="modal-description">
				<h2>Are you sure?</h2>
				<p>Are you sure you want to remove this user from the featured users list? You will not be able to undo this.</p>
			</div>
			<div class="modal-button">
				<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>
				<button class="btn btn-danger remove-confirm" data-dismiss="modal" >Remove</button>
			</div>	
		</div>
	</div>
</div>
