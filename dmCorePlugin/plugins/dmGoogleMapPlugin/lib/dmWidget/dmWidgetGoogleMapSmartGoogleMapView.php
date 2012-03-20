<?php

class dmWidgetGoogleMapSmartGoogleMapView extends dmWidgetPluginView
{

  public function configure()
  {
    parent::configure();
    
    $this->addRequiredVar(array(
      'titreBloc',
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
    $titreBloc = ($vars['titreBloc'] == NULL || $vars['titreBloc'] == ' ') ? $dmPage->getName() : $vars['titreBloc'];
    // requète pour construire l'adresse du cabinet
    $adresses = Doctrine_Query::create()->from('SidCoordName a')
          ->where('a.is_active = ?', true)
          ->orderBy('a.siege_social DESC')
          ->execute();
    return $this->getHelper()->renderPartial('dmWidgetGoogleMap', 'smartGoogleMap', array(
                    'adresses' => $adresses,
                    'titreBloc' => $titreBloc,
                ));
  }
  
//  protected function doRenderForIndex()
//  {
//    $vars = $this->getViewVars();
//    return $vars['address'];
//  }

}
