<div class="table-responsive " style="width: 100%; border: 0">
	<table class="table dataTable">
		<?php $this->load->view('admin/base/table_header') ?>
		<tbody>
			<?php foreach ($genres as $key => $row): ?>
				<tr role="row" class="<?= $key % 2 == 0 ? 'even' : 'odd' ?>">
					<td><img src="<?php echo base_url($row['image'])?>" alt='genre-image' width='102' height='60'></td>
					<td><?php echo $row['id']?></td>
					<td><?php echo $row['name']?></td>
					<td><?php echo $row['num_stories']?></td>
					<td><?php echo date('m/d/Y h:iA', $row['created_at'])?></td>
					<td><?php echo $row['status']?></td>
					<td style="text-align: center">
						<div class="btn-group">
							<i type="button" style="font-size: 36px; color: #c7c7c7; line-height: 25px;" class="md md-more-horiz m-r-5 dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></i>
							<ul class="dropdown-menu" role="menu" style="min-width: 132px">
								<div class="dropdown-arrow"></div>
								<li class="edit-genre-btn" data-id="<?php echo $row['id']; ?>">
									<a href="#">Edit<span class="action-icon"> <i class="fa fa-edit"></i></span></a>
								</li>
								<?php if($row['status'] == 1):?>
								<li class="dis-genre-btn btnAct" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#dis-modal">
									<a href="#">Disable<span class="action-icon"> <i class="fa fa-block"></i></span></a>
								</li>
								<?php else: ?>
								<li class="en-genre-btn btnAct" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#en-modal">
									<a href="#">Enable<span class="action-icon"> <i class="fa fa-edit"></i></span></a>
								</li>
								<?php endif; ?>
								<li class="divider"></li>
								<li class="delete-genre-btn btnAct" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#del-modal">
									<a href="#">Delete<span class="action-icon"> <i class="fa fa-trash"></i></span></a>
								</li>
							</ul>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
		
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