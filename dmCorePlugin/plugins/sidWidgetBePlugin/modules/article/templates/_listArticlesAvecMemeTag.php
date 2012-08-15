<?php // Vars: $articles
if(dmConfig::get('site_theme_version') == 'v1'){
$html = '';

if(count($articles) != NULL){
	
	$html.= get_partial('global/titleWidget', array('title' => __('Read also ')));
	
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
												'dateCreated' => $article->created_at,
												'isDateMeta' => true,
												'count' => $count,
												'maxCount' => $maxCount,
												'container' => 'li.element',
												'isListing' => true,
												'url' => $article
												));
    }
	
    //fermeture du listing
    $html.= _close('ul.elements');
	
}
//affichage html en sortie
echo $html;
}
elseif(dmConfig::get('site_theme_version') == 'v2'){
	$html = '';
	$i = 0;
	$i_max = count($articles);
	if(count($articles) != NULL){
		echo _tag('h3',__('Read also '));
		echo _open('div');
			echo _open('ul.thumbnails');
				foreach($articles as $article){
					$i++;
			        $position = '';
			        switch ($i){
			            case '1' : 
			                if ($i_max == 1) $position = ' .first last';
			                else $position = ' .first';
			                break;
			            default : 
			                if ($i == $i_max) $position = ' .last';
			                else $position = '';
			                break;
	        		}
					echo _open('li');
						echo _link($article)->set('.thumbnail')->text(_tag('p',$article->getTitle()))->set($position);
					echo _close('li');
				}
			echo _close('ul');
		echo _close('div');
	}
}