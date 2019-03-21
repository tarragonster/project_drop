<div class="row">
	<div class="col-xs-12" style='margin-top: 10px'>
		<div class="box">
			<div class="card-box table-responsive">
				<div class="box-header">
					<h3 class="box-title"><?= $title ?></h3>
				</div>
				<table id="example3" class="table table-bordered table-hover">
					<thead>
					<tr>
						<th>#</th>
						<th>Image</th>
						<th>Email</th>
						<th>User Name</th>
						<th>Full Name</th>
						<th>Type</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if (isset($users) && is_array($users)) {
						foreach ($users as $row) {
							echo '<tr>';
							echo '<td align="center">' . $row['user_id'] . '</td>';
							echo '<td><img width="70" height="70" src="' . media_thumbnail($row['avatar'], 70) . '" /></td>';
							echo '<td>' . $row['email'] . '</td>';
							echo '<td>' . $row['user_name'] . '</td>';
							echo '<td>' . $row['full_name'] . '</td>';
							echo '<td>' . getTypeMember($row['user_type']) . '</td>';
							echo "<td><div class='button-list'>
									<a href='" . base_url('user/edit/' . $row['user_id']) . "' /><button class='btn btn-info btn-sm'>Edit</button></a>";
							echo "<a class='btn btn-primary btn-sm' href='" . base_url('user/profile/' . $row['user_id']) . "' >View Profile</a>";
							if ($row['status'] == 1) {
								echo "<a href='" . base_url('user/block/' . $row['user_id'] . '?redirect=' . uri_string()) . "' /><button class='btn btn-danger btn-sm'>Block</button></a>";
							} else {
								echo "<a href='" . base_url('user/block/' . $row['user_id'] . '?redirect=' . uri_string()) . "' /><button class='btn btn-success btn-sm'>Unblock</button></a>";
							}
							echo "<a href='" . base_url('user/delete/' . $row['user_id'] . '?redirect=' . uri_string()) . "' /><button class='btn btn-danger btn-sm'>Delete</button></a>";
							echo '</div></td>';
							echo '</tr>';
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
