<div class="section-content">
        <?php foreach ($seasons as $key=>$value): ?>
        	<div class="empty-season">
		        <div class="hub-faq-title"><span><?=$value['name']?></span></div>
		        <div class="table-responsive">
		            <table class="table dataTable">
						<div class="col-sm-1 col-lg-1" style="padding: 0px"></div>
						<div class="col-sm-2 col-lg-1" style="padding: 0px">
							<div class="title-header">Block ID</div>
						</div>
						<div class="col-sm-3 col-lg-1">
							<div class="title-header">Block #</div>
						</div>
						<div class="col-sm-2 col-lg-3">
							<div class="title-header">Block Name</div>
						</div>
						<div class="col-sm-2 col-lg-2">
							<div class="title-header">Block Activity</div>
						</div>
						<div class="col-sm-2 col-lg-2">
							<div class="title-header">Create Date</div>
						</div>
						<div class="col-sm-1 col-lg-1">
							<div class="title-header">Status</div>
						</div>
						<div class="col-sm-1 col-lg-1">
							<div class="title-header">Actions</div>
						</div>
		                <tbody>
							<div class="card-box season-mess-box">
								<span>Click <b>Add New</b> to add blocks to this story</span>
							</div>
						</tbody>
					</table>
				</div>
			</div>
		<?php endforeach; ?>
</div>

<div class="modal custom-modal below-header fade visiting right" id="add-block-popup" tabindex="-1" role="dialog">
	<form action="<?php echo base_url('product/addEpisode')?>" method="post" id="block-form-add" enctype="multipart/form-data">
		<div class="modal-dialog" role="document">
			<div class="modal-content" id="add-block-form" style="padding: 0px">
				
			</div>
		</div>
	</form>
</div>
