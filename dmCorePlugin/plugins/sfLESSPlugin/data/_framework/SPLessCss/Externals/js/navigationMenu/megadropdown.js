// megadropdown.js
// v1.6.6
// Last Updated : 2012-04-04 16:15
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){
	
	//Définition du plugin
	$.fn.menuMegaDropdown = function() {
		//test ajout de debug
		$.fn.menuMegaDropdown.debug("menuMegaDropdown | initialisation : " + $(this).attr("class"));
		
		//largeur de rangée
		var largeurLigneFull = $('#dm_main_inner').outerWidth();

		// iterate and reformat each matched element
		return this.each(function() {

			//récupération du plus fort z-index de zone
			var highestZindex = 0;
			$('.dm_area:not(#dm_header, #dm_footer)').each(function() {
				//récupération z-index courant
				var currentZindex = $(this).css('zIndex');
				//on exclue les z-index non définis
				if(currentZindex == 'auto') currentZindex = 0;
				//on convertie la valeur en integer
				currentZindex = parseInt(currentZindex);
				//on récupère le plus grand z-index
				if(currentZindex > highestZindex) highestZindex = currentZindex;
			});

			//on vérifie dans quel zone se situe le menu
			var dmArea = $(this).closest('.dm_area');
			var dmAreaIndex = dmArea.css('zIndex');
			if(dmAreaIndex == 'auto') dmAreaIndex = 0;
			dmAreaIndex = parseInt(dmAreaIndex);

			//détection du rollOver et du rollOut sur l'ensemble du menu
			if(dmAreaIndex < highestZindex) {
				$(this).bind('mouseover', function(){
					//on fait passer devant le menu lors du rollOver de ce dernier
					 $(dmArea).css('zIndex', highestZindex + 1);
				}).bind('mouseout', function(){
					//on remet l'index par défaut
					$(dmArea).css('zIndex', dmAreaIndex);
				});
			}

			//ciblage du widgetInner
			var getWidgetInner = $(this).closest('.dm_widget_inner');

			//détection du rollOver et du rollOut uniquement sur les liens de dossier de premier niveau
			$(this).children('li.dm_dir').bind('mouseover', function(){
				//ajout d'une classe d'hover
				if(!getWidgetInner.hasClass('megadropdownHover')) getWidgetInner.addClass('megadropdownHover');
			}).bind('mouseout', function(){
				//suppression de la classe seulement si présente
				if(getWidgetInner.hasClass('megadropdownHover')) getWidgetInner.removeClass('megadropdownHover');
			});

			//on ne s'occupe que des liens ayant des enfants (dm_dir)
			$(this).children('li').each(function(index) {
				//sélection diverses
				var selectRow = $(this).children('ul');
				var selectCol = $(this).children('ul').children('li');

				//calcul du nombre de colonnes par rangée
				var nbreCol = selectCol.length;
				
				//on parcourt chacun des li enfants et on cherche le plus grand en hauteur
				// var highestCol = 0;
				var colWidth = 0;
				var hasDmDir = false;

				//on parcourt toutes les colonnes courantes
				$(selectCol).each(function() {
					//on détecte la présence d'un sous-dossier
					if(!hasDmDir && $(this).hasClass('dm_dir')) hasDmDir = true;
				});

				//on rajoute une classe spécifique sur le ul de niveau 1 pour indiquer si oui ou non il contient des dossiers
				if(hasDmDir) $(selectRow).addClass('hasDmDir');
				else		 $(selectRow).addClass('hasNoDir');

				//on parcourt toutes les colonnes courantes
				$(selectCol).each(function() {
					//on récupère la plus grande largeur
					// var currentColWidth = $(this).width();
					// if(currentColWidth > colWidth) colWidth = currentColWidth;
					//on additionne toutes les largeurs
					colWidth += $(this).width();
				});
				//on fait la moyenne des largeurs
				colWidth = colWidth / selectCol.length;

				//permet d'éviter d'ajuster la taille des éléments lorsque c'est inutile, et de lancer une division par zéro
				if(hasDmDir && nbreCol > 0 && colWidth > 0) {
					
					//calcul largeur de la zone
					var rowWidth = $(selectRow).width();
					
					//calcul du nombre de colonnes affichable en largeur
					var displayNbreCol = Math.floor(rowWidth / colWidth);
					// $.fn.menuMegaDropdown.debug(index + ' rowWidth : ' + rowWidth + ' colWidth : ' + colWidth + ' displayNbreCol : ' + displayNbreCol);

					//on ajoute une classe CSS spécifique à chaque début et fin de ligne
					//(on cible les enfants avec find car children fait bugger modernizr avec le sélecteur nth-of-type)
					var lastOfRow = $(this).children('ul').find('> li:nth-of-type('+displayNbreCol+'n)');
					var firstOfRow = $(this).children('ul').find('> li:nth-of-type('+displayNbreCol+'n+1)');
					$(lastOfRow).addClass('lastOfRow');
					$(firstOfRow).addClass('firstOfRow');
					
					//calcul du nombre de lignes affichables
					//si le modulo (reste de la division) n'est pas égale à zéro alors on arrondi à l'entier inférieur et on rajoute un
					var displayNbreRow = (nbreCol % displayNbreCol == 0) ? nbreCol / displayNbreCol : Math.floor(nbreCol / displayNbreCol) + 1;

					//ligne courante
					var currentRow = 0;

					//boucle sur chacune des lignes
					for (var i = 0; i < displayNbreRow; i++) {

						//on parcourt chacun des li enfants et on cherche le plus grand en hauteur
						var highestCol = 0;

						//sélection dans les colonnes
						var startCol = displayNbreCol * i;
						var endCol = startCol + displayNbreCol;

						//sélection de la tranche
						var sliceSelect = $(selectCol).slice(startCol, endCol);

						//on parcourt la tranche ainsi sélectionnée
						$(sliceSelect).each(function() {
							//récupération de la hauteur
							var currentColHeight = $(this).height();
							// $(this).addClass('testH-'+currentColHeight);

							//on actualise la plus grande hauteur détectée dans la ligne
							if(currentColHeight > highestCol) highestCol = currentColHeight;
						});

						//application de la hauteur sur la ligne
						$(sliceSelect).height(highestCol);
					};

					//on parcourt toutes les colonnes courantes
					$(selectCol).each(function(index) {
						//ligne courante
						currentRow = Math.floor(index / displayNbreCol);
						
						//on ajoute une classe CSS en fonction de la ligne courante
						if(currentRow == 0) $(this).addClass('inFirstRow');
						if(currentRow >= displayNbreRow - 1) $(this).addClass('inLastRow');
					});
				}
			});
		});
	}

	//fonction de debuggage
	$.fn.menuMegaDropdown.debug = function(txt){
		if (window.console && window.console.log)
			window.console.log(txt);
	}

	//lancement automatique de la fonction
	$(document).ready(function(){
		$('ul.menu-megadropdown').menuMegaDropdown();
	});
})(jQuery);