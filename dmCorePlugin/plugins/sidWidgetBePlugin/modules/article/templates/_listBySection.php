<?php // Vars: $articlePager, $parent, $route
//insertions des includes nécessaires à ce partial
$initValues = sfConfig::get('dm_front_dir') . '/templates/_schema/_partialInitValues.php';
include $initValues;

$html = '';

$html.= get_partial('global/titleWidget', array('title' => $parent . $dash . $route));

//affichage du pager top
$html.= get_partial('global/navigationWrapper', array(
												'placement' => 'top',
												'pager' => $articlePager
												));

//ouverture du listing
$html.= _open('ul.elements');

//compteur
$count = 0;
$maxCount = count($articlePager);

foreach ($articlePager as $article) {
	//incrémentation compteur
	$count++;
	//options de l'article
	$articleOpt = array(
					'name' => $article->getTitle(),
					'description' => $article->getChapeau(),
					'image' => '/_images/lea' . $article->filename . '-p.jpg',
					'dateCreated' => $article->created_at,
					'isDateMeta' => true,
					'count' => $count,
					'maxCount' => $maxCount,
					'container' => 'li.element',
					'isListing' => true,
					'descriptionLength' => $defaultValueLength,
					'url' => $article
				);
	
	//on supprime les photos après les 3 premiers articles
	if($count > 3) $articleOpt['image'] = false;

	//ajout de l'article
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpt);
}

//fermeture du listing
$html.= _close('ul.elements');

//affichage du pager bottom
$html.= get_partial('global/navigationWrapper', array(
												'placement' => 'bottom',
												'pager' => $articlePager
												));

//affichage html en sortie
echo $html;