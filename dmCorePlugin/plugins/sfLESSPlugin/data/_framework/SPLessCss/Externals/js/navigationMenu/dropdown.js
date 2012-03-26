// dropdown.js
// v1.0
// Last Updated : 2011-12-06 16:30
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){
	
	//Définition du plugin
	$.fn.menuDropdown = function(options) {
		//test ajout de debug
		$.fn.menuDropdown.debug("menuDropdown | initialisation : " + $(this).attr("class"));
		
		
		// build main options before element iteration
		var opts = $.extend({}, $.fn.menuDropdown.defaults, options);
		// iterate and reformat each matched element
		return this.each(function() {
			$this = $(this);
			//on récupère les options passées en JSON dans le container et on les mixe avec celles déjà présentes
			var o = $.metadata ? $.extend({}, opts, $this.closest('.dm_widget_navigation_menu_container').metadata()) : opts;
			
			//lancement de la fonction de compatibilité
			$.fn.menuDropdown.compatibility(this);
		});
	}
	
	//fonction de debuggage
	$.fn.menuDropdown.debug = function(txt){
		if (window.console && window.console.log)
			window.console.log(txt);
	}
	
	//Paramètres par défaut
	$.fn.menuDropdown.defaults = {
		duration: 250
	};
	
	$.fn.menuDropdown.compatibility = function(menu) {
		
		//TODO : fonction de rajout d'un bouton de fermeture de la zone pour les touchScreens
		
		//variable permettant de détecter la valeur de touch screen de l'écran
		//var isTouchDevice = 'ontouchstart' in document.documentElement;
		
		/*
		//compatibilité ie 6-7
		$(this).hover(function(){
			$(this).addClass("hover");
			//$('> .dir',this).addClass("open");
			//$('ul:first',this).css('visibility', 'visible');
		},function(){
			$(this).removeClass("hover");
			//$('.open',this).removeClass("open");
			//$('ul:first',this).css('visibility', 'hidden');
		});
		
		if(Modernizr.touch) {
			$(this).live("touchstart", function(e) {
				$(this).addClass("touch");
				e.preventDefault();
				return false;
			}).live("touchend", function(e) {
				$(this).removeClass("touch");
				e.preventDefault();
				return false;
			});
		}
		*/
	}
	
	//lancement lorsque le document est chargé
	$(document).ready(function(){
		$('ul.menu-dropdown').menuDropdown();
	});
	
})(jQuery);