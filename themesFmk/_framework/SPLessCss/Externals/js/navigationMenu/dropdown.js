// dropdown.js
// v0.1
// Last Updated : 2011-10-10 17:05
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){
	
	dropdownCompatibility = function() {
		
		//variable permettant de détecter la valeur de touch screen de l'écran
		//var isTouchDevice = 'ontouchstart' in document.documentElement;
		
		//compatibilité ie 6-7
		$("ul.dropdown li").each(function() {
			$(this).hover(function(){
				$(this).addClass("hover");
				/*
				$('> .dir',this).addClass("open");
				$('ul:first',this).css('visibility', 'visible');
				*/
			},function(){
				$(this).removeClass("hover");
				/*
				$('.open',this).removeClass("open");
				$('ul:first',this).css('visibility', 'hidden');
				*/
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
		});
	}
	
	//lancement lorsque le document est chargé
	$(document).ready(function(){
		//dropdownCompatibility();
	});
	
})(jQuery);