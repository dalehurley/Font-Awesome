// frontTemplate.js
// v1.2
// Last Updated : 2012-06-26 15:15
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){

	//Définition du plugin
	$.fn.frontTemplate = function() {

		// iterate and reformat each matched element
		return this.each(function() {
			//Affichage debug initialisation
			$.fn.frontFramework.debug("frontTemplate modeleTheme | initialisation");

			//lancement fonction de redimensionnement de la page
			var offsetWindow = $.fn.frontFramework.globalHeight();

			//on applique le décalage que si la zone existe et que l'offset est significatif
			if($('#dm_custom_bottom').length > 0 && offsetWindow >= 0) {
				$('#dm_page_content, #dm_sidebar_left, #dm_sidebar_right').css('marginBottom', offsetWindow);
			}
		});
	}

	//lancement automatique de la fonction
	$(document).ready(function(){
		$('html').frontTemplate();
	});

	//lorsque la fenêtre est redimmensionnée
	$(window).resize(function() {
		//lancement de la fonction de replacement avec un délai lors du redimenssionnement
		$.fn.frontFramework.delay(function(){
			$('html').frontTemplate();
		}, 500);
	});

})(jQuery);