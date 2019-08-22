<div class="tab-pane <?= $page_index == 'general_settings' ? 'active' : ''; ?>" id="setting-profile" style="padding-top: 30px">
    <div class="card-box setting-card-box" style="border-radius: 16px">
        <div class="row">
            <div class="col-md-12">
                <h3 class="card-title">
                    Change Email
                </h3>
            </div>
        </div>
        <form action="<?= base_url('setting/changeEmail') ?>" method="post" autocomplete="off">
            <?php
                $error_message = $this->session->flashdata('error_email');
                if (isset($error_message)) {
                    echo "<div class='alert alert-warning'>{$error_message}</div>";
                }
                $success_email = $this->session->flashdata('success_email');
                if (isset($success_email)) {
                    echo "<div class='alert alert-success'>{$success_email}</div>";
                }
            ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group form-dispatch">
                        <label>Enter Current Email</label>
                        <input type="email" required name="account_email" class="form-control form-dispatch" autocomplete="off"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group form-dispatch">
                        <label>Enter Current Password</label>
                        <input type="password" required name="account_password" class="form-control form-dispatch" autocomplete="new-password"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group form-dispatch">
                        <label>Enter New Email</label>
                        <input type="email" required name="new_email" class="form-control form-dispatch"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="margin-top: 20px">
                    <button type="submit" class="btn btn--rounded btn-orange btn-change-email">Update</button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-box setting-card-box" style="border-radius: 16px">
        <div class="row">
            <div class="col-md-12">
                <h3 class="card-title">
                    Change  Password
                </h3>
            </div>
        </div>
        <form action="<?= base_url('setting/changePassword') ?>" method="post" id="form-change-password">
            <?php
                $error_password = $this->session->flashdata('error_password');
                if (isset($error_password)) {
                    echo "<div class='alert alert-warning'>{$error_password}</div>";
                }
                $success_password = $this->session->flashdata('success_password');
                if (isset($success_password)) {
                    echo "<div class='alert alert-success'>{$success_password}</div>";
                }
            ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group form-dispatch">
                        <label>Enter current password</label>
                        <input type="password" required name="account_password" class="form-control form-dispatch"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group form-dispatch">
                        <label>Enter new password</label>
                        <input type="password" required name="new_password" class="form-control form-dispatch"/>
                    </div>
                </div>
	            <div class="col-md-4">
		            <div class="form-group form-dispatch">
			            <label>Confirm new password</label>
			            <input type="password" required name="re_password" class="form-control form-dispatch"/>
		            </div>
	            </div>
                <div class="col-md-12" style="margin-top: 20px">
                    <button type="submit" class="btn btn--rounded btn-orange" id="change-password-btn">Update</button>
                </div>
            </div>
        </form> 
    </div>
</div>
