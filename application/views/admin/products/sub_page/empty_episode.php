<div class="section-content">
    <form action="" method="get">
        <?php foreach ($seasons as $key=>$value): ?>
        <div class="hub-faq-title"><span><?=$value['name']?></span></div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table dataTable">
                <?php $this->load->view('admin/base/table_header')?>
                <tbody>
					<div class="card-box season-mess-box">
						<span>Click <b>Add New</b> to add blocks to this story</span>
					</div>
				</tbody>
			</table>
		</div>
		<?php endforeach; ?>
	</form>
</div>

<div class="modal custom-modal below-header fade visiting right" id="add-block-popup" tabindex="-1" role="dialog">
	<form action="<?php echo base_url('product/addEpisode')?>" method="post" id="block-form-add" enctype="multipart/form-data">
		<div class="modal-dialog" role="document">
			<div class="modal-content" id="add-block-form" style="padding: 0px">
				
			</div>
		</div>
	</form>
</div>
