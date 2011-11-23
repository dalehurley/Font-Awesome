<?php
// var partial : $equipe, $textLenght, $textEnd, $showLink
//if (isset($equipe)) {
//    if (!isset($textLength))
//	$textLength = 0;
    if (!isset($textEnd))
	$textEnd = ' ...';
        if (!isset($showLink))
	$showLink = false;
    
echo '<li id="equipe_' . $equipe->id . '" class="element" itemscope itemtype="http://schema.org/Person">';
			echo _open('div.imageWrapper');
				echo _media($equipe->getImage())
						->set('.image itemprop="image"')
						->alt($equipe->getTitle())
						->width(myUser::gridGetWidth(1,0))
						->height(myUser::gridGetHeight(4,0));
			echo _close('div');
			echo _open('div.wrapper');
				echo _open('div.subWrapper');
					echo _tag('span.name itemprop="name"', $equipe->getTitle());
					echo '&nbsp;-&nbsp;';
					echo _tag('span.jobTitle itemprop="jobTitle"', $equipe->getStatut());
				echo _close('div');
				
				echo _open('span.telephone');
					echo _tag('span.type', __('phone'));
					echo '&nbsp;';
					echo _tag('span.value itemprop="telephone"', $equipe->getTel());
				echo _close('span');
echo '<br/>';
				echo _open('span.email');
					echo _tag('span.type', __('Email'));
					echo '&nbsp;:&nbsp;';
					echo _tag('span.value', _link('mailto:' . $equipe->getEmail())->text($equipe->getEmail())->set('itemprop="email"'));
				echo _close('span');
if($showLink == true){
				echo _link($page)->text(
					_tag('span.description itemprop="description"', stringTools::str_truncate(
					    myUser::textEditorStripParagraph($equipe->getText()), $textLength, $textEnd)
					)
				    )
				    ->set('.link_box')
                                    ->anchor('equipe_'.$equipe->id)
				    ->title($equipe->getTitle());
}
else{
                                
                                echo _tag('span.description itemprop="description"', stringTools::str_truncate(myUser::textEditorStripParagraph($equipe->getText()), $textLength, $textEnd));
}
				echo _close('div');
    echo _close('li');

//} else {
//    echo __('This bias needs to be a news item');
//}
?>
