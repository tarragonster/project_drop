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
                    <label>Image</label>
                    <div class="row">
                        <div class="col-md-4">
                            <img id='image' width='120' height='120' src='<?php  ?>' style='border: 4px solid #c6c6c6; border-radius: 4px'/>
                        </div>
                        <div class="col-md-8">
                            <img style="padding-left: 45%;position: absolute;top: 40%;" src="<?php echo base_url('assets/images/plus.png')?>">
                            <div class="uploader" onclick="$('#imagePhoto').click()">
                                <input type="file" accept="img/*" name="image" id="imagePhoto"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 m-b-30">
                    <label>Trailler Image</label>
                    <div class="row">
                        <div class="col-md-4">
                            <img id='coverimg' width='120' height='120' style='border: 4px solid #c6c6c6; border-radius: 4px'/>
                        </div>
                        <div class="col-md-8">
                            <img style="padding-left: 45%;position: absolute;top: 40%;" src="<?php echo base_url('assets/images/plus.png')?>">
                            <div class="uploader" onclick="$('#coverPhoto').click()">
                                <input type="file" accept="img/*" name="trailler_image" id="coverPhoto"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <label>Trailler Video</label>
                    <input name="trailler_url" class="form-control" type="file"  onchange="setFileInfo(this.files)"/>
                    <div id="infos" style="margin-top: 8px;"></div>
                    <input id="duration" name="duration" style='opacity: 0; z-index: -1000'/>
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