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
    
    $map = $this->getService('google_map_helper')->map()
    ->address($adresseCabinet)
    // passage d'une valeur supplémentaire au fichier dmGoogleMapTag.php
    ->idCabinet($vars['address'])
    ->mapTypeId($vars['mapTypeId'])
    ->zoom($vars['zoom'])
    ->style(sprintf(
      'width: %s; height: %s;',
      dmArray::get($vars, 'width', '100%'),
      dmArray::get($vars, 'height', '300px')
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
