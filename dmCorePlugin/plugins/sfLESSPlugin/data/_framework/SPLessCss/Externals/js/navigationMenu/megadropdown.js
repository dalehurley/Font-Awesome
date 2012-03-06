// megadropdown.js
// v1.0
// Last Updated : 2012-03-06 17:30
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){
	
	megadropdownResize = function() {
		//GESTION DU MENU DE FOOTER
		
		//largeur de rangée
		var largeurLigneFull = $('#dm_main_inner').outerWidth();
		
		
		//on ne s'occupe que des liens ayant des enfants (dm_dir)
		$('ul.megadropdown > li').each(function(index) {
			//sélection diverses
			var selectRow = $(this).children('ul');
			var selectCol = $(this).children('ul').children('li');

			window.console.log(index + ' selectCol : ' + selectCol.length);
			
			//calcul du nombre de colonnes par rangée
			var nbreCol = selectCol.length;
			
			//on parcourt chacun des li enfants et on cherche le plus grand en hauteur
			// var highestCol = 0;
			var colWidth = 0;
			var hasDmDir = false;

			//on parcourt toutes les colonnes courantes
			$(selectCol).each(function(index) {
				//on détecte la présence d'un sous-dossier
				if(!hasDmDir && $(this).hasClass('dm_dir')) hasDmDir = true;

				//on récupère la plus grande largeur
				var currentColWidth = $(this).width();
				if(currentColWidth > colWidth) colWidth = currentColWidth;
			});

			//on rajoute une classe spécifique sur le ul de niveau 1 pour indiquer si oui ou non il contient des dossiers
			if(hasDmDir) $(selectRow).addClass('hasDmDir');

			//permet d'éviter d'ajuster la taille des éléments lorsque c'est inutile, et de lancer une division par zéro
			if(hasDmDir && nbreCol > 0) {
				
				//calcul largeur de la zone
				var rowWidth = $(selectRow).width();
				
				//calcul du nombre de colonnes affichable en largeur
				var displayNbreCol = Math.floor(rowWidth / colWidth);

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
					$(sliceSelect).each(function(index) {
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
	}
	
	//lancement lorsque le document est chargé
	$(document).ready(function(){
		megadropdownResize();
	});
})(jQuery);