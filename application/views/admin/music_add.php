<div class="container">
	<form action='' method='POST' enctype="multipart/form-data">
		<div class="row">
			<!-- left column -->
			<div class="col-md-6 card-box">
				<!-- general form elements -->
				<div class="box-header">
					<h3 class="m-t-0 m-b-30 header-title">Create Music</h3>
				</div>
				<div class="box-body">
					<div class="form-group">                       
						<label>Name</label>                        
                        <input type="text" name='name' required class="form-control" placeholder="" />
					</div>
					<div class="form-group">                       
						<label>Singer</label>                        
                        <input type="text" name='singer' required class="form-control" placeholder="" />
					</div>
					<div class="form-group">
                        <label>Film</label>
                        <select id='product_id' class="form-control" required name='product_id'>
                        	<option value="">Select Film</option>
                            <?php
                                foreach ($products as $item) {
                                    echo "<option value='{$item['product_id']}'>{$item['name']}</option>";
                                }
                            ?>
                        </select>
                    </div>
					<div class="form-group">
                    	<label>Mp3</label>
						<input type="file" class="form-control" name="music_url" accept="audio/*" required/>
					</div>
					<div class="form-group m-b-0">
                        <button type="submit" class="btn btn-inverse btn-custom" name='cmd' value='Save'>Save</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>