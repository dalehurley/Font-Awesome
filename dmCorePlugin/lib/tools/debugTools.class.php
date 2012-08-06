<?php
/**
 * Class debug tools
 */

class debugTools {
    /**
     * Affichage d'une fenetre d'info de deboggage simple
     * @param string $info the infos ('label' => 'info')
     * @param string $type debug or warning. In bootstrap V2 we can use error / success / info / error
     * @return string $return content div with info debug/warning
     *
     */
    public static function infoDebug($infos, $type = 'debug') {

        if (dmConfig::get('site_theme_version') == 'v1'){
            sfContext::getInstance()->getResponse()->addStylesheet('/dmCorePlugin/lib/symfony/debug.css');
            $return = '';
            if (sfConfig::get('sf_environment') == 'dev' || sfConfig::get('sf_environment') == 'less' || sfContext::getInstance()->getUser()->isSuperAdmin()) {
                $listInfos = "";
                
                foreach ($infos as $label => $info) {
                    $listInfos.= _tag('span.info', $label) . ' ' . _tag('span.value', $info) . "<br>";
                }
                $return.= _tag('div.' . $type, array(
                    'onClick' => '$(this).hide();'
                ) , $listInfos);
            }        
        } else {
            $return = '';
            if (sfConfig::get('sf_environment') == 'dev' || sfContext::getInstance()->getUser()->isSuperAdmin()) {
                // ajout du js alert
                sfContext::getInstance()->getResponse()->addJavascript('/theme/less/bootstrap/js/bootstrap-alert.js');

                $listInfos = "";
                
                // compatibility with theme_version V1
                switch ($type) {
                    case 'warning':
                        $type = 'warning';
                        break;

                    case 'debug':
                        $type = 'info';
                        break;

                    default:
                        break;
                }

                foreach ($infos as $label => $info) {
                    $listInfos.= _tag('div.alert.alert-'.$type,
                            '<a class="close" data-dismiss="alert" href="#">x</a>'.
                            _tag('h4.alert-heading', $label). 
                            $info
                        );
                }
                $return.= $listInfos."<script>
                    $(document).ready(function(){
                        $(\".alert\").alert();
                    });
                </script>";
            }        
        }
        return $return;
    }
}
