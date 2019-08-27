<div class="modal-header" style="background: #EFEFEF; z-index: 1056; padding-bottom: 0; height: 110px">
	<div class="custom-header">
		<div class="row">
			<div class="col-md-8 col-lg-10">
				<h1 class="modal-title-name">Add Story to Trending</h1>
			</div>
			<div class="col-md-4 col-lg-2">
	            <button type="button" class="btn btn-header" id="add-story-btn" onclick="addStoryTrending()">Add</button>
	        </div>
		</div>
	</div>
</div>
<div class="modal-body">
	<div class="tab-content custom-tab">
		<h4>Story details</h4>
		<div class="form-group" style="margin-top: 20px;width: 100%">
	        <label>Search story</label>
	        <input type="hidden" name="product_id" id="product_id">
	        <input type="text" id="story_key" required class="form-control custom-input" onkeyup='searchStoryTrending()' placeholder="Search Story Name"/>
	        <span class="mess_err" id="product_err"></span>
	        <div id="other_story"></div>
	    </div>
	    <div class="form-group" style="padding-right: 0;margin-top: 50px;">
            <label>Poster Image</label>
            <div class="row" style="padding-left: 10px">
                <img id='explore_image' width='70' height='100' src="<?php echo base_url('assets/images/borders/233x346@3x.png')?>"/>
                <div class='mess_err' id="ex_err1"></div>
                <div class='mess_err' id="ex_err2"></div>
                <div class="mess_err" id="img_err"></div>
                <div class="uploader" onclick="$('#exploreImg').click()" style="display: none">
                    <button type="button" class="btn">Upload alternative image</button>
                    <input type="file" accept="image/*" name="promo_image" id="exploreImg" class="imagePhoto"/>
                </div>
            </div>
        </div>
	</div>
</div>

<script type="text/javascript">
	var exploreImgLoader = document.getElementById('exploreImg');
	if (exploreImgLoader) {
	    exploreImgLoader.addEventListener('change', handleExploreImage, false);
	}
	function handleExploreImage(e) {
	    const arr = ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'JPG', 'PNG', 'JPEG', 'GIF', 'PDF']

	    var explore_image = $('#exploreImg').val()
	    var fileSize = document.getElementById('exploreImg').files[0].size;
	    isImage = arr.includes(explore_image.split('.').pop())

	    if (isImage == false) {
	        $('#ex_err1').text('Image format is not supported');
	        $('#ex_err2').text('');
	        $('#img_err').text('');
	        $('#explore_image').attr('src', BASE_APP_URL + 'assets/images/borders/233x346@3x.png');
	    } 
	    else if(fileSize /(1024*1024) > 1){
	        $('#ex_err2').text('The size must be less than 1MB');
	        $('#ex_err1').text('');
	        $('#img_err').text('');
	        $('#explore_image').attr('src', BASE_APP_URL + 'assets/images/borders/233x346@3x.png');
	    }else {
	        $('#ex_err1').text('');
	        $('#ex_err2').text('');
	        $('#img_err').text('');
	        var reader = new FileReader();
	        var reader = new FileReader();
	        reader.onload = function (event) {

	            $('#explore_image').attr('src',event.target.result);
	        }
	        reader.readAsDataURL(e.target.files[0]);
	    }
	}
</script>