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
                <h3 class="m-t-0 m-b-30 header-title">Edit Film</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row box-body">
                <div class="col-md-12">
                    <label>Film Name</label>
                    <div class="form-group">
                        <input type="text" name='name' value="<?php echo $name;?>" class="form-control" required="" placeholder="Film Name" />
                    </div>
                </div>
                <div class="col-md-12">
                    <label>Description</label>
                    <div class="form-group">
                        <textarea name="description" id='text-area-des' maxlength='475' class="form-control textarea" required="" rows="4" placeholder="Film Description..."><?php echo $description;?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Years</label>
                    <div class="form-group">
                        <input type="text" name='publish_year' class="form-control" required="" placeholder="Years" value="<?php echo $publish_year;?>"  />
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Creators</label>
                    <div class="form-group">
                        <input type="text" name='creators' class="form-control" required="" placeholder="Creators" value="<?php echo $creators;?>"  />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Rate</label>
                        <select id='rate_id' class="form-control" required name='rate_id'>
                            <?php
                                foreach ($rates as $item) {
                                    echo "<option value='{$item['rate_id']}'". ($item['rate_id'] == $rate_id ? ' selected' : '').">{$item['name']}</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Paywall Episode</label>
                        <select  class="form-control" name='paywall_episode'>
	                        <option value="">Choose which episode will propmt paywall</option>
                            <?php
                                foreach ($episodes as $item) {
                                    echo "<option value='{$item['episode_id']}'". ($item['episode_id'] == $paywall_episode ? ' selected' : '').">{$item['season_name']} - {$item['name']}</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12 m-b-30">
                    <label>Poster</label>
                    <div class="row">
                        <div class="col-md-4">
                            <img id='image' width='120' height='120' src="<?= media_thumbnail($image, 120) ?>"  style='border: 4px solid #c6c6c6; border-radius: 4px'/>
                        </div>
                        <div class="col-md-8">
                            <img style="padding-left: 45%;position: absolute;top: 40%;" src="<?php echo base_url('assets/images/plus.png')?>">
                            <div class="uploader" onclick="$('#imagePhoto').click()">
                                <input type="file" accept="image/*" name="image" id="imagePhoto"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 m-b-30">
                    <label>Series Image</label>
                    <div class="row">
                        <div class="col-md-4">
                            <img id='background_photo' width='120' src="<?= media_thumbnail($background_img, 120) ?>"  style='border: 4px solid #c6c6c6; border-radius: 4px'/>
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
			            <input type="text" name='jw_media_id' value="<?php echo $jw_media_id;?>" class="form-control" required="" placeholder="JW Media ID" />
		            </div>
	            </div>
            </div>
        </div>
        <div style='margin-top: 16px' class="col-md-12">
            <div class="form-group">
                <button type="submit" class="btn btn-inverse btn-custom btn-xs" style='width: 100px' name='cmd' value='Save'>Update</button>
            </div>
        </div>
    </div>
</form>