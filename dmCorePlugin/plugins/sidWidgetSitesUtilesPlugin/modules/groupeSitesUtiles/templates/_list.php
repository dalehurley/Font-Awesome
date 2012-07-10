<?php // Vars: $groupeSitesUtilesPager
/*
$html = get_partial('global/titleWidget', array('title' => __('Different groups of useful sites')));

//affichage du pager du haut
$html.= get_partial('global/navigationWrapper', array('placement' => 'top', 'pager' => $groupeSitesUtilesPager));

//ouverture du listing
$html.= _open('ul.elements');

//compteur
$count = 0;
$maxCount = count($groupeSitesUtilesPager);

foreach ($groupeSitesUtilesPager as $groupeSitesUtiles) {
	//incrémentation compteur
	$count++;
	
	//options de l'article
	$articleOpt = array(
					'name' => $groupeSitesUtiles,
					'url' => $groupeSitesUtiles,
					'count' => $count,
					'maxCount' => $maxCount,
					'container' => 'li.element',
					'isListing' => true
				);

	//ajout de l'article
	$html.= get_partial('global/schema/Thing/CreativeWork/Article', $articleOpt);
}
	
//fermeture du listing
$html.= _close('ul.elements');

//affichage du pager du bas
$html.= get_partial('global/navigationWrapper', array('placement' => 'bottom', 'pager' => $groupeSitesUtilesPager));

//affichage html en sortie
echo $html;*/
$i = 1;
$i_max = count($groupeSitesUtilesPager);
$class = '';
$title = 'Different groups of useful sites';
if (dmConfig::get('client_type') == 'aga'){$title = 'Different groups of docs and useful sites';};
echo _tag('h4', array('class' => 'title'), __($title));
echo _open('ul', array('class' => 'elements'));


foreach ($groupeSitesUtilesPager as $groupeSitesUtile) {
	$link = '';
	if ($i == 1) {
            $class = 'first';
            if ($i == $i_max)
                $class = 'first last';
        }
        elseif ($i == $i_max)
            $class = 'last';
        else
            $class = '';
        
        $link .= _open('li', array('class' => 'element itemscope Article '.$class, 'itemtype' => 'http://schema.org/Article' , 'itemscope' => 'itemscope'));

                    $link .= _tag('span', array('class' =>'title itemprop name'),$groupeSitesUtile->getTitle());
               $link .= _close('span');
      
       echo _link($groupeSitesUtile)->set('.link_box')->text($link); 
       $i++;   

        echo _close('li');
}
echo _close('ul');
?>