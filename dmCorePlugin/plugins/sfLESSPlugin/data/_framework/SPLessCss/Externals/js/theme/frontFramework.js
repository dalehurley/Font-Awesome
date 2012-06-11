// frontFramework.js
// v1.2
// Last Updated : 2012-06-11 15:05
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){

	//Définition du plugin
	$.fn.frontFramework = function() {

		// iterate and reformat each matched element
		return this.each(function() {
			//Affichage debug initialisation
			$.fn.frontFramework.debug("frontFramework SPLessCss | initialisation");
		});
	}

	//Gestion de la taille minimale du site
	$.fn.frontFramework.globalHeight = function (options) {

		//Récupération hauteur du la vue du navigateur et du contenu
		var hW = $(window).height();
		var hB = $('body').height();

		//sélection des éléments de page
		var dmHeader = $('#dm_header');
		var dmMain = $('#dm_main');
		var dmFooter = $('#dm_footer');

		//Calcul des différentes hauteurs composant le site
		var hH = (dmHeader.length > 0) ? dmHeader.height() : 0;
		var hM = (dmMain.length > 0) ? dmMain.height() : 0;
		var hF = (dmFooter.length > 0) ? dmFooter.height() : 0;

		//calcul de la différence entre le contenu et la fenetre
		var offsetWindow = hW - hB;

		//sortie de débug
		$.fn.frontFramework.debug("frontFramework | hW : " + hW + " hB : " + hB + " offsetWindow : " + offsetWindow);

		//on affecte les dimensions que si l'écart est supérieur à 0
		if(offsetWindow > 0) {
			//application de la nouvelle hauteur sur diverses éléments
			dmMain.css('minHeight', hM + offsetWindow);

			//retour de la valeur d'offset
			return offsetWindow;
		}else{
			//sinon on remet par default
			dmMain.css('minHeight', 'inherit');

			//retour d'une valeur nulle
			return 0;
		}
	}

	//Gestion de la taille des colonnes
	$.fn.frontFramework.resizeCols = function (options) {

		//définitions de valeurs par défaut
		if(options.offsetHC == null) options.offsetHC = 0;
		if(options.offsetHSL == null) options.offsetHSL = 0;
		if(options.offsetHSR == null) options.offsetHSR = 0;

		if(options.isPostHC == null) options.isPostHC = false;
		if(options.isPostHSL == null) options.isPostHSL = false;
		if(options.isPostHSR == null) options.isPostHSR = false;

		//sélection des éléments de page
		var dmZonesPageContent = $('#dm_page_content > .dm_zones');
		var dmZonesSidebarLeft = $('#dm_sidebar_left > .dm_zones');
		var dmZonesSidebarRight = $('#dm_sidebar_right > .dm_zones');

		//réinitialisation des hauteurs minimales
		dmZonesPageContent.css('minHeight', 'inherit');
		dmZonesSidebarLeft.css('minHeight', 'inherit');
		dmZonesSidebarRight.css('minHeight', 'inherit');

		//lancement fonction de redimensionnement de la page
		// var offsetWindow = $.fn.frontFramework.globalHeight();
		var offsetWindow = 0;

		//hauteur contenu
		var hC = (dmZonesPageContent.length > 0) ? dmZonesPageContent.height() + offsetWindow : 0;
		//hauteur sidebar left
		var hSL = (dmZonesSidebarLeft.length > 0) ? dmZonesSidebarLeft.height() + offsetWindow : 0;
		//hauteur sidebar right
		var hSR = (dmZonesSidebarRight.length > 0) ? dmZonesSidebarRight.height() + offsetWindow : 0;

		//on rajoute les offsets si définis sur les dimensions
		if(!options.isPostHC) hC += options.offsetHC;
		if(!options.isPostHSL) hSL += options.offsetHSL;
		if(!options.isPostHSR) hSR += options.offsetHSR;

		//calcul de la plus grande hauteur
		var maxH = 0;
		if(hC > maxH) maxH = hC;
		if(hSL > maxH) maxH = hSL;
		if(hSR > maxH) maxH = hSR;

		//affichage infos de débug
		$.fn.frontFramework.debug("frontFramework | maxH : " + maxH + " hC : " + hC + " hSL : " + hSL + " hSR : " + hSR);

		//application des hauteurs sur les éléments (on utilise minHeight pour adapter automatiquement en fonction du changement du contenu)
		if(dmZonesPageContent.length > 0) dmZonesPageContent.css('minHeight', (options.isPostHC ? maxH + options.offsetHC  : maxH));
		if(dmZonesSidebarLeft.length > 0) dmZonesSidebarLeft.css('minHeight', (options.isPostHSL ? maxH + options.offsetHSL  : maxH));
		if(dmZonesSidebarRight.length > 0) dmZonesSidebarRight.css('minHeight', (options.isPostHSR ? maxH + options.offsetHSR  : maxH));
	}

	//fonction de debuggage
	$.fn.frontFramework.debug = function(txt){
		if (window.console && window.console.log)
			window.console.log(txt);
	}

	//permet de décaler le lancement d'une fonction
	// cf : http://stackoverflow.com/questions/2854407/javascript-jquery-window-resize-how-to-fire-after-the-resize-is-completed
	$.fn.frontFramework.delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();


	//Gestion des classes de lignages pour les listes non ordonnées
	$.fn.listClassCutter = function () {

		$.fn.frontFramework.debug("frontFramework listClassCutter");

		// iterate and reformat each matched element
		return this.each(function() {
			//sélection diverses
			var selectRow = $(this);
			var selectCol = $(this).children('li');	

			//calcul du nombre de colonnes par rangée
			var nbreCol = selectCol.length;

			//initialisation largeur moyenne
			var colWidth = 0;

			//on additionne toutes les largeurs des colonnes courantes
			selectCol.each(function() {
				colWidth += $(this).width();
			});

			//on fait la moyenne des largeurs
			colWidth = colWidth / nbreCol;

			//permet d'éviter d'ajuster la taille des éléments lorsque c'est inutile, et de lancer une division par zéro
			if(nbreCol > 0 && colWidth > 0) {
				//calcul largeur de la zone
				var rowWidth = selectRow.width();

				//calcul du nombre de colonnes affichable en largeur
				var displayNbreCol = Math.floor(rowWidth / colWidth);

				//on ajoute une classe CSS spécifique à chaque début et fin de ligne
				//(on cible les enfants avec find car children fait bugger modernizr avec le sélecteur nth-of-type)
				var lastOfRow = $(this).find('> li:nth-of-type('+displayNbreCol+'n)');
				var firstOfRow = $(this).find('> li:nth-of-type('+displayNbreCol+'n+1)');
				lastOfRow.addClass('lastOfRow');
				firstOfRow.addClass('firstOfRow');

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
					var sliceSelect = selectCol.slice(startCol, endCol);

					//on parcourt la tranche ainsi sélectionnée
					sliceSelect.each(function() {
						//récupération de la hauteur
						var currentColHeight = $(this).height();

						//on actualise la plus grande hauteur détectée dans la ligne
						if(currentColHeight > highestCol) highestCol = currentColHeight;
					});

					//application de la hauteur sur la ligne
					sliceSelect.height(highestCol);
				};

				//on parcourt toutes les colonnes courantes
				selectCol.each(function(index) {
					//ligne courante
					currentRow = Math.floor(index / displayNbreCol);
					
					//on ajoute une classe CSS en fonction de la ligne courante
					if(currentRow == 0) $(this).addClass('inFirstRow');
					if(currentRow >= displayNbreRow - 1) $(this).addClass('inLastRow');
				});
			}
		});
	}

	//lancement automatique de la fonction
	$(document).ready(function(){
		$('html').frontFramework();
	});
	
})(jQuery);