<style>
	.button {
		text-align: center;
		background: #428923;
		display: inline-block;
		width: 100px !important;
		padding: 5px 15px 5px 15px;
		font-weight: bold;
	}

	.form-group img {
		border: 2px #ECECEC solid;
		padding: 1px;
		border-radius: 4px;
	}
</style>
<form action='' method='POST' enctype="multipart/form-data">
	<div class="row">
		<!-- left column -->
		<div class="col-md-6">
			<!-- general form elements -->
			<div class="card-box">
				<div class="box-header">
					<h3 class="box-title">Account</h3>
				</div>
				<?php
				$error_message = $this->session->flashdata('error_message');
				if (!empty($error_message)) {
					echo '<div class="row"><div class="col-xs-12">';
					echo '<div class="alert alert-danger">' . $error_message . '</div>';
					echo '</div></div>';
				}
				?>
				<div class="box-body">
					<div class="form-group">
						<label>Email</label>
						<input type="text" name='email' value="<?php echo $email; ?>" class="form-control" placeholder=""/>
					</div>
					<div class="form-group">
						<label>Username</label>
						<input type="text" name='user_name' value="<?php echo $user_name; ?>" class="form-control" placeholder=""/>
					</div>
					<div class="form-group">
						<label>Full Name</label>
						<input type="text" name='full_name' value="<?php echo $full_name; ?>" class="form-control" placeholder=""/>
					</div>
					<div class="form-group">
						<label>Type</label>
						<select class="form-control" required name='user_type'>
							<option value="0" <?= $user_type == 0 ? 'selected' : '' ?>>Normal user</option>
							<option value="1" <?= $user_type == 1 ? 'selected' : '' ?>>Verified user</option>
							<option value="2" <?= $user_type == 2 ? 'selected' : '' ?>>SS Curators</option>
						</select>
					</div>
					<div class="form-group">
						<label>Avatar</label> <br/>
						<img width='200' src='<?= media_thumbnail($avatar, 200) ?>'/>
					</div>
					<div class="form-group">
						<label>Change Avatar</label> <br/>
						<input type="file" name='avatar' class="form-control" placeholder=""/>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary">Save And Continue</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

