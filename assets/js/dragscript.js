$(document).ready(function() {

	/*****************************
	 Variables
	 *****************************/
	var imgWidth = 180,
			imgHeight = 180,
			imageCount = 0,
			imageTotal = 0,
			imageAvatar = 0,
			dropzone = $('#droparea'),
			uploadBtn = $('#uploadbtn'),
			defaultUploadBtn = $('#upload');

	imageCount = $('#sortable li').length;

	/*****************************
	 Events Handler
	 *****************************/
	dropzone.on('dragover', function() {
		//add hover class when drag over
		dropzone.addClass('hover');
		return false;
	});
	dropzone.on('dragleave', function() {
		//remove hover class when drag out
		dropzone.removeClass('hover');
		return false;
	});
	dropzone.on('drop', function(e) {
		//prevent browser from open the file when drop off
		e.stopPropagation();
		e.preventDefault();
		dropzone.removeClass('hover');

		//retrieve uploaded files data
		var files = e.originalEvent.dataTransfer.files;
		//processFiles(files);

		return false;
	});

	uploadBtn.on('click', function(e) {
		e.stopPropagation();
		e.preventDefault();
		//trigger default file upload button
		defaultUploadBtn.click();
	});
	defaultUploadBtn.on('change', function() {
		//retrieve selected uploaded files data
		var files = $(this)[0].files;
		processFiles(files);

		return false;
	});


	/*****************************
	 internal functions
	 *****************************/
	//Bytes to KiloBytes conversion
	function convertToKBytes(number) {
		return (number / 1024).toFixed(1);
	}

	function compareWidthHeight(width, height) {
		var diff = [];
		if(width > height) {
			diff[0] = width - height;
			diff[1] = 0;
		} else {
			diff[0] = 0;
			diff[1] = height - width;
		}
		return diff;
	}

	function dataURItoBlob(dataURI) {

		// convert base64 to raw binary data held in a string
		// doesn't handle URLEncoded DataURIs
		var byteString;
		if (dataURI.split(',')[0].indexOf('base64') >= 0)
			byteString = atob(dataURI.split(',')[1]);
		else
			byteString = unescape(dataURI.split(',')[1]);

		// separate out the mime component
		var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]

		// write the bytes of the string to an ArrayBuffer
		var ab = new ArrayBuffer(byteString.length);
		var ia = new Uint8Array(ab);
		for (var i = 0; i < byteString.length; i++) {
			ia[i] = byteString.charCodeAt(i);
		}

		//Passing an ArrayBuffer to the Blob constructor appears to be deprecated, 
		//so convert ArrayBuffer to DataView
		var dataView = new DataView(ab);
		var blob = new Blob([dataView], {type: mimeString});

		return blob;
	}

	/*****************************
	 Process FileList
	 *****************************/
	var processFiles = function(files) {
		if(files && typeof FileReader !== "undefined") {
			$('#sortable .img-new').remove();
			imageCount = $('#sortable li').length;
			imageTotal = 0;

			if (imageAvatar>0){
				imageAvatar = 0;
				$('#imgavatar').val(imageAvatar);
			}

			if (imageCount + files.length > 5) {
				alert('maximum is 5 image');
			} else {
				imageCount += files.length;
				for(var i=0; i<files.length; i++) {
					readFile(files[i], i);
				}
			}
		}
	}


	/*****************************
	 Read the File Object
	 *****************************/
	var readFile = function(file, id) {
		if( (/image/i).test(file.type) ) {
			//define FileReader object
			var reader = new FileReader();

			//init reader onload event handlers
			reader.onload = function(e) {
//				var image = $('<img/>')
//				.load(function() {
//					//when image fully loaded
//					var newimageurl = getCanvasImage(this);
//					createPreview(file, newimageurl);
////					uploadToServer(file, dataURItoBlob(newimageurl));
//				})
//				.attr('src', e.target.result);
				//var sClass = (imageTotal==0)?'ui-state-error':'ui-state-default';
				sClass = 'ui-state';

				$('#sortable').append('<li class="'+sClass+' img-new"><img src="' + e.target.result + '" width=96 height=96/><input type="hidden" name="imgorder[]" value="'+id+'" /> <div class="btn-group"><button type="button" class="btn btn-default btn-xs act-remove" aria-label="Remove"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></div></li>');
				imageTotal++;
			};

			//begin reader read operation
			reader.readAsDataURL(file);

			$('#err').text('');
		} else {
			//some message for wrong file format
			$('#err').text('*Selected file format not supported!');
		}
	}


	/*****************************
	 Get New Canvas Image URL
	 *****************************/
	var getCanvasImage = function(image) {
		//define canvas
		var canvas = document.createElement('canvas');
		canvas.width = imgWidth;
		canvas.height = imgHeight;
		var ctx = canvas.getContext('2d');

		//default resize variable
		var diff = [0, 0];
		diff = compareWidthHeight(image.width, image.height);

		//draw canvas image
		ctx.drawImage(image, diff[0]/2, diff[1]/2, image.width-diff[0], image.height-diff[1], 0, 0, imgWidth, imgHeight);
		//convert canvas to jpeg url
		return canvas.toDataURL("image/jpeg");
	}


	/*****************************
	 Draw Image Preview
	 *****************************/
	var createPreview = function(file, newURL) {
		//populate jQuery Template binding object
		var imageObj = {};
		imageObj.filePath = newURL;
		imageObj.fileName = file.name.substr(0, file.name.lastIndexOf('.')); //subtract file extension
		imageObj.fileOriSize = convertToKBytes(file.size);
		imageObj.fileUploadSize = convertToKBytes(dataURItoBlob(newURL).size); //convert new image URL to blob to get file.size

		//extend filename
		var effect = $('input[name=effect]:checked').val();
		if(effect == 'grayscale') {
			imageObj.fileName += " (Grayscale)";
		} else if(effect == 'blurry') {
			imageObj.fileName += " (Blurry)";
		}

		//append new image through jQuery Template
		var randvalue = Math.floor(Math.random()*31)-15;  //random number
		var img = $("#imageTemplate").tmpl(imageObj).appendTo("#result")
				.hide()
				.css({
					'Transform': 'scale(1)',
					'msTransform': 'scale(1)',
					'MozTransform': 'scale(1)',
					'webkitTransform': 'scale(1)',
					'OTransform': 'scale(1)',
				})
				.show();

		if(isNaN(imageObj.fileUploadSize)) {
			$('.imageholder span').last().hide();
		}
	}


	/****************************
	 Browser compatible text
	 ****************************/
	if (typeof FileReader === "undefined") {
		//$('.extra').hide();
		$('#err').html('Hey! Your browser does not support <strong>HTML5 File API</strong> <br/>Try using Chrome or Firefox to have it works!');
	} else if (!Modernizr.draganddrop) {
		$('#err').html('Ops! Look like your browser does not support <strong>Drag and Drop API</strong>! <br/>Still, you are able to use \'<em>Select Files</em>\' button to upload file =)');
	} else {
		$('#err').text('');
	}

	$( "#sortable" ).sortable({
		placeholder: "ui-state-error"
	});
	$( "#sortable" ).disableSelection();
	/*$('.ui-state img').load(function() {
	 var newimageurl = getCanvasImage(this);
	 createPreview(file, newimageurl);
	 });*/
	$("#prdadd").submit( function(eventObj) {
		//eventObj.preventDefault();

		var par = $('#sortable li:first-child');
		var val = parseInt($('input', par).val());
		imageAvatar = (val<0)?val:val+1;
		$('#imgavatar').val(imageAvatar);
	});

	$( "#sortable").on('click', 'button', function (){
		if ($(this).hasClass('act-remove')){
			var par = $(this).parents('li');
			var val = $('input', par).val();
			$(this).parents('li').remove();
			imageCount--;
		}

		if ($(this).hasClass('act-avatar')){
			$('#sortable li').removeClass('ui-state-error').addClass('ui-state-default');
			var par = $(this).parents('li');
			par.removeClass('ui-state-default').addClass('ui-state-error');
			var val = parseInt($('input', par).val());
			imageAvatar = (val<0)?val:val+1;
			$('#imgavatar').val(imageAvatar);
		}
	});
});
