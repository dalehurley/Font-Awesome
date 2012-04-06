// spLessGrid.js - Pour le framework SPLessCss
// v1.3
// Last Updated : 2012-03-01 14:30
// Copyright : SID Presse | Arnau March http://arnaumarch.com/en/lessgrid.html, freely distributable under the terms of the MIT license.
// Author : Arnaud GAUDIN | Arnau March

//permet d'isoler le code du reste de l'environnement javascript
(function($) {

	//Définition du plugin
	$.fn.spLessGrid = function() {
		//Ajout de debug
		// $.fn.spLessGrid.debug("spLessGrid | jQueryMobile : " + $.mobile);

		//stockage du this courant dans une variable pour accès dans les sous-boucles
		var getThis = this;
		
		//on récupère les options passées en JSON dans le container
		var options = $.metadata ? $(this).metadata() : new Array();
		
		//déclaration de la variable de délay de mise à jour
		var timeoutID = "";
		
		//ajout des événéments sur la page (indépendamment de la présence de jQueryMobile, l'événement est géré si existant)
		$(window).bind("pageinit resize orientationchange", function(e) {
			//actualisation de la dimension de la page
			$.fn.spLessGrid.debugUpdateValue('windowInnerWidth', window.innerWidth);
			
			//récupération de la valeur d'orientation
			var orientation = false;
			if(e.orientation != undefined)				orientation = e.orientation;
			else if(window.orientation != undefined)	orientation = ((window.orientation === 90 || window.orientation === -90) ? "landscape" : "portrait");
			//actualisation de l'orientation si gérée par le support
			if(orientation) $.fn.spLessGrid.debugUpdateValue('windowOrientation', orientation);
			
			//on actualise affiche la grille sur si on change de taille ou d'orientation
			if(e.type != "pageinit"){
				//suppression du délay si déjà déclaré
				if(timeoutID != null) this.clearTimeout(timeoutID);
				//on décale dans le temps le lancement de l'actualisation (évite un lancement trop fréquent et permet d'actualiser sur les touchScreens)
				timeoutID = this.setTimeout(function(){
					$.fn.spLessGrid.updateGrid(options);
				},250);
			}
		});
		
		//activation du raccourci clavier pour afficher la grille
		$(document).keydown(function(e) {
			//vérification affichage de la grille
			var switchRel = $('#less-grid-switch').attr('rel');
			var gridToggle = (switchRel == 'off' || switchRel == undefined) ? false : true;

			//code permettant de trouver une combinaison
			// $.fn.spLessGrid.debug("key : " + e.which);
			// e.preventDefault();
			
			//combinaisons possibles : e.ctrlKey, e.altKey, e.shiftKey, e.metaKey
			if(e.metaKey && e.which == 71) {
				//message de debug
				// if(gridToggle)	$.fn.spLessGrid.debug("Masquage de la grille");
				// else			$.fn.spLessGrid.debug("Affichage de la grille");

				//on inverse l'affichage de la grille par rapport à l'état switch
				$.fn.spLessGrid.toggleGrid(getThis, options, !gridToggle);

				//gestion du cmd + shift
				if(e.ctrlKey && e.shiftKey) {
					$.fn.spLessGrid.adjustGrid();
					if(gridToggle) {
						$('#less-baseline').removeClass('isBl');
					}else{
						$('#less-baseline').addClass('isBl');
					}
				}

				//désactivation comportement par défaut
				e.preventDefault();
			}
		});
		
		//TEST : on vérifie la présence du jQueryMobile
		//if($.mobile) {
		//	$(this).live("tap taphold swipe swipeleft swiperight", function(e) {
		//		$(this).toggleClass(e.type);
		//	});
		//}
		
		// iterate and reformat each matched element
		return this.each(function() {
			//ciblage du widget
			var getWidget = $(this).closest('.dm_widget');
			
			//gestion de la disparition de la zone de débug
			$(this).bind('click', $.fn.spLessGrid.toggleDisplay);
			
			//on ajoute la grille
			$.fn.spLessGrid.createSwitch(this, options);
			
			//gestion de la génération des sprites
			$(this).find(".spriteInit").bind('click', $.fn.spLessGrid.spriteBind);
		});
	};

	$.fn.spLessGrid.adjustGrid = function() {
		//on vérifie dans quel mode on est
		var isDev = $('body').hasClass('isDev');
		var isLess = $('body').hasClass('isLess');
		//Hauteur bl
		var bH = 18;

		//application paramètres
		$('.imageWrapper, .imageFullWrapper, .dm_widget_nivo_gallery_container, .dm_widget.content_image > .dm_widget_inner, .dm_widget_content_gallery').each(function(index){
			//ciblage des éléments
			var wrapper = this;
			var getImg = $(wrapper).find("img");
			//récupération propriétés
			var imgWidth = $(getImg).width();
			var imgHeight = $(getImg).height();
			//récupération approx.
			var nbreBl = Math.floor(imgHeight / bH);
			//modification en mode Less
			if(isLess) nbreBl += 1;
			//calcul paramètres de placement
			var getHeight = nbreBl * bH;
			var decalMarginTop = (getHeight - imgHeight) / 2;
			//application sur les éléments
			if(getHeight != imgHeight) {
				$(wrapper).height(getHeight).css('overflow', 'hidden');
				$(getImg).css('marginTop', decalMarginTop);
			}
			//application au chargement
			// $(getImg).load(function(){
			// });
		});
	}
	
	//gestion du click de génération des sprites
	$.fn.spLessGrid.spriteBind = function(e) {
		//récupération de l'url de l'actions
		var action = $(this).attr('formaction');
		
		//ajout et ciblage de la bar de progression
		if($(this).siblings(".spriteProgress").length == 0) $(this).parent().append('<div class="spriteProgress disabled"><div class="picker"></div></div>');
		var progressBar = $(this).siblings(".spriteProgress");
		
		//lancement requête AJAX
		$.fn.spLessGrid.spriteGenerate(action, {prct: 0}, progressBar);
		
		//désactivation comportement par défaut
		e.preventDefault();
	 	return false;
	}
	
	//gestion de l'actualisation AJAX des sprites
	$.fn.spLessGrid.spriteGenerate = function(action, dataRecup, progressBar) {
		//on initialise la barre de progression
		if(dataRecup.prct == 0) {
			if(progressBar.hasClass('disabled')) progressBar.removeClass('disabled');
			progressBar.width('0%').find(".picker").text(0);
		}
		
		$.fn.spLessGrid.debug('dataRecup.hashMd5 : ' + dataRecup.hashMd5 + ' dataRecup.prct : ' + dataRecup.prct + ' dataRecup.spriteFormat : ' + dataRecup.spriteFormat);
		
		$.getJSON(action, dataRecup, function(data) {
			//on appel la fonction de façon récursive si on a pas atteint 100
			if(data.prct < 100) $.fn.spLessGrid.spriteGenerate(action, data, progressBar);
			
			$.fn.spLessGrid.debug('data.hashMd5 : ' + data.hashMd5 + ' data.prct : ' + data.prct + ' data.spriteFormat : ' + data.spriteFormat);
			
			//actualisation de la barre de progression
			progressBar.stop().animate({width: data.prct + '%'}, 1000, function() {
				//disparition et suppression à la fin de l'animation
				if(data.prct >= 100) {
					$(progressBar).addClass('disabled');
					setTimeout(function(){ $(progressBar).remove(); },200);
					
					//message de débug
					$.fn.spLessGrid.debug("spLessGrid | spriteGenerate : génération des sprites terminées");
					
					//rechargement de la page
					window.location.reload();
				}
			}).find(".picker").text(data.prct);
		});
	}
	
	//gestion apparition de la zone de debug
	$.fn.spLessGrid.toggleDisplay = function(e, active) {
		var getWidget = $(e.target).closest('.dm_widget');

		if(active == true) {
			if($(getWidget).hasClass('disabled')){
				$(getWidget).removeClass('disabled');
			}
		}else{
			$(getWidget).toggleClass('disabled');
		}
	}
	
	//mise à jour de la grille
	$.fn.spLessGrid.updateGrid = function(options) {
		if($('#less-grid-switch').attr('rel')=="on") {
			$('#less-grid').remove();
			$('#less-baseline').remove();
			$.fn.spLessGrid.createGrid(options);
		}
	}
	
	//création de la grille
	$.fn.spLessGrid.createGrid = function(options) {
		//détection du type du container
		var isBody = (options.gridContainer.indexOf('body') != -1);
		
		//dimension page
		var fullPageWidth = $('body').width();
		var fullPageHeight = $('body').height();
		
		//dimension container
		var pageWidth = (isBody) ? $('body').width() : $('#'+options.gridContainer).width();
		
		//ajout container baseline
		$('body').append('<div id="less-baseline" class="debugBaseline"></div>');
		$('#less-baseline').css({
								position: "absolute",
								top: "0",
								width: fullPageWidth,
								height: fullPageHeight,
								zIndex: 899
		});
		
		//ajout container grille
		$('body').append('<div id="less-grid"></div>');
		
		//placement
		$('#less-grid').css({ 
								position: "absolute",
								top: "0",
								width: pageWidth,
								height: fullPageHeight,
								zIndex: 900
		});
		
		//on rajoute automatiquement le décalage si le container n'est pas le body
		var pageLeft = ($('body').width() - pageWidth) / 2;	  //If you don't set body width, uncomment this
		if(!isBody){
			$('#less-grid').css("left", pageLeft);
		}
		
		//pour être sûr d'avoir des valeurs numériques
		var colWidth = parseInt(options.gridColWidth);
		var colSep = parseInt(options.gridGutter);
		
		var colCount = 1;
		for(colLeft = 0 ; colLeft <= pageWidth ; colLeft = (colWidth + colSep) * (colCount-1)) {
			$('#less-grid').append('<span class="col col-'+colCount+'"><span class="info">&nbsp;col:&nbsp;'
			+colCount+'<br/>&nbsp;w:&nbsp;'+(((colWidth+colSep)*colCount)-colSep)+'</span></span>');
			$('#less-grid .col-'+colCount).css('left', colLeft);
			colCount++;
		};
	}

	//toggle général de la grille
	$.fn.spLessGrid.toggleGrid = function(debug, options, toggle) {
		if(toggle == true) {
			$('#less-grid-switch').text("x").attr('rel','on');
			$('#less-grid').show();
			$('#less-baseline').show();
			
			//mise à jour de la grille
			$.fn.spLessGrid.updateGrid(options);
			
			//réapparition de la zone de debug
			var param = new Object();
			param.target = debug;
			$.fn.spLessGrid.toggleDisplay(param, true);
		}else{
			$('#less-grid-switch').text('afficher la grille').attr('rel','off');
			$('#less-grid').hide();
			$('#less-baseline').hide();
		}
	}
	
	//création du switch
	$.fn.spLessGrid.createSwitch = function(debug, options) {
		//calcul positionnement à droite du bouton
		var switchPositionRight = 100;
		if ($("#sfWebDebugBar").length > 0){
			switchPositionRight += $('#sfWebDebugBar').outerWidth();
		}
		
		$('body').append('<span id="less-grid-switch">afficher la grille</span>');

		$('#less-grid-switch').css('right', switchPositionRight);
		
		$('#less-grid-switch').toggle(function() {
			$.fn.spLessGrid.toggleGrid(debug, options, true);
		}, function() {
			$.fn.spLessGrid.toggleGrid(debug, options, false);
		});
	}
		
	//fonction d'ajout de valeur à la sortie de debug
	$.fn.spLessGrid.debugAddValue = function(info, value) {
		htmlOutput = '<span class="info ' + info + '">' + info + ' : <span class="value">' + value + '</span></span><br />';
		//ajout de la valeur à la sortie de debug
		$.fn.spLessGrid.debugTemplate.each(function(){
			$(this).find('.debugInfo').append(htmlOutput);
		});
	}

	//fonction d'update de valeur à la sortie de debug
	$.fn.spLessGrid.debugUpdateValue = function(info, value) {
		//changement de la valeur dans la sortie de debug
		$.fn.spLessGrid.debugTemplate.each(function(){
			$(this).find('.debugInfo .info.' + info + ' .value').text(value);
		});
	}
	
	//fonction de debuggage
	$.fn.spLessGrid.debug = function(txt){
		if (window.console && window.console.log)
			window.console.log(txt);
	}
	
	//Paramètres par défaut
	$.fn.spLessGrid.debugTemplate = $('.debugTemplate');
	
	$.fn.spLessGrid.initialize = function() {
		//lancement de la fonction que si le block de débug est bien présent
		$.fn.spLessGrid.debugTemplate.each(function() {
			$(this).spLessGrid();
		});
		
		//désactivation de l'Ajax pour les transitions de page
		if($.mobile) $.mobile.ajaxEnabled = false;
	}
	
	//lancement automatique de la fonction lors du chargement de la page (événement différent selon la présence de jQueryMobile)
	$(document).bind(($.mobile ? "pageinit" : "ready"), $.fn.spLessGrid.initialize);
})(jQuery);