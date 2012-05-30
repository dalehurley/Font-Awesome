<?php
use_stylesheet('/sidSiteMapPlugin/css/sitemap.css');

//$toto = implode("'", sfConfig::get('app_pages-for-sitemap_not-visibles')); echo 'toto :'.$toto.' titi';
echo _tag('nav', $menu);



