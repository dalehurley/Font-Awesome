/* 
	less grid v4.0 - For Less Framework 4.
	(c) Arnau March http://arnaumarch.com/en/lessgrid.html, freely distributable under the terms of the MIT license.
*/

(function($){
	//lorsque la page est chargée
	$(document).ready(function() {
		
		createSwitch();
		
		//test lancement auto grille :
		//createGrid();
		
		//ajout de paramètres personnalisés en JS à la sortie de débug
		debugAddValue('windowInnerWidth', window.innerWidth);
		debugAddValue('windowOrientation', window.orientation);
		
		$('.debugTemplate').click(function(e){
			$(this).closest('.dm_widget').toggleClass('disabled');
		});
	});
	
	//lorsque la page est redimenssionée
	$(window).resize(function() {
		//update de la grille
		if($('#less-grid-switch').attr('rel')=="on") {
			$('#less-grid').remove();
			$('#less-baseline').remove();
			createGrid();
		}
		
		//changements des paramètres personnalisés en JS à la sortie de débug
		debugUpdateValue('windowInnerWidth', window.innerWidth);
		debugUpdateValue('windowOrientation', window.orientation);
	});
	
})(jQuery);

//fonction d'ajout de valeur à la sortie de debug
function debugAddValue(info, value) {
	htmlOutput = '<span class="info ' + info + '">' + info + ' : <span class="value">' + value + '</span></span><br />';
	//ajout de la valeur à la sortie de debug
	$('.debugTemplate .debugInfo').append(htmlOutput);
}

//fonction d'update de valeur à la sortie de debug
function debugUpdateValue(info, value) {
	//changement de la valeur dans la sortie de debug
	htmtOutput = $('.debugTemplate .debugInfo .info.' + info + ' .value').text(value);
}

//création de la grille
function createGrid () {
	//dimension page
	var fullPageWidth = $('body').width();
	var fullPageHeight = $('body').height();
	
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
	
	//fonction d'extraction de valeur de background-image
	function extractCssBgiValue(stringValue){
		//permet de récupérer la valeur numérique située dans une chaine de type : url(http://sitev3-trunk-arnaud.com/80%)
		var indexStart = stringValue.lastIndexOf('/') + 1;
		var indexEnd = stringValue.lastIndexOf(')');

		var extract = stringValue.substring(indexStart, indexEnd).replace('"','').replace("'","");

		return extract;
	}
	
	//récup depuis widget de debug
	//var recupGridContainer = $('.debugTemplate .debugInfo .gridContainer .value').css('background-image');
	//var gridContainer = extractCssBgiValue(recupGridContainer);
	var gridContainer = $('.debugTemplate .debugInfo .gridContainer .value').text();
	var isBody = (gridContainer.indexOf('body') != -1) ? true : false;

	//ajout sélecteur ID
	if(!isBody){
		gridContainer = '#' + gridContainer;
	}
	
	var pageWidth = $(gridContainer).width();
	
	//var pageWidth = $('body').children(':first').width();   //If you don't set body width, uncomment this
	var pageLeft = ($('body').width() - pageWidth) / 2;	  //If you don't set body width, uncomment this
	
	$('#less-grid').css({ 
							position: "absolute",
							top: "0",
							width: pageWidth,
							height: fullPageHeight,
							zIndex: 900
	});
	
	//on rajoute automatiquement le décalage si le container n'est pas le body
	if(!isBody){
		$('#less-grid').css("left", pageLeft);
	}
	
	//récup depuis widget de debug
	//var recupGridColWidth = $('.debugTemplate .debugInfo .gridColWidth .value').css('background-image');
	//var gridColWidth = parseInt(extractCssBgiValue(recupGridColWidth));
	var recupGridColWidth = $('.debugTemplate .debugInfo .gridColWidth .value').text();
	var gridColWidth = parseInt(recupGridColWidth);
	//récup depuis widget de debug
	//var recupGridGutter = $('.debugTemplate .debugInfo .gridGutter .value').css('background-image');
	//var gridGutter = parseInt(extractCssBgiValue(recupGridGutter));
	var recupGridGutter = $('.debugTemplate .debugInfo .gridGutter .value').text();
	var gridGutter = parseInt(recupGridGutter);
	
	var colWidth = gridColWidth;
	var colSep = gridGutter;
	
	/*
	var debugOutput = $("#testjs2 .content");
	htmlOutput = "<br/>";
	htmlOutput += "gridContainer : "+gridContainer;
	htmlOutput += "<br/>";
	htmlOutput += "isBody : "+isBody;
	htmlOutput += "<br/>";
	htmlOutput += "pageWidth : "+pageWidth;
	htmlOutput += "<br/>";
	htmlOutput += "gridColWidth : "+gridColWidth;
	htmlOutput += "<br/>";
	htmlOutput += "gridGutter : "+gridGutter;
	//$(debugOutput).append(htmlOutput);
	*/
	
	var colCount = 1;
	for(colLeft=0;colLeft<=pageWidth;colLeft=(colWidth+colSep)*(colCount-1)){
		$('#less-grid').append('<span class="col col-'+colCount+'"><span class="info">&nbsp;col:&nbsp;'
		+colCount+'<br/>&nbsp;w:&nbsp;'+(((colWidth+colSep)*colCount)-colSep)+'</span></span>');
		$('#less-grid .col-'+colCount).css('left', colLeft);
		colCount++;
	};
}

//création du switch
function createSwitch () {
	
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
		$('#less-grid').remove();
		$('#less-baseline').remove();
		createGrid();
		
		//rajout apparition debug si masqué
		if($('.debugTemplate').closest('.dm_widget').hasClass('disabled')){
			$('.debugTemplate').closest('.dm_widget').toggleClass('disabled');
		}
	}, function() {
		$(this).text('show grid');
		$('#less-grid').hide();
		$('#less-baseline').hide();
		$(this).attr('rel','off');
	});
	
}