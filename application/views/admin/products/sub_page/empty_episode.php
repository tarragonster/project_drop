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