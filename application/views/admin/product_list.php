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

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="card-box table-responsive">
				<div class="box-header">
					<h3 class="box-title">List Products</h3>
				</div>
				<table id="example3" class="table table-striped table-bordered" data-alert="Are you want to delete this product?">
					<thead>
						<tr>
							<th>#</th>
							<th>Image</th>
							<th>Name</th>
							<th>Year</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($products != null && count($products) > 0) {
							foreach ($products as $row): ?>
						<tr>
							<td align="center"><?php echo $row['product_id'];?></td>
							<td><img style="max-width: 70px; max-height: 70px" src="<?php echo media_url($row['image']); ?>"/></td>
							<td><?php echo $row['name']?></td>
							<td><?php echo $row['publish_year']; ?></td>
							<td><?php echo ($row['status'] == 1 ? 'Enable' : ($row['status'] == 0 ? 'Disable' : 'Deleted')) ?></td>
							<td><div class='button-list'>
								<a href='<?php echo base_url('product/edit/' . $row['product_id']) ?>'>
									<button class='btn btn-inverse btn-custom btn-xs'>Edit</button>
								</a>
								<a href='<?php echo base_url('product/managerActor/' . $row['product_id']) ?>'>
									<button class='btn btn-inverse btn-custom btn-xs'>Manage Actors</button>
								</a>
								<a href='<?php echo base_url('product/managerMusic/' . $row['product_id']) ?>'>
									<button class='btn btn-inverse btn-custom btn-xs'>Manage Musics</button>
								</a>
								<a href='<?php echo base_url('product/managerSeason/' . $row['product_id']) ?>'>
									<button class='btn btn-inverse btn-custom btn-xs'>Manage Seasons</button>
								</a>
								<span class="sa-warning" data-href="<?php echo base_url('product/delete/' . $row['product_id']) ?>"><button class="btn btn-danger btn-custom btn-xs">Delete</button></span>
							</div>
							</td>
						</tr>
						<?php endforeach ;
						} ?>
					</tbody>
				</table>
				<div class="row">
					<div class="col-xs-6">
						<div class="dataTables_info" id="example3_info">
							<?php echo $pinfo['total'] > 0 ? "Showing {$pinfo['from']} to {$pinfo['to']} of {$pinfo['total']} entries" : '';?>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="dataTables_paginate paging_bootstrap">
							<?php echo $this->pagination->create_links();?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>