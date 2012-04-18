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

			$.fn.frontTheme.resizeCols(0,0,0);
		});
	}

	//Gestion de la taille des colonnes
	$.fn.frontTheme.resizeCols = function (offsetHC, offsetHSL, offsetHSR) {

		//sélection des éléments de page
		var dmZonesPageContent= $('#dm_page_content > .dm_zones');
		var dmZonesSidebarLeft = $('#dm_sidebar_left > .dm_zones');
		var dmZonesSidebarRight = $('#dm_sidebar_right > .dm_zones');

		//hauteur contenu
		var hC = (dmZonesPageContent.length > 0) ? dmZonesPageContent.height() : null;
		//hauteur sidebar left
		var hSL = (dmZonesSidebarLeft.length > 0) ? dmZonesSidebarLeft.height() : null;
		//hauteur sidebar right
		var hSR = (dmZonesSidebarRight.length > 0) ? dmZonesSidebarRight.height() : null;

		//on rajoute les offsets si définis sur les dimensions
		if(offsetHC != null && hC != null) hC += offsetHC;
		if(offsetHSL != null && hSL != null) hSL += offsetHSL;
		if(offsetHSR != null && hSR != null) hSR += offsetHSR;

		//calcul de la plus grande hauteur
		var maxH = 0;
		if(hC != null && hC > maxH) maxH = hC;
		if(hSL != null && hSL > maxH) maxH = hSL;
		if(hSR != null && hSR > maxH) maxH = hSR;

		//affichage infos de débug
		$.fn.frontTheme.debug("frontTheme | maxH : " + maxH + " hC : " + hC + " hSL : " + hSL + " hSR : " + hSR);

		//application des hauteurs sur les éléments (on utilise minHeight pour adapter automatiquement en fonction du changement du contenu)
		if(dmZonesPageContent.length > 0) dmZonesPageContent.css('minHeight', maxH);
		if(dmZonesSidebarLeft.length > 0) dmZonesSidebarLeft.css('minHeight', maxH);
		if(dmZonesSidebarRight.length > 0) dmZonesSidebarRight.css('minHeight', maxH);
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