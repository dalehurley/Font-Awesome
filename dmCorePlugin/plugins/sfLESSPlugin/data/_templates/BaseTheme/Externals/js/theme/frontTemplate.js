// frontTemplate.js
// v1.3
// Last Updated : 2012-07-04 15:00
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){

	//compteur d'événements de page
	// var eventCount = 0;

	//Définition du plugin
	$.fn.frontTemplate = function() {

		// iterate and reformat each matched element
		return this.each(function() {
			//Affichage debug initialisation
			$.fn.frontFramework.debug("frontTemplate BaseTheme | initialisation");

			//lancement fonction de redimensionnement de la page
			var offsetWindow = $.fn.frontFramework.globalHeight();

			//on applique le décalage que si la zone existe et que l'offset est significatif
			if($('#dm_custom_bottom').length > 0 && offsetWindow >= 0) {
				$('#dm_page_content, #dm_sidebar_left, #dm_sidebar_right').css('marginBottom', offsetWindow);
			}
			
			//Replacement des adresses multiples
			$('.dm_widget.adresses_multiples').each(function(){
				//gestion des classes sur le listing
				$(this).children('.dm_widget_inner').children('ul.elements').listClassCutter();
			});
		});
	}

	//lancement automatique de la fonction
	$(document).ready(function(){
		$('html').frontTemplate();
		// $.fn.spLessGrid.debugAddValue("eventType", "#" + eventCount + " | ready");
	});

	//ajout des événéments sur la page (indépendamment de la présence de jQueryMobile, l'événement est géré si existant)
	$(window).bind("resize orientationchange", function(e) {
		//affichage infos de débug directement dans la page
		// eventCount++;
		// $.fn.spLessGrid.debugUpdateValue("eventType", "#" + eventCount + " | " + e.type);

		//lancement de la fonction de replacement avec un délai lors du redimenssionnement
		$.fn.frontFramework.delay(function(){
			$('html').frontTemplate();
		}, 500);
	});

	//lorsque la fenêtre est redimmensionnée
	// $(window).resize(function() {
	// 	//lancement de la fonction de replacement avec un délai lors du redimenssionnement
	// 	$.fn.frontFramework.delay(function(){
	// 		$('html').frontTemplate();
	// 	}, 500);
	// });

})(jQuery);