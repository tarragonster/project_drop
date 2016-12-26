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
        <h3 class="m-t-0 m-b-20 header-title">Collection: <?php echo $name ?></h3>
    </div>
</div>
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#list">List Films</a></li>
    <li><a data-toggle="tab" href="#add">Add film</a></li>
</ul>

<div class="row card-box"> 
    <div class="tab-content">
        <div id="list" class="tab-pane fade in active">
			<div class="col-xs-12">
				<div class="box">
					<div class="card-box table-responsive">
						<table id="example3" class="table table-striped table-bordered" data-alert="Are you want to delete this product?">
							<thead>
								<tr>
									<th>#</th>
									<th>Image</th>
									<th>Name</th>
									<th>Year</th>
									<th>Position</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if ($products != null && count($products) > 0) {
									foreach ($products as $row): ?>
								<tr>
									<td align="center"><?php echo $row['product_id'];?></td>
									<td><img style="max-width: 70px; max-height: 70px" src="<?php echo base_url($row['image']); ?>"/></td>
									<td><?php echo $row['name']?></td>
									<td><?php echo $row['publish_year']; ?></td>
									<?php echo '<td><div class="button-list">';
		                                if ($row['priority_collection'] > 1) {
		                                    echo "<a href='" . base_url('admin/collection/upFilm/'  .$collection_id. '/' . $row['priority_collection']) . '/' . $row['id'] . "'>
				                                    <button class='btn btn-sm btn-purple'><i class='fa fa-long-arrow-up'></i> Up</button>
				                                </a>";
		                                }
		                                if ($row['priority_collection'] < $max) {
		                                    echo "<a href='" . base_url('admin/collection/downFilm/' .$collection_id. '/' . $row['priority_collection']). '/' . $row['id'] . "'>
				                                    <button class='btn btn-sm btn-inverse'><i class='fa fa-long-arrow-down'></i> Down</button>
				                                </a>";
		                                }
		                                echo '</div></td>';?>
									<td><div class='button-list'>
										<a href='<?php echo base_url('admin/collection/removeFilm/'. $collection_id.'/' . $row['product_id'].'/'. $row['priority_collection']) ?>'>
											<button class='btn btn-danger btn-custom btn-xs'>Remove</button>
										</a>
									</div>
									</td>
								</tr>
								<?php endforeach ;
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		        <div id="add" class="tab-pane fade">
            <div class="row">
                <div class="col-xs-12">               
                    <div class="box-body table-responsive">
                        <table id="example3" class="table table-bordered table-hover">
                            <thead>
								<tr>
									<th>#</th>
									<th>Image</th>
									<th>Name</th>
									<th>Year</th>
									<th>Actions</th>
								</tr>
							</thead>
                            <?php
                            if ($others != null && count($others) > 0) {
                                foreach ($others as $row) {?>
                                	<td align="center"><?php echo $row['product_id'];?></td>
									<td><img style="max-width: 70px; max-height: 70px" src="<?php echo base_url($row['image']); ?>"/></td>
									<td><?php echo $row['name']?></td>
									<td><?php echo $row['publish_year']; ?></td>
									<td><div class='button-list'>
                                    <a href="<?php echo base_url('admin/collection/addFilm/' . $row['product_id'].'/'.$collection_id) ?>"><button class='btn btn-inverse btn-custom btn-xs'>Add</button></a>
                                    </div></td>
                                    </tr>
                            <?php    }
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>