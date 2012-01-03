<?php
/**
 * Framework SPLessCss actions
 */
class sidSPLessCssActions extends myFrontModuleActions
{
	public function executeSpriteInit(sfWebRequest $request) {
		
		//on vérifie que l'appel est bien fait en AJAX
		if($request->isXmlHttpRequest()) {
			
			//on récupère les valeurs passées dans la requête
			$parameters = $request->getGetParameters();
			
			//lancement de l'initialisation des sprites et récupération des valeurs d'avancement
			$spriteValues = json_encode(spLessCss::spriteInit($spriteFormat = "S"));
			
			//$testJson = json_encode(array('prct' => 50, 'testResult' => 'Message de fin de test JSON'));
			
			return $this->renderText($spriteValues);
			//return $this->renderText('Génération AJAX terminée');
		}
	}
}