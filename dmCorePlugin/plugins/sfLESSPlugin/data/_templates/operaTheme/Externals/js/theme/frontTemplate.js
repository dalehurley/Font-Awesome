// frontTemplate.js
// v1.0
// Last Updated : 2012-04-17 17:30
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){

	//Définition du plugin
	$.fn.frontTemplate = function() {

		// iterate and reformat each matched element
		return this.each(function() {
			//Affichage debug initialisation
			$.fn.frontFramework.debug("frontTemplate operaTheme | initialisation");
		});
	}

	//lancement automatique de la fonction
	$(document).ready(function(){
		$('html').frontTemplate();
	});

})(jQuery);