<?php

echo '<li id="equipe_' . $equipe->id . '" class="element" itemscope itemtype="http://schema.org/Person">';
echo _open('div.imageWrapper');
echo _media($equipe->getImage())
        ->set('.image itemprop="image"')
        ->alt($equipe->getTitle())
        ->width(spLessCss::gridGetWidth(1, 0))
        ->height(spLessCss::gridGetHeight(4, 0));
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
if(array_key_exists($equipe->id, $nomRubrique)){
echo _open('span.rubrique');
echo _tag('span.type', __('Responsable in'));
echo '&nbsp;:&nbsp;';
echo _tag('span.value', $nomRubrique[$equipe->id]);
echo _close('span');
echo '<br />';
};
echo _open('span.email');
echo _tag('span.type', __('Email'));
echo '&nbsp;:&nbsp;';
echo _tag('span.value', _link('mailto:' . $equipe->getEmail())->text($equipe->getEmail())->set('itemprop="email"'));
echo _close('span');

echo _tag('span.description itemprop="description"', $equipe->getText());

echo _close('div');

echo _close('li');
?>
