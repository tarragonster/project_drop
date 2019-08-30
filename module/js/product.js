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
        $('#poster_err').text('Image format is not suppported');
        $('#poster_image').attr('src', BASE_APP_URL + 'assets/images/borders/233x346@3x.png');
    } 
    else if(fileSize /(1024*1024) > 1){
        $('#poster_err').text('The size must be less than 1MB');
        $('#poster_image').attr('src', BASE_APP_URL + 'assets/images/borders/233x346@3x.png');
    }else {
        $('#poster_err').text('');
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
        $('#story_err').text('Image format is not suppported');
        $('#series_image').attr('src', BASE_APP_URL + 'assets/images/borders/750x667@3x.png');
    } 
    else if(fileSize /(1024*1024) > 1){
        $('#story_err').text('The size must be less than 1MB');
        $('#series_image').attr('src', BASE_APP_URL + 'assets/images/borders/750x667@3x.png');
    }else {
        $('#story_err').text('');
        var reader = new FileReader();
        reader.onload = function (event) {

            $('#series_image').attr('src',event.target.result);
        }
        reader.readAsDataURL(e.target.files[0]);
        err2 = false
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
        $('#car_err').text('Image format is not suppported');
        $('#carousel_image').attr('src', BASE_APP_URL + 'assets/images/borders/667x440@3x.png');
    } 
    else if(fileSize /(1024*1024) > 1){
        $('#car_err').text('The size must be less than 1MB');
        $('#carousel_image').attr('src', BASE_APP_URL + 'assets/images/borders/667x440@3x.png');
    }else {
        $('#car_err').text('');
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
        $('#ex_err').text('Image format is not suppported');
        $('#explore_image').attr('src', BASE_APP_URL + 'assets/images/borders/650x688@3x.png');
    } 
    else if(fileSize /(1024*1024) > 1){
        $('#ex_err').text('The size must be less than 1MB');
        $('#explore_image').attr('src', BASE_APP_URL + 'assets/images/borders/650x688@3x.png');
    }else {
        $('#ex_err').text('');
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
    var explore_image = $('#explore_image').attr('src')

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
    if(explore_image == BASE_APP_URL + 'assets/images/borders/650x688@3x.png') {
        $('#ex_err').text('Explore preview image must not be empty')
    }else {
        $('#ex_err').text('')
    }
    if($('#name_err').text() == '' && $('#status_err').text() == '' &&
        $('#des_err').text() == '' && $('#creators_err').text() == '' &&
        $('#jw_err').text() == '' && $('#poster_err').text() == '' &&
        $('#story_err').text() == '' && $('#ex_err').text() == ''
    ) {
        if(key == 'add') {
            $('#product_add').submit();
        }else {
            $('#product_edit').submit();
        }
    }
}






