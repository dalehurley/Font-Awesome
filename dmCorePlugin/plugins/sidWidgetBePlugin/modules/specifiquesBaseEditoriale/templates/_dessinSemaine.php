<?php
if (dmConfig::get('site_theme_version')=='v1XXXXXXXXXXXXXXXX'){
    use_stylesheet('../../sidWidgetBePlugin/ad-gallery/jquery.ad-gallery');
    use_javascript('../sidWidgetBePlugin/ad-gallery/jquery.ad-gallery');

    // var $titreBloc
    echo _tag('h4.title', $titreBloc);
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
                    $img = sfConfig::get('sf_web_dir') . $dessin['imgLinkBig'];
                    $imgExist = is_file($img);
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

<?php
} else {

    use_stylesheet('../../sidWidgetBePlugin/css/style');
    use_stylesheet('../../sidWidgetBePlugin/css/elastislide');
    use_javascript('../sidWidgetBePlugin/js/jquery.tmpl.min');
    use_javascript('../sidWidgetBePlugin/js/jquery.easing.1.3');
    use_javascript('../sidWidgetBePlugin/js/jquery.elastislide');
    use_javascript('../sidWidgetBePlugin/js/gallery'); 
?>       

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>


<script id="img-wrapper-tmpl" type="text/x-jquery-tmpl">    
    <div class="rg-image-wrapper">
        {{if itemsCount > 1}}
            <div class="rg-image-nav">
                <a href="#" class="rg-image-nav-prev">Previous Image</a>
                <a href="#" class="rg-image-nav-next">Next Image</a>
            </div>
        {{/if}}
        <div class="rg-image"></div>
        <div class="rg-loading"></div>
        <div class="rg-caption-wrapper">
            <div class="rg-caption" style="display:none;">
                <p></p>
            </div>
        </div>
    </div>
</script>

<?php
// var $titreBloc
    echo _tag('h4.title', $titreBloc);
?>
<div id="rg-gallery" class="rg-gallery">
    <div class="rg-thumbs">
        <!-- Elastislide Carousel Thumbnail Viewer -->
        <div class="es-carousel-wrapper">
            <div class="es-nav">
                <span class="es-nav-prev">Previous</span>
                <span class="es-nav-next">Next</span>
            </div>
            <div class="es-carousel">
                <ul>
                <?php
                    $i = 0;
                    foreach ($dessins as $dessin) {
                        
                        //on vérifie que l'image existe
                        $img = sfConfig::get('sf_web_dir') . $dessin['imgLinkBig'];
                        $imgExist = is_file($img);
                        $imgSmall = sfConfig::get('sf_web_dir') . $dessin['imgLinkBig'];
                        $imgSmallExist = is_file($imgSmall);                    
                        $imageDessin = "";
                        // on teste si le fichier image est présent sur le serveur avec son chemin absolu
                        if($imgExist && $imgSmallExist) {
                            $i++;
                            echo '<li><a href="#"><img src="'.$dessin['imgLinkSmall'].'" data-large="'.$dessin['imgLinkBig'].'" alt="'.$dessin['titre'].'" data-description="'.' ('.$dessin['dateArticle'].') '.$dessin['titre'].': '.$dessin['chapeau'].'" /></a></li>';
                        } 
                    }
                ?>                                    
                </ul>
            </div>
        </div>
        <!-- End Elastislide Carousel Thumbnail Viewer -->
    </div><!-- rg-thumbs -->
</div><!-- rg-gallery -->
<?php } ?>