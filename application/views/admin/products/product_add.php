<div class="background">
    <form id="product_add" action='' method='POST' enctype="multipart/form-data">
        <div class="row">
            <?php if($this->session->flashdata('msg')){
                echo '<div class="col-md-6"><div class="alert alert-success">';
                echo $this->session->flashdata('msg');
                echo '</div></div>';
            } ?>
        </div>
        <div class="title">Add Story</div> 
        <hr>
        <div class="row">
            <div class="content-form">
                <div class="col-md-7">
                    <div class="col-md-12">
                        <label>Story Name</label>
                        <div class="form-group">
                            <input type="text" name='name' value="" class="form-control" required="" placeholder="Type Name"/>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Story Description</label>
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Genre</label>
                            <select id='genre_id' class="form-control" required name='genre_id[]' multiple="multiple">
                                <?php
                                foreach ($genres as $item) {
                                    echo "<option value='{$item['id']}'>{$item['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select id='status' class="form-control" required name='status'>
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
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
                        <div class="form-group">
                            <label>Paywall Block</label>
                            <select id='paywall_episode' class="form-control" name='paywall_episode' disabled="disabled">
                                <option>Select Paywall Block</option>
                            </select>
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
                                        <img id='poster_image' src="<?php echo base_url('assets/images/borders/233x346@3x.png')?>"/>
                                        <div class='err-format' id="poster_err1">Image format is not suppported</div>
                                        <div class='err-size' id="poster_err2">The size must be less than 1MB</div>
                                        <div class="uploader" onclick="$('#posterImg').click()">
                                            <button type="button" class="btn ">Upload</button>
                                            <input type="file" accept="image/*" name="poster_img" id="posterImg" class="imagePhoto" required="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Story Image</label>
                                    <div class="row">
                                        <img id='series_image' src="<?php echo base_url('assets/images/borders/750x667@3x.png')?>"/>
                                        <div class='err-format' id="series_err1">Image format is not suppported</div>
                                        <div class='err-size' id="series_err2">The size must be less than 1MB</div>
                                        <div class="uploader" onclick="$('#seriesImg').click()">
                                            <button type="button" class="btn ">Upload</button>
                                            <input type="file" accept="image/*" name="series_img" id="seriesImg" class="imagePhoto" required="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Preview Round Image</label>
                                    <div class="row">
                                        <img id='preview_image' src="<?php echo base_url('assets/images/borders/135x135@3x.png')?>"/>
                                        <div class='err-format' id="pre_err1">Image format is not suppported</div>
                                        <div class='err-size' id="pre_err2">The size must be less than 1MB</div>
                                        <div class="uploader" onclick="$('#previewImg').click()">
                                            <button type="button" class="btn ">Upload</button>
                                            <input type="file" accept="image/*" name="preview_img" id="previewImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding-left: 0;padding-right: 0">
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Carousel Banner</label>
                                    <div class="row">
                                        <img id='carousel_image' src="<?php echo base_url('assets/images/borders/667x440@3x.png')?>"/>
                                        <div class='err-format' id="car_err1">Image format is not suppported</div>
                                        <div class='err-size' id="car_err2">The size must be less than 1MB</div>
                                        <div class="uploader" onclick="$('#carouselImg').click()">
                                            <button type="button" class="btn ">Upload</button>
                                            <input type="file" accept="image/*" name="carousel_img" id="carouselImg" class="imagePhoto" required="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Explore Preview Image</label>
                                    <div class="row">
                                        <img id='explore_image' src="<?php echo base_url('assets/images/borders/650x688@3x.png')?>"/>
                                        <div class='err-format' id="ex_err1">Image format is not suppported</div>
                                        <div class='err-size' id="ex_err2">The size must be less than 1MB</div>
                                        <div class="uploader" onclick="$('#exploreImg').click()">
                                            <button type="button" class="btn ">Upload</button>
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
                        <button type="submit" class="btn btn-update" name='cmd' value='Save' onclick="createProduct()">Create</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
