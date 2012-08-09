<?php
echo _tag('ul.help',
	_tag('li',"Affichage des derniers articles sous forme de grille d'images")
	);

echo

$form->renderGlobalErrors(),
 _tag('div', 
    $form['nbImagesByLine']->renderRow().
    $form['nbLines']->renderRow().	
    $form['containerWidth']->renderRow().
    $form['interval']->renderRow().
    $form['animSpeed']->renderRow().
    $form['maxStep']->renderRow()
);


   