<div class="background">
    <div class="row">
        <div class="header">Add Series</div> <hr>
    </div>
    <div class="row">
        <div class="content">
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
                    <div class="form-group">
                        <label>Paywall Block</label>
                        <select id='rate_id' class="form-control" required name='rate_id'>
                            <option value="">Select Paywall Block</option>
                            <?php
                                foreach ($rates as $item) {
                                    echo "<option value='{$item['rate_id']}'>{$item['name']}</option>";
                                }
                            ?>
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
                <div class="row">
                    <div class="col-md-12 portlets m-b-30">
                        <label>Poster Image</label>
                        <div class="row">
                            <div class="col-md-6">
                                <img id='image' width='70' height='110'/>
                                <button>
                                    
                                </button>
                                <div class="uploader" onclick="$('#imagePhoto').click()" >
                                    <input type="file" accept="image/*" name="image" id="imagePhoto" />
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
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="bottom"></div>
    </div>
</div>