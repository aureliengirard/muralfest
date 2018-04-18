$(function(){
	
	/////// 
	// Prevent transitions before DOM load
	$('#ink-transition .bg-layer').one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
		$("body").removeClass("preload");
	});
	
	
	// Menumobile - http://mmenu.frebsite.nl/
	$(window).load(function() {
		$('#mmenu').mmenu({
			"slidingSubmenus": false,
			"extensions": [
				"theme-dark"
			],
			"offCanvas": {
				"zposition": 'front'
			}
		});
	});
	
	
	$('#mmenu .mm-subopen + a').click(function(e){
		e.preventDefault();
		$(this).prev().trigger('click');
	});
	$('#mmenu .search-field').attr('placeholder', '').focusout(function(){
		if($(this).val() !== ''){
			$(this).closest('form').submit();
		}
	});
	
	
	// Slider exemple - https://owlcarousel2.github.io/OwlCarousel2/
	$(document).ready(function(){
		$(".owl-carousel").owlCarousel({
			items: 1,
			loop: true,
			autoplay: true,
			autoplayTimeout: 5000,
			smartSpeed: 500,
			center: true,
		});
	});
	
	
	
	/* inscription a l'infolettre */
	$('.infolettre, .newsletter').submit(function(e){
		e.preventDefault();

		var messageLog = $(this).find('.error-log');

		if(!validateEmail($(this).find('input[name="email"]').val())){
			messageLog.text(traduction.emailError).show();
		}else{

			// update user interface
			messageLog.html( traduction.mail_mess_1 ).show();
			
			// Prepare query string and send AJAX request
			$.post( phpData.THEMEURI+'/inc/add_address.php', { ajax:true, email: $(this).find('input[name="email"]').val(), lang: phpData.lang}, function(res){
				console.log(res);
				
				if(res.ok==1){				
					var msgOk = traduction.mail_mess_success;
					
					messageLog.html(msgOk);
				}else{
					
					messageLog.html(res.error);
				}
			}, 'json');
			
			return false;
		}
	});
	
	$('.content-wrap .background-parallax .img-parallax').each(function (index, element) {
		parallax($(this), $(this).parents('.background-parallax'));
	});
});