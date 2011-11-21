<?php // Vars: $accueil
// affichage des chapeaux PRESENTATION, MISSIONS
echo _tag('h4.title', __('Presentation of the firm'));

//lien vers l'image
$imgLink = '/uploads/photo.jpg';
//on vérifie que l'image existe
$imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);

echo _open('div.imageWrapper');
	echo _media($imgLink)
		->set('.image')
		->alt($nom->getTitle())	//à remplacer quand cela sera implémenté
		//redimenssionnement propre lorsque l'image sera en bibliothèque
		->width(spLessCss::gridGetWidth(spLessCss::getLessParam('thumbL_col')))
		->height(spLessCss::gridGetHeight(spLessCss::getLessParam('thumbL_bl')));
echo _close('div');

//on affiche la présentation
echo _tag('div.wrapper', $presentation);

echo _open('div.navigation.navigationBottom');
	echo _open('ul.elements');
		echo _open('li.element');
			echo _link('/le-cabinet/presentation')->text(__('Learn more about').' '.$nom->getTitle());
		echo _close('li');
	echo _close('ul');
echo _close('div');