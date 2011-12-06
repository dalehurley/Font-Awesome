<?php

// var $rubriqueTitle 
echo _tag('h4.title', $rubriqueTitle);

echo _open('ul.elements');

foreach ($dessins as $dessin) {
    echo _open('li.element');

    //on vérifie que l'image existe
    $imgExist = is_file(sfConfig::get('sf_web_dir') . $dessin['imgLinkBig']);
    $imageDessin = "";
    // on teste si le fichier image est présent sur le serveur avec son chemin absolu
    if ($imgExist) {
        $imageDessin = _open('div.imageFullWrapper');
        $imageDessin .= _media($dessin['imgLinkBig'])
                ->set('.image itemprop="image"')
                ->alt($dessin['titre'])
        //redimenssionnement propre lorsque l'image sera en bibliothèque
        //->width(spLessCss::gridGetContentWidth())
        ;
        //->height(spLessCss::gridGetHeight(14,0))
        $imageDessin .= _close('div');
    }

    echo $imageDessin;
    echo _tag('span.title',$dessin['titre']);
    echo _tag('span.teaser',$dessin['chapeau']);
    
}


echo _close('ul');
echo _close('li');

?>


    <div id="gallery" class="ad-gallery">
      <div class="ad-image-wrapper">
      </div>
      <div class="ad-controls">
      </div>
      <div class="ad-nav">
        <div class="ad-thumbs">
          <ul class="ad-thumb-list">
            <li>
              <a href="images/1.jpg">
                <img src="images/thumbs/t1.jpg" class="image0">
              </a>
            </li>
            <li>
              <a href="images/10.jpg">
                <img src="images/thumbs/t10.jpg" title="A title for 10.jpg" alt="This is a nice, and incredibly descriptive, description of the image 10.jpg" class="image1">
              </a>
            </li>

          </ul>
        </div>
      </div>
    </div>

    <div id="descriptions">

    </div>
