// polyfills.js
// v0.5
// Last Updated : 2012-03-01 15:50
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){
	
	//lancement lorsque le document est chargé
	$(document).ready(function(){
		
		//on vérifie dans quel mode on est
		var isDev = $('body').hasClass('isDev');
		var isLess = $('body').hasClass('isLess');

		//variable permettant de détecter la valeur de touch screen de l'écran
		var isTouchDevice = 'ontouchstart' in document.documentElement;

		//Définition hauteur bl
		var bH = 18;
		$('.imageWrapper, .imageFullWrapper').each(function(index){
			//ciblage du wrapper
			var wrapper = this;
			//récupération de l'image
			var image = $(wrapper).find("img");

			//application au chargement de l'image
			$(image).load(function(){
				//dimenssions de l'image
				var imgWidth = $(image).width();
				var imgHeight = $(image).height();
				
				//on récupère le nbre le plus proche
				var nbreBl = Math.floor(imgHeight / bH);

				//on rajoute +1 en mode Less pour bien voir le décalage
				if(isLess) nbreBl += 1;

				//calcul hauteur rectifiée
				var getHeight = nbreBl * bH;

				//calcul marge de décalage de l'image
				var decalMarginTop = (getHeight - imgHeight) / 2;

				//application hauteur sur imageWrapper et marginTop sur l'image uniquement si différence de hauteur
				if(getHeight != imgHeight) {
					$(wrapper).height(getHeight);
					$(image).css('marginTop', decalMarginTop);
					//$(this).animate({height:getHeight}, 1000);
					//$(image).animate({marginTop:decalMarginTop}, 1000);
				}

				//affichage dans console de débug
				window.console.log("#" + index + " alt : " + $(image).attr('alt')  + " | " + imgWidth + " x " + imgHeight + " | nbreBl : " + nbreBl + " | getHeight : " + getHeight + " | decalMarginTop : " + decalMarginTop);
			});
		});

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