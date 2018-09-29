<div class="container">
	<form action='' method='POST'>
		<div class="row">
			<div class="col-md-6 card-box">
				<div class="box-header">
					<h3 class="m-t-0 m-b-30 header-title">Edit Quote</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label>Quote</label>
						<input type="text" name='quote' value="<?php echo $quote; ?>" required class="form-control"/>
					</div>
					<div class="form-group m-b-0">
						<button type="submit" class="btn btn-inverse btn-custom">Update</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>