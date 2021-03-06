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
	if ($('.program-filters select[name="filtre-artiste"] option').size() >= 5){
		$search_artiste = $('.program-filters select[name="filtre-artiste"] option').size();
	}
	$('.program-filters select[name="filtre-artiste"]').select2({
		allowClear: true,
		minimumResultsForSearch: $search_artiste,
		placeholder: $('.program-filters select[name="filtre-artiste"]').attr("placeholder")
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

	$search_year = -1
	if ($('.program-filters select[name="years"] option').size() >= 5) {
		$search_year = $('.program-filters select[name="years"] option').size();
	}
	$('.program-filters select[name="years"]').select2({
		allowClear: true,
		minimumResultsForSearch: $search_year,
		placeholder: $('.program-filters select[name="years"]').attr("placeholder")
	})

	if ($.urlParam('category') || $.urlParam('filtre-artiste') || $.urlParam('years') ||  $.urlParam('date') ){
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


	/// ajout la classe mobile sur le mobile pour menu mobile sur Ipad
    $(window).on('load resize',function() {
        if(!isMobile()){
             $('body').addClass('desktop');
        }else{
            $('body').removeClass('desktop');
        }
    });
    $(window).on('load resize',function() {
        if(isMobile()){
             $('body').addClass('mobile');
        }else{
            $('body').removeClass('mobile');
        }
	});

	//afficher l'infolette
	$('.popup-exit').on('click',function(){
		$('.newsletter').removeClass('popup-visible');
	});

	//femer l'infolette
	$('.formulaire_infolettre .button').on('click',function(){
		$('.newsletter').addClass('popup-visible');
	});
	//
	$('.newsletter').on('click',function(e){
		e.stopPropagation();
		if(this==e.target){
			$('.newsletter').removeClass('popup-visible');
		}
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

});

$.urlParam = function (name) {
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results){
		return results[1] || null;
	}else{
		return null;
	}
}