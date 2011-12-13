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
		
		//stockage des paramètres à afficher
		$paramSpLessCss = array(
							array(
								'info'	=>	'mainTemplate',
								'value' =>	spLessCss::getLessParam('mainTemplate')
							),
							array(
								'info'	=>	'templateVersion',
								'value' =>	spLessCss::getLessParam('templateVersion')
							),
							array(
								'info'	=>	'templateDate',
								'value' =>	spLessCss::getLessParam('templateDate')
							),
							array(
								'info'	=>	'templateGabarit',
								'value' =>	spLessCss::getLessParam('templateGabarit')
							),
							array(
								'info'	=>	'templateGrid',
								'value' =>	spLessCss::getLessParam('templateGrid')
							),
							array(
								'info'	=>	'templateCopyright',
								'value' =>	spLessCss::getLessParam('templateCopyright')
							),
							array(
								'info'	=>	'templateAuthor',
								'value' =>	spLessCss::getLessParam('templateAuthor')
							),
							array(
								'info'	=>	'currentGabarit',
								'value' =>	$currentGabarit
							),
							array(
								'info'	=>	'gridContainer',
								'value' =>	spLessCss::getLessParam('gridContainer')
							),
							array(
								'info'	=>	'gridColWidth',
								'value' =>	spLessCss::getLessParam('gridColWidth')
							),
							array(
								'info'	=>	'gridGutter',
								'value' =>	spLessCss::getLessParam('gridGutter')
							),
							array(
								'info'	=>	'screenType'
							)
						);
		
		
		$debugParam = array();
		$debugDisplay = '';
		
		//on parcourt le tableau associatif
		foreach ($paramSpLessCss as $value) {
			//rajout des valeurs au JSON utilisé en sortie
			if(isset($value['value'])) {
				$debugParam[$value['info']] = $value['value'];
			}
			
			//rajout des valeurs à la variable d'affichage
			$debugDisplay.= _tag('span.info.'.$value['info'], $value['info'].' : ' . _tag('span.value', (isset($value['value']) ? $value['value'] : ''))) . _tag('br');
		}
		
		$this->html = _open('div.debugTemplate', array('json' => $debugParam));
		$this->html.= _open('div.debugInfo');
		
		$this->html.= $debugDisplay;
		
		/*,
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
							);*/
		
		$this->html.= _close('div.debugInfo');
		$this->html.= _close('div.debugTemplate');
		/*
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
						);*/
	}
}