// Mise en place et lancement du menuAccordion.js
$.getScript('/theme/js/menuAccordion.js', function() {
    
    // traitement du menu
    $('div.navigation_menu').find('div.dm_widget_inner.accordion').find('ul.dropdown').addClass('accordion');
    $('div.navigation_menu').find('div.dm_widget_inner.accordion').find('ul.dropdown').addClass('acitem');
    // oter la class dropdown
    $('div.navigation_menu').find('div.dm_widget_inner.accordion').find('ul.dropdown').removeClass('dropdown');

    // ajouter une classe a tous les fils
    $('div.navigation_menu').find('ul.accordion').find('ul').addClass('accordion');
    $('div.navigation_menu').find('ul.accordion').find('ul').addClass('acitem');

    // lancement du menu
    $('ul.accordion').initMenuAccordion();
    
    	// page en cours, on déplie son parent
	$('li.dm_current').parent().show();
    
    
    /* style à appliquer */
    /*


ul.accordion, ul.accordion ul {
  list-style-type:none;
  margin: 0;
  padding: 0;
}

ul.accordion a {
  display: block;
  text-decoration: none;	
}

ul.accordion li {
  margin-top: 1px;
}

ul.accordion li a, ul.accordion ul.accordion li a {
  background: #333;
  color: #fff;	
  padding: 0.5em;
}

ul.accordion li a:hover, ul.accordion ul.accordion li a:hover {
  background: #000;
}

ul.accordion li ul li a, ul.accordion ul.accordion li ul li a {
  background: #ccc;
  color: #000;
  padding-left: 20px;
}

ul.accordion li ul li a:hover, ul.accordion ul.accordion li ul li a:hover {
  background: #aaa;
  border-left: 5px #000 solid;
  padding-left: 15px;
}
ul.accordion ul.accordion li a:hover {
    border-left: 0;
    padding-left: 0.5em;
}
ul.accordion ul.accordion {
	margin-left: 5px;
}
ul.accordion a.active, ul.accordion ul.accordion li a.active, ul.accordion a.active:hover, ul.accordion ul.accordion li a.active:hover {
    text-decoration: underline;
    background: #c00;
}
div.panel {
    border: 1px #000 solid;
    padding: 5px;
    margin-top: 1px;
}

ul.accordion div.panel a, ul.accordion div.panel li a:hover  {
    display :inline;
    color: #666;
    background: none;
    margin: 0;
    padding: 0;
    border: none;
    font-weight: bold;
}
ul.accordion div.panel a:hover {
    color: #000;
    text-decoration: underline;
}




     */
    
    
});