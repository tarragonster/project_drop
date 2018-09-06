var imageLoader = document.getElementById('imagePhoto');
imageLoader.addEventListener('change', handleImage, false);

function handleImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {
        
        $('#image').attr('src',event.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
}

var coverLoader = document.getElementById('coverPhoto');
coverLoader.addEventListener('change', handleCoverImage, false);

function handleCoverImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {
        
        $('#coverimg').attr('src',event.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
}