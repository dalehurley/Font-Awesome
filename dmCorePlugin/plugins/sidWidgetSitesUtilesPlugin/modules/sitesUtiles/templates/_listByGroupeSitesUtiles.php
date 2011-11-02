<?php

// Vars: $sitesUtilesPager

echo $sitesUtilesPager->renderNavigationTop();

foreach ($sitesUtilesPager as $sitesUtiles) {
    $firstSitesUtilesGroup = $sitesUtiles->getGroupeSitesUtiles()->title;
    break;
}
echo _tag('h2.title', $firstSitesUtilesGroup);

echo _open('ul.elements');

foreach ($sitesUtilesPager as $sitesUtiles) {
    echo _open('li.element');

    // echo _link($sitesUtiles);
    echo $sitesUtiles->description;
    echo _tag('div.siteLogo', _link($sitesUtiles->url)->text(
                    _media($sitesUtiles->getImage())->width(160)->method('inflate')
            )
    );
    echo _link($sitesUtiles->url)->set('#siteLink.small')->text($sitesUtiles )->target('blank');

    echo _tag('hr');
    
    echo _close('li');
}

echo _close('ul');

echo $sitesUtilesPager->renderNavigationBottom();