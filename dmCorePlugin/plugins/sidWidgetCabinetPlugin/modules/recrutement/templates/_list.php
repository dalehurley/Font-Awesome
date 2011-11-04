<?php // Vars: $recrutementPager

if(count($recrutementPager) == NULL){
    echo _tag('p.teaser', __('There is no offer of appointment to this moment'));
}
else {
echo _tag('h2.title', _('Recruitment'));
echo $recrutementPager->renderNavigationTop();

echo _open('ul.elements');

foreach ($recrutementPager as $recrutement)
{
  echo _open('li.element');
  echo _open('span.teaser');
    echo _link($recrutement)->set('.link_box');
    echo _close('span');
  echo _close('li');
}

echo _close('ul');

echo $recrutementPager->renderNavigationBottom();

// Vars: $recrutes
//use_stylesheet('/sidWidgetCabinetPlugin/css/styleSidWidgetCabinet.css');
//echo _tag('h2.title', 'Recrutement');
//echo _open ('div');
//foreach ($recrutes as $recrute){
//  echo _open('li.element');
//
//    echo _link('pageCabinet/recrutement')->anchor($recrute->id)->text($recrute)->currentSpan(true);
//
//  echo _close('li');
//}
//foreach ($recrutes as $recrute) {
//    echo _open('div.annonce #'.$recrute->id);
//        echo _tag('h3.couleur.titre', $recrute);
//        echo _tag('div.text', $recrute->getText());
//    echo _close('div');
//}
//echo _close('div');
}