<?php
/**
 * Class debug tools
 */
class debugTools {
    /**
     * Affichage d'une fenetre d'info de deboggage simple
     * @param string $info the infos ('label' => 'info')
     * @param string $type debug or warning
     * @return string $return content div with info debug/warning
     *
     */
    public static function infoDebug($infos, $type = 'debug') {
        $return = '';
        if (sfConfig::get('sf_environment') == 'dev') {
            $listInfos = "";
            
            foreach ($infos as $label => $info) {
                $listInfos.= _tag('span.info', $label) . ' ' . _tag('span.value', $info) . "<br>";
            }
        }
        $return.= _tag('div.' . $type, array(
            'onClick' => '$(this).hide();'
        ) , $listInfos);
        
        return $return;
    }
}
