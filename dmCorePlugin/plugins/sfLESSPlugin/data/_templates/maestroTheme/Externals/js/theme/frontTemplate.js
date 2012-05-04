// frontTemplate.js
// v1.2
// Last Updated : 2012-05-02 15:20
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

			//Configuration du redimenssionnement
			$.fn.frontTemplate.resizeCols({
										offsetHC: 0,
										offsetHSL: 0,
										offsetHSR: 0
										});
		});
	}

	//Gestion de la taille des colonnes
	$.fn.frontTemplate.resizeCols = function (options) {
		
		//on rajoute l'espace manquant en bas à droite à priori afin de combler le manque éventuel
		options.offsetHC += $('#dm_main').height() - $('#dm_main_inner').height();

		//on calcul la hauteur de la zone customBottom et de sa sous-zone à gauche
		var customBottomHeight = $('#dm_custom_bottom').outerHeight(true);
		var customBottomLeftHeight = $('#dm_custom_bottom').find('.dm_zone.left').outerHeight(true);
		// $.fn.frontFramework.debug("customBottomHeight : " + customBottomHeight + " customBottomLeftHeight : " + customBottomLeftHeight);

		//on ne rajoute de l'espace que si la zone est insuffisamment grande pour le contenir
		if(customBottomHeight != null && customBottomLeftHeight!= null && customBottomHeight < customBottomLeftHeight) {
			//on ne rajoute que l'espace manquant
			//en soustrayant la hauteur de la zone située en bas à gauche au calcul à posteriori
			options.isPostHSL = true;
			options.offsetHSL += (customBottomHeight - customBottomLeftHeight);
		}

		//appel de la fonction de redimenssionnement générale
		$.fn.frontFramework.resizeCols(options);
	}

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