
<?php

class dmGoogleMapTag extends dmHtmlTag
{

  public function __construct(array $options = array())
  {
    $this->initialize($options);
  }

  public function initialize(array $options = array())
  {
    parent::initialize($options);


    $this
    ->addAttributeToRemove(array('splash'))
    ->addClass('dm_google_map')
    ->setOption('mapTypeId', 'hybrid')
    ->setOption('zoom', 14)
    ->setOption('splash', '');
  }

  public function getDefaultOptions()
  {
    return array_merge(parent::getDefaultOptions(), array(
      'address' => null,
      'center'  => null,
      'idCabinet'=> null
    ));
  }

  /*
   * Change the splash
   */
  public function splash($splash)
  {
    return $this->setOption('splash', (string) $splash);
  }

  public function address($location)
  {
    return $this->setOption('address', (string) $location);
  }

  public function markers(array $markers)
  {
    return $this->setOption('markers', $markers);
  }

  public function center($latitude, $longitude)
  {
    return $this->setOption('center', array($latitude, $longitude));
  }

  public function mapTypeId($mapType)
  {
    return $this->setOption('mapTypeId', (string) $mapType);
  }

  public function zoom($zoom)
  {
    return $this->setOption('zoom', (int) $zoom);
  }
  // rajout par stef
  public function idCabinet($idCabinet)
  {
    return $this->setOption('idCabinet', (int) $idCabinet);
  }
  // fin rajout 

  public function navigationControl($bool)
  {
    return $this->setOption('navigationControl', (bool) $bool);
  }

  public function mapTypeControl($bool)
  {
    return $this->setOption('mapTypeControl', (bool) $bool);
  }

  public function scaleControl($bool)
  {
    return $this->setOption('scaleControl', (bool) $bool);
  }

  public function render()
  {
    $preparedAttributes = $this->prepareAttributesForHtml($this->options);

    $splash = $preparedAttributes['splash'];
    unset($preparedAttributes['splash']);
	
	//récupération de l'adresse en base
	$adresseRequest = DmDb::table('SidCoordName')->findOneByIdAndIsActive($preparedAttributes['idCabinet'], true);
	
	//Ajout Arnaud : affichage de l'adresse
	
	//récupération du tiret
	$dash = _tag('span.dash', sfConfig::get('app_vars-partial_dash'));
	
	//html de sortie
	$html = '';
	
	//Si l'adresse à afficher est le siège social, alors on affiche le titreBloc
	//Sinon on n'affiche rien dans le titreBloc car normalement la première adresse est tjrs celle du siège social
	$isSiegeSocial = ($adresseRequest->siege_social == true);
	$titreBloc = sfContext::getInstance()->getI18N()->__('Map');
	if($isSiegeSocial) $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
	
	//composition des options du partial d'adresse
	$addressOpts = array(
					'name' => $adresseRequest->getTitle(),
					'addressLocality' => $adresseRequest->getVille(),
					'postalCode' => $adresseRequest->getCodePostal(),
					'faxNumber' => $adresseRequest->getFax(),
					'telephone' => $adresseRequest->getTel(),
					'container' => 'div'
				);
	
	$addressOpts['streetAddress'] = $adresseRequest->getAdresse();
	if ($adresseRequest->getAdresse2() != NULL) $addressOpts['streetAddress'].= $dash . $adresseRequest->getAdresse2();
	
	//insertion du partial d'organization
	$html.= get_partial('global/schema/Thing/Organization', $addressOpts);
	
	//insertion de la carte GoogleMap
    $html.= '<div'.$this->convertAttributesToHtml($preparedAttributes).'>'.$splash.'</div>';
	
    return $html;
  }

  protected function prepareAttributesForHtml(array $attributes)
  {
    return $this->jsonifyAttributes($attributes);
  }

  protected function jsonifyAttributes(array $attributes)
  {
    $jsonAttributes = array();

    foreach($this->getJsonAttributes() as $jsonAttribute)
    {
      $jsonAttributes[$jsonAttribute] = dmArray::get($attributes, $jsonAttribute);

      unset($attributes[$jsonAttribute]);
    }

    // ease unit tests
    ksort($jsonAttributes);

    $attributes['class'][] = json_encode($jsonAttributes);

    return $attributes;
  }

  protected function getJsonAttributes()
  {
    return array('address', 'center', 'mapTypeId', 'zoom', 'navigationControl', 'mapTypeControl', 'scaleControl', 'markers');
  }

  public function getJavascripts()
  {
    return array(
      'dmGoogleMapPlugin.dmGoogleMap',
      'dmGoogleMapPlugin.launcher'
    );
  }

  public function getStylesheets()
  {
    return array();
  }
}
