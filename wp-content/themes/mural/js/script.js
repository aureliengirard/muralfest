$(function(){
	
	/////// 
	// Prevent transitions before DOM load
	$(window).load(function() {
		$("body").removeClass("preload");
	});
	/*$('#ink-transition .bg-layer').one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
		$("body").removeClass("preload");
	});*/
	$search_artiste = -1
	if ($('.program-filters select[name="artiste"] option').size() >= 5){
		$search_artiste = $('.program-filters select[name="artiste"] option').size();
	}
	$('.program-filters select[name="artiste"]').select2({
		allowClear: true,
		minimumResultsForSearch: $search_artiste, 
		placeholder: $('.program-filters select[name="artiste"]').attr("placeholder")
	})

	$search_category = -1
	if ($('.program-filters select[name="category"] option').size() >= 5) {
		$search_category= $('.program-filters select[name="category"] option').size();
	}
	$('.program-filters select[name="category"]').select2({
			allowClear: (true),
			minimumResultsForSearch: $search_category,
			placeholder: $('.program-filters select[name="category"]').attr("placeholder")
	})

	$search_style = -1
	if ($('.program-filters select[name="style"] option').size() >= 5) {
		$search_style = $('.program-filters select[name="style"] option').size();
	}
	$('.program-filters select[name="style"]').select2({
		allowClear: true,
		minimumResultsForSearch: $search_style,
		placeholder: $('.program-filters select[name="style"]').attr("placeholder")
	})

	if ($.urlParam('category') || $.urlParam('artiste') ||  $.urlParam('date') ){
		$('html, body').animate({
			scrollTop: $(".filters").offset().top 
		}, 0);
	}

	
	// Menumobile - http://mmenu.frebsite.nl/
	$(window).load(function() {
		$('#mmenu').mmenu({
			"slidingSubmenus": false,
			"extensions": [
				"pagedim-black",
				"fx-menu-fade",
				"multiline",
				"theme-white",
				"border-full"
			],
			"offCanvas": {
				"position": "right"
			},
			"navbars": [				
				{
					"position": "top",
					"content": [
						$('.main-menu .home-link').clone(),
						"close"
					]
				},
				{
					"position": "bottom",
					"content": [
						$('footer .sociaux').clone().wrap("<ul></ul>"),
					]
				}
			]
		});
	});
	
	
	$('#mmenu .mm-subopen + a').click(function(e){
		e.preventDefault();
		$(this).prev().trigger('click');
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
			$.post( phpData.childURI+'/inc/add_address.php', { ajax:true, email: $(this).find('input[name="email"]').val(), lang: phpData.lang}, function(res){
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

	if($('#sidebar .img-parallax').length){
		parallax($('#sidebar .img-parallax'), $('#sidebar'));
	}
	

	/*************/
	/* ANIMATION */
	/*************/
	// add class .toAnimate to html element with a animate.css effect class
	// when on screen, the animation will execute
	$(document).ready(function(){
		$(window).on('scroll load', function(event) {
			$('.toAnimate').each(function(index, el) {
				if($(this).isOnScreen() === true){
					$(this).removeClass('toAnimate').addClass('animating');
					var elem_class = $(this).attr('class');
					var pos = elem_class.search('animS_');

					$(this).one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
						$(this).removeClass('animating').addClass('animated');
					});
					
					if(pos !== -1){
						var anim_name = elem_class.substring(pos, elem_class.indexOf(" ", pos));
						$(this).removeClass(anim_name);
						
						anim_name = anim_name.replace('animS_', '');
						
						$(this).addClass(anim_name);
					}
				}
			});
		}); 
	});
});

$.urlParam = function (name) {
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results){	
		return results[1] || null;
	}else{		
		return null;
	}
}