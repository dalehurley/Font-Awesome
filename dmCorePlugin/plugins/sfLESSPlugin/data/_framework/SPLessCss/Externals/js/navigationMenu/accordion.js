// accordion.js
// v1.0
// Last Updated : 2011-12-06 12:00
// Copyright : SID Presse
// Author : Arnaud GAUDIN

// TODO : gestion événement mobile avec jQueryMobile : http://jquerymobile.com/demos/1.0/docs/api/events.html

//permet d'isoler le code du reste de l'environnement javascript
(function($) {
	
	//Définition du plugin
	$.fn.menuAccordion = function(options) {
		//test ajout de debug
		$.fn.menuAccordion.debug("menuAccordion | initialisation : " + $(this).attr("class"));
		
		// build main options before element iteration
		var opts = $.extend({}, $.fn.menuAccordion.defaults, options);
		// iterate and reformat each matched element
		return this.each(function() {
			$this = $(this);
			//on récupère les options passées en JSON dans le container et on les mixe avec celles déjà présentes
			var o = $.metadata ? $.extend({}, opts, $this.closest('.dm_widget_navigation_menu_container').metadata()) : opts;
			
			$this.find('li.dm_dir').each(function() {
				//on ne lance l'événement click que sur le lien enfant
				$.fn.menuAccordion.addEvent(this, o);
			});
		});
	};
	
	//gestion ouverture de chaque li
	$.fn.menuAccordion.toggle = function(li, options) {
		//si le menu est ouvert on le ferme
		if($(li).hasClass('dm_parent')) {
			$(li).find('> ul').animate(
				{ height: 'hide', opacity: 'hide' },
				{
					duration: options.duration,
					complete: function(){
						$(li).removeClass('dm_parent');
						//activation écouteur
						$.fn.menuAccordion.addEvent(li, options);
					}
				}
			);
		}
		//sinon on l'ouvre
		else {
			//on ajoute la classe et on masque au début de l'animation pour initialiser l'animation
			//(car l'absence de dm_parent masque le ul dès le début et le fait apparaitre brutalement)
			$(li).addClass('dm_parent');
			$(li).find('> ul').hide();
			//désactivation écouteur
			$.fn.menuAccordion.removeEvent(li);
			
			$(li).find('> ul').animate(
				{ height: 'show', opacity: 'show' },
				{ duration: options.duration }
			);
		}
	}
	
	//gestion des li de même niveau et des enfants
	$.fn.menuAccordion.sibChild = function(li, options) {
		//fermeture des autres et de leurs enfants
		if(!$(li).hasClass('dm_parent')) {
			//alert("!! dm_parent : "+$(li).find('> .link').text());
			//on sélectionne tous les autres dm_parent ouverts du même niveau et on les ferme
			$(li).parent().find('li.dm_parent').each(function(index) {
				//ouverture/fermeture du menu
				$.fn.menuAccordion.toggle(this, options);
			});
		}
		
		//fermeture des enfants si déjà ouvert
		if($(li).hasClass('dm_parent')){
			//alert("dm_parent : "+$(li).find('> .link').text());
			//on sélectionne les dm_parent ouverts enfants et on les ferme
			$(li).find('li.dm_parent').each(function(index) {
				//ouverture/fermeture du menu
				$.fn.menuAccordion.toggle(this, options);
			});
		}
	}
	
	//gestion ajouts des événements sur les li
	$.fn.menuAccordion.bindEvent = function(e) {
		//récupération des options passées dans l'événement
		options = e.data;
		
		//ciblage du lien
		link = e.target;
		
		//on récupère le plus proche dm_dir
		li = $(link).closest('li.dm_dir');
		
		//fermeture des siblings et éventuellements des children
		$.fn.menuAccordion.sibChild(li, options);
		
		//ouverture/fermeture du menu
		$.fn.menuAccordion.toggle(li, options);
		
		//désactivation comportement par défaut
		e.preventDefault();
		return false;
	}
	
	//ajout de l'événement sur le lien
	$.fn.menuAccordion.addEvent = function(li, options) {
		//on ne lance l'événement click que sur le lien enfant (on enlève le handler avant de l'ajouter au cas où une instance multiple se produit)
		$(li).find('> .link').unbind('click', $.fn.menuAccordion.bindEvent).bind('click', options, $.fn.menuAccordion.bindEvent);
	}
	
	//suppression de l'événement sur le lien
	$.fn.menuAccordion.removeEvent = function(li) {
		//on ne lance l'événement click que sur le lien enfant (on enlève le handler avant de l'ajouter au cas où une instance multiple se produit)
		$(li).find('> .link').unbind('click', $.fn.menuAccordion.bindEvent);
	}
	
	//fonction de debuggage
	$.fn.menuAccordion.debug = function(txt){
		if (window.console && window.console.log)
			window.console.log(txt);
	}
	
	//Paramètres par défaut
	$.fn.menuAccordion.defaults = {
		duration: 250,
		easing: 'swing'
	};
	
	//lancement automatique de la fonction
	$(document).ready(function(){
		$('ul.menu-accordion').menuAccordion();
		/*$('ul.menu-accordion').menuAccordion({
									duration: 500
									});*/
	});
	
})(jQuery);