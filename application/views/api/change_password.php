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
                        <?php if($this->session->flashdata('error')):?>
                            <p class="error-msg">
                                <?php echo $this->session->flashdata('error')?>
                            </p>
                        <?php else: ?>
                            <p class="error-msg"></p>
                            <?php if($this->session->flashdata('error1')):?>
                                <p class="error-msg">
                                    <?php echo $this->session->flashdata('error1')?>
                                </p>
                            <?php endif;?>
                        <?php endif;?>
                        <button type="submit" class="btn btn-block" name="cmd" value="submit">Submit</button>
                        <button type="submit" class="btn btn-lg" id="btn-arrow"><i class="fa fa-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
