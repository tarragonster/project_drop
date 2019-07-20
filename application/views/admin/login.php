<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>10 Block cPanel | Log in</title>
        <link rel="shortcut icon" type="image/ico" href="<?php echo base_url("media/core/favicon.ico"); ?>" />
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="../assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <script src="../assets/js/modernizr.min.js"></script>
    </head>
    <body class="bg-black">
        <div class="animationload">
            <div class="loader"></div>
        </div>

        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class=" card-box">
            <div class="panel-heading"> 
                <h3 class="text-center"> Sign In to <strong class="text-custom">10 Block</strong> </h3>
            </div> 


            <div class="panel-body">
            <form class="form-horizontal m-t-20" action="<?php echo isset($url) ? "?url=$url" : ''; ?>" method="post">
                <?php 
                        if (isset($error)) {
                            echo "
                            <div style='color: red; magrin: 8px; border: solid 1px #f0a8b5; background: rgba(230,117,136,0.15); padding: 10px; border-radius: 5px'>
                                $error
                            </div>";
                        }
                    ?>
                <div class="form-group ">
                    <div class="col-xs-12">
                        <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo isset($email) ? $email : '';?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo isset($password) ? $password : '';?>"/>
                    </div>
                </div>
                
                <div class="form-group text-center m-t-40">
                    <div class="col-xs-12">
                        <button class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit" name='cmd' value='Submit'>Log In</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/detect.js"></script>
        <script src="../assets/js/fastclick.js"></script>
        <script src="../assets/js/jquery.slimscroll.js"></script>
        <script src="../assets/js/jquery.blockUI.js"></script>
        <script src="../assets/js/waves.js"></script>
        <script src="../assets/js/wow.min.js"></script>
        <script src="../assets/js/jquery.nicescroll.js"></script>
        <script src="../assets/js/jquery.scrollTo.min.js"></script>


        <script src="../assets/js/jquery.core.js"></script>
        <script src="../assets/js/jquery.app.js"></script>      
    </body>
</html>