<div class="container">
	<form action='' method='POST'>
		<div class="row">
			<div class="col-md-6">
				<div class="card-box">
					<div class="box-header">
						<h3 class="m-t-0 m-b-30 header-title">Edit Collection</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label>Name</label>
							<input type="text" name='name' value="<?php echo $name; ?>" class="form-control" placeholder="" required/>
						</div>
						<div class="form-group">
							<label>Description</label>
							<input type="text" name='short_bio' value="<?php echo $short_bio; ?>" class="form-control" placeholder="" required/>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary" name='cmd' value='Save'>Update</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>