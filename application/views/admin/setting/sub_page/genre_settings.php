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
								<li class="divider"></li>
								<li class="delete-click"
								    data-url="<?php echo base_url('customer/delete/' . $row['id'] . '?redirect=' . uri_string()); ?>">
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