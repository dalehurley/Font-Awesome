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
      'height',
      'titreBloc',
      'withResume',
      'length'
    ));
  }
  public function getStylesheets() {
	//on créé un nouveau tableau car c'est un nouveau widget (si c'est une extension utiliser $stylesheets = parent::getStylesheets();)
	$stylesheets = parent::getStylesheets();

	//lien vers le js associé au menu
	$cssLink = '/theme/css/_templates/'.dmConfig::get('site_theme').'/Widgets/GoogleMapShow/GoogleMapShow.css';
	//chargement de la CSS si existante
	if (is_file(sfConfig::get('sf_web_dir') . $cssLink)) $stylesheets[] = $cssLink;

	return $stylesheets;
  }
  protected function doRender()
  {
    $vars = $this->getViewVars();
    $dmPage = $dmPage = sfContext::getInstance()->getPage();
    if($vars['address'] == '' || $vars['address'] == ' '){
    // requète pour construire l'adresse du cabinet
    $adresseRequest = Doctrine_Query::create()->from('SidCoordName a')
          ->where('a.id = ?', $dmPage->record_id )
          ->fetchOne();
    $adresseCabinet = $adresseRequest->getAdresse();
    if($adresseRequest->getAdresse2() != NULL) {$adresseCabinet .='-'.$adresseRequest->getAdresse2();};
    $adresseCabinet .= '-'.$adresseRequest->getCodePostal().' '.$adresseRequest->getVille();
    }
    else $adresseCabinet = $vars['address'];
    
    $map = $this->getService('google_map_helper')
        ->map()
        ->address($adresseCabinet)
        // passage d'une valeur supplémentaire au fichier dmGoogleMapTag.php
        ->idCabinet($dmPage->record_id)
        ->titreBloc($vars['titreBloc'])
        ->length($vars['length'])
        ->withResume($vars['withResume'])
        ->mapTypeId($vars['mapTypeId'])
        ->zoom($vars['zoom'])
        ->style(sprintf(
          'width: %s; height: %s;',
          dmArray::get($vars, 'width', '350px'),
          dmArray::get($vars, 'height', '250px')
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
  
//  protected function doRenderForIndex()
//  {
//    $vars = $this->getViewVars();
//    return $vars['address'];
//  }

}
