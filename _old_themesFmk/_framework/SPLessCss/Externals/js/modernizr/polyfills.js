// polyfills.js
// v0.1
// Last Updated : 2011-10-10 16:20
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){
	
	//lancement lorsque le document est chargé
	$(document).ready(function(){
		
		//variable permettant de détecter la valeur de touch screen de l'écran
		var isTouchDevice = 'ontouchstart' in document.documentElement;
		
		if(isTouchDevice){
			/*
			$("*").live("touchstart", function() {
				$(this).addClass("touch");
				}).live("touchend", function() {
					$(this).removeClass("touch");
				});*/
			/*	
			$(".debugTemplate").live("tap", function() {
				$(this).addClass("touch");
			});*/
			/*
			$(".debugTemplate").live("tap taphold swipe swipeleft swiperight", function(e) {
				$(this).toggleClass(e.type);
			});
			*/
		}
		
		/*
		if(Modernizr.touch) {
			$("*").live("touchstart", function() {
				$(this).addClass("touch");
				}).live("touchend", function() {
					$(this).removeClass("touch");
				});
			$('*').bind('touchstart', function() {
				$(this).addClass("touch");
			});
			$('*').bind('touchend', function() {
				$(this).removeClass("touch");
			});
		}else {
			
		}
		*/
		
	});
	
})(jQuery);