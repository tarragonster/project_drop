<div class="container">
	<form action='' method='POST' enctype="multipart/form-data">
		<div class="row">
			<!-- left column -->
			<div class="col-md-6 card-box">
				<!-- general form elements -->
				<div class="box-header">
					<h3 class="m-t-0 m-b-30 header-title">Edit Music</h3>
				</div>
				<div class="box-body">
					<div class="form-group">                       
						<label>Name</label>                        
                        <input type="text" name='name' value="<?php echo $name;?>"  required class="form-control" placeholder="" />
					</div>
					<div class="form-group">                       
                        <label>Singer</label>                        
                        <input type="text" name='singer' value="<?php echo $singer;?>"   class="form-control" placeholder="" />
					</div>
					<div class="form-group">  
						<label>Film</label>
                        <select id='product_id' class="form-control" required name='product_id'>
                            <?php
                            	if($product_id == null || $product_id == ''){
                            		echo "<option value=''>Select Film</option>";
                            	}
                                foreach ($products as $item) {
                                    echo "<option value='{$item['product_id']}' ". ($item['product_id'] == $product_id ? ' selected' : '').">{$item['name']}</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                    	<label>Mp3</label>
                    	<p>
                    	<audio controls>
						  	<source src="<?php echo base_url($url);?>" type="audio/mpeg">
							Your browser does not support the audio element.
						</audio>
						</p>
						<input type="file" class="form-control" accept="audio/*" name="music_url"/>
					</div>
					<div class="form-group m-b-0">
                        <button type="submit" class="btn btn-inverse btn-custom" name='cmd' value='Save'>Update</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>