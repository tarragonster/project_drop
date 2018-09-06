var imageLoader = document.getElementById('inputupload');
    imageLoader.addEventListener('change', handleImage, false);

function handleImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {
        
        $('#sortable').append('<li class="ui-state-default"><img src="' + event.target.result + '" width=100 height=100/></li>');
    }
    reader.readAsDataURL(e.target.files[0]);
}

$(function() {
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
});