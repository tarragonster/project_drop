<div class="container">
	<form action='' method='POST' enctype="multipart/form-data">
		<div class="row">
			<!-- left column -->
			<div class="col-md-6 card-box">
				<!-- general form elements -->
				<div class="box box-primary">
					<div class="box-header">
						<h3 class="m-t-0 m-b-30 header-title">Edit Season</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<label>Name</label> 
							<input type="text" name='name' value="<?php echo $name;?>" class="form-control" placeholder="" required />
						</div>
						<div class="form-group">
	                        <label>Film</label>
	                        <select id='product_id' class="form-control" required name='product_id'>
	                            <?php
	                                foreach ($products as $item) {
	                                    echo "<option value='{$item['product_id']}' ". ($item['product_id'] == $product_id ? ' selected' : '').">{$item['name']}</option>";
	                                }
	                            ?>
	                        </select>
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