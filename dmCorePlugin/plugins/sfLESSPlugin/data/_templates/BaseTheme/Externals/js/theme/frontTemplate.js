// frontTemplate.js
// v1.1.1
// Last Updated : 2012-06-11 15:25
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){

	//Définition du plugin
	$.fn.frontTemplate = function() {

		// iterate and reformat each matched element
		return this.each(function() {
			//Affichage debug initialisation
			$.fn.frontFramework.debug("frontTemplate BaseTheme | initialisation");

			//lancement fonction de redimensionnement de la page
			var offsetWindow = $.fn.frontFramework.globalHeight();

			//gestion des classes sur les adresses multiples
			$('.dm_widget.adresses_multiples > .dm_widget_inner > ul.elements').listClassCutter();
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