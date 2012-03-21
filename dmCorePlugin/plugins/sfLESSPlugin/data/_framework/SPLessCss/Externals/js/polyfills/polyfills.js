// polyfills.js
// v0.5
// Last Updated : 2012-03-01 15:50
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){

	//options de la page en JSON : pageOptions

	//permet le chargement des versions compressées
	var getMin = pageOptions.isDev ? '' : '.min';

	//chargement des polyfills lorsque nécessaires
	Modernizr.load([
		{
	    	test : Modernizr.placeholder,
	    	nope : {
	    		'jqueryPlaceholder' : '/theme/less/_framework/SPLessCss/Externals/js/polyfills/jquery-placeholder/jquery.placeholder'+getMin+'.js'
	    	},
	    	callback : {
	    		'jqueryPlaceholder' : function (url, result, key) {
	    			if(result) $('input, textarea').placeholder();
				}
			}
 		}
	]);
	
	//lancement lorsque le document est chargé
	$(document).ready(function(){

		//on teste les variables


		
		//on vérifie dans quel mode on est
		var isDev = $('body').hasClass('isDev');
		var isLess = $('body').hasClass('isLess');

		//variable permettant de détecter la valeur de touch screen de l'écran
		var isTouchDevice = 'ontouchstart' in document.documentElement;

		//test diverses d'utilisation du touch
		// if(isTouchDevice){
		// 	$("*").live("touchstart", function() {
		// 		$(this).addClass("touch");
		// 		}).live("touchend", function() {
		// 			$(this).removeClass("touch");
		// 		});
		// 	$(".debugTemplate").live("tap", function() {
		// 		$(this).addClass("touch");
		// 	});
		// 	$(".debugTemplate").live("tap taphold swipe swipeleft swiperight", function(e) {
		// 		$(this).toggleClass(e.type);
		// 	});
		// }

		// if(Modernizr.touch) {
		// 	$("*").live("touchstart", function() {
		// 		$(this).addClass("touch");
		// 		}).live("touchend", function() {
		// 			$(this).removeClass("touch");
		// 		});
		// 	$('*').bind('touchstart', function() {
		// 		$(this).addClass("touch");
		// 	});
		// 	$('*').bind('touchend', function() {
		// 		$(this).removeClass("touch");
		// 	});
		// }else {
		// }
	});
	
})(jQuery);