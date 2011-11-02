<?php

/**
 * Main components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class mainComponents extends myFrontModuleComponents {

    public function executeHeader() {
        // Your code here
    }

    public function executeFooter() {
        // Your code here
    }

    public function executeLessDebug() {
        //insertion de la CSS du widget du theme courant
        use_stylesheet('/theme/css/widgetMainLessDebug.css');

        //Gabarit de la page visible en environnement de dev
        $currentGabarit = sfContext::getInstance()->getPage()->get('gabarit');
        if ($currentGabarit == 'default') {
            $currentGabarit = myUser::getLessParam('templateGabarit');
        }

        $html = _tag('div.debugTemplate', _tag('div.debugInfo', _tag('span.info.mainTemplate', 'mainTemplate : ' . _tag('span.value', myUser::getLessParam('mainTemplate'))) . tag('br') .
                        _tag('span.type.version', 'templateVersion : ' . _tag('span.value', myUser::getLessParam('templateVersion'))) . tag('br') .
                        _tag('span.type.date', 'templateDate : ' . _tag('span.value', myUser::getLessParam('templateDate'))) . tag('br') .
                        _tag('span.type.gabarit', 'templateGabarit : ' . _tag('span.value', myUser::getLessParam('templateGabarit'))) . tag('br') .
                        _tag('span.type.grid', 'templateGrid : ' . _tag('span.value', myUser::getLessParam('templateGrid'))) . tag('br') .
                        _tag('span.type.copyright', 'templateCopyright : ' . _tag('span.value', myUser::getLessParam('templateCopyright'))) . tag('br') .
                        _tag('span.type.author', 'templateAuthor : ' . _tag('span.value', myUser::getLessParam('templateAuthor'))) . tag('br') .
                        _tag('span.type.currentGabarit', 'currentGabarit : ' . _tag('span.value', $currentGabarit)) . tag('br') .
                        _tag('span.type.gridContainer', 'gridContainer : ' . _tag('span.value', myUser::getLessParam('gridContainer'))) . tag('br') .
                        _tag('span.type.gridColWidth', 'gridColWidth : ' . _tag('span.value', myUser::getLessParam('gridColWidth'))) . tag('br') .
                        _tag('span.type.gridGutter', 'gridGutter : ' . _tag('span.value', myUser::getLessParam('gridGutter'))) . tag('br') .
                        _tag('span.type.screenType', 'screenType : ' . _tag('span.value')) . tag('br')
                )
        );

        // ajout jquery control
        $html .= '
        <script type="text/javascript"> 
            $(document).ready(function($){
                $(\'.main_less_debug\').click(function(){
                    $(this).toggle();
                });
            });
        </script>';
        
        $this->html = $html;
    }

}