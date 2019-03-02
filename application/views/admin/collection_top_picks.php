<div class="row">
	<div class="col-xs-12">
		<h3 class="m-t-0 m-b-20 header-title"><?php echo $name ?></h3>
	</div>
</div>
<ul class="nav nav-tabs">
	<li class="active"><a data-toggle="tab" href="#list">List Films on <?php echo $name ?></a></li>
</ul>

<div class="row card-box">
	<div class="tab-content" style="box-shadow: none!important;">
		<div id="list" class="tab-pane fade in active">
			<div class="row">
				<div class="col-xs-12">
					<div class="table-responsive">
						<table id="example3" class="table table-striped table-bordered" data-alert="Are you want to delete this product?">
							<thead>
							<tr>
								<th>#</th>
								<th>Image</th>
								<th>Name</th>
								<th>Year</th>
							</tr>
							</thead>
							<tbody>
							<?php
							if ($products != null && count($products) > 0) {
								foreach ($products as $row): ?>
									<tr>
										<td align="center"><?php echo $row['product_id']; ?></td>
										<td><img style="max-width: 70px; max-height: 70px" src="<?= media_thumbnail($row['image'], 70) ?>"/></td>
										<td><?php echo $row['name'] ?></td>
										<td><?php echo $row['publish_year']; ?></td>
									</tr>
								<?php endforeach;
							} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>