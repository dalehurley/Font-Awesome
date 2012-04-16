<?php

echo _tag('ul.help', _tag('li', "Ce widget permet d'afficher les derniers articles d'actu, les derniers dossiers, les dernière faq et le dessin de la semaine.")
);


echo

$form->renderGlobalErrors(),
 _tag('div', $form['titreBloc']->renderRow() .
        $form['length']->renderRow() .
        $form['nbArticles']->renderRow() .
        $form['section']->renderRow() .
        $form['lien']->renderRow().
        $form['withImage']->renderRow().
        $form['widthImage']->renderRow().
        $form['justTitle']->renderRow()
);


