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
<form action="" method="get">
	<div class="search-container">
		<i class="fa fa-search"></i>
		<input type="search" id="search_text" name="key" class="form-control" placeholder="Search Stories" value="<?php echo isset($conditions['key']) ? $conditions['key'] : '' ?>">
		<!-- <button type="submit" class="btn-nothing" name="cmd" value="nothing" style="display: none;">&nbsp;</button> -->
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="card-box table-responsive">
					<div id="product_table">
						<table id="example3 " class="table dataTable">
							<?php $this->load->view('admin/products/table_header')?>
							<tbody>
						        <?php
						        if ($products != null && count($products) > 0) {
						            foreach ($products as $row): ?>
						                <tr>
							                <td><img style="max-width: 37px; height: 55px;border-radius: 5px;" src="<?= media_thumbnail($row['image'], 70) ?>"/></td>
							                <td><?php echo $row['product_id']?></td>
							                <td><?php echo $row['name']?></td>
							                <td><?php echo $row['total_block']?></td>
							                <td>
							                    <?php 
							                        echo ($row['episode_id'] == null)? 'N/a' : 'B' . $row['position'] . ' - ' . $row['paywall_block_name'] 
							                    ?>
							                </td>
							                <td><?php echo empty($row['genre']) ? 'N/a' : $row['genre']?></td>
							                <td>Comments:&nbsp;<?php echo $row['total_cmt']?><br>
							                  Thumbs&nbsp;up:&nbsp;<?php echo $row['total_like']?> <br>
							                  Picks:&nbsp;<?php echo $row['total_pick']?>
							                </td>
							                <td><?php echo date('m/d/Y h:iA', $row['created'])?></td>
							                <td>
							                	<?php if($row['status'] == 0): ?>
					                                <i class="fa fa-circle text-danger" style="font-size: 60%;"></i>&nbsp;
					                                <span>DISABLED</span>
				                                <?php else: ?>
				                                	<i class="fa fa-circle text-success" style="font-size: 60%;"></i>&nbsp;
					                                <span>ENABLE</span>
				                                <?php endif;?>
				                            </td>
							                <td>
							                	<div class="btn-group">
													<i type="button" style="font-size: 36px; color: #c7c7c7; line-height: 25px;" class="md md-more-horiz m-r-5 dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></i>
													<ul class="dropdown-menu" role="menu" style="min-width: 87px">
														<div class="dropdown-arrow"></div>
														<li class="edit-genre-btn" data-id="<?php echo $row['product_id']; ?>">
															<a href="#">Edit
																<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/edit.svg')?>"></span>
															</a>
														</li>
														<?php if($row['status'] == 1):?>
														<li class="dis-genre-btn btnAct" data-id="<?php echo $row['product_id']; ?>" data-toggle="modal" data-target="#dis-modal">
															<a href="#">Disable
																<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/block.svg')?>"></span>
															</a>
														</li>
														<?php else: ?>
														<li class="en-genre-btn btnAct" data-id="<?php echo $row['product_id']; ?>" data-toggle="modal" data-target="#en-modal">
															<a href="#">
																Enable
																<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/block.svg')?>"></span>
															</a>
														</li>
														<?php endif; ?>
														<li class="delete-genre-btn btnAct" data-id="<?php echo $row['product_id']; ?>" data-toggle="modal" data-target="#del-modal">
															<a href="#">
																Delete
																<span class="action-icon"><img src="<?php echo base_url('assets/images/icons/trash.svg')?>"></span>
															</a>
														</li>
													</ul>
												</div>
							                </td>
							            </tr>
							        <?php
							    	endforeach;
								} ?>
							</tbody>
						</table> 
						<!-- Pagination -->
						<?php
							$has_items = isset($paging) && $paging['total'] > 0;
							$dropdown_size = $has_items && isset($paging['dropdown-size']) ? $paging['dropdown-size'] - 25 : '40';
						?>
						<div class="row" style="padding: 0 20px;padding-top: 10px; margin: 0; background: white; border-bottom-left-radius: 6px;border-bottom-right-radius: 6px;">
						    <?php if (isset($paging)) : ?>
						        <div class="col-xs-4">
						            <?php
						            $per_page = isset($conditions['per_page']) ? $conditions['per_page'] * 1 : 0;
						            ?>
						            <div class="dataTables_info" id="table-driver_info" role="status" aria-live="polite">
						                <?php if ($paging['total'] > 0) : ?>
						                    Showing <?= $paging['from'] ?> to <?= $paging['to'] ?> of <?= $paging['total'] ?> stories
						                <?php else: ?>
						                    No Results
						                <?php endif; ?>
						            </div>
						        </div>
						        <div class="col-xs-8">
						            <?php if ($has_items): ?>
						                <div class="dataTables_paginate paging_bootstrap" style="float: right">
						                    <?php echo $this->pagination->create_links(); ?>
						                </div>
						            <?php endif; ?>
						            <div class="per_page m-r-15" style="float: right; margin-top: 2px; margin-left: 30px">
						                <label>
						                    <select name="per_page" class="form-control input-sm">
						                        <option value="25"<?php echo $per_page == 25 ? ' selected' : '' ?>>25</option>
						                        <option value="50"<?php echo $per_page == 50 ? ' selected' : '' ?>>50</option>
						                    </select> &nbsp;
						                    Items per page
						                </label>
						            </div>
						        </div>
						    <?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
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
					<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal" style="margin-right: 10px;color: #FFFFFF;">Cancel</button>
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
					<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal" style="margin-right: 10px;color: #FFFFFF;">Cancel</button>
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
					<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal" style="margin-right: 10px;color: #FFFFFF;">Cancel</button>
					<button class="btn btn-danger del-btn" data-toggle="modal" data-target="#confirm-del-modal" data-dismiss="modal" >Delete</button>
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
					<button type="button" class="btn btn-secondary cancel-btn" data-dismiss="modal" style="margin-right:10px;color: #FFFFFF;">Cancel</button>
					<button type="submit" class="btn btn-danger del-confirm">Delete</button>
				</div>  
			</div>
		</div>
	</div>