<table id="example4" class="table table-bordered-bottom table-hover" style="width:100%">
	<thead>
		<tr><hr>
			<th></th>
			<th>Film ID</th>
			<th>Film Name</th>
			<th># of Blocks</th>
			<th>Paywall Block</th>
			<th>Rating</th>
			<th>Year</th>
			<th>Film Activity</th>
			<th>Status</th>
			<th>Created</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ($products != null && count($products) > 0) {
			foreach ($products as $row): ?>
                <tr>
                <td><img style="max-width: 50px; height: 75px;border-radius: 5px;" src="<?= media_thumbnail($row['image'], 70) ?>"/></td>
                <td><?php echo $row['product_id']?></td>
                <td><?php echo $row['name']?></td>
                <td><?php echo $row['total_epi']?></td>
                <td>
                    <?php 
                        echo ($row['paywall_episode_id'] == null)? 'N/a' : 'B' . $row['paywall_episode_id'] . ' - ' . $row['paywall_episode_name'] 
                    ?>
                </td>
                <td><?php echo $row['rate_name']?></td> 
                <td><?php echo $row['publish_year']; ?></td>
                <td>Comments:&nbsp;<?php echo $row['total_cmt']?><br>
                  Thumbs&nbsp;up:&nbsp;<?php echo $row['total_like']?> <br>
                  Picks:&nbsp;<?php echo $row['total_pick']?>
                </td>
                <td><?php echo ($row['status'] == 1 ? 'Enabled' : 'Disable') ?></td>
                <td><?php echo date('m/d/Y h:iA', $row['created'])?></td>
                <td>
                    <div class="dropdown">
                        <span class="btnAction dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-ellipsis-v" style="color: #d8d8d8"></i></span>
                            <ul class="dropdown-menu" id="customDropdown">
                                <li><a href="<?php echo base_url('product/edit/' . $row["product_id"])?>">Edit Film</a></li>
                                <li><a href="<?php echo base_url('product/managerActor/' . $row["product_id"])?>">Actors</a></li>
                                <li><a href="<?php echo base_url('product/managerMusic/' . $row["product_id"])?>">Music</a></li>
                                <li><a href="<?php echo base_url('product/managerSeason/' . $row["product_id"])?>">Seasons</a></li>
                                <?php if ($row['status'] == 1):?>
                                    <li>
                                       <a href="" class="btnAct" data-toggle="modal" data-target="#dis-modal" data-id="<?php echo $row['product_id']?>">Disable</a>
                                   </li>
                                   <?php else: ?>
                                    <li>
                                       <a href="" class="btnAct" data-toggle="modal" data-target="#en-modal" data-id="<?php echo $row['product_id']?>">Enable</a>
                                   </li>
                               <?php endif;?>
                               <li class="divider"></li>
                               <li>
                                <a href="" class="btnAct" data-toggle="modal" data-target="#del-modal" data-id="<?php echo $row['product_id']?>">Delete</a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            
        <?php
    endforeach ;
} ?>
</tbody>
</table> 
<hr style="margin-top: -59px;">

<div class="modal fade" id="dis-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-img">
            <img src="<?php echo base_url('assets/images/exclamation.jpg')?>">
        </div>
        <div class="modal-description">
         <h2>Are You Sure?</h2>
         <p>Are you sure you want to disable this film? You will be able to undo this in the actions section.</p>
     </div>
     <div class="modal-button">
         <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;color: #FFFFFF;">Cancel</button>
         <button type="button" class="btn btn-warning dis-confirm">Disable</button>
     </div>	
 </div>
</div>
</div>
<div class="modal fade" id="en-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-img">
            <img src="<?php echo base_url('assets/images/exclamation.jpg')?>">
        </div>
        <div class="modal-description">
         <h2>Are You Sure?</h2>
         <p>Are you sure you want to enable this film? You will be able to undo this in the actions section.</p>
     </div>
     <div class="modal-button">
         <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;color: #FFFFFF;">Cancel</button>
         <button type="submit" class="btn btn-warning en-confirm">Enable</button>
     </div>	
 </div>
</div>
</div>
<div class="modal fade" id="del-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-img">
            <img src="<?php echo base_url('assets/images/quit.jpg')?>">
        </div>
        <div class="modal-description">
         <h2>Are You Sure?</h2>
         <p>Are you sure you want to delete this film? You will not be able to undo this.</p>
     </div>
     <div class="modal-button">
         <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;color: #FFFFFF;">Cancel</button>
         <button class="btn btn-danger" data-toggle="modal" data-target="#confirm-del-modal" data-dismiss="modal" >Delete</button>
     </div>	
 </div>
</div>
</div>
<div class="modal fade" id="confirm-del-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-img">
            <img src="<?php echo base_url('assets/images/quit.jpg')?>">
        </div>
        <div class="modal-description">
            <h2>Are You Sure?</h2>
            <p>Type "DELETE" in the box below to proceed. <br> You will not be able to undo this.</p>
            <input type="text" id="text-confirm">
        </div>
        <div class="modal-button">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right:10px;color: #FFFFFF;">Cancel</button>
            <button type="submit" class="btn btn-danger del-confirm">Confirm</button>
        </div>  
    </div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#example4').DataTable({
              'ordering': false,
              'dom' : 'frtilp',
              'searching': false,
          });

        
    });
</script>