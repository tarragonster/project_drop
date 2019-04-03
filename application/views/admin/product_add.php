<div class="background">
    <form id="prdadd" action='' method='POST' enctype="multipart/form-data">
        <div class="row">
            <?php if($this->session->flashdata('msg')){
                echo '<div class="col-md-6"><div class="alert alert-success">';
                echo $this->session->flashdata('msg');
                echo '</div></div>';
            } ?>
            <div class="header-title">Add Series</div> <hr>
        </div>
        <div class="row">
            <div class="content-form">
                <div class="col-md-7">
                    <div class="col-md-12">
                        <label>Series Name</label>
                        <div class="form-group">
                            <input type="text" name='name' value="" class="form-control" required="" placeholder="Type Name"/>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Series Description</label>
                        <div class="form-group">
                            <textarea name="description" id='text-area-des' maxlength='475' class="form-control textarea" required="" rows="4" placeholder="Type Description"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Year</label>
                        <div class="form-group">
                            <input type="text" name='publish_year' value="" class="form-control" required="" placeholder="Type Year"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Rating</label>
                            <select id='rate_id' class="form-control" required name='rate_id'>
                                <option value="">Select Rating</option>
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
                            <input type="text" name='creators' value="" class="form-control" required="" placeholder="Type Creator"/>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Trailer JW Media ID</label>
                        <div class="form-group">
                            <input type="text" name='jw_media_id' value="" class="form-control" required="" placeholder="Type JW Media ID" />
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
                                        <img id='poster_image' src="<?php echo base_url('assets/images/borders/border1.jpg')?>"/>
                                        <div class="uploader" onclick="$('#posterImg').click()">
                                            <span>Upload</span>
                                            <input type="file" accept="image/*" name="poster_img" id="posterImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Series Image</label>
                                    <div class="row">
                                        <img id='series_image' src="<?php echo base_url('assets/images/borders/border2.jpg')?>"/>
                                        <div class="uploader" onclick="$('#seriesImg').click()">
                                            <span>Upload</span>
                                            <input type="file" accept="image/*" name="series_img" id="seriesImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Preview Round Image</label>
                                    <div class="row">
                                        <img id='preview_image' src="<?php echo base_url('assets/images/borders/border3.jpg')?>"/>
                                        <div class="uploader" onclick="$('#previewImg').click()">
                                            <span>Upload</span>
                                            <input type="file" accept="image/*" name="preview_img" id="previewImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Carousel Banner</label>
                                    <div class="row">
                                        <img id='carousel_image' src="<?php echo base_url('assets/images/borders/border4.jpg')?>"/>
                                        <div class="uploader" onclick="$('#carouselImg').click()">
                                            <span>Upload</span>
                                            <input type="file" accept="image/*" name="carousel_img" id="carouselImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Explore Preview Image</label>
                                    <div class="row">
                                        <img id='explore_image' src="<?php echo base_url('assets/images/borders/border5.jpg')?>"/>
                                        <div class="uploader" onclick="$('#exploreImg').click()">
                                            <span>Upload</span>
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

