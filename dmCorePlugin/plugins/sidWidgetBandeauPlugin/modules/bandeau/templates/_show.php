<?php 
// Vars: $gererMonBandeau

use_javascript('/sidWidgetBandeauPlugin/js/jquery.marquee.js');
//<script type="text/javascript" src="/sidWidgetBandeauPlugin/js/jquery.marquee.js"></script> 

echo '
<script type="text/javascript">
    $(document).ready(function (){
        $(\'#marquee\').marquee();
    });
</script>';

$arrayMarquees = array();
//$attribut='';
if ($gererMonBandeau->getIsActive() == TRUE)
{
//    if($gererMonBandeau->getScrollamount() != 0) $arrayMarquees['scrollamount'] = 'scrollamount="'.$gererMonBandeau->getScrollamount().'"';
//    if($gererMonBandeau->getScrolldelay() != 0) $arrayMarquees['scrolldelay'] = 'scrolldelay="'.$gererMonBandeau->getScrolldelay().'"';
//    if($gererMonBandeau->getBoucle() != 0) $arrayMarquees['loop'] = 'loop="'.$gererMonBandeau->getBoucle().'"';
//    $arrayMarquees['behavior'] = 'behavior="'.$gererMonBandeau->getBehavior().'"';
//    $arrayMarquees['direction'] = 'direction="'.$gererMonBandeau->getDirection().'"';
////    $arrayMarquee['debutDate']=$gererMonBandeau->getDebutDate();
////    $arrayMarquee['finDate']=$gererMonBandeau->getFinDate();


//foreach($arrayMarquees as $arrayMarquee)
//{
//    $attribut =$attribut.$arrayMarquee.' ';
//}

echo _tag('div#marquee '.$attribut.'',$gererMonBandeau);
} else {
    // on affiche un message que pour l'environnement de dev
    if (sfConfig::get('sf_environment') == 'dev') {
    echo _tag(
            'div.debug', array('onClick' => '$(this).hide();'), 'Le bandeau est inactif.<br/>Soit manuellement, soit parce qu\'il est en dehors des dates d\'affichage.'
    );
}
}
/*
 * scrollamount : vitesse de défilement -1(lent) 10(normal) 40(rapide) - integer
 * scrolldelay : intervalle entre 2 apparition du texte - integer (Truespeed = options pour vitesse )
 * loop : nbre d'aller-retour - integer
 * behavior : slide(de gauche à droite) | scroll(de bas en haut) | alternate
 * direction : left | right | down | up
 * height : hauteur de ligne - integer
 * width : longueur de ligne - integer
 *
 */

