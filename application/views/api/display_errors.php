<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4"></div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
        <div class="wrapper">
            <div class="header">10 Block</div>
            <div class="body">
                <div class="content-title">
                	<img src="assets/images/x.jpg" alt="icon-x">
	                <?php if($this->session->flashdata('error2')):?>
                        <p>
                            <?php echo $this->session->flashdata('error2')?>
                        </p>
                    <?php endif;?>
	            </div>
            </div>
        </div>
    </div>
</div>
