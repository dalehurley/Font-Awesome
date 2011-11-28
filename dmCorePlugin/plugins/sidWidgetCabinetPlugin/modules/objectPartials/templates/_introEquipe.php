<?php
// var partial : $equipe, $textLenght, $textEnd, $showLink

    
echo '<li id="equipe_' . $equipe->id . '" class="element" itemscope itemtype="http://schema.org/Person">';
$html ="";
			$html .= _open('div.imageWrapper');
				$html .= _media($equipe->getImage())
						->set('.image itemprop="image"')
						->alt($equipe->getTitle())
						->width(spLessCss::gridGetWidth(1,0))
						->height(spLessCss::gridGetHeight(4,0));
			$html .= _close('div');
			$html .= _open('div.wrapper');
				$html .= _open('div.subWrapper');
					$html .= _tag('span.jobTitle itemprop="jobTitle"', $equipe->getStatut()).'<br />';
                                        $html .= _tag('span.name itemprop="name"', $equipe->getTitle());
                                        $html .= _open('span.telephone');
                                            $html .= _tag('span.type', __('phone'));
                                            $html .= '&nbsp;';
                                            $html .= _tag('span.value itemprop="telephone"', $equipe->getTel());
                                        $html .= _close('span');
                                        $html .= _open('span.email');
                                            $html .= _tag('span.type', __('Email'));
                                            $html .= '&nbsp;:&nbsp;';
                                            $html .= _tag('span.value', _link('mailto:' . $equipe->getEmail())->text($equipe->getEmail())->set('itemprop="email"'));
                                        $html .= _close('span');
				$html .= _close('div');
			$html .= _close('div');	
				
				echo _link('pageCabinet/equipe')->text($html)
				    ->set('.link_box')
                                    ->anchor('equipe_'.$equipe->id)
				    ->title($equipe->getTitle());

				
    echo _close('li');

//} else {
//    echo __('This bias needs to be a news item');
//}
?>
