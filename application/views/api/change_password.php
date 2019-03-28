<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4"></div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
        <div class="wrapper">
            <div class="header">10 Block</div>
            <div class="body">
                <div class="content-title">Change Your Password</div>
                <div class="content-form">
                    <form action="<?php echo root_domain() . '/reset-success' ?>" method="post" id="form">
                        <div class="form-group">
                          <label for="pwd">New Password</label>
                          <input type="password" class="form-control" name="pwd" id="pwd">
                        </div>
                        <div class="form-group">
                          <label for="re-pwd">Repeat Password</label>
                          <input type="password" class="form-control" name="re-pwd" id="re-pwd">
                        </div>
                        <p class="error-msg"></p>
                        <button type="submit" class="btn btn-block" name="cmd" value="submit">Submit</button>
                        <button type="submit" class="btn btn-lg" id="btn-arrow"><i class="fa fa-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
