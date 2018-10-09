<?php header('Content-typ: content="text/html; charset=UTF-8'); ?>
<div class="container">
	<form action='' method='POST' enctype="multipart/form-data">
		<div class="row">
			<!-- left column -->
			<div class="col-md-6 card-box">
				<!-- general form elements -->
				<div class="box-header">
					<h3 class="m-t-0 m-b-30 header-title">Create Actor</h3>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label>Name</label>
						<input id="nameActor" type="text" name='name' required class="form-control" placeholder=""/>
					</div>
					<div class="form-group">
						<label>From</label>
						<input type="text" name='country' required class="form-control" placeholder=""/>
						<input id="link_imdb" type="hidden" name='link_imdb' required class="form-control" placeholder=""/>
					</div>
					<div class="form-group">
						<div style="display: flex; display: -webkit-flex;justify-content: space-between;align-items: center;margin-bottom: 8px;">
							<label style="margin-bottom: 0px;">Description</label>
							<button id="getDescription" type="button" class="btn btn-inverse btn-custom btn-xs">Get Description</button>
						</div>
						<textarea id="descriptionActor" name="description" class="form-control" placeholder="" rows="3" required=""></textarea>
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
					<div class="form-group row">
						<label class="col-md-12">Image</label>
						<div class="col-md-4">
							<img id='image' width='120' height='120' src='' style='border: 4px solid #c6c6c6; border-radius: 4px'/>
						</div>
						<div class="col-md-8">
							<img style="padding-left: 45%;position: absolute;top: 40%;" src="<?php echo base_url('assets/images/plus.png') ?>">
							<div class="uploader" onclick="$('#imagePhoto').click()">
								<input type="file" accept="image/*" name="image" id="imagePhoto"/>
							</div>
						</div>
					</div>
					<div class="form-group" style='margin-top:10px'>
						<label>Social Connection</label>

						<div class="input-group m-t-10">
							<span class="input-group-btn">
								<button type="button" style='width: 48px' class="btn waves-effect waves-light btn-facebook"><i
										class="fa fa-facebook"></i></button>
						</span>
							<input id="facebook" type="text" name="facebook_link" class="form-control" placeholder="Facebook link">
							<input id="idFaceBook" type="hidden" name="facebook" class="form-control" placeholder="Facebook link">
						</div>
						<div class="input-group m-t-10">
						<span class="input-group-btn">
							<button type="button" style='width: 48px' class="btn waves-effect waves-light btn-twitter"><i class="fa fa-twitter"></i>
							</button>
						</span>
							<input type="text" name="twitter" class="form-control" placeholder="Twitter link">
						</div>
						<div class="input-group m-t-10">
						<span class="input-group-btn">
							<button type="button" style='width: 48px' class="btn waves-effect waves-light btn-instagram"><i class="fa fa-instagram"></i>
							</button>
						</span>
							<input type="text" name="instagram" class="form-control" placeholder="Instagram link">
						</div>
					</div>
					<div class="form-group m-b-0">
						<button type="submit" class="btn btn-inverse btn-custom" name='cmd' value='Save'>Save</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>