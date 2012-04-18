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

		//changement des valeurs des options

		//on vérifie la présence de différents widgets à placer en bas dans la sidebarLeft

		//sélection de diverses widgets à placer en bas de la colonne
		// #dm_sidebar_left 
		var widgetSocialNetworkLogos = $('.dm_widget.social_network_logos');
		if(widgetSocialNetworkLogos.length > 0) {
			//on soustrait la hauteur au calcul à posteriori
			options.isPostHSL = true;
			options.offsetHSL -= widgetSocialNetworkLogos.outerHeight(true);

			$.fn.frontFramework.debug("widgetSocialNetworkLogos outerHeight : " + widgetSocialNetworkLogos.outerHeight(true));
		}

		//ajout d'un padding bottom à l'area pour placer l'élément
		$('#dm_sidebar_left').css('paddingBottom', Math.abs(options.offsetHSL));

		//appel de la fonction de redimenssionnement générale
		$.fn.frontFramework.resizeCols(options);
	}

	//lancement automatique de la fonction
	$(document).ready(function(){
		$('html').frontTemplate();
	});

})(jQuery);