<?php // Vars: $missionPager
if(count($missionPager) != 0){
echo $missionPager->renderNavigationTop();
if(sfConfig::get('app_site-type') == 'ec'){
echo _tag('h3', 'Les missions du cabinet');
}
else echo _tag('h3', 'Les services du cabinet');
echo _open('ul.elements');

foreach ($missionPager as $mission)
{
  echo _open('li.element');

    echo _link($mission);

  echo _close('li');
}

echo _close('ul');

echo $missionPager->renderNavigationBottom();
}