<div class="modal-header" style="background: #EFEFEF; z-index: 1056; padding-bottom: 0; height: 110px">
	<div class="custom-header">
		<div class="row">
			<div class="col-md-8 col-lg-10">
				<h1 class="modal-title-name">Add Story to Preview List</h1>
			</div>
			<div class="col-md-4 col-lg-2">
	            <button type="button" class="btn btn-header" id="add-story-btn" onclick="addStory()">Add</button>
	        </div>
		</div>
	</div>
</div>
<div class="modal-body">
	<div class="tab-content custom-tab">
		<h4>Story details</h4>
		<div class="form-group" style="margin-top: 20px;width: 100%">
	        <label>Search story</label>
	        <input type="text" name="product_id" id="story_key" required class="form-control custom-input" onkeyup='searchStory()' placeholder="Search Story Name"/>
	        <div id="other_story"></div>
	    </div>
	    <div class="form-group" style="padding-right: 0;">
            <label>Explore Preview Image</label>
            <div class="row" style="padding-left: 10px">
                <img id='explore_image' width="140" height="150" src="<?php echo (!empty($product['explore_img'])) ? base_url($product['explore_img']) : base_url('assets/images/borders/650x688@3x.png')?>"/>
                <div class='err-format' id="ex_err1">Image format is not suppported</div>
                <div class='err-size' id="ex_err2">The size must be less than 1MB</div>
                <div class="uploader" onclick="$('#exploreImg').click()">
                    <button type="button" class="btn  ">Upload</button>
                    <input type="file" accept="image/*" name="explore_img" id="exploreImg" class="imagePhoto"/>
                </div>
            </div>
        </div>
	</div>
</div>
