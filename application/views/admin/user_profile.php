<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#profile">Profile</a></li>
</ul>

<div class="row card-box"> 
	<div class="tab-content">
	    <div id="profile" class="tab-pane fade in active">
	        <div class="col-xs-6">
	            <p class="lead">General</p>
	            <div class="table-responsive">
	                <table class="table">
	                    <tbody>
	                        <tr>
	                            <th style="width:50%">Full Name</th>
	                            <td><?php echo empty($user['full_name']) ? 'N/A' : $user['full_name']; ?></td>
	                        </tr>
	                        <tr>
	                            <th style="width:50%">User Name</th>
	                            <td><?php echo empty($user['user_name']) ? 'N/A' : $user['user_name']; ?></td>
	                        </tr>
	                        <tr>
	                            <th>Email</th>
	                            <td><?php echo $user['email']; ?></td>
	                        </tr>
	                        <tr>
	                            <th>Profile picture : </th>
	                            <td><img width='80' src='<?php echo createThumbnailName(base_url($user['avatar']));?>' /></td>
	                        </tr>
	                    </tbody>
	                </table>
	            </div>
	        </div>
	    </div>
	</div>
</div>