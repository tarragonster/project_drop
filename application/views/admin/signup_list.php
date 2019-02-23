<div class="row">
	<div class="col-xs-12" style='margin-top: 10px'>
		<div class="box">
			<div class="card-box table-responsive">
				<div class="row">
					<div class="col-sm-6">
						<h3 class="box-title"><?= $title ?></h3>
					</div>
					<div class="col-sm-6 text-right">
						<a href="<?= base_url('user/exportSignUp')?>"><button class="btn btn-sm btn-inverse">Export</button></a>
					</div>
				</div>
				<table id="example3" class="table table-bordered table-hover">
					<thead>
					<tr>
						<th>#</th>
						<th>Email</th>
						<th>Full Name</th>
						<th>Date&nbsp;Submitted</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if (isset($users) && is_array($users)) {
						foreach ($users as $row) {
							echo '<tr>';
							echo '<td align="center">' . $row['id'] . '</td>';
							echo '<td>' . $row['email'] . '</td>';
							echo '<td>' . $row['full_name'] . '</td>';
							echo '<td>' . date('m/d/Y h:i:a', $row['added_at']) . '</td>';
							echo "<td><div class='button-list'>";
							echo "<a href='" . base_url('user/deleteSignUp/' . $row['id'] . '?redirect=' . uri_string()) . "' /><button class='btn btn-danger btn-sm'>Delete</button></a>";
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
