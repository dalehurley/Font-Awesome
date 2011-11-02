<?php  // var $agendas - var $rubriqueTitle - var $rubrique
echo _tag('h4.title', $rubriqueTitle);

echo _open('ul.elements');
	foreach ($agendas as $agenda) {
		echo _open('li.element');
                echo _open('span.wrapper');
			//composition du html contenu dans le lien (ne peux contenir que des span)
                       
			$html = _tag('span.title', $agenda->getTitle());
			//on ajoute le chapeau dans tous les cas
                        $chapeauEntier = substr($agenda->getChapeau(), 0, 200);
			$space = strrpos($chapeauEntier,' ');
			$chapo = substr($chapeauEntier, 0, $space).' (...)';
			$html.= _tag('span.teaser', $chapo);
			//On englobe l'ensemble du contenu dans un lien que l'on affiche
			echo _link($agenda)
				->set('.link_box')
				->title($agenda->getTitle())
				->text($html);
                echo _close('span');
		echo _close('li');
	}
echo _close('ul');

echo _open('div.navigation.navigationBottom');
	echo _open('ul.elements');
		echo _open('li.element');
			echo _link($agendas[0]->getSection()->getRubrique())->text('Découvrir notre rubrique ' . $agendas[0]->getRubriquePageName());
		echo _close('li');
	echo _close('ul');
echo _close('div');