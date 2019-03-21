<?php
$active = isset($_GET['active']) ? $_GET['active'] : 'list';
?>
<div class="row">
	<div class="col-xs-12">
		<h3 class="m-t-0 m-b-20 header-title">Featured Users</h3>
	</div>
</div>
<ul class="nav nav-tabs">
	<li <?= $active == 'list' ? 'class="active"' : '' ?>>
		<a data-toggle="tab" href="#list">List Profile</a>
	</li>
	<li <?= $active == 'add' ? 'class="active"' : '' ?>><a data-toggle="tab" href="#add">
			Add Profile</a>
	</li>
</ul>

<div class="row card-box">
	<div class="tab-content">
		<div id="list" class="tab-pane fade in<?= $active == 'list' ? ' active' : '' ?>">
			<div class="col-xs-12">
				<div class="box">
					<div class="card-box table-responsive">
						<table id="example3" class="table table-striped table-bordered" data-alert="Are you want to delete this product?">
							<thead>
							<tr>
								<th style="text-align: center">#</th>
								<th>Image</th>
								<th>Email</th>
								<th>User Name</th>
								<th>Full Name</th>
								<th>Type</th>
								<th>Position</th>
								<th>Actions</th>
							</tr>
							</thead>
							<tbody>
							<?php
							if ($users != null && count($users) > 0) {
								foreach ($users as $row): ?>
									<tr>
										<td align="center"><?= $row['user_id'] ?></td>
										<td><img width="70" height="70" src="<?= media_thumbnail($row['avatar'], 70) ?>"/></td>
										<td><?= $row['email'] ?></td>
										<td><?= $row['user_name'] ?></td>
										<td><?= $row['full_name'] ?></td>
										<td><?= getTypeMember($row['user_type']) ?></td>
										<?php
										echo '<td>';
										echo '<div class="button-list">';
										if ($row['priority_profile'] > 1) {
											echo "<a href='" . base_url('featured/upProfile/' . $row['user_id']) . "'>
				                                    <button class='btn btn-sm btn-purple'><i class='fa fa-long-arrow-up'></i> Up</button>
				                                </a>";
										}
										if ($row['priority_profile'] < $max) {
											echo "<a href='" . base_url('featured/downProfile/' . $row['user_id']) . "'>
				                                    <button class='btn btn-sm btn-inverse'><i class='fa fa-long-arrow-down'></i> Down</button>
				                                </a>";
										}
										echo '</div></td>'; ?>
										<td>
											<div class='button-list'>
												<a href='<?= base_url('featured/removeProfile/' . $row['user_id']) ?>'>
													<button class='btn btn-danger btn-custom btn-xs'>Remove</button>
												</a>
											</div>
										</td>
									</tr>
								<?php endforeach;
							} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="add" class="tab-pane fade in<?= $active == 'add' ? ' active' : '' ?>">
			<div class="row">
				<div class="col-xs-12">
					<div class="box-body table-responsive">
						<table id="example3" class="table table-bordered table-hover">
							<thead>
							<tr>
								<th style="text-align: center">#</th>
								<th>Image</th>
								<th>Email</th>
								<th>User Name</th>
								<th>Full Name</th>
								<th>Type</th>
								<th>Actions</th>
							</tr>
							</thead>
							<?php
							if ($other_users != null && count($other_users) > 0) {
								foreach ($other_users as $row) { ?>
									<td align="center"><?= $row['user_id'] ?></td>
									<td><img width="70" height="70" src="<?= media_thumbnail($row['avatar'], 70) ?>"/></td>
									<td><?= $row['email'] ?></td>
									<td><?= $row['user_name'] ?></td>
									<td><?= $row['full_name'] ?></td>
									<td><?= getTypeMember($row['user_type']) ?></td>
									<td>
										<div class='button-list'>
											<a href="<?= base_url('featured/addProfile/' . $row['user_id']) ?>">
												<button class='btn btn-inverse btn-custom btn-xs'>Add</button>
											</a>
										</div>
									</td>
									</tr>
								<?php }
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>