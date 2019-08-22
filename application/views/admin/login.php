<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon_1.ico'); ?>">

        <title>10 Block cPanel</title>

        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">
        <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/css/core.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/css/components.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/css/icons.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/css/pages.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/css/responsive.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/css/font-awesome.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('module/css/admin_main_layout.css'); ?>" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('assets/css/login.css'); ?>" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.4/css/all.css" >

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <?php
        if (isset($customCss) && is_array($customCss)) {
            foreach ($customCss as $style) {
                echo '<link href="' . base_url($style) . '" rel="stylesheet" type="text/css" />' . "\n";
            }
        }
        ?>
        <script src="<?php echo base_url('assets/js/modernizr.min.js'); ?>"></script>
        <script>
            var BASE_APP_URL = "<?= base_url()?>"
        </script>
    </head>
    <body style="background: #c7ae6e">
        <div class="grnlt-table-wrap">
            <div class="grnlt-align-wrap">
                <div class="login">
                    <div class="logo text-center">
                        <img src="<?php echo base_url('favicon.ico')?>" width="120" alt="logo name">
                    </div>
                    <form class="m-form" action="<?php echo isset($url) ? "?url=$url" : ''; ?>" method="post">
                        <?php 
                            if (isset($error)) {
                                echo "
                                <div style='color: white; margin-bottom: 20px;border: solid 1px #ffffff; background: rgba(255, 255, 255, 0.15); padding: 10px; border-radius: 5px'>
                                    $error
                                </div>";
                            }
                            if(!empty($this->session->flashdata('mess'))) {
                            ?>
                                <div style='color: white; margin-bottom: 20px;border: solid 1px #ffffff; background: rgba(255, 255, 255, 0.15); padding: 10px; border-radius: 5px'>
                                    <?php echo $this->session->flashdata('mess'); ?>
                                </div>
                            <?php
                            }
                        ?>
                        <div class="form-group">
                            <label id="m_email_label" class="form-label">EMAIL ADDRESS</label>
                            <input type="text" class="m-form-control" name="email" required="" autocomplete="off" value="<?php echo isset($email) ? $email : '';?>" autofocus="">
                        </div>
                        <div class="form-group">
                            <label id="m_password_label" class="form-label">PASSWORD</label>
                            <input type="password" class="m-form-control" name="password" required="" autocomplete="off"value="<?php echo isset($password) ? $password : '';?>">
                        </div>
                        <div class="text-center text-guide">By logging in you agree to our terms of service and privacy policy.</div>
                        <div class="form-group text-center">
                            <input type="submit" class="btn" name="cmd" value="login">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="forgot-block text-center">
            <a href="<?php echo base_url('forgotPassword')?>">Forgot Password?</a>
        </div>
    </body>
</html>