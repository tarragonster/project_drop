<?php
$active = isset($_GET['active']) ? $_GET['active'] : 'profile';
?>
<ul class="nav nav-tabs">
	<li <?= $active == 'profile' ? 'class="active"' : '' ?>>
		<a data-toggle="tab" href="#profile">Profile</a>
	</li>
	<li <?= $active == 'your-picks' ? 'class="active"' : '' ?>>
		<a data-toggle="tab" href="#your-picks">Your Picks</a>
	</li>
	<li <?= $active == 'watch-list' ? 'class="active"' : '' ?>>
		<a data-toggle="tab" href="#watch-list">Watch List</a>
	</li>
	<li <?= $active == 'thumb-up' ? 'class="active"' : '' ?>>
		<a data-toggle="tab" href="#thumb-up">Thumb up</a>
	</li>
</ul>

<div class="row card-box">
	<div class="tab-content">
		<div id="profile" class="tab-pane fade in<?= $active == 'profile' ? ' active' : '' ?>">
			<div class="row">
				<div class="col-xs-6">
					<p class="lead">General</p>
					<div class="table-responsive">
						<table class="table">
							<tbody>
							<tr>
								<th style="width:50%">Full Name</th>
								<td><?php echo empty($user['full_name']) ? 'N/A' : $user['full_name']; ?></td>
							</tr>
							<tr>
								<th style="width:50%">User Name</th>
								<td><?php echo empty($user['user_name']) ? 'N/A' : $user['user_name']; ?></td>
							</tr>
							<tr>
								<th>Email</th>
								<td><?php echo $user['email']; ?></td>
							</tr>
							<tr>
								<th>Email</th>
								<td><?= getTypeMember($user['user_type']) ?></td>
							</tr>
							<tr>
								<th>Profile picture :</th>
								<td><img width='80' src='<?= media_thumbnail($user['avatar'], 80) ?>'/></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div id="your-picks" class="tab-pane fade in<?= $active == 'your-picks' ? ' active' : '' ?>">
			<div class="row">
				<div class="col-xs-12">
					<table class="table table-striped table-bordered"
					       data-delete-alert="Are you want to remove this pick?"
					       data-delete-confirm="Remove">
						<thead>
						<tr>
							<th>#</th>
							<th>Image</th>
							<th>Series Name</th>
							<th>Quote</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
						</thead>
						<tbody>
						<?php
						if ($your_picks != null && count($your_picks) > 0) {
							foreach ($your_picks as $row): ?>
								<tr>
									<td align="center"><?php echo $row['product_id']; ?></td>
									<td><img style="max-width: 70px; max-height: 70px" src="<?= media_thumbnail($row['image'], 70) ?>"/></td>
									<td><?php echo $row['name'] ?></td>
									<td><?php echo $row['quote']; ?></td>
									<td><?php echo($row['status'] == 1 ? 'Enable' : ($row['status'] == 0 ? 'Disable' : 'Deleted')) ?></td>
									<td>
										<div class='button-list'>
											<a href='<?php echo base_url('user/editPick/' . $row['pick_id']) ?>'>
												<button class='btn btn-inverse btn-custom btn-xs'>Edit</button>
											</a>
											<button class="btn btn-danger btn-custom btn-xs sa-delete" type="button"
											        data-href="<?php echo redirect_url('user/removePick/' . $row['pick_id'], ['active' => 'your-picks']) ?>">
												Remove
											</button>
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

		<div id="watch-list" class="tab-pane fade in<?= $active == 'watch-list' ? ' active' : '' ?>">
			<div class="row">
				<div class="col-xs-12">
					<table class="table table-striped table-bordered"
					       data-delete-alert="Are you want to remove this series from you watch list?"
					       data-delete-confirm="Remove">
						<thead>
						<tr>
							<th>#</th>
							<th>Image</th>
							<th>Series Name</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
						</thead>
						<tbody>
						<?php
						if ($watch_list != null && count($watch_list) > 0) {
							foreach ($watch_list as $row): ?>
								<tr>
									<td align="center"><?php echo $row['product_id']; ?></td>
									<td><img style="max-width: 70px; max-height: 70px" src="<?= media_thumbnail($row['image'], 70) ?>"/></td>
									<td><?php echo $row['name'] ?></td>
									<td><?php echo($row['status'] == 1 ? 'Enable' : ($row['status'] == 0 ? 'Disable' : 'Deleted')) ?></td>
									<td>
										<div class='button-list'>
											<button class="btn btn-danger btn-custom btn-xs sa-delete" type="button"
											        data-href="<?php echo redirect_url('user/removeWatch/' . $row['id'], ['active' => 'watch-list']) ?>">
												Remove
											</button>
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

		<div id="thumb-up" class="tab-pane fade in<?= $active == 'thumb-up' ? ' active' : '' ?>">
			<div class="row">
				<div class="col-xs-12">
					<table class="table table-striped table-bordered"
					       data-delete-alert="Are you want to remove this episode from your thumb up list?"
					       data-delete-confirm="Remove">
						<thead>
						<tr>
							<th>#</th>
							<th>Image</th>
							<th>Name</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
						</thead>
						<tbody>
						<?php
						if ($thumbs_up != null && count($thumbs_up) > 0) {
							foreach ($thumbs_up as $row): ?>
								<tr>
									<td align="center"><?php echo $row['product_id']; ?></td>
									<td><img style="max-width: 70px; max-height: 70px" src="<?= media_thumbnail($row['image'], 70) ?>"/></td>
									<td><?php echo $row['name'] ?></td>
									<td><?php echo($row['status'] == 1 ? 'Enable' : ($row['status'] == 0 ? 'Disable' : 'Deleted')) ?></td>
									<td>
										<div class='button-list'>
											<button class="btn btn-danger btn-custom btn-xs sa-delete" type="button"
											        data-href="<?php echo redirect_url('user/removeProductLike/' . $row['id'], ['active' => 'thumb-up']) ?>">
												Remove
											</button>
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
</div>