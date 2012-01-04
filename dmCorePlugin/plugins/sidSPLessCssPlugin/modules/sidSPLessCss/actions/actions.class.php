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
			
			//lancement des sprites avec récupération des paramètres passés dans la requête
			$spriteValues = spLessCss::spriteInit($parameters['spriteFormat'], $parameters['lessDefinitions']);
			
			//assemblage des valeurs et encodage en JSON
			$jsonOutput = json_encode(array_merge($parameters, $spriteValues));
			
			//sortie de valeurs en AJAX
			return $this->renderText($jsonOutput);
		}
	}
}