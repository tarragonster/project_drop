<div class="background">
    <form id="prdadd" action='' method='POST' enctype="multipart/form-data">
        <div class="row">
            <?php if($this->session->flashdata('msg')){
                echo '<div class="col-md-6"><div class="alert alert-success">';
                echo $this->session->flashdata('msg');
                echo '</div></div>';
            } ?>
        </div>
        <div class="title">Edit Series</div> 
        <hr>
        <div class="row">
            <div class="content-form">
                <div class="col-md-7">
                    <div class="col-md-12">
                        <label>Series Name</label>
                        <div class="form-group">
                            <input type="text" name='name' value="<?php echo $name;?>" class="form-control" required="" placeholder="Type Name"/>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Series Description</label>
                        <div class="form-group">
                            <textarea name="description" id='text-area-des' maxlength='475' class="form-control textarea" required="" rows="4" placeholder="Type Description"><?php echo $description;?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Year</label>
                        <div class="form-group">
                            <input type="text" name='publish_year' value="<?php echo $publish_year;?>" class="form-control" required="" placeholder="Type Year"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Rating</label>
                            <select id='rate_id' class="form-control" required name='rate_id'>
                                <option value="<?php echo $rate_id?>"><?php echo $rate_name?></option>
                                <?php
                                foreach ($rates as $item) {
                                    echo "<option value='{$item['rate_id']}'>{$item['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Creators</label>
                        <div class="form-group">
                            <input type="text" name='creators' value="<?php echo $creators;?>" class="form-control" required="" placeholder="Type Creator"/>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Paywall Block</label>
                            <select id='paywall_episode' class="form-control" name='paywall_episode'>
                                <?php if($paywall_episode == 0): ?>
                                    <option value="">Select Paywall Block</option>
                                <?php else: ?>
                                    <option value="<?php echo $paywall_episode?>"><?php echo $paywall_episode_name?></option>
                                <?php endif; ?>
                                <?php
                                foreach ($episodes as $item) {
                                    echo "<option value='{$item['episode_id']}'>{$item['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Trailer JW Media ID</label>
                        <div class="form-group">
                            <input type="text" name='jw_media_id' value="<?php echo $jw_media_id;?>" class="form-control" required="" placeholder="Type JW Media ID" />
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div id="upload-img">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Poster Image</label>
                                    <div class="row">
                                        <img id='poster_image' src="<?php echo (!empty($image)) ? base_url($image) : base_url('assets/images/borders/border1.jpg')?>"/>
                                        <div class="uploader" onclick="$('#posterImg').click()">
                                            <button type="button" class="btn  ">Upload</button>
                                            <input type="file" accept="image/*" name="poster_img" id="posterImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Series Image</label>
                                    <div class="row">
                                        <img id='series_image' src="<?php echo (!empty($background_img)) ? base_url($background_img) : base_url('assets/images/borders/border2.jpg')?>"/>
                                        <div class="uploader" onclick="$('#seriesImg').click()">
                                            <button type="button" class="btn  ">Upload</button>
                                            <input type="file" accept="image/*" name="series_img" id="seriesImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Preview Round Image</label>
                                    <div class="row">
                                        <img id='preview_image' src="<?php echo (!empty($preview_img)) ? base_url($preview_img) : base_url('assets/images/borders/border3.jpg')?>"/>
                                        <div class="uploader" onclick="$('#previewImg').click()">
                                            <button type="button" class="btn  ">Upload</button>
                                            <input type="file" accept="image/*" name="preview_img" id="previewImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Carousel Banner</label>
                                    <div class="row">
                                        <img id='carousel_image' src="<?php echo (!empty($trailler_image)) ? base_url($trailler_image) : base_url('assets/images/borders/border4.jpg')?>"/>
                                        <div class="uploader" onclick="$('#carouselImg').click()">
                                            <button type="button" class="btn  ">Upload</button>
                                            <input type="file" accept="image/*" name="carousel_img" id="carouselImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Explore Preview Image</label>
                                    <div class="row">
                                        <img id='explore_image' src="<?php echo (!empty($explore_img)) ? base_url($explore_img) : base_url('assets/images/borders/border5.jpg')?>"/>
                                        <div class="uploader" onclick="$('#exploreImg').click()">
                                            <button type="button" class="btn  ">Upload</button>
                                            <input type="file" accept="image/*" name="explore_img" id="exploreImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="bottom">
                <div style='margin-top: 16px' class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-update" name='cmd' value='Save'>Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

