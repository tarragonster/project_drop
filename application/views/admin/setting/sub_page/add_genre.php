<div class="modal-header" style="background: #EFEFEF; z-index: 1056; padding-bottom: 0; height: 110px">
	<div class="custom-header">
		<div class="row">
			<div class="col-md-8 col-lg-8">
				<h1 class="modal-title-name">Add Genre</h1>
			</div>
			<div class="col-md-4 col-lg-4">
	            <button type="button" class="btn btn-header" id="create-genre-btn" onclick="saveGenre()">Create</button>
	        </div>
		</div>
	</div>
</div>
<div class="modal-body">
	<div class="tab-content custom-tab">
		<h4>Genre Details</h4>
			<div class='err-format'>Image format is not suppported</div>
			<div class='err-size'>The size of the image more than 1MB</div>
			<div class="form-group" style="margin-top: 20px">
                <label>Genre name</label>
                <input type="text" name="genre_name" id="genre_name" required class="form-control custom-input" placeholder="Enter Name" autocomplete="off"/>
            </div>
            <div class="input-file">
                <label>Genre image</label>
                <div class="row" style="margin-left: 0;">
                    <img id='genre_image' src="<?php echo base_url('assets/images/borders/369x214.svg')?>" width='183' height='108' sty />
                    <div class="uploader" onclick="$('#genreImg').click()">
                        <button type="button" class="btn ">Upload</button>
                        <input type="file" name="genre_img" id="genreImg" class="imagePhoto" required="" />
                    </div>
                </div>
            </div>
	</div>
</div>

<script type="text/javascript">
	var imgLoader = document.getElementById('genreImg');
	if (imgLoader) {
	    imgLoader.addEventListener('change', handleGenrelImage, false);
	}
	function handleGenrelImage(e) {
		const arr = ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'JPG', 'PNG', 'JPEG', 'GIF', 'PDF']

		var genre_image = $('#genreImg').val()
		var fileSize = document.getElementById('genreImg').files[0].size;
	    isImage = arr.includes(genre_image.split('.').pop())

	    if (isImage == false) {
			$('.err-format').css('display', 'block');
			$('.err-size').css('display', 'none');
			$('#genre_image').attr('src','<?php echo base_url('assets/images/borders/369x214.svg')?>');
		} 
		else if(fileSize /(1024*1024) > 1){
			$('.err-size').css('display', 'block');
			$('.err-format').css('display', 'none');
			$('#genre_image').attr('src','<?php echo base_url('assets/images/borders/369x214.svg')?>');
		}
		else {
			$('.err-format').css('display', 'none');
			$('.err-size').css('display', 'none');
		    var reader = new FileReader();
		    reader.onload = function (event) {
		        
		        $('#genre_image').attr('src',event.target.result);
		    }
		}
	    reader.readAsDataURL(e.target.files[0]);
	}

	function saveGenre() {
		var genre_name = $('#genre_name').val()
		var genre_image = $('#genreImg').val()
		if ((genre_name != '' || genre_image != '') && $('.err-format').css('display') == 'none' && $('.err-size').css('display') == 'none') {
			$('#genre-form-add').submit();
		}
	}
</script>