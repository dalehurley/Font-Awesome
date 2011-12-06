// accordion.js
// v0.1
// Last Updated : 2011-10-07 09:30
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){
	
	//paramètrage de la future classe en attendant code propre
	var accordionDuration = 250;
	//voir pour intégration jQuery UI dans front
	var accordionEasing = 'swing';
	
	accordionToggle = function(li) {
		//si le menu est ouvert on le ferme
		if($(li).hasClass('dm_parent')) {
			$(li).find('> ul').animate(
				{ height: 'hide', opacity: 'hide' },
				{
					duration: accordionDuration,
					complete: function(){
						$(li).removeClass('dm_parent');
						//activation écouteur
						accordionAddEvent(li);
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
			accordionRemoveEvent(li);
			
			$(li).find('> ul').animate(
				{ height: 'show', opacity: 'show' },
				{ duration: accordionDuration }
			);
		}
	}
	
	accordionSibChild = function(li) {
		//fermeture des autres et de leurs enfants
		if(!$(li).hasClass('dm_parent')) {
			//alert("!! dm_parent : "+$(li).find('> .link').text());
			//on sélectionne tous les autres dm_parent ouverts du même niveau et on les ferme
			$(li).parent().find('li.dm_parent').each(function(index) {
				//ouverture/fermeture du menu
				accordionToggle(this);
				//alert("sib"+index+" : "+$(this).find('> .link').text());
			});
		}
		
		//fermeture des enfants si déjà ouvert
		if($(li).hasClass('dm_parent')){
			//alert("dm_parent : "+$(li).find('> .link').text());
			//on sélectionne les dm_parent ouverts enfants et on les ferme
			$(li).find('li.dm_parent').each(function(index) {
				//ouverture/fermeture du menu
				accordionToggle(this);
				//alert("child"+index+" : "+$(this).find('> .link').text());
			});
		}
	}
	
	accordionBind = function(e) {
		link = e.target;
		
		//on récupère le plus proche dm_dir
		li = $(link).closest('li.dm_dir');
		
		//fermeture des siblings et éventuellements des children
		accordionSibChild(li);
		
		//ouverture/fermeture du menu
		accordionToggle(li);
		
		//désactivation comportement par défaut
		e.preventDefault();
		return false;
	}
	
	accordionAddEvent = function(li) {
		//on ne lance l'événement click que sur le lien enfant (on enlève le handler avant de l'ajouter au cas où une instance multiple se produit)
		$(li).find('> .link').unbind('click', accordionBind).bind('click', accordionBind);
	}
	
	accordionRemoveEvent = function(li) {
		//on ne lance l'événement click que sur le lien enfant (on enlève le handler avant de l'ajouter au cas où une instance multiple se produit)
		$(li).find('> .link').unbind('click', accordionBind);
	}
	
	accordionSetup = function() {
		$('ul.accordion li.dm_dir').each(function() {
			//on ne lance l'événement click que sur le lien enfant (on enlève le handler avant de l'ajouter au cas où une instance multiple se produit)
			accordionAddEvent(this);
		});
	}
	
	//gestion du menu accordion
	//lancement lorsque le document est chargé
	$(document).ready(function(){
		accordionSetup();
	});
	
})(jQuery);