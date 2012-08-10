<?php
// vars : $articles, $titreBloc, $lien, $length, $rubrique, $section
if(dmConfig::get('site_theme_version') == 'v1'){
$html = '';

if (count($articles)) { // si nous avons des actu articles
	
	$html.= get_partial('global/titleWidget', array('title' => $titreBloc));
	
	//ouverture du listing
    $html.= _open('ul.elements');
	
	//compteur
	$count = 0;
	$maxCount = count($articles);
	
	foreach ($articles as $article) {
		//incrémentation compteur
		$count++;
		
		$html.= get_partial('global/schema/Thing/CreativeWork/Article', array(
												'name' => $article->getTitle(),
												'description' => $article->getChapeau(),
												'dateCreated' => $article->created_at,
												'isDateMeta' => true,
												'count' => $count,
												'maxCount' => $maxCount,
												'container' => 'li.element',
												'isListing' => true,
												'descriptionLength' => $length,
												'url' => $article
												));
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
	
	//création d'un tableau de liens à afficher
	$elements = array();
	// avant
	//$elements[] = array('title' => ($lien ? $lien : $section->title . ' en ' . $rubrique->name), 'linkUrl' => 'sidActuArticle/list');
	// après
	$elements[] = array('title' => ($lien ? $lien : $section . ' en ' . $rubrique), 'linkUrl' => $pageLink);
	
	$html.= get_partial('global/navigationWrapper', array(
													'placement' => 'bottom',
													'elements' => $elements
													));
}

//affichage html en sortie
echo $html;
}
// vars $pageLink
elseif(dmConfig::get('site_theme_version') == 'v2'){
	//echo 'fff'.$articles[0]->Section() . ' en ' . $rubrique['name'];
	if($lien == '')
		{$lien = $section.
			' '.
			__('in').
			' '.
			$rubrique;}
	$i = 0;
	$i_max = count($articles);
	if(count($articles) != NULL){
		echo _tag('h3',$titreBloc);
		//echo _open('div', array('class' => 'thumbnail'));
			echo _open('ul', array('class' => 'thumbnails'));
				foreach($articles as $article){
					$html = '';
					$i++;
			        $position = '';
			        switch ($i){
			            case '1' : 
			                if ($i_max == 1) $position = ' first last';
			                else $position = ' first';
			                break;
			            default : 
			                if ($i == $i_max) $position = ' last';
			                else $position = '';
			                break;
	        		}

	        		$html .= _open('div', array('class' => 'caption'));
	        			$html .= _tag('h5', $article->getTitle());
	        			$html .= _tag('p',stringTools::str_truncate($article->getChapeau(), $length, '(....)', true));
	        		$html .= _close('div');
					echo _open('li');
						echo _link($article)->text($html)->set('.thumbnail '.$position);
					echo _close('li');
				}
			echo _close('ul');
			echo _link($pageLink)->text($lien)->set('.btn');
		//echo _close('div');
	}
}