<div class="row">
	<div class="col-xs-12" style='margin-top: 10px'>
		<div class="box">
			<div class="card-box table-responsive">
				<div class="box-header">
					<h3 class="box-title"><?= $title ?></h3>
				</div>
				<table id="example3" class="table table-bordered-bottom table-hover">
					<thead>
					<tr>
						<th></th>
						<th>ID</th>
						<th>Name</th>
						<th>E-mail</th>
						<th>App Activity</th>
						<th>Verified</th>
						<th>Version</th>
						<th>Created</th>
						<th>Actions</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if (isset($users) && is_array($users))
					{
						foreach ($users as $key => $row) {
					?>
					<tr>
						<td><img style="max-width: 70px; max-height: 70px" src="<?= media_thumbnail($row['avatar'], 70) ?>"/></td>
						<td><?php echo $row['user_id']?></td>
						<td><?php echo $row['user_name']?></td>
						<td><?php echo $row['email']?></td>
						<td>Comments: <?php echo $row['total_comment']?> <br> 
							Thumbs up: <?php echo $row['total_like']?> <br>
							Picks:
						</td>
						<td>Verified</td>
						<td><?php echo $row['device_name']?></td>
						<td><?php echo date('d/m/Y h:iA', $row['joined'])?></td>
						<td>
							<div class="dropdown">
							    <span class="btnAction dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-ellipsis-v"></i></span>
							    <ul class="dropdown-menu">
							      <li><a href="#">View Profile</a></li>
							      <li><a href="#">Edit Profile</a></li>
							      <li><a href="#">Verified</a></li>
							      <li><a href="#">Block</a></li>
							      <li class="divider"></li>
							      <li><a href="#">Delete</a></li>
							    </ul>
							</div>
						</td>
					</tr>
					<?php 
						}
					}
					?>
					</tbody>
				</table>
				<div class="row">
					<?php if ($pinfo['total'] > 0): ?>
						<div class="col-xs-6">
							<div class="dataTables_info" id="example3_info">
								<?php echo "Showing {$pinfo['from']} to {$pinfo['to']} of {$pinfo['total']} entries" ?>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="dataTables_paginate paging_bootstrap">
								<?php echo $this->pagination->create_links(); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
