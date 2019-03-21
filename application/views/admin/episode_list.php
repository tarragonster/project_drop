<?php
if ($this->session->flashdata('alert')) {

    echo '<div class="row"><div class="col-xs-12 alert alert-success">';

    echo $this->session->flashdata('alert');
    echo '</div></div>';
}
?>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="card-box table-responsive">
				<div class="box-header">
					<h3 class="box-title">List Episodes</h3>
				</div>
				<div class="box-header">
                    <a href="<?php echo base_url('episode/add/'.$season_id) ?>">
                        <button class="btn btn-primary">Create Episode</button>
                    </a>
                </div>
                <hr/>
                <div class="clearfix">
                    <div class="pull-right">
                        <p>Film: <strong><?php echo $product_name ?></strong></p>
                        <p>Season: <strong><?php echo $name ?></strong></p>
                    </div>
                </div>
				<table id="example3" class="table table-striped table-bordered" data-alert="Are you want to delete this product?">
					<thead>
						<tr>
							<th>#</th>
							<th>Episode</th>
							<th>Name</th>
							<th>Image</th>
							<th>Created</th>
							<th>Position</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($episodes != null && count($episodes) > 0) {
							foreach ($episodes as $row): ?>
						<tr>
							<td align="center"><?php echo $row['episode_id'];?></td>
							<td>#<?php echo $row['position']?></td>
							<td><?php echo $row['name']?></td>
							<td><img style="max-width: 70px; max-height: 70px" src="<?= media_thumbnail($row['image'], 70) ?>"/></td>
							<td><?php echo timeFormat($row['created'])?></td>
							<?php echo '<td><div class="button-list">';
                            if ($row['position'] > 1) {
                                echo "<a href='" . base_url('episode/up/'  .$row['episode_id']. '/' . $row['season_id']) . "'>
	                                    <button class='btn btn-sm btn-purple'><i class='fa fa-long-arrow-up'></i> Up</button>
	                                </a>";
                            }
                            if ($row['position'] < $max) {
                                echo "<a href='" . base_url('episode/down/' .$row['episode_id']. '/' . $row['season_id']) . "'>
	                                    <button class='btn btn-sm btn-inverse'><i class='fa fa-long-arrow-down'></i> Down</button>
	                                </a>";
                            }
                            echo '</div></td>';?>
							<td><div class='button-list'>
								<a href='<?php echo base_url('episode/edit/' . $row['episode_id'].'/' . $row['season_id']) ?>'>
									<button class='btn btn-inverse btn-custom btn-xs'>Edit</button>
								</a>
								<span class="sa-warning" data-href="<?php echo base_url('episode/delete/' . $row['episode_id'].'/' . $row['season_id']) ?>"><button class="btn btn-danger btn-custom btn-xs">Delete</button></span>
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