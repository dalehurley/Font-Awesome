<?php
// Vars: $articlePager
// affichage des photos dans nivoslider
$listImg = '';
$nbImg = 0;
foreach ($articlePager as $article) {
    $imageLink = '/_images/lea' . $article->filename . '-g.jpg'; // /_images/ est le dossier local au site en lien symbolique avec la base editoriale
    if (is_file(sfConfig::get('sf_web_dir') . $imageLink)) {
        $listImg .= _link($article)->text('<img src="' . $imageLink . '" width="462px" alt="" title="' . $article . '" />');
        $nbImg++;
    }
}

if ($nbImg > sfConfig::get('app_nb-min-articles-slide', 3)) {

    use_stylesheet('../../sidWidgetBePlugin/nivo-slider/css/themes/default/default');
   // use_stylesheet('../../sidWidgetBePlugin/nivo-slider/css/themes/pascal/pascal');
   // use_stylesheet('../../sidWidgetBePlugin/nivo-slider/css/themes/orman/orman');    
    use_stylesheet('../../sidWidgetBePlugin/nivo-slider/css/nivo-slider');
    use_stylesheet('../../sidWidgetBePlugin/nivo-slider/css/style');
    use_javascript('../sidWidgetBePlugin/nivo-slider/js/jquery.nivo.slider.pack');
    use_javascript('../sidWidgetBePlugin/nivo-slider/js/jquery.nivo.slider.control');
    
    ?>

    <div class="slider-wrapper theme-default">
        <div class="ribbon"></div>
        <div id="slider" class="nivoSlider">

            <?php echo $listImg; ?>

        </div>
    </div>

<?php
} else {

    echo $articlePager->renderNavigationTop();

    echo _open('ul.elements');

    foreach ($articlePager as $article) {
        echo _open('li.element');

        echo _link($article);
//    $imageLink = '/_images/lea' . $article->filename . '-u.jpg'; // /_images/ est le dossier local au site en lien symbolique avec la base editoriale
//    //echo $imageLink;
//    echo _tag('img.leaImg', array('src' => $imageLink));

        echo _close('li');
    }

    echo _close('ul');

    echo $articlePager->renderNavigationBottom();

    echo $articlePager->renderNavigationTop();
}
?>
    
