$(function(){
	//// The namespace
	var Avatar = {
		// Variables
		lang : $('html').attr('lang'),
		
		// Will hold the base64 data for the image file later on...
		base64holding : '',
		
		// Methods
		init : function(){
			
			// Initialize the cropper
			Avatar.initCropper();
			
			$('.upload-avatar').change(function(){
				$this = $('.upload-avatar');
				$('.preview-avatar').html($(this).val());
				
				// if has files
				if ($this[0].files && $this[0].files[0]) {
					var reader = new FileReader();
					
					// Set the image in the cropper area
					reader.onload = function(e){
						Avatar.base64holding = e.target.result;
						Avatar.$cropperImage.cropper('replace', Avatar.base64holding);
						
						// Ouver le popup
						Avatar.openCropper();
					}
					
					reader.readAsDataURL($this[0].files[0]);
				}// if has file
				
			});
			
			
			/// Save Crop button
			$('.cropper-wrap a.button').click(function(e){
				e.preventDefault();
				Avatar.closeCropper();
			});
			
		}, // init
		
		
		///// 
		openCropper : function(){
			$('#cropper-popup').show();
			$('#cropper-popup').clearQueue().stop().animate({opacity:1}, 700);
			$('#cropper-popup > div').clearQueue().stop().animate({top:50}, 700);
		},
		
		////////////
		// Close
		closeCropper : function(){
			
			// Close window
			$('#cropper-popup').clearQueue().stop().animate({opacity:0}, 700, function(){
				$('#cropper-popup').hide();
			});
			$('#cropper-popup > div').clearQueue().stop().animate({top:-600}, 700);
		},
		
		
		// Initialize the cropper
		initCropper : function(){
			Avatar.$cropperImage = $('#user-image-cropper > img');
			
			Avatar.$cropperImage.cropper({
				aspectRatio: 1/1,
				movable: false,
				zoomable: false,
				rotatable: false,
				scalable: false,
				minCropBoxWidth: 300,
				minCropBoxHeight: 300,
				crop: function (e) {
					// Set les valeurs dynamiquement
					var json = [
						'{"x":' + e.x,
						'"y":' + e.y,
						'"height":' + e.height,
						'"width":' + e.width,
						'"rotate":' + 0 + '}'
					].join();
					
					$('input[name=cropData]').val(json);
				}
			});
		},
		
		
	};	//// The namespace
	
	Avatar.init();
	
});