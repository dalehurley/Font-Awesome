<?php
/**
 * Framework SPLessCss components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class sidSPLessCssComponents extends myFrontModuleComponents {
	
	public function executeDebug() {
		//insertion de la CSS du widget du theme courant
		$this->getResponse()->addStylesheet(sidSPLessCss::getCssPathTemplate().'/Widgets/SidSPLessCssDebug/SidSPLessCssDebug.css');
		
        // affichage de la page courante
        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
        $pageCurrent =  $dmPage->module.'/'.$dmPage->action.' - '.$dmPage->record_id;
        // récupération du Layout de la page en cours
        $layoutPage = sfContext::getInstance()->getPage()->getPageView()->get('layout');
		
		//stockage des paramètres à afficher
		$paramSpLessCss = array(
							array(
								'info'	=>	'mainTemplate',
								'value' =>	sidSPLessCss::getLessParam('mainTemplate')
							),
							array(
								'info'	=>	'gridColWidth',
								'value' =>	'...' // a afficher à partir du css généré avec :after
							),
							array(
								'info'	=>	'gridGutter',
								'value' =>	'...' // a afficher à partir du css généré avec :after
							),
							array(
								'info'	=>	'pageCurrent',
								'value'	=>	$pageCurrent
							),
							array(
								'info'	=>	'pageLayout',
								'value'	=>	$layoutPage
							)                    
						);
		
		//déclaration des variables se remplissant avec les valeurs et propriétés à afficher
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
		$this->html.= _close('div.debugInfo');
		
		$this->html.= _close('div.debugTemplate');
	}
}