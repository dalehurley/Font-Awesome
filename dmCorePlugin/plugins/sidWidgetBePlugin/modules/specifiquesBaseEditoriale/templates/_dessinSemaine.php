<?php
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
                            $chapo = ($dessin['chapeau']=='')? '' : ' : '.$dessin['chapeau'];
                            echo '<li><a href="#"><img src="'.$dessin['imgLinkSmall'].'" data-large="'.$dessin['imgLinkBig'].'" alt="'.$dessin['titre'].'" data-description="'.' ('.$dessin['dateArticle'].') '.$dessin['titre'].$chapo.'" /></a></li>';
                        } 
                    }
                ?>                                    
                </ul>
            </div>
        </div>
        <!-- End Elastislide Carousel Thumbnail Viewer -->
    </div><!-- rg-thumbs -->
</div><!-- rg-gallery -->
