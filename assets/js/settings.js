// Upload Images File
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

// Load Users List
// var sttUser = $('select option:selected').val();
// function loadUser(sttUser) {
//     $.ajax({
//         type: 'GET',
//         dataType: 'json',
//         url: "user/getUsersByStatus",
//         data: {status:sttUser},
//         success: function(data) {
//             $('#user_table').html(data);
//         }
//     });
// }
// loadUser(sttUser);
//
// $('.status-user').change(function(){
//     sttUser = $(this).val();
//     loadUser(sttUser);
// });

// Load Products List
// var sttFilm = $('select option:selected').val();
// function loadData(sttFilm){
//     $.ajax({
//         type: 'GET',
//         dataType: 'json',
//         url: "product/getProductsByStatus",
//         data: {status:sttFilm},
//         success: function(data) {
//             $('#product_table').html(data);
//         },
//     });
// }
// loadData(sttFilm);

// $('.status-film').change(function(){
//     sttFilm = $(this).val();
//     loadData(sttFilm);
// });

// Update Product Status
// $('body').delegate('.btnAct', 'click', function(e) {
//     e.preventDefault();
//     var product_id = $(this).attr('data-id');
//     $('.dis-confirm').click(function(){
//         $.get('product/disable', {product_id:product_id}, function(data){
//             loadData($('select option:selected').val());
//         });
//         $(this).attr('data-dismiss', 'modal');
//     });
//     $('.en-confirm').click(function(){
//         $.get('product/enable', {product_id:product_id}, function(data){
//             loadData($('select option:selected').val());
//         });
//         $(this).attr('data-dismiss', 'modal');
//     });
//     $('.del-confirm').click(function(){
//         if ($('#text-confirm').val() == 'DELETE') {
//             $.get('product/delete', {product_id:product_id}, function(data){
//                 loadData($('select option:selected').val());
//             });
//             $(this).attr('data-dismiss', 'modal');
//         }
//     });
// });

// Multiple select
$('#genre_id').multipleSelect({
    placeholder: 'Select Genre'
})









