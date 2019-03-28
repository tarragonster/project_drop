<table id="example3" class="table table-bordered-bottom table-hover" data-alert="Are you want to delete this product?">
	<thead>
		<tr>
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
			<td><img style="max-width: 50px; height: 80px" src="<?= media_thumbnail($row['image'], 70) ?>"/></td>
			<td><?php echo $row['product_id']?></td>
			<td><?php echo $row['name']?></td>
			<td># of Blocks</td>
			<td>Paywall</td>
			<td><?php echo $row['rate_name']?></td> 
			<td><?php echo $row['publish_year']; ?></td>
			<td>Comments: <br>
				Thumbs up: <?php echo $row['total_like']?> <br>
				Picks: <?php echo $row['total_pick']?>
			</td>
			<td><?php echo ($row['status'] == 1 ? 'Enabled' : 'Disable') ?></td>
			<td><?php echo date('m/d/Y h:iA', $row['created'])?></td>
			<td>
				<div class="dropdown">
				    <span class="btnAction dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-ellipsis-v"></i></span>
				    <ul class="dropdown-menu" id="customDropdown">
				      	<li><a href="">Edit Film</a></li>
				      	<li><a href="">Actors</a></li>
				      	<li><a href="">Music</a></li>
				      	<li><a href="">Seasions</a></li>
				      	<?php if ($row['status'] == 1):?>
				      			<li>
				      				<a href="" class="button" data-toggle="modal" data-target="#dis-modal" data-id="<?php echo $row['product_id']?>">Disable</a>
				      			</li>
				      	<?php else: ?>
				      			<li><a href=""data-toggle="modal" data-target="#en-modal">Enable</a></li>
				      	<?php endif;?>
				      	<li class="divider"></li>
				      	<li><a href="">Delete</a></li>
				    </ul>
				</div>
			</td>
		</tr>
		<?php
			endforeach ;
		} ?>
	</tbody>
</table>

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
        	<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right: 10px;">Cancel</button>
	        <button type="button" class="btn btn-warning disable">
	        	<a href="<?php echo base_url('product/disable/' . $row['product_id']) ?>">Disable</a>
	        </button>
        </div>	
    </div>
  </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#example3').DataTable({
            'ordering': false,
            'dom' : '<"top"f>rt<"bottom"ipl>',
            'searching': false
        });

        $('.button').click(function(e){
        	e.preventDefault();
	    	var product_id = $(this).attr('data-id');
	    	
	    });
    });

    
</script>