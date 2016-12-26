<div class="login-form">
    <div class="card-box">
        <?php if($this->session->flashdata('error')){
			echo "<div class='panel-heading'><h3 class='text-center'>Error</h3></div>";
            echo '<div class="panel-body"><div class="col-xs-12" style="padding-left: 15px;padding-right: 15px;"><div class="alert alert-danger">';
            echo $this->session->flashdata('error');
            echo '</div></div></div>';
        }else if($this->session->flashdata('msg')){
            echo '<div class="panel-body"><div class="col-xs-12" style="padding-left: 15px;padding-right: 15px;"><div class="alert alert-success">';
            echo $this->session->flashdata('msg');
            echo '</div></div></div>';
        }else{?>
        	<div class="panel-heading"> 
	            <h3 class="text-center">Change password in <strong class="text-custom">Second Screen</strong> </h3>
	        </div>
	        <?php if($this->session->flashdata('error1')){
                echo '<div class="col-xs-12" style="padding-left: 15px;padding-right: 15px;"><div class="alert alert-danger">';
                echo $this->session->flashdata('error1');
                echo '</div></div>';
            }?>
	        <div class="panel-body">
		        <form accept-charset="UTF-8" method="post">
                    <div class="form-group">
                        <label class="control-label">New password</label>
    			    	<input type='password' class="form-control" name='password' />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Repeat password</label>
    			    	<input type='password' class="form-control" name='re_password' />
                    </div>
                    <div class="form-group">
			    	    <button type="submit" class="btn btn-pink btn-block text-uppercase waves-effect waves-light" name='cmd' value='Submit'>Submit »</button>
                    </div>
				</form>
			</div> 
        <?php } ?>
    </div>
</div>
