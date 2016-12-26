<!DOCTYPE html>
<html class="lockscreen">
    <head>
        <meta charset="UTF-8">
        <title>AdminLTE | Lockscreen</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="<?php echo base_url("assets/css/bootstrap.min.css");?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/css/font-awesome.min.css");?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url("assets/css/AdminLTE.css");?>" rel="stylesheet" type="text/css" />
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <!-- Automatic element centering using js -->
        <div class="center">
        	<?php 
        	if (isset($error))
        		echo "
    		<div style='padding: 10px; border: 2px solid #ddd; color: #f00;'>
        		" . $error . "
        	</div> ";	
        	?>       
            <div class="headline text-center" id="time">
            </div>
            
            <!-- User name -->
            <div class="lockscreen-name"><strong><?php echo $account['name'];?></strong></div>
            
            <!-- START LOCK SCREEN ITEM -->
            <form action='' method='POST'>
            <div class="lockscreen-item">
                <!-- lockscreen image -->
                <div class="lockscreen-image">
                    <img src="<?php echo base_url($account['avatar']);?>" alt="user image"/>
                </div>
                <!-- /.lockscreen-image -->

                <!-- lockscreen credentials (contains the form) -->
                <div class="lockscreen-credentials">   

                    <div class="input-group">
                        <input type="password" name='password' class="form-control" placeholder="password" />
                        <div class="input-group-btn">
                            <button name='cmd' value='GoLock' class="btn btn-flat"><i class="fa fa-arrow-right text-muted"></i></button>
                        </div>
                    </div>
                </div><!-- /.lockscreen credentials -->

            </div><!-- /.lockscreen-item -->
             </form>
            
            <div class="lockscreen-link">
                <a href="<?php echo $other_account ?>">Or login with orther account</a>
            </div>            
        </div><!-- /.center -->

        <script src="<?php echo base_url("assets/js/jquery.min.js");?>"></script>
        <script src="<?php echo base_url("assets/js/bootstrap.min.js");?>" type="text/javascript"></script>        
        

        <!-- page script -->
        <script type="text/javascript">
            $(function() {
                startTime();
                $(".center").center();
                $(window).resize(function() {
                    $(".center").center();
                });
            });

            /*  */
            function startTime()
            {
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();

                // add a zero in front of numbers<10
                m = checkTime(m);
                s = checkTime(s);

                //Check for PM and AM
                var day_or_night = (h > 11) ? "PM" : "AM";

                //Convert to 12 hours system
                if (h > 12)
                    h -= 12;

                //Add time to the headline and update every 500 milliseconds
                $('#time').html(h + ":" + m + ":" + s + " " + day_or_night);
                setTimeout(function() {
                    startTime()
                }, 500);
            }

            function checkTime(i)
            {
                if (i < 10)
                {
                    i = "0" + i;
                }
                return i;
            }

            /* CENTER ELEMENTS IN THE SCREEN */
            jQuery.fn.center = function() {
                this.css("position", "absolute");
                this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                        $(window).scrollTop()) - 30 + "px");
                this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                        $(window).scrollLeft()) + "px");
                return this;
            }
        </script>
    </body>
</html>