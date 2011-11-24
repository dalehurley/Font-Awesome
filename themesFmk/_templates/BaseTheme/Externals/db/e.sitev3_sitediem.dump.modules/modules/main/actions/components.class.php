<?php
/**
 * Main components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 * 
 */
class mainComponents extends myFrontModuleComponents
{
	

	public function executeHeader()
	{
		// Your code here
	}

	public function executeFooter()
	{
		// Your code here
	}

	public function executeLessDebug()
	{
		//insertion de la CSS du widget du theme courant
		use_stylesheet('/theme/css/widgetMainLessDebug.css');

		//on vérifie si on est en DEV
		$isDev = (sfConfig::get('sf_environment') == 'dev') ? true : false;

		//Gabarit de la page visible en environnement de dev
		$currentGabarit = sfContext::getInstance()->getPage()->get('gabarit');
		if($currentGabarit == 'default') {
			$currentGabarit = myUser::getLessParam('templateGabarit');
		}

		if($isDev){
			$html = _tag('div.debugTemplate',
						_tag('div.debugInfo',
							_tag('span.info.mainTemplate', 'mainTemplate : '._tag('span.value', myUser::getLessParam('mainTemplate'))).tag('br').
							_tag('span.info.version', 'templateVersion : '._tag('span.value', myUser::getLessParam('templateVersion'))).tag('br').
							_tag('span.info.date', 'templateDate : '._tag('span.value', myUser::getLessParam('templateDate'))).tag('br').
							_tag('span.info.gabarit', 'templateGabarit : '._tag('span.value', myUser::getLessParam('templateGabarit'))).tag('br').
							_tag('span.info.grid', 'templateGrid : '._tag('span.value', myUser::getLessParam('templateGrid'))).tag('br').
							_tag('span.info.copyright', 'templateCopyright : '._tag('span.value', myUser::getLessParam('templateCopyright'))).tag('br').
							_tag('span.info.author', 'templateAuthor : '._tag('span.value', myUser::getLessParam('templateAuthor'))).tag('br').
							_tag('span.info.currentGabarit', 'currentGabarit : '._tag('span.value', $currentGabarit)).tag('br').
							_tag('span.info.gridContainer', 'gridContainer : '._tag('span.value', myUser::getLessParam('gridContainer'))).tag('br').
							_tag('span.info.gridColWidth', 'gridColWidth : '._tag('span.value', myUser::getLessParam('gridColWidth'))).tag('br').
							_tag('span.info.gridGutter', 'gridGutter : '._tag('span.value', myUser::getLessParam('gridGutter'))).tag('br')
						)
					);
		}else{
			$html = '';
		}
		$this->html=$html;
	}
}