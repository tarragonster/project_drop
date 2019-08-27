<div class="table-responsive " style="width: 100%; border: 0;overflow: unset;">
	<table class="table dataTable table_review">
		<thead>
			<tr class="display-flex">
				<th class="flex-item"></th>
				<th class="flex-item"></th>
				<th class="flex-item">Username</th>
				<th class="flex-item">Story Name</th>
				<th class="flex-item">Reviews</th>
				<th class="flex-item">Status</th>
				<th class="flex-item">Actions</th>
			</tr>
		</thead>
		<tbody>
			<tr><td colspan="8" style="background-color: unset;">
				<form action="" method="post" id="form-data">
					<ul data-url="<?= base_url('product/sortable')?>" class="sortable">
						<input type="hidden" name="dragging" value="0"/>
						<?php foreach ($reviews as $key => $row):?>
						<li id="pick_<?php echo $row['pick_id']; ?>" data-id="<?php echo $row['pick_id']; ?>">
		                    <input type="hidden" name="positions[<?php echo $row['pick_id']?>]" value="1">
		                    <div class="card-box card-sorting card-sorting-review row">
		                        <div class="col-sm-3 col-lg-1 sortable-box width-5" style="width: 40px;">
		                            <div class="sortable-move"><i class="icon-menu"></i></div>
		                        </div>
		                        <div class="col-sm-3 col-lg-2 sortable-box width-13 circle-image" style="width: 60px">
		                               <img src="<?= media_thumbnail($row['avatar'], 70) ?>" alt='avatar'>
		                        </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-15" style="width: 18%">
	                                <?php echo $row['full_name']?> <br> @<?php echo $row['user_name']?>
		                        </div>
		                        <div class="col-sm-3 col-lg-2 sortable-box width-10">
	                                <?= $row['name'] ?>
	                            </div>
	                            <div class="col-sm-3 col-lg-1 sortable-box width-48" style="width: 43%;">
	                                <?= $row['quote'] ?>
		                        </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-9" style="width: 10%;">
		                        	<?php if($row['is_hidden'] == 1): ?>
		                                <i class="fa fa-circle text-danger" style="font-size: 60%;"></i>&nbsp;&nbsp;
		                                <span>DISABLED</span>
	                                <?php else: ?>
	                                	<i class="fa fa-circle text-success" style="font-size: 60%;"></i>&nbsp;&nbsp;
		                                <span>ENABLED</span>
	                                <?php endif;?>
	                            </div>
		                        <div class="col-sm-3 col-lg-1 sortable-box width-5" style="float: right;width: 60px;">
		                            <div class="btn-group">
										<i type="button" style="font-size: 36px; color: #c7c7c7; line-height: 25px;" class="md md-more-horiz m-r-5 dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></i>
										<ul class="dropdown-menu" role="menu" style="min-width: 87px">
											<div class="dropdown-arrow"></div>
											<?php if($row['is_hidden'] == 0):?>
											<li class="dis-genre-btn btn_action" data-id="<?php echo $row['pick_id']; ?>" data-product="<?php echo $row['product_id'] ?>" data-toggle="modal" data-target="#dis-modal">
												<a href="#">
													Disable
													<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/block.svg')?>"></span>
												</a>
											</li>
											<?php else: ?>
											<li class="en-genre-btn btn_action" data-id="<?php echo $row['pick_id']; ?>" data-product="<?php echo $row['product_id'] ?>" data-toggle="modal" data-target="#en-modal">
												<a href="#">
													Enable
													<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/block.svg')?>"></span>
												</a>
											</li>
											<?php endif; ?>
											<li class="delete-genre-btn btn_action" data-id="<?php echo $row['pick_id']; ?>" data-product="<?php echo $row['product_id'] ?>" data-toggle="modal" data-target="#del-modal">
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

<div class="modal fade" id="dis-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-action">
		<div class="modal-content modal-content-popup">
			<div class="modal-img">
				<img src="<?php echo base_url('assets/images/exclamation.jpg')?>">
			</div>
			<div class="modal-description">
				<h2>Are you sure?</h2>
				<p>Are you sure you want to disable this review? You will be able to undo this in the actions section.</p>
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
				<p>Are you sure you want to enable this review? You will be able to undo this in the actions section.</p>
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
				<p>Are you sure you want to delete this review? You will not be able to undo this.</p>
			</div>
			<div class="modal-button">
				<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal">Cancel</button>
				<button class="btn btn-danger" data-toggle="modal" data-target="#confirm-del-modal" data-dismiss="modal" >Delete</button>
			</div>	
		</div>
	</div>
</div>
<div class="modal fade" id="confirm-del-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-action">
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