<?php
// vars : $titreBloc, $lien, $message, $href
if (dmConfig::get('site_theme_version') == 'v1'){
$html = get_partial('global/titleWidget', array('title' => $titreBloc));

//texte de présentation
$html.= _tag('div.wrapper', $message);

//création d'un tableau de liens à afficher
$elements = array();
$elements[] = array('title' => $lien, 'linkUrl' => $href);
$html.= get_partial('global/navigationWrapper', array(
												'placement' => 'bottom',
												'elements' => $elements
												));

//affichage html en sortie
echo $html;
}
elseif (dmConfig::get('site_theme_version') == 'v2'){
	echo _open('div.thumbnail');
		echo _open('div.caption');
			echo _tag('h3', $titreBloc);
			echo _open('div', array());
				echo _tag('p', $message);
				echo _link($href)->text($lien)->set('.btn.btn-primary');
			echo _close('div');
		echo _close('div');
	echo _close('div');
}

