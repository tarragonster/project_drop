<div class="row">
	<div class="col-md-6">
		<div class="card-box">
			<div class="box-header">
				<h3 class="m-t-0 m-b-30 header-title">Edit Film</h3>
			</div>
			<div class="box-body">
				<form method='POST' enctype="multipart/form-data" action="">
					<div class="form-group" id='block_product'>
						<label>Film</label>
						<input id='select_product' class='form-control' type="text" placeholder='Film Name' data-href='<?php echo base_url('preview/ajaxProduct?q=') ?>'
						       data-linked-id='product_id'
						       value="<?= $name ?>"/>
						<input type="hidden" id='product_id' name='product_id' value="<?= $product_id ?>" class='form-control'/>
					</div>
					<div class="form-group m-b-30">
						<label>Image</label>
						<div class="row">
							<div class="col-md-4">
								<img id='image' width='120' height='120' style='border: 4px solid #c6c6c6; border-radius: 4px' src="<?= media_thumbnail($promo_image, 120) ?>"/>
							</div>
							<div class="col-md-8">
								<img style="padding-left: 45%;position: absolute;top: 40%;" src="<?php echo base_url('assets/images/plus.png') ?>">
								<div class="uploader" onclick="$('#imagePhoto').click()">
									<input type="file" accept="image/*" name="promo_image" id="imagePhoto"/>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary" name='cmd' value='Add'>Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
