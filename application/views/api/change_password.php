<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4"></div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
        <div class="wrapper">
           
            <div class="body">
                <div class="content-title">Change Your Password</div>
                <div class="content-form">
                    <form action="" method="post" id="form">
                        <div class="form-group">
                          <label for="pwd">New Password</label>
                          <input type="password" class="form-control" name="password">
                        </div>
                        <div class="form-group">
                          <label for="re-pwd">Repeat Password</label>
                          <input type="password" class="form-control" name="re_password">
                        </div>
	                    <?php
	                    $error = $this->session->flashdata('error');
	                    ?>
	                    <p class="error-msg"<?= empty($error) ? ' style="display: none"' : '' ?> ><?= $error ?></p>
                        <button type="button" class="btn btn-block button-submit-password" name="cmd" value="submit">Submit</button>
                        <button type="button" class="btn btn-lg button-submit-password" id="btn-arrow"><i class="fa fa-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
