var imageLoader = document.getElementById('imagePhoto');
if (imageLoader) {
	imageLoader.addEventListener('change', handleImage, false);
}

function handleImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {
        
        $('#image').attr('src',event.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
}

var coverLoader = document.getElementById('coverPhoto');
if (coverLoader) {
	coverLoader.addEventListener('change', handleCoverImage, false);
}

function handleCoverImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {
        
        $('#coverimg').attr('src',event.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
}

var backgroundImgLoader = document.getElementById('backgroundImg');
if (backgroundImgLoader) {
	backgroundImgLoader.addEventListener('change', handleBackgroundImage, false);
}

function handleBackgroundImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {

        $('#background_photo').attr('src',event.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
}


