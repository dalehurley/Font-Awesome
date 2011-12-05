<?php

echo _tag('ul.help', _tag('li', "Ce widget permet d'afficher les derniers articles d'actu, les derniers dossiers, les dernière faqet le dessin de la semaine.")
);


echo

$form->renderGlobalErrors(),
 _tag('div', $form['titreBloc']->renderRow() .
        $form['longueurTexte']->renderRow() .
        $form['nbArticle']->renderRow() .
        $form['section']->renderRow() .
        $form['titreLien']->renderRow().
        $form['photo']->renderRow()
);


