<?php
use_stylesheet('../../sidWidgetBePlugin/ad-gallery/jquery.ad-gallery');
use_javascript('../sidWidgetBePlugin/ad-gallery/jquery.ad-gallery');

// var $rubriqueTitle 
echo _tag('h4.title', $rubriqueTitle);
?>

<div class="ad-gallery">
    <div class="ad-image-wrapper"></div>
    <div class="ad-controls"></div>
    <div class="ad-descriptions"></div>
    <div class="ad-nav">
        <div class="ad-thumbs">

            <!--            
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
            -->            

            <?php
            echo _open('ul.ad-thumb-list');

            $i = 0;
            foreach ($dessins as $dessin) {
                echo _open('li');
                //on vérifie que l'image existe
                $img = sfConfig::get('sf_web_dir') . $dessin['imgLinkBig'];
                $imgExist = is_file($img);
                $imageDessin = "";
                // on teste si le fichier image est présent sur le serveur avec son chemin absolu
                if ($imgExist) {
                    $imageDessin = _link($dessin['imgLinkBig'])->text(
                            _media($dessin['imgLinkSmall'])
                                    ->set('.image' . $i . ' title="' . $dessin['titre'] . ' ('.$dessin['dateArticle'].')"')
                                    ->alt($dessin['chapeau'])
                                    ->height(60)
                            )
                    ;
                    $i++;
                } else {
                    echo debugTools::infoDebug(array('fichier absent' => $img),'debug');

                }


                echo $imageDessin;
                echo _close('li');
            }

            echo _close('ul');
            ?>

        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        var galleries = $('.ad-gallery').adGallery(
        {
            effect: '<?php echo $effect ?>' // or 'slide-hori', 'slide-vert', 'fade', or 'resize', 'none'
            
        }
    );
    });
</script>