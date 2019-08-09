<div class="background">
    <form id="prdadd" action='' method='POST' enctype="multipart/form-data">
        <div class="row">
            <?php if($this->session->flashdata('msg')){
                echo '<div class="col-md-6"><div class="alert alert-success">';
                echo $this->session->flashdata('msg');
                echo '</div></div>';
            } ?>
        </div>
        <div class="title">Manage Story</div> 
        <hr>
        <div class="row">
            <div class="content-form">
                <div class="col-md-7">
                    <div class="col-md-12">
                        <label>Story Name</label>
                        <div class="form-group">
                            <input type="text" name='name' value="<?php echo $product['name'];?>" class="form-control" required="" placeholder="Type Name"/>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Story Description</label>
                        <div class="form-group">
                            <textarea name="description" id='text-area-des' maxlength='475' class="form-control textarea" required="" rows="4" placeholder="Type Description"><?php echo $product['description'];?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Year</label>
                        <div class="form-group">
                            <input type="text" name='publish_year' value="<?php echo $product['publish_year'];?>" class="form-control" required="" placeholder="Type Year"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Rating</label>
                            <select id='rate_id' class="form-control" required name='rate_id'>
                                <option value="<?php echo $product['rate_id']?>"><?php echo $product['rate_name']?></option>
                                <?php
                                foreach ($product['rates'] as $item) {
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
                                if(!empty($product['selected_genres'])) {
                                    foreach ($product['genres'] as $key => $value) {
                                        foreach ($product['selected_genres'] as $key => $value1) {
                                            if ($value1['genre_id'] == $value['id']) {
                                                $flag = 'selected';?>
                                                <option value="<?php echo $value['id']?>" <?php echo $flag?>><?php echo $value['name']?></option>
                                            <?php
                                            }
                                        }
                                    }
                                    foreach ($product['deselect_genres'] as $key => $value) { ?>
                                        <option value="<?php echo $value['id']?>" ><?php echo $value['name']?></option>
                                        <?php
                                    }
                                }else {
                                    foreach ($product['genres'] as $key => $value) { ?>
                                        <option value="<?php echo $value['id']?>" ><?php echo $value['name']?></option>
                                        <?php
                                    }
                                }
                                ?>   
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select id='status' class="form-control" required name='status'>
                                <option value="<?php echo $product['status']?>"><?php echo $product['status'] == 0 ? 'Inactive' : 'Active' ?></option>
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Creators</label>
                        <div class="form-group">
                            <input type="text" name='creators' value="<?php echo $product['creators'];?>" class="form-control" required="" placeholder="Type Creator"/>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Paywall Block</label>
                            <select id='paywall_episode' class="form-control" name='paywall_episode'>
                                <?php if($product['paywall_episode'] != 0): ?>
                                    <option value="<?php echo $product['paywall_episode']?>"> B<?php echo $product['position']?> - <?php echo $product['paywall_episode_name']?></option>
                                    <option value="">None</option>
                                <?php else: ?>
                                    <option value=""><?php echo $product['paywall_episode_name']?></option>
                                <?php endif;?>
                                <?php
                                foreach ($product['episodes'] as $item) {
                                    echo "<option value='{$item['episode_id']}'>B{$item['position']} - {$item['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Trailer JW Media ID</label>
                        <div class="form-group">
                            <input type="text" name='jw_media_id' value="<?php echo $product['jw_media_id'];?>" class="form-control" required="" placeholder="Type JW Media ID" />
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
                                        <img id='poster_image' src="<?php echo (!empty($product['image'])) ? base_url($product['image']) : base_url('assets/images/borders/233x346@3x.png')?>"/>
                                        <div class="uploader" onclick="$('#posterImg').click()">
                                            <button type="button" class="btn  ">Upload</button>
                                            <input type="file" accept="image/*" name="poster_img" id="posterImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Series Image</label>
                                    <div class="row">
                                        <img id='series_image' src="<?php echo (!empty($product['background_img'])) ? base_url($product['background_img']) : base_url('assets/images/borders/750x667@3x.png')?>"/>
                                        <div class="uploader" onclick="$('#seriesImg').click()">
                                            <button type="button" class="btn  ">Upload</button>
                                            <input type="file" accept="image/*" name="series_img" id="seriesImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Preview Round Image</label>
                                    <div class="row">
                                        <img id='preview_image' src="<?php echo (!empty($product['preview_img'])) ? base_url($product['preview_img']) : base_url('assets/images/borders/135x135@3x.png')?>"/>
                                        <div class="uploader" onclick="$('#previewImg').click()">
                                            <button type="button" class="btn  ">Upload</button>
                                            <input type="file" accept="image/*" name="preview_img" id="previewImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding-left: 0;padding-right: 0">
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Carousel Banner</label>
                                    <div class="row">
                                        <img id='carousel_image' src="<?php echo (!empty($product['trailler_image'])) ? base_url($product['trailler_image']) : base_url('assets/images/borders/667x440@3x.png')?>"/>
                                        <div class="uploader" onclick="$('#carouselImg').click()">
                                            <button type="button" class="btn  ">Upload</button>
                                            <input type="file" accept="image/*" name="carousel_img" id="carouselImg" class="imagePhoto"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 portlets m-b-30">
                                    <label>Explore Preview Image</label>
                                    <div class="row">
                                        <img id='explore_image' src="<?php echo (!empty($product['explore_img'])) ? base_url($product['explore_img']) : base_url('assets/images/borders/650x688@3x.png')?>"/>
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

