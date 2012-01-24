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
			$recupHashMd5 = (isset($parameters['hashMd5'])) ? $parameters['hashMd5'] : null;
			$recupSpriteFormat = (isset($parameters['spriteFormat'])) ? $parameters['spriteFormat'] : null;
			
			$spriteValues = spLessCss::spriteInit($recupHashMd5, $recupSpriteFormat);
			
			//assemblage des valeurs et encodage en JSON
			$jsonOutput = json_encode(array_merge($parameters, $spriteValues));
			
			//sortie de valeurs en AJAX
			return $this->renderText($jsonOutput);
		}
	}
}