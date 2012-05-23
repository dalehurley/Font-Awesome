// frontTemplate.js
// v1.2.2
// Last Updated : 2012-05-23 12:15
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){

	//Définition du plugin
	$.fn.frontTemplate = function() {

		// iterate and reformat each matched element
		return this.each(function() {
			//Affichage debug initialisation
			$.fn.frontFramework.debug("frontTemplate maestroTheme | initialisation");

			//on fusionne les options courantes avec les options par défaut
			var getOptions = $.extend({}, $.fn.frontTemplate.defaultOptions, $.fn.frontTemplate.currentOptions);

			//Configuration du redimenssionnement
			var resultOptions = $.fn.frontTemplate.resizeCols(getOptions);

			//on fusionne les options résultantes avec les options courantes
			$.extend($.fn.frontTemplate.currentOptions, resultOptions);
		});
	}

	//Gestion de la taille des colonnes
	$.fn.frontTemplate.resizeCols = function (options) {
		
		// $.fn.frontFramework.debug("options.offsetHC : " + options.offsetHC);

		//on rajoute l'espace manquant à priori en bas à droite afin de combler le manque éventuel
		var offsetHC = $('#dm_main').height() - $('#dm_main_inner').height();

		//on rajoute le décalage
		if(offsetHC > 0) options.offsetHC+= offsetHC;
		// $.fn.frontFramework.debug("offsetHC : " + offsetHC);
		
		//on calcul la hauteur de la zone customBottom et de sa sous-zone à gauche
		var customBottomHeight = $('#dm_custom_bottom').outerHeight(true);
		var customBottomLeftHeight = $('#dm_custom_bottom_left').outerHeight(true);
		// $.fn.frontFramework.debug("customBottomHeight : " + customBottomHeight + " customBottomLeftHeight : " + customBottomLeftHeight);
		
		//on ne rajoute de l'espace que si la zone est insuffisamment grande pour le contenir
		if(customBottomHeight < customBottomLeftHeight) {
			//on ne rajoute que l'espace manquant
			//en soustrayant la hauteur de la zone située en bas à gauche au calcul à posteriori
			options.isPostHSL = true;
			options.offsetHSL += (customBottomHeight - customBottomLeftHeight);
		}
		
		//appel de la fonction de redimenssionnement générale
		$.fn.frontFramework.resizeCols(options);

		//on retourne les options
		return options;
	}

	//Paramètres par défaut
	$.fn.frontTemplate.defaultOptions = {
		offsetHC: 0,
		offsetHSL: 0,
		offsetHSR: 0
	};

	//Paramètres appliqués actuellement
	$.fn.frontTemplate.currentOptions = {};

	//lancement automatique de la fonction
	$(document).ready(function(){
		$('html').frontTemplate();

		//inversion ordre du wrapper dans les listings (pour passer devant l'image quand présente)
		// $('.elements .element .imageWrapper').each(function(index) {
		// 	$(this).insertAfter($(this).parent().children('.wrapper').children('.subWrapper'));
		// });
	});

	//lorsque la fenêtre est redimmensionnée
	$(window).resize(function() {
		//lancement de la fonction de replacement avec un délai lors du redimenssionnement
		$.fn.frontFramework.delay(function(){
			$('html').frontTemplate();
		}, 500);
	});

})(jQuery);