<div class="section-content">
    <form action="" method="get">
        <?php
        $numb = 0;
        if(!empty($episodes)){ foreach ($episodes as $key=>$value){
            $numb++;
        ?>
        <div class="hub-faq-title"><span><?=$seasons[$key]?></span></div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table dataTable">
                <?php $this->load->view('admin/base/table_header')?>
                <tbody>
                	<tr><td colspan="8" style="background-color: unset;">
						<form action="" method="post" id="form-data">
							<ul data-url="<?= base_url('product/sortable')?>" class="sortable">
								<input type="hidden" name="dragging" value="0"/>
				                <?php if(!empty($value)){ foreach ($value as $keyP=>$valueP){?>
				                    <li id="block_<?php echo $valueP['episode_id']; ?>" data-id="<?php echo $valueP['episode_id']; ?>">
				                    <input type="hidden" name="positions[<?php echo $valueP['episode_id']?>]" value="1">
				                    <div class="card-box card-sorting row">
				                        <div class="col-sm-1 sortable-box width-7" style="padding: 0px">
				                            <div class="sortable-move"><i class="icon-menu"></i></div>
				                        </div>
				                        <div class="col-sm-3 sortable-box width-12" style="padding: 0px">
			                                <?php echo $valueP['episode_id']?>
				                        </div>
				                        <div class="col-sm-3 sortable-box width-12">
			                                <?= $valueP['position'] ?>
			                            </div>
			                            <div class="col-sm-3 sortable-box width-25">
			                                <?= $valueP['name'] ?>
				                        </div>
				                        <div class="col-sm-3 sortable-box width-13">
			                                Comments:&nbsp;<?= $valueP['total_cmt'] ?> <br>
			                                Thumbs&nbsp;up:&nbsp;<?= $valueP['total_like']?>
				                        </div>
				                        <div class="col-sm-3 sortable-box width-15">
			                                <?= date('m/d/Y H:iA', $valueP['created']) ?>
				                        </div>
				                        <div class="col-sm-3 sortable-box width-5 status">
				                        	<?php if($valueP['status'] == 0): ?>
				                                <i class="fa fa-circle text-danger" style="font-size: 60%;"></i>&nbsp;&nbsp;
				                                <span>DISABLED</span>
			                                <?php else: ?>
			                                	<i class="fa fa-circle text-success" style="font-size: 60%;"></i>&nbsp;&nbsp;
				                                <span>ENABLE</span>
			                                <?php endif;?>
			                            </div>
				                        <div class="col-sm-3 sortable-box width-5" style="float: right;">
				                            <div class="btn-group">
												<i type="button" style="font-size: 36px; color: #c7c7c7; line-height: 25px;" class="md md-more-horiz m-r-5 dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></i>
												<ul class="dropdown-menu" role="menu" style="min-width: 87px">
													<div class="dropdown-arrow"></div>
													<li class="edit-season-btn" data-id="<?php echo $valueP['episode_id']; ?>">
														<a href="">Edit
															<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/edit.svg')?>"></span>
														</a>
													</li>
													<?php if($valueP['status'] == 1):?>
													<li class="dis-genre-btn btn_season" data-id="<?php echo $valueP['episode_id']; ?>" data-product="<?php echo $product_id ?>" data-toggle="modal" data-target="#dis-modal">
														<a href="#">
															Disable
															<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/block.svg')?>"></span>
														</a>
													</li>
													<?php else: ?>
													<li class="en-genre-btn btn_season" data-id="<?php echo $valueP['episode_id']; ?>" data-product="<?php echo $product_id ?>" data-toggle="modal" data-target="#en-modal">
														<a href="#">
															Enable
															<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/block.svg')?>"></span>
														</a>
													</li>
													<?php endif; ?>
													<li class="delete-genre-btn btn_season" data-id="<?php echo $valueP['episode_id']; ?>" data-product="<?php echo $product_id ?>" data-toggle="modal" data-target="#del-modal">
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
				                <?php } } ?>
				            </ul>
				        </form>
				    </td></tr>
                </tbody>
            </table>
        </div>
    <?php } }?>
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
				<p>Are you sure you want to disable this block? You will be able to undo this in the actions section.</p>
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
				<p>Are you sure you want to enable this block? You will be able to undo this in the actions section.</p>
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
				<p>Are you sure you want to delete this block? You will not be able to undo this.</p>
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

