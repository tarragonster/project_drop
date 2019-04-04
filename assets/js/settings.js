var imageLoader = document.getElementById('posterImg');
if (imageLoader) {
    imageLoader.addEventListener('change', handleImage, false);
}
function handleImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {
        
        $('#poster_image').attr('src',event.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
}

var seriesImgLoader = document.getElementById('seriesImg');
if (seriesImgLoader) {
    seriesImgLoader.addEventListener('change', handleSeriesImage, false);
}
function handleSeriesImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {

        $('#series_image').attr('src',event.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
}

var previewImgLoader = document.getElementById('previewImg');
if (previewImgLoader) {
    previewImgLoader.addEventListener('change', handlePreviewImage, false);
}
function handlePreviewImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {
        
        $('#preview_image').attr('src',event.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
}

var carouselImgLoader = document.getElementById('carouselImg');
if (carouselImgLoader) {
    carouselImgLoader.addEventListener('change', handleCarouselImage, false);
}
function handleCarouselImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {
        
        $('#carousel_image').attr('src',event.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
}

var exploreImgLoader = document.getElementById('exploreImg');
if (exploreImgLoader) {
    exploreImgLoader.addEventListener('change', handleExploreImage, false);
}
function handleExploreImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {
        
        $('#explore_image').attr('src',event.target.result);
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



var sttUser = $('select option:selected').val();
$.get("user/getUsersByStatus", {status:sttUser}, function(data){
    $('#user_table').html(data);
});

$('.status-user').change(function(){
    sttUser = $(this).val();
    $.get("user/getUsersByStatus", {status:sttUser}, function(data){
        $('#user_table').html(data);
    });
});

var sttFilm = $('select option:selected').val();
function loadData(sttFilm){
    $.get("product/getProductsByStatus", {status:sttFilm}, function(data){
        $('#product_table').html(data);
    });
}
loadData(sttFilm);

$('.status-film').change(function(){
    sttFilm = $(this).val();
    $.get("product/getProductsByStatus", {status:sttFilm}, function(data){
        $('#product_table').html(data);
    });
});







