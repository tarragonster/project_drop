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
	border: 3px #999 solid;
	padding: 1px;
}
</style>
<form action='' method='POST' enctype="multipart/form-data">
	<div class="row">
		<!-- left column -->
		<div class="col-md-6">
			<!-- general form elements -->
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">Account</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label>Email</label>
						<input type="text" name='email' value="<?php echo $email;?>" class="form-control" placeholder="" />
					</div>
					<div class="form-group">
						<label>Username</label>
						<input type="text" name='user_name' value="<?php echo $user_name;?>" class="form-control" placeholder="" />
					</div>
					<div class="form-group">
						<label>Fullname</label>
						<input type="text" name='full_name' value="<?php echo $full_name;?>" class="form-control" placeholder="" />
					</div>
					<div class="form-group">
						<label>Avatar</label> <br/>
						<img width='200' src='<?php echo createThumbnailName(base_url($avatar)); ?>'/>
					</div>
					<div class="form-group">
						<label>Change Avatar</label> <br/>
						<input type="file" name='avatar'  class="form-control" placeholder="" />
					</div>
					<div class="form-group">
						<!-- <button type="submit" class="btn btn-primary" name='cmd'
							value='Save'>Save</button>-->
						<button type="submit" class="btn btn-primary" name='cmd' value='SaveContinue'>Save And Continue</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

