<?php

echo _tag('h1', dmConfig::get('site_name'));
//Modif stef le 28-09-2011
echo _tag('h3', 'Bienvenue sur la page d\'administration de votre site Internet');
//echo _tag('p',  _media('/dmCorePlugin/images/16/required.png')._tag('span', ' : champs obligatoires pour les formulaires'));
//fin Modif stef le 28-09-2011

if($checkVersion)
{
  echo _tag('div#dm_async_version_check');
}

if($reportAnonymousData)
{
  echo _tag('div#dm_async_report');
}

echo $homepageManager->render();