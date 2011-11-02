<?php // Vars: $articlePager
if(count($articlePager) != 0){
echo _tag('h3', 'Les articles de la rédaction');
echo $articlePager->renderNavigationTop();

echo _open('ul.elements');

foreach ($articlePager as $article)
{
  echo _open('li.element');

    echo _link($article)->text(_tag('span.rubrique', strtoupper($article->Section->Rubrique)).'::'._tag('span.section', $article->Section).'::'._tag('span.titre', $article));

  echo _close('li');
}

echo _close('ul');

echo $articlePager->renderNavigationBottom();
}
