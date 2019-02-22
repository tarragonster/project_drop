<div class="row">
	<div class="col-sm-12">
		<div class="box card-box">
			<div class="box-header">
				<h3 class="box-title">Product Info</h3>
			</div>
			<div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                      <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed">
                             Details
                        </a>
                      </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="box-body table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Product Name</th>
                                        <td><?php echo $product['title']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Category</th>
                                        <td><?php echo $product['category']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Color</th>
                                        <td><?php echo $product['color1'] . ($product['color2'] != '' ? ',' . $product['color2'] : ''); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Selling Price</th>
                                        <td><?php echo $product['selling_price']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Original Price</th>
                                        <td><?php echo $product['original_price']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Shipping Price</th>
                                        <td><?php 
                                        echo $product['package_size'] == 0 ? '3.29' : ($product['package_size'] == 1? '5.20': '11.30'); ?></td>
                                    </tr>
                                    <tr>
                                       <th>Description</th>
                                        <td><?php echo $product['description']; ?></td>
                                    </tr>
                                    <tr>
                                       <th>Details</th>
                                        <td><?php echo $product['details']; ?></td>
                                    </tr>
                                    <tr>
                                       <th>Views</th>
                                        <td><?php echo $product['views']; ?></td>
                                    </tr>
                                     <tr>
                                       <th>Likes</th>
                                        <td><?php echo $product['likes']; ?></td>
                                    </tr>
                                    <tr>
                                       <th>Status</th>
                                       <td>
                                        <?php
                                        if ($product['status'] == 1){
                                           if ($product['qty'] == 0){
                                               $statusText = 'Sold Out';
                                           } else{
                                               $statusText = 'Enable';
                                           }
                                        } else if ($product['status'] == 0) {
                                            $statusText = 'Disabled';
                                        } else {
                                            $statusText = 'Deleted';
                                        }            
                                        echo $statusText;
                                        ?>
                                       </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel box box-danger">
                    <div class="box-header with-border">
                      <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="false">
                            Comments
                        </a>
                      </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                        <div class="box-body table-responsive">        
                            <?php if ($comments && count($comments) > 0) : ?>
                            <table id="comment_block" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Created</th>
                                        <th>Username</th>                      
                                        <th>Content</th>    
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($comments as $row) : ?>                          
                                    <tr>			
                                        <td align="center"><?php echo $row['comment_id']; ?></td>
                                        <td><?php echo date('Y-m-d h:i',$row['timestamp']) ?></td>
                                        <td><a href="<?php echo base_url('user/edit/' . $row['uid']);?>"> <?php echo $row['username']; ?> </a></td>             
                                        <td><?php echo $row['content']; ?></td> 
                                        <td align="center">                                
                                        <?php 
                                        if ($row['status'] == 1) {
                                        ?>
                                            <a class="btn_close" data-href="<?php echo base_url('product/hideComment/' . $row['comment_id']) ?>" href="#" data-callback="<?php echo base_url('product');?>">
                                                <button class='btn btn-danger btn-sm margin2'>Hide</button>
                                            </a>
                                        <?php
                                        } 
                                        ?>
                                        </td>
                                    </tr>
                                <?php endforeach ;?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>             
                                There aren't no comments for this product.
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="panel box box-success">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                          <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="" aria-expanded="true">
                            Images
                          </a>
                        </h4>
                      </div>
                      <div id="collapseThree" class="panel-collapse collapse in" aria-expanded="true">
                        <div class="box-body">    
                            <div class="row">
                              <?php foreach ($images as $key => $value) :?>
                              <div class="col-sm-4 col-md-2">
                                <div class="color-palette-set">
                                    <a href="<?php echo $key; ?>" data-lightbox="roadtrip">
                                        <img style="max-width: 150px; max-height: 150px" src="<?php echo $value; ?>"/>
                                    </a>

                                </div>
                              </div><!-- /.col -->
                             <?php endforeach; ?> 
                            </div><!-- /.row -->        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="confirm_close" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirm Hide</h4>
            </div>

            <div class="modal-body">
                <div id="message">
                    <p>You are about to hide one comment, it won't be shown on frontend.</p>
                    <p>Do you want to proceed?</p>
                </div>                 
                <input type="hidden" id="ajax_url" />
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="close_confirmation">Confirm</button>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="confirm_message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirm Message</h4>
            </div>
            <div class="modal-body" >           
                <p id="msg"></p>       
            </div>        
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               
        </div>
    </div>
</div>