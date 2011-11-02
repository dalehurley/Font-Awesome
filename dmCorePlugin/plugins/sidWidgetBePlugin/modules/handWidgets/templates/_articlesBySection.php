<style type="text/css">

    .hand_widgets_articles_by_section div.conteneurSlides {
        width: <?php echo $largeur * $nbArticles; ?>px;
        height: 286px;
        background-color: red;
    }
    .hand_widgets_articles_by_section .container {
        width: <?php echo $largeur; ?>px;
        padding: 0;
        margin: 0 auto;
    }

    /*--Main Container--*/

    .hand_widgets_articles_by_section .main_view {
        float: left;
        position: relative;
    }

    /*--Window/Masking Styles--*/

    .hand_widgets_articles_by_section .window {
        height:<?php echo $hauteur; ?>px;	
        width: <?php echo $largeur; ?>px;
        overflow: hidden; /*--Hides anything outside of the set width/height--*/
        position: relative;
    }

    .hand_widgets_articles_by_section .slides {
        position: absolute;
        top: 0; left: 0;
    }

    /*--Paging Styles--*/
    .hand_widgets_articles_by_section .paging {
        position: absolute;
        top: 0px; right: 0px;
        text-align: right;
        z-index: 100; /*--Assures the paging stays on the top layer--*/
        line-height: 40px;
        display: none; /*--Hidden by default, will be later shown with jQuery--*/
        width: 100%;
    }

    .hand_widgets_articles_by_section .paging a {
        padding: 0 10px;
        background: #220000; 
        margin-right: 5px;
        -moz-border-radius: 3px;
        -khtml-border-radius: 3px;
        -webkit-border-radius: 3px;
    }

    .hand_widgets_articles_by_section .paging a.active {
        background: #660000; 
        border: 2px solid #660000;
    }

    .hand_widgets_articles_by_section .paging a:hover {
        background: #990000; 
        border: 2px solid #990000;
    }

    /* style contenu */
    .hand_widgets_articles_by_section div.slide {
        width: <?php echo $largeur; ?>px;
        height: <?php echo $hauteur; ?>px;
        padding: 0;
        background-color: #4D5D65;
        float: left;
        position: relative;
    }

    .hand_widgets_articles_by_section .slides div.tc {
        position: absolute;
        top: 45px;
        left: 200px;  
    }  

    .hand_widgets_articles_by_section .slides span.titre {
        font-size: 1.3em;
        position: relative;
    }  
    .hand_widgets_articles_by_section .slides img {
        position: absolute;
        float: left;
        top: 5px;
        left: 10px;
    }  
    .hand_widgets_articles_by_section .slides p.chapeau {
        font-size: 0.9em;
        position: relative;
    }  
    .hand_widgets_articles_by_section  .slides span.rubrique {
        font-size: 14px;
        font-weight: bold;
        text-transform: uppercase;
        text-decoration: none;
        position: absolute;
        top: 15px;
        left: 200px;        

        background-image: -webkit-gradient(
            linear,
            left bottom,
            left top,
            color-stop(0.13, #611200),
            color-stop(0.5, #9C1700),
            color-stop(0.5, #B51800),
            color-stop(0.87, #D9210D)
            );
        background-image: -moz-linear-gradient(
            center bottom,
            #611200 13%,
            #9C1700 50%,
            #B51800 50%,
            #D9210D 87%
            );
        -pie-background: no-repeat, linear-gradient(#BF1700, #7E1700);/*PIE*/
        behavior: url(ie/PIE.htc); /* PIE */
        z-index: 0;
        color: #FFF;
        display: block;
        /* [disabled]letter-spacing: 0.3em; */
        border-radius: 0px 10px 10px 0px;
        -webkit-border-radius: 0px 10px 10px 0px;
        -moz-border-radius:0px 10px 10px 0px;
        -ms-border-radius:0px 10px 10px 0px;
        padding-top: 2px;
        padding-right: 15px;
        padding-bottom: 2px;
        padding-left: 5px;
        margin-bottom: 0px;
        float: left;
        clear: left;
    }

</style>


<?php //print_r($articles); ?>

<div class="main_view">
    <div class="window">	
        <div class="slides">
            <div class="conteneurSlides">
                <?php
                foreach ($articles as $article) {
                    $imgLink = '/_images/lea' . $article->filename . '-g.jpg';
                    echo '<div class="slide">';
                    echo _tag('span.rubrique', $sectionName->name);
                    echo _tag('div.tc', _tag('span.titre', $article->title) .
                            _tag('p.chapeau', $article->chapeau)
                    );
                    echo _tag('img', array('src' => $imgLink, 'width' => '180px'));
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <div class="paging">
<?php
$i = 1;
foreach ($articles as $article) {
    echo '<a href="#" rel="' . $i . '"></a>';
    $i++;
}
?>
    </div>

</div>


<script type="text/javascript">
    $(document).ready(function() {
        //Set Default State of each portfolio piece
        $(".paging").show();
        $(".paging a:first").addClass("active");

        //Get size of images, how many there are, then determin the size of the image reel.
        var imageWidth = $(".window").width();
        var imageSum = $(".slides").size();
        var imageReelWidth = imageWidth * imageSum;

        //Adjust the image reel to its new size
        $(".slides").css({'width' : imageReelWidth});

        //Paging + Slider Function
        rotate = function(){	
            var triggerID = $active.attr("rel") - 1; //Get number of times to slide
            var slidesPosition = triggerID * imageWidth; //Determines the distance the image reel needs to slide

            $(".paging a").removeClass('active'); //Remove all active class
            $active.addClass('active'); //Add active class (the $active is declared in the rotateSwitch function)

            //Slider Animation
            $(".slides").animate({ 
                left: -slidesPosition
            }, 500 );
        }; 

        //Rotation + Timing Event
        rotateSwitch = function(){		
            play = setInterval(function(){ //Set timer - this will repeat itself every 3 seconds
                $active = $('.paging a.active').next();
                if ( $active.length === 0) { //If paging reaches the end...
                    $active = $('.paging a:first'); //go back to first
                }
                rotate(); //Trigger the paging and slider function
            }, 5000); //Timer speed in milliseconds (3 seconds)
        };

        rotateSwitch(); //Run function on launch

        //On Hover
        $(".slides").hover(function() {
            clearInterval(play); //Stop the rotation
        }, function() {
            rotateSwitch(); //Resume rotation
        });	

        //On Click
        $(".paging a").click(function() {	
            $active = $(this); //Activate the clicked paging
            //Reset Timer
            clearInterval(play); //Stop the rotation
            rotate(); //Trigger rotation immediately
            rotateSwitch(); // Resume rotation
            return false; //Prevent browser jump to link anchor
        });	
    });
</script>

