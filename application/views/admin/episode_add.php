<form id="prdadd" action='' method='POST' enctype="multipart/form-data">
    <div class="row">
        <!-- left column -->
        <?php if($this->session->flashdata('msg')){
            echo '<div class="col-md-6"><div class="alert alert-success">';
            echo $this->session->flashdata('msg');
            echo '</div></div>';
        } ?>
        <div class="col-md-12">
            <div class="box-header">
                <h3 class="m-t-0 m-b-30 header-title">Add Episode</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row box-body">
                <div class="col-md-12">
                    <label>Name</label>
                    <div class="form-group">
                        <input type="text" name='name' class="form-control" required="" placeholder="Name" />
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Film name</label>
                    <div class="form-group">
                        <input type="text" class="form-control" required="" placeholder="Film name" value="<?php echo $product_name;?>" disabled/>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Season Name</label>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Seasone Name" value="<?php echo $name;?>"  disabled/>
                    </div>
                </div>
                <div class="col-md-12">
                    <label>JW Media ID</label>
                    <div class="form-group">
                        <input type="text" name='jw_media_id' class="form-control" required="" placeholder="JW Media ID" />
                    </div>
                </div>
                <div class="col-md-12">
                    <label>Description</label>
                    <div class="form-group">
                        <textarea name="description" class="form-control" placeholder="" rows="3" required=""></textarea>
                    </div>
                </div>
                <div class="col-md-12 portlets m-b-30">
                    <label>Image</label>
                    <div class="row">
                        <div class="col-md-4">
                            <img id='image' width='120' height='120'  style='border: 4px solid #c6c6c6; border-radius: 4px'/>
                        </div>
                        <div class="col-md-8">
                            <img style="padding-left: 45%;position: absolute;top: 40%;" src="<?php echo base_url('assets/images/plus.png')?>">
                            <div class="uploader" onclick="$('#imagePhoto').click()">
                                <input type="file" accept="image/*" name="image" id="imagePhoto"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style='margin-top: 16px' class="col-md-12">
            <div class="form-group">
                <button type="submit" class="btn btn-inverse btn-custom btn-xs" style='width: 100px' name='cmd' value='Save'>Save</button>
            </div>
        </div>
    </div>
</form>