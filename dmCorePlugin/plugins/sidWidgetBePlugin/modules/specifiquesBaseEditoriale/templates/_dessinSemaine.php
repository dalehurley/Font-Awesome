<?php
use_stylesheet('../../sidWidgetBePlugin/ad-gallery/jquery.ad-gallery');
use_javascript('../sidWidgetBePlugin/ad-gallery/jquery.ad-gallery');

// var $rubriqueTitle 
echo _tag('h4.title', $rubriqueTitle);
?>

<div class="ad-gallery">
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
			
			//compteur
            $i = 0;
			$maxCount = count($dessins);
			
            foreach ($dessins as $dessin) {
				
				//ajout des options au li
				$liOpts = array();
				if($i == 0)				$liOpts['class'][] = 'first';
				if($i >= $maxCount-1)	$liOpts['class'][] = 'last';
				
				//ouverture du li
				echo _open('li.ad-thumb', $liOpts);
				
                //on vérifie que l'image existe
                $imgExist = is_file(sfConfig::get('sf_web_dir') . $dessin['imgLinkBig']);
                $imageDessin = "";
                // on teste si le fichier image est présent sur le serveur avec son chemin absolu
                if($imgExist) {
                    $imageDessin = _link($dessin['imgLinkBig'])->text(
                            _media($dessin['imgLinkSmall'])
                                    ->set('.image title="' . $dessin['titre'] . ' ('.$dessin['dateArticle'].')"')
                                    ->alt($dessin['chapeau'])
                                    ->height(72)
                            )
                    ;
                    $i++;
                }
				
                echo $imageDessin;
                echo _close('li');
            }

            echo _close('ul');
            ?>

        </div>
    </div>
    <div class="ad-image-wrapper"></div>
    <div class="ad-controls"></div>
    <div class="ad-descriptions"></div>
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