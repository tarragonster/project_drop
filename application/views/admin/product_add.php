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
                <h3 class="m-t-0 m-b-30 header-title">Film Information</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row box-body">
                <div class="col-md-12">
                    <label>Film Name</label>
                    <div class="form-group">
                        <input type="text" name='name' value="" class="form-control" required="" placeholder="Film Name"/>
                    </div>
                </div>
                <div class="col-md-12">
                    <label>Description</label>
                    <div class="form-group">
                        <textarea name="description" id='text-area-des' maxlength='475' class="form-control textarea" required="" rows="4" placeholder="Film Description..."></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Years</label>
                    <div class="form-group">
                        <input type="text" name='publish_year' value="" class="form-control" required="" placeholder="Years"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Creators</label>
                    <div class="form-group">
                        <input type="text" name='creators' value="" class="form-control" required="" placeholder="Creators"/>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Rate</label>
                        <select id='rate_id' class="form-control" required name='rate_id'>
                            <option value="">Select Rate Type</option>
                            <?php
                                foreach ($rates as $item) {
                                    echo "<option value='{$item['rate_id']}'>{$item['name']}</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12 portlets m-b-30">
                    <label>Poster</label>
                    <div class="row">
                        <div class="col-md-4">
                            <img id='image' width='120' height='120' style='border: 4px solid #c6c6c6; border-radius: 4px'/>
                        </div>
                        <div class="col-md-8">
                            <img style="padding-left: 45%;position: absolute;top: 40%;" src="<?php echo base_url('assets/images/plus.png')?>">
                            <div class="uploader" onclick="$('#imagePhoto').click()">
                                <input type="file" accept="image/*" name="image" id="imagePhoto"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 portlets m-b-30">
                    <label>Series Image</label>
                    <div class="row">
                        <div class="col-md-4">
                            <img id='background_photo' width='120' style='border: 4px solid #c6c6c6; border-radius: 4px'/>
                        </div>
                        <div class="col-md-8">
                            <img style="padding-left: 45%;position: absolute;top: 40%;" src="<?php echo base_url('assets/images/plus.png')?>">
                            <div class="uploader" onclick="$('#backgroundImg').click()">
                                <input type="file" accept="image/*" name="background_img" id="backgroundImg" class="imagePhoto"/>
                            </div>
                        </div>
                    </div>
                </div>
	            <div class="col-md-12">
		            <label>Trailer JW Media ID</label>
		            <div class="form-group">
			            <input type="text" name='jw_media_id' value="" class="form-control" required="" placeholder="JW Media ID" />
		            </div>
	            </div>
            </div>
        </div>
        <div style='margin-top: 16px' class="col-md-12">
            <div class="form-group">
                <button type="submit" class="btn btn-inverse btn-custom btn-xs" style='width: 100px' name='cmd' value='Save'>Post</button>
            </div>
        </div>
    </div>
</form>