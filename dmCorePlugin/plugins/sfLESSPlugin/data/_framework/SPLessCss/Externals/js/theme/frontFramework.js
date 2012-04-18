// frontFramework.js
// v1.0
// Last Updated : 2012-04-17 17:30
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){

	//Définition du plugin
	$.fn.frontFramework = function() {

		// iterate and reformat each matched element
		return this.each(function() {
			//Affichage debug initialisation
			$.fn.frontFramework.debug("frontFramework SPLessCss | initialisation");
		});
	}

	//Gestion de la taille des colonnes
	$.fn.frontFramework.resizeCols = function (options) {

		//définitions de valeurs par défaut
		if(options.offsetHC == null) options.offsetHC = 0;
		if(options.offsetHSL == null) options.offsetHSL = 0;
		if(options.offsetHSR == null) options.offsetHSR = 0;

		if(options.isPostHC == null) options.isPostHC = false;
		if(options.isPostHSL == null) options.isPostHSL = false;
		if(options.isPostHSR == null) options.isPostHSR = false;

		//sélection des éléments de page
		var dmZonesPageContent= $('#dm_page_content > .dm_zones');
		var dmZonesSidebarLeft = $('#dm_sidebar_left > .dm_zones');
		var dmZonesSidebarRight = $('#dm_sidebar_right > .dm_zones');

		//hauteur contenu
		var hC = (dmZonesPageContent.length > 0) ? dmZonesPageContent.height() : 0;
		//hauteur sidebar left
		var hSL = (dmZonesSidebarLeft.length > 0) ? dmZonesSidebarLeft.height() : 0;
		//hauteur sidebar right
		var hSR = (dmZonesSidebarRight.length > 0) ? dmZonesSidebarRight.height() : 0;

		//on rajoute les offsets si définis sur les dimensions
		if(!options.isPostHC) hC += options.offsetHC;
		if(!options.isPostHSL) hSL += options.offsetHSL;
		if(!options.isPostHSR) hSR += options.offsetHSR;

		//calcul de la plus grande hauteur
		var maxH = 0;
		if(hC > maxH) maxH = hC;
		if(hSL > maxH) maxH = hSL;
		if(hSR > maxH) maxH = hSR;

		//affichage infos de débug
		$.fn.frontFramework.debug("frontFramework | maxH : " + maxH + " hC : " + hC + " hSL : " + hSL + " hSR : " + hSR);

		//application des hauteurs sur les éléments (on utilise minHeight pour adapter automatiquement en fonction du changement du contenu)
		if(dmZonesPageContent.length > 0) dmZonesPageContent.css('minHeight', (options.isPostHC ? maxH + options.offsetHC  : maxH));
		if(dmZonesSidebarLeft.length > 0) dmZonesSidebarLeft.css('minHeight', (options.isPostHSL ? maxH + options.offsetHSL  : maxH));
		if(dmZonesSidebarRight.length > 0) dmZonesSidebarRight.css('minHeight', (options.isPostHSR ? maxH + options.offsetHSR  : maxH));
	}

	//fonction de debuggage
	$.fn.frontFramework.debug = function(txt){
		if (window.console && window.console.log)
			window.console.log(txt);
	}

	//lancement automatique de la fonction
	$(document).ready(function(){
		$('html').frontFramework();
	});

})(jQuery);