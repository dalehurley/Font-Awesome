<?php // Vars: $sitesUtiles

echo _tag('h2',$sitesUtiles);
echo _media($sitesUtiles->getImage())->width(160);
echo $sitesUtiles->description;
echo $sitesUtiles->url;