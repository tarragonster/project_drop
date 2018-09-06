var myVideos = [];
window.URL = window.URL || window.webkitURL;
function setFileInfo(files) {
    myVideos.push(files[0]);
    var video = document.createElement('video');
    video.preload = 'metadata';
    video.onloadedmetadata = function() {
      window.URL.revokeObjectURL(this.src)
      var duration = video.duration;
      myVideos[myVideos.length-1].duration = duration;
      updateInfos();
    }
    video.src = URL.createObjectURL(files[0]);;
}
function updateInfos(){
    document.querySelector('#infos').innerHTML="";
    document.querySelector('#infos').innerHTML = "Duration: "+myVideos[myVideos.length - 1].duration + "s";
    document.querySelector('#duration').value = myVideos[myVideos.length - 1].duration;
}