// megadropdown.js
// v0.6
// Last Updated : 2012-02-23 15:00
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){
	
	megadropdownResize = function() {
		//GESTION DU MENU DE FOOTER
		
		//largeur de rangée
		var largeurLigneFull = $('#dm_main_inner').outerWidth();
		
		
		//on ne s'occupe que des liens ayant des enfants (dm_dir)
		$('ul.menu-megadropdown > li.dm_dir').each(function() {
			//sélection diverses
			var selectRow = $(this).find('ul');
			var selectCol = $(this).find('ul > li.dm_dir');
			
			//calcul du nombre de colonnes par rangée
			var nbreCol = $(selectCol).length;
			
			//on parcourt chacun des li enfants et on cherche le plus grand en hauteur
			var highestCol = 0;
			var colWidth = 0;
			$(selectCol).each(function() {
				var currentColHeight = $(this).height();
				if(currentColHeight > highestCol) {
					highestCol = currentColHeight;
				}
				//on récupère la plus grande largeur
				var currentColWidth = $(this).width();
				if(currentColWidth > colWidth) {
					colWidth = currentColWidth;
				}
			});
			
			
			//permet d'éviter de lancer une division par zéro
			if(nbreCol > 0) {
				//application hauteur de colonne
				$(selectCol).height(highestCol);
				
				//calcul largeur de la zone
				var rowWidth = $(selectRow).width();
				
				//calcul du nombre de colonnes affichable en largeur
				var displayNbreCol = Math.floor(rowWidth / colWidth);
				
				//on ajoute une classe CSS spécifique à chaque début et fin de ligne
				$(this).find('ul > li.dm_dir:nth-child('+displayNbreCol+')').addClass('lastOfRow');
				$(this).find('ul > li.dm_dir:nth-child('+displayNbreCol+') + li.dm_dir').addClass('firstOfRow');
				
				//calcul du nombre de lignes affichables
				//si le modulo (reste de la division) n'est pas égale à zéro alors on arrondi à l'entier inférieur et on rajoute un
				var displayNbreRow = (nbreCol % displayNbreCol == 0) ? nbreCol / displayNbreCol : Math.floor(nbreCol / displayNbreCol) + 1;
				
				$(this).find('ul > li.dm_dir').each(function(intIndex) {
					//ligne courante
					var currentRow = Math.floor(intIndex / displayNbreCol);
					
					//affichage en console pour debug
					//window.console.log("verif-" + intIndex + " currentRow : "+ currentRow + " displayNbreCol : " + displayNbreCol + " displayNbreRow : " + displayNbreRow);
					
					//on ajoute une classe CSS en fonction de la ligne courante
					if(currentRow == 0) {
						$(this).addClass('inFirstRow');
					}
					if(currentRow >= displayNbreRow - 1) {
						$(this).addClass('inLastRow');
					}
				});
			}
		});
	}
	
	//lancement lorsque le document est chargé
	$(document).ready(function(){
		megadropdownResize();
	});
})(jQuery);