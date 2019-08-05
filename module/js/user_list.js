$('.view-user-click').on('click', function (e) {
    e.preventDefault();
    var user_id = $(this).data('user_id') * 1;
    $('#view-user-content').html("");
    $.ajax({
        type: "GET",
        dataType: "",
        url: BASE_APP_URL + 'user/ajaxProfile/' + user_id,
        success: function (data) {
            $('#view-user-content').html(data.content);

            var viewModalLazyLoad;
            $('#view-user-popup')
                .bind('shown.bs.modal', function () {
                    viewModalLazyLoad = new LazyLoad({
                        elements_selector: ".b-lazy"
                    });
                    // popUpInit();
                })
                .bind('hidden.bs.modal', function () {
                    viewModalLazyLoad.destroy();
                });
            // $('#customer-edit-body').html("");
            $('#view-user-popup').modal('show');
        }
    });

});

function popUpInit() {

    $('.image-popup').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-fade',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
        }
    });
}