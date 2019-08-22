// Action of Product
$('body').delegate('.btnAct', 'click', function(e) {
    e.preventDefault();

    var product_id = $(this).data('id');

    $('.dis-confirm').click(function(){
        $.get(BASE_APP_URL + 'product/disable', {product_id:product_id}, function(data){
	        $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });

    $('.en-confirm').click(function(){
        $.get(BASE_APP_URL + 'product/enable', {product_id:product_id}, function(data){
	        $(this).attr('data-dismiss', 'modal');
	        location.reload();
        });
    });

    $('.del-confirm').click(function(){
        if ($('#text-confirm').val() == 'DELETE') {
            $.get(BASE_APP_URL + 'product/delete', {product_id:product_id}, function(data){
                $(this).attr('data-dismiss', 'modal');
		        location.reload();
            });
        }
    });
});

// Multi select
$('#genre_id').multiselect({
    placeholder: 'Select Genre',
});

// Sorting
$(".sortable").sortable({
    update: function (event, data) {
        console.log($('#form-data').serialize())
        $.ajax({
            type: "POST",
            url: $(this).attr('data-url'),
            data: $('#form-data').serialize(),
            success: function (data) {
                if (!data.success) {
                    // location.reload();
                }
                console.log(JSON.stringify(data)); // show response from the php script.
            }
        });
    },
}).disableSelection();

// Actions of Review
$('body').delegate('.btn_action', 'click', function(e) {
    e.preventDefault();

    var pick_id = $(this).data('id');
    var product_id = $(this).data('product');
    
    $('.dis-confirm').click(function(){
        $.get(BASE_APP_URL + 'product/disableReview', {pick_id:pick_id, product_id:product_id}, function(data){
            $(this).attr('data-dismiss', 'modal');
            location.reload();
        });
    });

    $('.en-confirm').click(function(){
        $.get(BASE_APP_URL + 'product/enableReview', {pick_id:pick_id, product_id:product_id}, function(data){
            $(this).attr('data-dismiss', 'modal');
            location.reload();
        });
    });

    $('.del-confirm').click(function(){
        if ($('#text-confirm').val() == 'DELETE') {
            $.get(BASE_APP_URL + 'product/deleteReview', {pick_id:pick_id, product_id:product_id}, function(data){
                $(this).attr('data-dismiss', 'modal');
                location.reload();
            });
        }
    });
});

// Validate form
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
        $('#poster_err').css('display', 'none');
        $('#poster_image').attr('src', BASE_APP_URL + 'assets/images/borders/233x346@3x.png');
    } 
    else if(fileSize /(1024*1024) > 1){
        $('#poster_err2').css('display', 'block');
        $('#poster_err1').css('display', 'none');
        $('#poster_err').css('display', 'none');
        $('#poster_image').attr('src', BASE_APP_URL + 'assets/images/borders/233x346@3x.png');
    }else {
        $('#poster_err1').css('display', 'none');
        $('#poster_err2').css('display', 'none');
        $('#poster_err').css('display', 'none');
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
        $('#story_err').css('display', 'none');
        $('#series_image').attr('src', BASE_APP_URL + 'assets/images/borders/750x667@3x.png');
    } 
    else if(fileSize /(1024*1024) > 1){
        $('#series_err2').css('display', 'block');
        $('#series_err1').css('display', 'none');
        $('#story_err').css('display', 'none');
        $('#series_image').attr('src', BASE_APP_URL + 'assets/images/borders/750x667@3x.png');
    }else {
        $('#series_err1').css('display', 'none');
        $('#series_err2').css('display', 'none');
        $('#story_err').css('display', 'none');
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
        $('#carousel_err').css('display', 'none');
        $('#carousel_image').attr('src', BASE_APP_URL + 'assets/images/borders/667x440@3x.png');
    } 
    else if(fileSize /(1024*1024) > 1){
        $('#car_err2').css('display', 'block');
        $('#car_err1').css('display', 'none');
        $('#carousel_err').css('display', 'none');
        $('#carousel_image').attr('src', BASE_APP_URL + 'assets/images/borders/667x440@3x.png');
    }else {
        $('#car_err1').css('display', 'none');
        $('#car_err2').css('display', 'none');
        $('#carousel_err').css('display', 'none');
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

function saveProduct(key) {
    var product_name = $('#name').val()
    var description = $('#text-area-des').val()
    var genre_id = $('#genre_id').val()
    var status = $('#status').val()
    var creators = $('#creators').val()
    var jw_media_id = $('#jw_media_id').val()
    var poster_image = $('#poster_image').attr('src')
    var series_image = $('#series_image').attr('src')
    var carousel_image = $('#carousel_image').attr('src')

    if(product_name == '') {
        $('#name_err').text('Story name must not be empty')
    }else {
        $('#name_err').text('')
    }
    if(description == '') {
        $('#des_err').text('Description must not be empty')
    }else {
        $('#des_err').text('')
    }
    if(status == '') {
        $('#status_err').text('Status must not be empty')
    }else {
        $('#status_err').text('')
    }
    if(creators == '') {
        $('#creators_err').text('Creators must not be empty')
    }else {
        $('#creators_err').text('')
    }
    if(jw_media_id == '') {
        $('#jw_err').text('Trailer JW Media ID must not be empty')
    }else {
        $('#jw_err').text('')
    }
    if(poster_image == BASE_APP_URL + 'assets/images/borders/233x346@3x.png') {
        $('#poster_err').text('Poster image must not be empty')
    }else {
        $('#poster_err').text('')
    }
    if(series_image == BASE_APP_URL + 'assets/images/borders/750x667@3x.png') {
        $('#story_err').text('Story image must not be empty')
    }else {
        $('#story_err').text('')
    }
    if(carousel_image == BASE_APP_URL + 'assets/images/borders/667x440@3x.png') {
        $('#carousel_err').text('Carousel banner must not be empty')
    }else {
        $('#carousel_err').text('')
    }
    if($('#name_err').text() == '' && $('#status_err').text() == '' &&
       $('#des_err').text() == '' && $('#creators_err').text() == '' &&
        $('#jw_err').text() == '' && $('#poster_err').text() == '' &&
        $('#story_err').text() == '' && $('#carousel_err').text() == '' &&
        $('#pre_err1').css('display') == 'none' && $('#pre_err2').css('display') == 'none' &&
        $('#ex_err1').css('display') == 'none' && $('#ex_err2').css('display') == 'none'
    ) {
        if(key == 'add') {
            $('#product_add').submit();
        }else {
            $('#product_edit').submit();
        }
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






