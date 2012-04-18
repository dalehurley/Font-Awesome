// frontTheme.js
// v1.0
// Last Updated : 2012-04-17 17:30
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){

	//Définition du plugin
	$.fn.frontTheme = function() {

		// iterate and reformat each matched element
		return this.each(function() {
			//Affichage debug initialisation
			$.fn.frontTheme.debug("frontTheme maestroTheme | initialisation");

			$.fn.frontTheme.resizeCols();
		});
	}

	//Gestion de la taille des colonnes
	$.fn.frontTheme.resizeCols = function (offsetHSL = 0, offsetHSR = 0, offsetHC = 0) {

		//sélection des éléments de page
		var dmZonesSidebarLeft = $('#dm_sidebar_left > .dm_zones');
		var dmZonesSidebarRight = $('#dm_sidebar_right > .dm_zones');
		var dmZonesPageContent= $('#dm_page_content > .dm_zones');

		//hauteur sidebar left
		var hSL = (dmZonesSidebarLeft.length > 0) ? dmZonesSidebarLeft.height() : null;
		//hauteur sidebar right
		var hSR = (dmZonesSidebarRight.length > 0) ? dmZonesSidebarRight.height() : null;
		//hauteur contenu
		var hC = (dmZonesPageContent.length > 0) ? dmZonesPageContent.height() : null;

		//calcul de la plus grande hauteur
		var maxH = 0;
		if(hC != null && hC > maxH) maxH = hC;
		if(hSL != null && hSL > maxH) maxH = hSL;
		if(hSR != null && hSR > maxH) maxH = hSR;

		//affichage infos de débug
		$.fn.frontTheme.debug("frontTheme | maxH : " + maxH + " hSL : " + hSL + " hSR : " + hSR + " hC : " + hC);

		//application des hauteurs sur les éléments
		if(dmZonesSidebarLeft.length > 0) dmZonesSidebarLeft.height(maxH);
		if(dmZonesSidebarRight.length > 0) dmZonesSidebarRight.height(maxH);
		if(dmZonesPageContent.length > 0) dmZonesPageContent.height(maxH);
	}

	//fonction de debuggage
	$.fn.frontTheme.debug = function(txt){
		if (window.console && window.console.log)
			window.console.log(txt);
	}

	//lancement automatique de la fonction
	$(document).ready(function(){
		$('html').frontTheme();
	});

})(jQuery);