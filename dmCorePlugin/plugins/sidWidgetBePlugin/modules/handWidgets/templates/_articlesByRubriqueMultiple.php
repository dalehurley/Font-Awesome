<?php

if ($titreBloc != '') {
    echo _tag('h4.title', $titreBloc);
}

echo _open('ul.elements');
foreach ($articles as $article) {
    echo _open('li.element');

    $articleTitle =  $article->getRubriquePageName() . ' :: ' . $article->getSectionPageName();
    echo _link($article)->text($article)->title($article->getRubriquePageName() . ' :: ' . $article->getSectionPageName());

    //echo '<br />filename : ' . $article->filename;
    
    echo _close('li');
}
echo _close('ul');


