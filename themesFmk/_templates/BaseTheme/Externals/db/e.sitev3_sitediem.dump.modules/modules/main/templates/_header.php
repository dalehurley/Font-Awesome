<?php
//composition du html
$html = '';

//lien vers l'image
$imgLink = myUser::getLessParam('urlImagesTemplate') . '/Page/logo_light.png';
//on vérifie que l'image existe
$imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);

if($imgExist){
	$html.= _open('span.imageWrapper');
		$html.= _media($imgLink)
				->set('.image')
				->alt('NomCabinet')
				->width(myUser::gridGetWidth(1,0))
				->height(myUser::gridGetHeight(3,0));
	$html.= _close('span.imageWrapper');
}

//On englobe l'ensemble du contenu dans un lien que l'on affiche
$html = _link('@homepage')
		->set('.link_box')
		->title('Nomcabinet')
		->text($html);

$html.= _tag('h1.title.dm_site_title', '{{Nomcabinet}}');
/*
$html.= _open('span.wrapper');
	$texteLegal =  'Inscrit au Registre des intermédiaires d\'assurances sous le numéro ORIAS 00 000 000
					<br/>Membre de l\'Association Nationale des Conseillers Financiers ANACOFI-CIF, agréé par l\'Autorité des
					<br/>Marchés Financiers, AMF, et inscrit en qualité de Conseil en Investissements Financiers, CIF, sous le numéro 00000
					<br />Le Nom du Cabinet est inscrit à la Préfecture de Police de Paris pour les activités de transactions immobilières, titulaire de la carte professionnelle n°000000.';


	$html.= _tag('p.teaser', $texteLegal);

$html.= _close('span.wrapper');
*/

//affichage du html
echo $html;