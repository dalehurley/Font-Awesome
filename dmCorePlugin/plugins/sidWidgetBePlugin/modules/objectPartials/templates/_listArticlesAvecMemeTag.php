<?php // Vars: $articleTag


			echo _open('li.element');
				//à améliorer ! (pas le temps ce matin)
				echo _link($articleTag)
//					->text(_tag('span.rubrique', $nomPages[$articleTag->id]['rubrique']).' > '._tag('span.section', $nomPages[$articleTag->id]['section']).' > '._tag('span', $articleTag));
                        ->text(_tag('span.wrapper',_tag('span.title',$articleTag)))->set('.link_box');
			echo _close('li');