<?php // Vars: $articlePager

//echo $articlePager->renderNavigationTop();
//
//echo _open('ul.elements');
//
//foreach ($articlePager as $article)
//{
//  echo _open('li.element');
//
//    echo _link($article);
//
//  echo _close('li');
//}
//
//echo _close('ul');
//
//echo $articlePager->renderNavigationBottom();
if(count($articles) != NULL){
    echo _tag('h4.title', 'Les articles apparentés');
	echo _open('ul.elements');
		foreach ($articles as $articleTag) {
			echo _open('li.element');
				//à améliorer ! (pas le temps ce matin)
				echo _link($articleTag)
//					->text(_tag('span.rubrique', $nomPages[$articleTag->id]['rubrique']).' > '._tag('span.section', $nomPages[$articleTag->id]['section']).' > '._tag('span', $articleTag));
                        ->text(_tag('span.wrapper',_tag('span.title',$articleTag)))->set('.link_box');
			echo _close('li');
		}
	echo _close('ul');
}