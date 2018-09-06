var accessToken = '';
window.fbAsyncInit = function() {
    FB.init({
      appId      : '592921250896039',
      xfbml      : true,
      version    : 'v2.7'
    });
    FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
          accessToken = response.authResponse.accessToken;
        }
        else {
          FB.login();
        }
    });
};
(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

$("#facebook").blur(function() {
    if($(this).val() != ''){
        var key = $(this).val().trim().trim('/');
        var keys = key.split('/');
        if(keys.length > 2){
            key = keys[3].split('?')[0];
        }
        var url = "https://graph.facebook.com/"+ key +"/?access_token=" + accessToken;
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            success: function(rData) {
                $('#idFaceBook').val(rData.id);
            }
        });
    }
});