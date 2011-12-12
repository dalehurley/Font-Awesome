// spLessGrid.js- Pour SPLessCss
// v1.1
// Last Updated : 2011-12-12 14:50
// Copyright : SID Presse | Arnau March http://arnaumarch.com/en/lessgrid.html, freely distributable under the terms of the MIT license.
// Author : Arnaud GAUDIN | Arnau March

//permet d'isoler le code du reste de l'environnement javascript
(function($) {
	
	//Définition du plugin
	$.fn.spLessGrid = function(options) {
		//test ajout de debug
		$.fn.spLessGrid.debug("Lancement du système de grille");
		
	};
	
	//fonction de debuggage
	$.fn.spLessGrid.debug = function(txt){
		if (window.console && window.console.log)
			window.console.log(txt);
	}
	
	//lancement automatique de la fonction lors du chargement de la page
	$(document).ready(function(){
		$('.debugTemplate').spLessGrid();
		
		
	});
	
})(jQuery);