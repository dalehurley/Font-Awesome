<?php
/**
 * Framework SPLessCss components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class sidSPLessCssComponents extends myFrontModuleComponents {
	
	public function executeDebug() {
		//insertion de la CSS du widget du theme courant
		$this->getResponse()->addStylesheet(sfConfig::get('sf_css_path_template').'/Widgets/SidSPLessCssDebug/SidSPLessCssDebug.css');
		
        //Gabarit de la page visible en environnement de dev
        $currentGabarit = sfContext::getInstance()->getPage()->get('gabarit');
        if ($currentGabarit == 'default') {
            $currentGabarit = spLessCss::getLessParam('templateGabarit');
        }
		
		$this->html = _tag('div.debugTemplate',
						_tag('div.debugInfo',
							_tag('span.info.mainTemplate', 'mainTemplate : ' . _tag('span.value', spLessCss::getLessParam('mainTemplate'))) . tag('br') .
							_tag('span.info.version', 'templateVersion : ' . _tag('span.value', spLessCss::getLessParam('templateVersion'))) . tag('br') .
							_tag('span.info.date', 'templateDate : ' . _tag('span.value', spLessCss::getLessParam('templateDate'))) . tag('br') .
							_tag('span.info.gabarit', 'templateGabarit : ' . _tag('span.value', spLessCss::getLessParam('templateGabarit'))) . tag('br') .
							_tag('span.info.grid', 'templateGrid : ' . _tag('span.value', spLessCss::getLessParam('templateGrid'))) . tag('br') .
							_tag('span.info.copyright', 'templateCopyright : ' . _tag('span.value', spLessCss::getLessParam('templateCopyright'))) . tag('br') .
							_tag('span.info.author', 'templateAuthor : ' . _tag('span.value', spLessCss::getLessParam('templateAuthor'))) . tag('br') .
							_tag('span.info.currentGabarit', 'currentGabarit : ' . _tag('span.value', $currentGabarit)) . tag('br') .
							_tag('span.info.gridContainer', 'gridContainer : ' . _tag('span.value', spLessCss::getLessParam('gridContainer'))) . tag('br') .
							_tag('span.info.gridColWidth', 'gridColWidth : ' . _tag('span.value', spLessCss::getLessParam('gridColWidth'))) . tag('br') .
							_tag('span.info.gridGutter', 'gridGutter : ' . _tag('span.value', spLessCss::getLessParam('gridGutter'))) . tag('br') .
							_tag('span.info.screenType', 'screenType : ' . _tag('span.value')) . tag('br')
							)
						);
	}
}