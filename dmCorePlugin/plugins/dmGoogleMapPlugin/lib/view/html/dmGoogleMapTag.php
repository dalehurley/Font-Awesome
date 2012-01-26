
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

  public function idCabinet($idCabinet)
  {
    return $this->setOption('idCabinet', (int) $idCabinet);
  }

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
    $adresseCabinet = '';
    $titreBloc ='';
    //$adresseCabinet est l'adresse du cabinet récupéré en base

    $adresseRequest = DmDb::table('SidCoordName')->findOneByIdAndIsActive($preparedAttributes['idCabinet'],true);
    $adresseCabinet = $adresseRequest->getAdresse();

    ($adresseRequest->getAdresse2() != NULL) ? $adresseCabinet .='-'.$adresseRequest->getAdresse2() : $adresseCabinet .='';

    $adresseCabinet .= ' - '.$adresseRequest->getCodePostal().' '.$adresseRequest->getVille();

    ($adresseRequest->getTel() != NULL) ? $tel = '<p>Tél : '.$adresseRequest->getTel() : $tel = '';
    ($adresseRequest->getFax() !=NULL) ? $fax = ' - Fax : '.$adresseRequest->getFax().'</p>' : $fax = '</p>';

    ($adresseRequest->siege_social == true ) ? $titreBloc = '<h2 class="title">'.__('Map').'</h2>' : $titreBloc ='' ;

    $tag = $titreBloc.'<div style="text-align: center"><p><b>'.$adresseRequest->getTitle().'</b><br />'.$adresseCabinet.'</p>'.$tel.$fax.'</div><div'.$this->convertAttributesToHtml($preparedAttributes).'>'.$splash.'</div>';

    return $tag;
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
