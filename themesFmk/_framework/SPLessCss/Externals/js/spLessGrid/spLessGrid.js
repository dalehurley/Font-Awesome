// spLessGrid.js - Pour le framework SPLessCss
// v1.2.1
// Last Updated : 2011-12-16 16:20
// Copyright : SID Presse | Arnau March http://arnaumarch.com/en/lessgrid.html, freely distributable under the terms of the MIT license.
// Author : Arnaud GAUDIN | Arnau March

//permet d'isoler le code du reste de l'environnement javascript
(function($) {
	
	//Définition du plugin
	$.fn.spLessGrid = function() {
		//Ajout de debug
		$.fn.spLessGrid.debug("spLessGrid | jQueryMobile : " + $.mobile);
		
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
		});
	};
	
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
	
	//création du switch
	$.fn.spLessGrid.createSwitch = function(debug, options) {
		//calcul positionnement à droite du bouton
		var switchPositionRight = 100;
		if ($("#sfWebDebugBar").length > 0){
			switchPositionRight += $('#sfWebDebugBar').outerWidth();
		}
		
		$('body').append('<span id="less-grid-switch">show grid</span>');

		$('#less-grid-switch').css('right', switchPositionRight);
		
		
		$('#less-grid-switch').toggle(function() {
			$(this).text("x");
			$('#less-grid').show();
			$('#less-baseline').show();
			$(this).attr('rel','on');
			
			//mise à jour de la grille
			$.fn.spLessGrid.updateGrid(options);
			
			//réapparition de la zone de debug
			var param = new Object;
			param.target = debug;
			$.fn.spLessGrid.toggleDisplay(param, true);
			
		}, function() {
			$(this).text('show grid');
			$('#less-grid').hide();
			$('#less-baseline').hide();
			$(this).attr('rel','off');
		});
	}
	
	//fonction d'ajout de valeur à la sortie de debug
	$.fn.spLessGrid.debugAddValue = function(info, value) {
		htmlOutput = '<span class="info ' + info + '">' + info + ' : <span class="value">' + value + '</span></span><br />';
		//ajout de la valeur à la sortie de debug
		$.fn.spLessGrid.debugTemplate.find('.debugInfo').append(htmlOutput);
	}

	//fonction d'update de valeur à la sortie de debug
	$.fn.spLessGrid.debugUpdateValue = function(info, value) {
		//changement de la valeur dans la sortie de debug
		htmtOutput = $.fn.spLessGrid.debugTemplate.find('.debugInfo .info.' + info + ' .value').text(value);
	}
	
	//fonction de debuggage
	$.fn.spLessGrid.debug = function(txt){
		if (window.console && window.console.log)
			window.console.log(txt);
	}
	
	//Paramètres par défaut
	$.fn.spLessGrid.debugTemplate = $('.debugTemplate');
	
	$.fn.spLessGrid.initialize = function() {
		$.fn.spLessGrid.debugTemplate.spLessGrid();
		
		//désactivation de l'Ajax pour les transitions de page
		if($.mobile) $.mobile.ajaxEnabled = false;
	}
	
	//lancement automatique de la fonction lors du chargement de la page (événement différent selon la présence de jQueryMobile)
	$(document).bind(($.mobile ? "pageinit" : "ready"), $.fn.spLessGrid.initialize);
	
})(jQuery);