/**
 * Rajoute du parallax a un élément
 * On doit déjà avoir un element dans le div qui est l'image
 *
 * @param image L'élément de l'image
 * @param section Le div qui va contenir le parallax
 * @param {int} [vitesse=0] La vitesse de défilement. Plus le chiffre est grand, mois la vitesse est grande, 0 = automatique
 * @param {int} [zIndex=-1] z-index de l'image
 * @return void
 */
function parallax(image, section, vitesse, zIndex){
	image = $(image); // image to animation
	section = $(section); // section to animate the image

	if(section.length == 0){
		console.log("la section pour le parallax n'existe pas");
	}

	if(image.length == 0){
		return false;
	}
	
	vitesse = (typeof vitesse === 'undefined') ? 3 : vitesse; // réduction de la vitesse du parallax
	zIndex = (typeof zIndex === 'undefined') ? -1 : zIndex; // zIndex de -1 par default
	
	
	image.addClass('img-parallax');
	section.addClass('section-parallax');
	
	// ajoute une wrapper pour l'image a l'intérieur de la section pour la garder dans le flow
	$('<div class="parallax-img-wrapper"></div>').appendTo(section).css('z-index', zIndex).append(image);
	
	var parallaxObject = {
		vitesse: vitesse,
		image: image,
		section: section
	};
	
	parallaxAnimationFrame(parallaxObject);
}

function parallaxAnimationFrame(parallaxObject){
	win = $(window);
	if(!isMobile()){
		//console.log(parallaxObject);
		parallaxObject.image.removeClass('inactif'); // active le parallax
		if(parallaxObject.section.isOnScreen() === true){
			var visiblePart = parseInt(parallaxObject.section.css('height')); // hauteur de la section = zone visible de l'image
			var maxScroll = parallaxObject.image.height() - visiblePart; // hauteur d'extra de l'image
			
			var sectionScroll = win.scrollTop() + win.height() - parallaxObject.section.offset().top; // sectionScroll est à 0 quand on commence a voir la section, est représente le nombre de pixels visibles
			
			centPourcent = parallaxObject.section.outerHeight() + win.height();
			//console.log(sectionScroll/centPourcent +"%");
			
			if(parallaxObject.vitesse == 0){
				// sectionScroll/centPourcent = mon pourcentage, entre 0 et 1
				parallaxObject.image.css('top', ((parallaxObject.section.outerHeight() - parallaxObject.image.height()) * (sectionScroll/centPourcent)) );
			}else{
				// si l'image est visible au top, on la part de 0
				if(parallaxObject.section.offset().top < win.height()){
					sectionScroll = win.scrollTop()/parallaxObject.vitesse;
				}else{
					sectionScroll = sectionScroll/parallaxObject.vitesse;
				}
				
				if(sectionScroll >= maxScroll){ // si on a atteint le scroll maximum on reste au max
					parallaxObject.image.css('top', maxScroll*-1);
				}else{
					parallaxObject.image.css('top', sectionScroll*-1);
				}
				
			}
			
		}
	}else{
		parallaxObject.image.css('top', '').addClass('inactif'); // mobile reset le top et désactive le parallax
	}
	window.requestAnimationFrame(function(){
		parallaxAnimationFrame(parallaxObject);
	});
}