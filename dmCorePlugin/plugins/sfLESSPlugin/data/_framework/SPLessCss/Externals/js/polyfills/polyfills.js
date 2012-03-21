// polyfills.js
// v1.0
// Last Updated : 2012-03-21 11:10
// Copyright : SID Presse
// Author : Arnaud GAUDIN

//permet d'isoler le code du reste de l'environnement javascript
(function($){

	//options de la page en JSON : pageOptions

	//permet le chargement des versions compressées
	var getMin = pageOptions.isDev ? '' : '.min';

	//chargement des polyfills lorsque nécessaires
	Modernizr.load([
		{
	    	test : Modernizr.placeholder,
	    	nope : {
	    		'jqueryPlaceholder' : '/theme/less/_framework/SPLessCss/Externals/js/polyfills/jquery-placeholder/jquery.placeholder'+getMin+'.js'
	    	},
	    	callback : {
	    		'jqueryPlaceholder' : function (url, result, key) {
	    			if(result) $('input, textarea').placeholder();
				}
			}
 		}
	]);
	
})(jQuery);