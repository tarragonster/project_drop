<div class="row">
	<?php if ($this->session->flashdata('add')) {
		echo '<div class="col-xs-12"><div class="alert alert-success">';
		echo $this->session->flashdata('add');
		echo '</div></div>';
	}
	if ($this->session->flashdata('remove')) {
		echo '<div class="col-xs-12"><div class="alert alert-success">';
		echo $this->session->flashdata('remove');
		echo '</div></div>';
	} ?>
	<div class="col-xs-12">
		<h3 class="m-t-0 m-b-20 header-title">Actor <?= $name ?></h3>
	</div>
</div>

<ul class="nav nav-tabs">
	<li class="<?php if (!$this->session->flashdata('add') && !$this->session->flashdata('remove')) echo 'active' ?>"><a data-toggle="tab" href="#detail">Detail</a></li>
	<li class="<?php if ($this->session->flashdata('remove')) echo 'active' ?>"><a data-toggle="tab" href="#list">List Films</a></li>
	<li class="<?php if ($this->session->flashdata('add')) echo 'active' ?>"><a data-toggle="tab" href="#add">Assign</a></li>
</ul>
<div class="row card-box">
	<div class="tab-content">
		<div id="detail" class="tab-pane fade in <?php if (!$this->session->flashdata('add') && !$this->session->flashdata('remove')) echo 'active' ?>">
			<div class="col-xs-12">
				<form action='' method='POST' enctype="multipart/form-data">
					<div class="row">
						<!-- left column -->
						<div class="col-md-6 card-box">
							<!-- general form elements -->
							<div class="box-header">
								<h3 class="m-t-0 m-b-30 header-title">Edit Actor</h3>
							</div>
							<div class="box-body">
								<div class="form-group">
									<label>Name</label>
									<input id="nameActor" type="text" name='name' value="<?= $name; ?>" required class="form-control" placeholder=""/>
								</div>
								<div class="form-group">
									<label>From</label>
									<input type="text" name='country' value="<?= $country; ?>" required class="form-control" placeholder=""/>
									<input id="link_imdb" type="hidden" name='link_imdb' required class="form-control" placeholder=""/>
								</div>
								<div class="form-group">
									<div style="display: flex; display: -webkit-flex;justify-content: space-between;align-items: center;margin-bottom: 8px;">
										<label style="margin-bottom: 0px;">Description</label>
										<button id="getDescription" type="button" class="btn btn-inverse btn-custom btn-xs">Get Description</button>
									</div>
									<div id="descriptionActor">
										<textarea name="description" class="form-control" placeholder="" rows="3" required=""><?= $description; ?></textarea>
									</div>

								</div>
								<div class="form-group row">
									<label class="col-md-12">Image</label>
									<div class="col-md-4">
										<img id='image' width='120' height='120' src='<?= media_thumbnail($image, 120) ?>' style='border: 4px solid #c6c6c6; border-radius: 4px'/>
									</div>
									<div class="col-md-8">
										<img style="padding-left: 45%;position: absolute;top: 40%;" src="<?= base_url('assets/images/plus.png') ?>">
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
										<input id="facebook" type="text" name="facebook_link" class="form-control" placeholder="Facebook link" value="<?= $facebook_link ?>">
										<input id="idFaceBook" type="hidden" name="facebook" class="form-control" placeholder="Facebook link">
									</div>
									<div class="input-group m-t-10">
									<span class="input-group-btn">
										<button type="button" style='width: 48px' class="btn waves-effect waves-light btn-twitter"><i class="fa fa-twitter"></i>
										</button>
									</span>
										<input type="text" name="twitter" class="form-control" placeholder="Twitter link"
										       value="<?= empty($twitter) ? '' : $twitter ?>">
									</div>
									<div class="input-group m-t-10">
									<span class="input-group-btn">
										<button type="button" style='width: 48px' class="btn waves-effect waves-light btn-instagram"><i class="fa fa-instagram"></i>
										</button>
									</span>
										<input type="text" name="instagram" class="form-control" placeholder="Instagram link"
										       value="<?= empty($instagram) ? '' : $instagram ?>">
									</div>
								</div>
								<div class="form-group m-b-0">
									<button type="submit" class="btn btn-inverse btn-custom" name='cmd' value='Save'>Update</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div id="list" class="tab-pane fade in <?php if ($this->session->flashdata('remove')) echo 'active' ?>">
			<div class="row">
				<div class="col-xs-12">

					<div class="table-responsive">
						<table id="example3" class="table table-bordered table-hover">
							<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Image</th>
								<th>Year</th>
								<th>Actions</th>
							</tr>
							</thead>
							<tbody>
							<?php
							if ($products != null && count($products) > 0) {
								foreach ($products as $row) {
									echo '<tr>';
									echo '<td align="center">' . $row['product_id'] . '</td>';
									echo '<td>' . $row['name'] . '</td>';
									echo '<td><img style="max-width: 70px; max-height: 70px" src="' . base_url($row['image']) . '"/></td>';
									echo '<td>' . $row['publish_year'] . '</td>';
									echo "<td><div class='button-list'>";
									echo "<a href='" . base_url('actor/removeProduct/' . $row['product_id'] . '/' . $cast_id) . "' /><button class='btn btn-danger btn-custom btn-xs'>Remove</button></a>";
									echo "</div></td>";
									echo '</tr>';
								}
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="add" class="tab-pane fade in <?php if ($this->session->flashdata('add')) echo 'active' ?>">
			<div class="row">
				<div class="col-xs-6 form-group">
					<label>Film Name</label>
					<input type="text" id="nameFilm" class="form-control">
				</div>
				<div class="col-xs-12">

					<div class="table-responsive">
						<table id="example3" class="table table-bordered table-hover">
							<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Image</th>
								<th>Year</th>
								<th>Actions</th>
							</tr>
							</thead>
							<tbody id="bodyFilm">
							<?php
							if ($others != null && count($others) > 0) {
								foreach ($others as $row) {
									echo '<tr>';
									echo '<td align="center">' . $row['product_id'] . '</td>';
									echo '<td>' . $row['name'] . '</td>';
									echo '<td><img style="max-width: 70px; max-height: 70px" src="' . base_url($row['image']) . '"/></td>';
									echo '<td>' . $row['publish_year'] . '</td>';
									echo "<td><div class='button-list'>";
									echo "<a href='" . base_url('actor/addProduct/' . $row['product_id'] . '/' . $cast_id) . "' /><button class='btn btn-inverse btn-custom btn-xs'>Add</button></a>";
									echo "</div></td>";
									echo '</tr>';
								}
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>