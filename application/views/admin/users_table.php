<table id="example3 " class="table dataTable table-hover">
        <?php $this->load->view('admin/users/table_header') ?>
	<tbody>
	<?php
	if (isset($users) && is_array($users))
	{
		foreach ($users as $key => $row):
	?>
	<tr>
		<td class="header-item-content item-style">
            <div style="width: 50px;height: 50px;">
                <img style="width: 100%; height: 100%;border-radius: 29.5px;" src="<?= media_thumbnail($row['avatar'], 70) ?>"/>
            </div>
		</td>
		<td class="header-item-content item-style"><?php echo $row['user_id'] ?></td>
		<td class="header-item-content item-style"><?php echo $row['user_name']?></td>
		<td class="header-item-content item-style"><?php echo $row['email']?></td>
		<td class="header-item-content item-style">Comments:&nbsp;<?php echo $row['total_comment']?> <br>
			Thumbs&nbsp;up:&nbsp;<?php echo $row['total_like']?> <br>
			Picks:&nbsp;<?php echo $row['total_pick']?>
		</td>

        <td class="header-item-content item-style">
        <?php if(!empty($row['version'])){ ?>
                <?php foreach ($row['version'] as $k => $vl){ ?>
                    <?= $vl['name']?> &nbsp;&nbsp;
                <?php } ?>
        <?php } ?>
        </td>


        <td class="header-item-content item-style"><?php echo date('m/d/Y h:iA', $row['joined'])?></td>
		<td class="header-item-content item-style"><?php echo $row['status'] ?></td>
		<td class="header-item-content item-style">
			<div class="dropdown">
			    <span class="btnAction dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-ellipsis-h" style="color: #d8d8d8"></i></span>
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
<!--	<script type="text/javascript">-->
<!--		$(document).ready(function(){-->
<!--			$('#example3').DataTable({-->
<!--			    'ordering': false,-->
<!--			    'dom' : 'frtilp',-->
<!--			    'searching': false-->
<!--			});-->
<!--		});-->
<!--	</script>-->
<!--	-->
