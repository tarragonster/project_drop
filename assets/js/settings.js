// Upload Images File
var err1, err2, err3, err4, err5 = true;

var imageLoader = document.getElementById('posterImg');
if (imageLoader) {
    imageLoader.addEventListener('change', handleImage, false);
}
function handleImage(e) {
    const arr = ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'JPG', 'PNG', 'JPEG', 'GIF', 'PDF']

    var poster_image = $('#posterImg').val()
    var fileSize = document.getElementById('posterImg').files[0].size;
    isImage = arr.includes(poster_image.split('.').pop())

    if (isImage == false) {
        $('#poster_err1').css('display', 'block');
        $('#poster_err2').css('display', 'none');
        $('#poster_image').attr('src', BASE_APP_URL + 'assets/images/borders/233x346@3x.png');
    } 
    else if(fileSize /(1024*1024) > 1){
        $('#poster_err2').css('display', 'block');
        $('#poster_err1').css('display', 'none');
        $('#poster_image').attr('src', BASE_APP_URL + 'assets/images/borders/233x346@3x.png');
    }else {
        $('#poster_err1').css('display', 'none');
        $('#poster_err2').css('display', 'none');
        var reader = new FileReader();
        reader.onload = function (event) {

            $('#poster_image').attr('src',event.target.result);
        }
        reader.readAsDataURL(e.target.files[0]);
        err1 = false
    }
}

var seriesImgLoader = document.getElementById('seriesImg');
if (seriesImgLoader) {
    seriesImgLoader.addEventListener('change', handleSeriesImage, false);
}
function handleSeriesImage(e) {
    const arr = ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'JPG', 'PNG', 'JPEG', 'GIF', 'PDF']

    var series_image = $('#seriesImg').val()
    var fileSize = document.getElementById('seriesImg').files[0].size;
    isImage = arr.includes(series_image.split('.').pop())

    if (isImage == false) {
        $('#series_err1').css('display', 'block');
        $('#series_err2').css('display', 'none');
        $('#series_image').attr('src', BASE_APP_URL + 'assets/images/borders/750x667@3x.png');
    } 
    else if(fileSize /(1024*1024) > 1){
        $('#series_err2').css('display', 'block');
        $('#series_err1').css('display', 'none');
        $('#series_image').attr('src', BASE_APP_URL + 'assets/images/borders/750x667@3x.png');
    }else {
        $('#series_err1').css('display', 'none');
        $('#series_err2').css('display', 'none');
        var reader = new FileReader();
        reader.onload = function (event) {

            $('#series_image').attr('src',event.target.result);
        }
        reader.readAsDataURL(e.target.files[0]);
        err2 = false
    }
}

var previewImgLoader = document.getElementById('previewImg');
if (previewImgLoader) {
    previewImgLoader.addEventListener('change', handlePreviewImage, false);
}
function handlePreviewImage(e) {
    const arr = ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'JPG', 'PNG', 'JPEG', 'GIF', 'PDF']

    var preview_image = $('#previewImg').val()
    var fileSize = document.getElementById('previewImg').files[0].size;
    isImage = arr.includes(preview_image.split('.').pop())

    if (isImage == false) {
        $('#pre_err1').css('display', 'block');
        $('#pre_err2').css('display', 'none');
        $('#preview_image').attr('src', BASE_APP_URL + 'assets/images/borders/135x135@3x.png');
    } 
    else if(fileSize /(1024*1024) > 1){
        $('#pre_err2').css('display', 'block');
        $('#pre_err1').css('display', 'none');
        $('#preview_image').attr('src', BASE_APP_URL + 'assets/images/borders/135x135@3x.png');
    }else {
        $('#pre_err1').css('display', 'none');
        $('#pre_err2').css('display', 'none');
        var reader = new FileReader();
        reader.onload = function (event) {

            $('#preview_image').attr('src',event.target.result);
        }
        reader.readAsDataURL(e.target.files[0]);
        err3 = false
    }
}

var carouselImgLoader = document.getElementById('carouselImg');
if (carouselImgLoader) {
    carouselImgLoader.addEventListener('change', handleCarouselImage, false);
}
function handleCarouselImage(e) {
    const arr = ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'JPG', 'PNG', 'JPEG', 'GIF', 'PDF']

    var carousel_image = $('#carouselImg').val()
    var fileSize = document.getElementById('carouselImg').files[0].size;
    isImage = arr.includes(carousel_image.split('.').pop())

    if (isImage == false) {
        $('#car_err1').css('display', 'block');
        $('#car_err2').css('display', 'none');
        $('#carousel_image').attr('src', BASE_APP_URL + 'assets/images/borders/667x440@3x.png');
    } 
    else if(fileSize /(1024*1024) > 1){
        $('#car_err2').css('display', 'block');
        $('#car_err1').css('display', 'none');
        $('#carousel_image').attr('src', BASE_APP_URL + 'assets/images/borders/667x440@3x.png');
    }else {
        $('#car_err1').css('display', 'none');
        $('#car_err2').css('display', 'none');
        var reader = new FileReader();
        reader.onload = function (event) {

            $('#carousel_image').attr('src',event.target.result);
        }
        reader.readAsDataURL(e.target.files[0]);
        err4 = false
    }
}

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
        $('#ex_err1').css('display', 'block');
        $('#ex_err2').css('display', 'none');
        $('#explore_image').attr('src', BASE_APP_URL + 'assets/images/borders/650x688@3x.png');
    } 
    else if(fileSize /(1024*1024) > 1){
        $('#ex_err2').css('display', 'block');
        $('#ex_err1').css('display', 'none');
        $('#explore_image').attr('src', BASE_APP_URL + 'assets/images/borders/650x688@3x.png');
    }else {
        $('#ex_err1').css('display', 'none');
        $('#ex_err2').css('display', 'none');
        var reader = new FileReader();
        var reader = new FileReader();
        reader.onload = function (event) {

            $('#explore_image').attr('src',event.target.result);
        }
        reader.readAsDataURL(e.target.files[0]);
        err5 = false
    }
}

function createProduct() {
    if(err1 == false && err2 == false && err3 == false && err4 == false && err5 == false) {
        $('#product_add').submit();
    }
}

function saveProduct() {
    if(err1 == false && err2 == false && err3 == false && err4 == false && err5 == false) {
        $('#product_edit').submit();
    }
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





