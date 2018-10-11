$('#select_product').autocomplete({
    delay: 100,
    source: function(request, response) {
        var hrefUrl = $('#select_product').attr('data-href');
        $.ajax({
                url: hrefUrl + encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item.label,
                            value: item.value
                        }
                    }));
                }
            });
        },
    select: function(event, ui) {
        $('#select_product').val(ui.item.label);
        $('#' + $('#select_product').attr('data-linked-id')).val(ui.item.value);
        return false;
    },
    focus: function(event, ui) {
        return false;
    }
});

var imageLoader = document.getElementById('imagePhoto');
imageLoader.addEventListener('change', handleImage, false);

function handleImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {
        
        $('#image').attr('src',event.target.result);
    }
    reader.readAsDataURL(e.target.files[0]);
}
