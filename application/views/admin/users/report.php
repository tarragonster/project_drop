<div class="row">
	<div class="col-xs-12" style='margin-top: 10px'>
		<div class="box">
			<div class="card-box table-responsive">
				<div class="box-header">
					<h3 class="box-title">Reported Users</h3>
				</div>
				<table id="example3" class="table table-bordered table-hover">
					<thead>
					<tr>
						<th>#</th>
						<th>User&nbsp;Name</th>
						<th>Reporter</th>
						<th>Date</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if (isset($reports) && is_array($reports)) {
						foreach ($reports as $row) {
							echo '<tr>';
							echo '<td align="center">' . $row['report_id'] . '</td>';
							echo '<td>' . $row['full_name'] . '</td>';
							echo '<td>' . $row['reporter_name'] . '</td>';
							echo '<td>' . timeFormat($row['created_at']) . '</td>';
							echo "<td><div class='button-list'>";
							echo "<a href='" . base_url('user/deleteReport/' . $row['report_id'] . '?redirect=' . uri_string()) . "' /><button class='btn btn-danger btn-sm'>Delete</button></a>";
							echo '</div></td>';
							echo '</tr>';
						}
					}
					?>
					</tbody>
				</table>

				<?php $has_items = isset($paging) && $paging['total'] > 0; ?>
				<div class="row">
					<?php if ($has_items): ?>
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
