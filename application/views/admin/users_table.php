<table id="example3" class="table table-hover">
	<thead>
	<tr><hr>
		<th></th>
		<th>ID</th>
		<th>Name</th>
		<th>E-Mail</th>
		<th>App Activity</th>
		<th>Version</th>
		<th>Created</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
	<?php
	if (isset($users) && is_array($users))
	{
		foreach ($users as $key => $row):
	?>
	<tr>
		<td><img style="max-width: 70px; max-height: 70px; border-radius:5px" src="<?= media_thumbnail($row['avatar'], 70) ?>"/>
		</td>
		<td><?php echo $row['user_id'] ?></td>
		<td><?php echo $row['user_name']?></td>
		<td><?php echo $row['email']?></td>
		<td>Comments:&nbsp;<?php echo $row['total_comment']?> <br> 
			Thumbs&nbsp;up:&nbsp;<?php echo $row['total_like']?> <br>
			Picks:&nbsp;<?php echo $row['total_pick']?>
		</td>
		<td><?php echo $row['device_name']?></td>
		<td><?php echo date('m/d/Y h:iA', $row['joined'])?></td>
		<td>
			<div class="dropdown">
			    <span class="btnAction dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-ellipsis-v"></i></span>
			    <ul class="dropdown-menu" id="customDropdown">
			      	<li><a href="<?php echo base_url('user/profile/' . $row['user_id'])?>">View Profile</a></li>
			      	<li><a href="<?php echo base_url('user/edit/' . $row['user_id'])?>">Edit Profile</a></li>
			      	<?php if ($row['status'] == 1): ?>
		      	   		<li><a href="<?php echo base_url('user/block/' . $row['user_id'])?>">Block</a></li>
		      	  	<?php else: ?>
			      		<li><a href="<?php echo base_url('user/unBlock/' . $row['user_id'])?>">Unblock</a></li>
		      		<?php endif; ?>
			      	<li class="divider"></li>
			      	<li><a href="<?php echo base_url('user/delete/' . $row['user_id']) ?>">Delete</a></li>
			    </ul>
			</div>
		</td>
	</tr>
	<?php 
		endforeach;
	}
	?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#example3').DataTable({
			    'ordering': false,
			    'dom' : 'frtilp',
			    'searching': false
			});
		});
	</script>
	
