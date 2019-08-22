<div class="modal-header" style="background: #EFEFEF; z-index: 1056; padding-bottom: 0; height: 110px">
	<div class="custom-header">
		<div class="row">
			<div class="col-md-8 col-lg-8">
				<h1 class="modal-title-name">Add Block</h1>
			</div>
			<div class="col-md-4 col-lg-4">
	            <button type="button" class="btn btn-header" id="create-genre-btn" onclick="saveEpisode(key='add')">Create</button>
	        </div>
		</div>
	</div>
</div>
<div class="modal-body">
	<div class="tab-content custom-tab">
		<h4>Block Details</h4>
			<div class='err-format'>Image format is not suppported</div>
			<div class='err-size'>The size of the image more than 1MB</div>
			<input type="hidden" name="product_id" value="<?php echo $product_id?>">
			<div class="form-group" style="margin-top: 20px">
                <label>Block name</label>
                <input type="text" name="episode_name" id="episode_name" required class="form-control custom-input" placeholder="Enter Name" autocomplete="off"/>
                <span class="mess_err" id="block_err"></span>
            </div>
            <div class="row">
            	<div class="col-lg-6 col-md-6">
            		<div class="form-group">
		                <label>Story name</label>
		                <input type="text" name="story_name" id="story_name" required class="form-control custom-input" readonly="readonly" value="<?php echo $product['name']?>" />
		                <span class="mess_err" id="story_err"></span>
		            </div>
		        </div>
            	<div class="col-lg-6 col-md-6">
            		<div class="form-group">
		                <label>Season name</label>
		                <select id='season_id' class="form-control" required name='season_id'>
                            <?php
                            foreach ($seasons as $item) {
                                echo "<option value='{$item['season_id']}'>{$item['name']}</option>";
                            }
                            ?>
                        </select>
		            </div>
            	</div>
            </div>
            <div class="form-group" style="margin-top: 20px">
                <label>JW Media ID</label>
                <input type="text" name="jw_media_id" id="jw_media_id" required class="form-control custom-input" placeholder="Enter ID" autocomplete="off"/>
                <span class="mess_err" id="jw_err"></span>
            </div>
             <div class="form-group">
                <label>Block Description</label>
                <textarea name="description" id="description" class='text-area-des' maxlength='475' class="form-control textarea" required="" rows="4" placeholder="Type Description"></textarea>
                <span class="mess_err" id="des_err"></span>
            </div>
            <div class="input-file">
                <label>Block image</label>
                <div class="row" style="margin-left: 0;">
                    <img id='block_image' src="<?php echo base_url('assets/images/borders/516x295.svg')?>" width='185' height='106' sty />
                    <div class='err-format' id="block_err1">Image format is not suppported</div>
                    <div class='err-size' id="block_err2">The size must be less than 1MB</div>
                    <div class="uploader" onclick="$('#blockImg').click()">
                        <button type="button" class="btn ">Upload</button>
                        <input type="file" name="block_img" id="blockImg" class="imagePhoto" required="" />
                    </div>
                </div>
            </div>
	</div>
</div>

<script type="text/javascript">
	var imgLoader = document.getElementById('blockImg');
	if (imgLoader) {
	    imgLoader.addEventListener('change', handleGenrelImage, false);
	}
	function handleGenrelImage(e) {
	    const arr = ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'JPG', 'PNG', 'JPEG', 'GIF', 'PDF']

	    var block_image = $('#blockImg').val()
	    var fileSize = document.getElementById('blockImg').files[0].size;
	    isImage = arr.includes(block_image.split('.').pop())

	    if (isImage == false) {
	        $('#block_err1').css('display', 'block');
	        $('#block_err2').css('display', 'none');
	        $('#block_image').attr('src',BASE_APP_URL + 'assets/images/borders/516x295.svg');
	    } 
	    else if(fileSize /(1024*1024) > 1){
	        $('#block_err2').css('display', 'block');
	        $('#block_err1').css('display', 'none');
	        $('#block_image').attr('src',BASE_APP_URL + 'assets/images/borders/516x295.svg');
	    }
	    else {
	        $('#block_err1').css('display', 'none');
	        $('#block_err2').css('display', 'none');
	        var reader = new FileReader();
	        reader.onload = function (event) {
	            
	            $('#block_image').attr('src',event.target.result);
	        }
	    }
	    reader.readAsDataURL(e.target.files[0]);
	}
</script>