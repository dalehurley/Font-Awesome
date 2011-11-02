<?php //var = $bandeauId - $parent
if(isset($bandeauId))
{
echo dm_get_widget('bandeau','show', array('recordId' => $bandeauId));
}
//else if (sfConfig::get('sf_environment') == 'dev') {
//    if (!isset($parent)){$texte = 'et le parent "'.$parent.'" ne possède pas de bandeau non plus';} else $texte = ' et il n\'y a pas de bandeau actif dans la page parent';
//    echo _tag(
//            'div.debug', array('onClick' => '$(this).hide();'), 'Le bandeau pour la page "'.$nomPage.'" est inactif '.$texte
//    );
//}