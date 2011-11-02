<?php // Vars: $articlePager
echo _tag('h2.title', $parent.' > '.$route);

echo _tag('div.navigation.navigationTop', $articlePager->renderNavigationTop());

$i = 0;
// Cas particulier pour les dossiers
if($this->context->getPage()->getName() == 'Dossiers'){
    $i=2;
}

echo _open('ul.elements');
	foreach ($articlePager as $article)
	{
		if($i<3){
			echo _open('li.element');
			// on affiche un message que pour l'environnement de dev
			if (sfConfig::get('sf_environment') == 'dev') {
				echo _tag(
					'div.debug', array('onClick' => '$(this).hide();'), _tag('span.type', 'id LEA&#160;:').' '._tag('span.value', $article->filename)
				);
			}

			$date = new DateTime($article->created_at);
			// je vérifie la longueur du texte
			if(strlen($article->getChapeau()) > 200){
			$chapeauEntier = substr($article->getChapeau(), 0, 200);
						$space = strrpos($chapeauEntier,' ');
						$chapo = substr($chapeauEntier, 0, $space).' (...)';
			}
			else $chapo = $article->getChapeau();
			// fin vérif longueur du texte
			if($i == 0){
				//lien vers l'image
				$imgLink = '/_images/lea' . $article->filename . '-p.jpg';
				//on vérifie que l'image existe
				$imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);

				//composition du html contenu dans le lien (ne peux contenir que des span)
				$html = '';

				if ($imgExist) {
					$html.= _open('span.imageWrapper');
					$html.= _media($imgLink)
							->set('.image itemprop="image"')
							->alt($article->getTitle())
							->width(myUser::gridGetWidth(myUser::getLessParam('thumbL_col')))
                            ->height(myUser::gridGetHeight(myUser::getLessParam('thumbL_bl')));
					$html.= _close('span.imageWrapper');
				}

				$html.= _open('span.wrapper');
					$html.= _tag('span.title itemprop="name"', $article->getTitle());
                                        $html.= _tag('span.date','('.$date->format('d/m/Y').')');
				//on ajoute le chapeau dans tous les cas
				$html.= _tag('span.teaser itemprop="description"', $chapo);
				$html.= _close('span.wrapper');

				//On englobe l'ensemble du contenu dans un lien que l'on affiche
				echo _link($article)
						->set('.link_box')
						->title($article->getTitle())
						->text($html);
			}else{
				//echo _open('span.wrapper');
				echo _link($article)->text(_tag('span.wrapper',_tag('span.title',''.$article._tag('span.date','('.$date->format('d/m/Y').')'))._tag('span.teaser',$chapo)))->set('.link_box');
			}
			//echo _close('span');
			echo _close('li');
		}
		else
		{
			if($i == 3){
				if($this->context->getPage()->getName() == 'Dossiers'){echo _tag('p', 'Les autres dossiers "'.$parent.'"');}
			}
			echo _open('li.element');

			$date = new DateTime($article->created_at);
			echo _open('span.wrapper');
			echo _link($article)->text(_tag('span.title',''.$article._tag('span.date','('.$date->format('d/m/Y').')')))->set('.link_box');
			echo _close('span');
			echo _close('li');
		}
		$i++;
	}
echo _close('ul');

echo _tag('div.navigation.navigationBottom', $articlePager->renderNavigationBottom());