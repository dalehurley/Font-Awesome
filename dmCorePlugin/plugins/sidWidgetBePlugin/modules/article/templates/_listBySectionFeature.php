<?php
// Vars: $articlePager
// affichage des photos dans nivoslider
$listImgOutput = '';
$listImgTabs = '';
$nbImg = 0;
foreach ($articlePager as $article) {
    if ($nbImg == 3) {
        break;
    } else {
        $imageLink = '/_images/lea' . $article->filename . '-g.jpg'; // /_images/ est le dossier local au site en lien symbolique avec la base editoriale ( -g : 550 * 240)
        if (is_file(sfConfig::get('sf_web_dir') . $imageLink)) {

            $listImgTabs .= ' <li>
                                    <a href="javascript:;">
                                        <h3>' . $article . '</h3>'.
                                    //    '<span>'. $article->chapeau .'</span>'.
                                   '</a>
                                </li>';
            
            $listImgOutput .= ' <li>
                                    <img style="width:463px;height:240px;" src="' . $imageLink . '" alt="" title="' . $article . '" />
                                    '. _link($article)->text(__('En savoir plus')).'
                                </li>';
            
            $nbImg++;
        }
    }
}

//$nbImg = 5;

if ($nbImg == 3) {

    use_stylesheet('../../sidWidgetBePlugin/featureList/style');
    use_javascript('../sidWidgetBePlugin/featureList/jquery.featureList-1.0.0');
    use_javascript('../sidWidgetBePlugin/featureList/jquery.featureList-1.0.0.control');

    ?>

    <div id="feature_list">
        <ul id="tabs">
            <?php echo $listImgTabs; ?>
        </ul>
        <ul id="output">
            <?php echo $listImgOutput; ?>
        </ul>

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
    
