<?php

class dmWidgetGoogleMapShowView extends dmWidgetPluginView
{

  public function configure()
  {
    parent::configure();
    
    $this->addRequiredVar(array(
      'address',
      'mapTypeId',
      'zoom',
      'navigationControl',
      'mapTypeControl',
      'scaleControl',
      'width',
      'height'
    ));
  }
  
  
  protected function filterViewVars(array $vars = array()) {
	  $vars = parent::filterViewVars($vars);
	  
	  //Ajout Arnaud
	  //composition du paramètres de miniature sélectionné et par défaut
	  $thumbTypeWidth = dmArray::get($vars, 'media_area', 'thumbContent', true) . '_col';
	  $thumbTypeHeight = dmArray::get($vars, 'media_area', 'thumbContent', true) . '_bl';
	  //récupération des paramètres depuis le framework
	  $thumbTypeWidth = sidSPLessCss::getLessParam($thumbTypeWidth);
	  $thumbTypeHeight = sidSPLessCss::getLessParam($thumbTypeHeight);
	  //ajout des variables au widget
	  $vars['width'] = spLessCss::gridGetWidth($thumbTypeWidth);
	  $vars['height'] = spLessCss::gridGetHeight($thumbTypeHeight);
	  
	  return $vars;
  }

  protected function doRender()
  {
    $vars = $this->getViewVars();
	// requète pour construire l'adresse du cabinet
    $adresseRequest = Doctrine_Query::create()->from('SidCoordName a')
          ->where('a.id = ?', $vars['address'] )
          ->fetchOne();
	
	//composition de l'adresse pour Google Map
	$adresseCabinet = $adresseRequest->getAdresse();
    if($adresseRequest->getAdresse2() != NULL) $adresseCabinet.= '-' . $adresseRequest->getAdresse2();
    $adresseCabinet.= '-' . $adresseRequest->getCodePostal() . ' ' . $adresseRequest->getVille();
	
	//récupération de valeurs par défaut pour la taille du widget
	$defaultWidth = spLessCss::gridGetWidth(sidSPLessCss::getLessParam('thumbX_col'));
	$defaultHeight = spLessCss::gridGetHeight(sidSPLessCss::getLessParam('thumbX_bl'));
    
	//composition de la googleMap
    $map = $this->getService('google_map_helper')->map()
    ->address($adresseCabinet)
    // passage d'une valeur supplémentaire au fichier dmGoogleMapTag.php
    ->idCabinet($vars['address'])
    ->mapTypeId($vars['mapTypeId'])
    ->zoom($vars['zoom'])
    ->style(sprintf(
      'width: %spx; height: %spx;',
      dmArray::get($vars, 'width', $defaultWidth),
      dmArray::get($vars, 'height', $defaultHeight)
    ))
    ->navigationControl($vars['navigationControl'])
    ->mapTypeControl($vars['mapTypeControl'])
    ->scaleControl($vars['scaleControl'])
    ->splash($vars['splash']);
	
    $this
    ->addJavascript($map->getJavascripts())
    ->addStylesheet($map->getStylesheets());

    return $map;
  }
  
  protected function doRenderForIndex()
  {
    $vars = $this->getViewVars();
    return $vars['address'];
  }

}
