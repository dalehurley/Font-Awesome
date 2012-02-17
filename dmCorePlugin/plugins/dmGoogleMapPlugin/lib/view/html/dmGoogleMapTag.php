
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
    $adresseRequest = DmDb::table('SidCoordName')->findOneByIdAndIsActive($preparedAttributes['idCabinet'],true);
	
    //Ajout Arnaud : correction affichage de l'adresse sans partial
	
	//récupération des différentes variables par défault
	$separator =  _tag('span.separator', sfConfig::get('app_vars-partial_separator'));
	$dash = _tag('span.dash', sfConfig::get('app_vars-partial_dash'));
	
	//html de sortie
	$html = '';
	
	//Si l'adresse à afficher est le siège social, alors on affiche le titreBloc
	//Sinon on n'affiche rien dans le titreBloc car normalement la première adresse est tjrs celle du siège social
	$isSiegeSocial = ($adresseRequest->siege_social == true);
	$titreBloc = sfContext::getInstance()->getI18N()->__('Map');
	//if($isSiegeSocial) $html.= get_partial('global/titleWidget', array('title' => $titreBloc));
	if($isSiegeSocial) $html.= _tag('h4.title', $titreBloc);
	
	//insertion de la carte GoogleMap
    $html.= '<div'.$this->convertAttributesToHtml($preparedAttributes).'>'.$splash.'</div>';
	
	/*
	//composition des options du partial d'adresse
	$addressOpts = array(
					'name' => $adresseRequest->getTitle(),
					'addressLocality' => $adresseRequest->getVille(),
					'postalCode' => $adresseRequest->getCodePostal(),
					'faxNumber' => $adresseRequest->getFax(),
					'telephone' => $adresseRequest->getTel(),
					'container' => 'div.mapAddress'
				);
	
	$addressOpts['streetAddress'] = $adresseRequest->getAdresse();
	if ($adresseRequest->getAdresse2() != NULL) $addressOpts['streetAddress'].= $dash . $adresseRequest->getAdresse2();
	
	//insertion du partial d'organization
	$html.= get_partial('global/schema/Thing/Organization', $addressOpts);
	*/
	
	//début de débug Arnaud sans partial
	//ouverture de la div contenant l'adresse
	$html.= _open('div.mapAddress.itemscope.Organization', array('itemtype' => 'http://schema.org/Organization', 'itemscope' => 'itemscope'));
		$html.= _tag('span.itemprop.name', array('itemprop' => 'name'), $adresseRequest->getTitle());
		
		//ouverture container de l'adresse
		$html.= _open('div.address.itemscope.PostalAddress', array('itemtype' => 'http://schema.org/PostalAddress', 'itemscope' => 'itemscope', 'itemprop' => 'address'));
			//composition de streetAddress
			$streetAddress = $adresseRequest->getAdresse();
			if ($adresseRequest->getAdresse2() != NULL) $$streetAddress.= $dash . $adresseRequest->getAdresse2();
			//insertion de l'adresse
			$html.= _tag('span.itemprop.streetAddress',
						_tag('span.type', array('title' => __('Street')), __('Street')) .
						$separator .
						_tag('span.value', array('itemprop' => 'streetAddress'), $streetAddress)
					);
			
			//ouverture du subwrapper pour le code postal et la ville
			$html.= _open('span.subWrapper');
				$html.= _tag('span.itemprop.postalCode',
						_tag('span.type', array('title' => __('Postal Code')), __('Postal Code')) .
						$separator .
						_tag('span.value', array('itemprop' => 'postalCode'), $adresseRequest->getCodePostal())
					);
				$html.= _tag('span.itemprop.addressLocality',
						_tag('span.type', array('title' => __('Locality')), __('Locality')) .
						$separator .
						_tag('span.value', array('itemprop' => 'addressLocality'), $adresseRequest->getVille())
					);
			$html.= _close('span.subWrapper');
		
		//fermeture container de l'adresse
		$html.= _close('div.address');
		
		//ajout du téléphone si non vide
		if ($adresseRequest->getTel() != NULL) $html.= _tag('span.itemprop.telephone',
															_tag('span.type', array('title' => __('Phone')), __('Phone')) .
															$separator .
															_tag('span.value', array('itemprop' => 'telephone'), $adresseRequest->getTel())
														);
		
		//ajout du fax si non vide
		if ($adresseRequest->getFax() != NULL) $html.= _tag('span.itemprop.faxNumber',
															_tag('span.type', array('title' => __('Fax')), __('Fax')) .
															$separator .
															_tag('span.value', array('itemprop' => 'faxNumber'), $adresseRequest->getFax())
														);
	
	//fermeture de la div contenant l'adresse
	$html.= _close('div.mapAddress');
	
	return $html;
	
	
	//ancien code brute de Stéphane
	/*
	// initialisation des variables
    $adresseCabinet = '';
    $titreBloc = '';
	
	$cabinet = '<div itemtype="http://schema.org/Organization" itemscope="itemscope" class="mapAddress itemscope Organization">';
	$cabinet.= '<span itemprop="name" class="itemprop name">'.$adresseRequest->getTitle().'</span>';
	
	$adresseCabinet = $adresseRequest->getAdresse();
    //vérification de adresse2
    ($adresseRequest->getAdresse2() != NULL) ? $adresseCabinet .='-'.$adresseRequest->getAdresse2() : $adresseCabinet .='';
    $cabinet .= '<div itemtype="http://schema.org/PostalAddress" itemscope="itemscope" class="address itemscope PostalAddress" itemprop="address"><span class="itemprop streetAddress"><span title="Rue" class="type">'.sfContext::getInstance()->getI18N()->__("Street").'</span><span class="separator"> : </span><span itemprop="streetAddress" class="value">'.$adresseCabinet.'</span></span>';
    $adresseCabinet .= ' - '.$adresseRequest->getCodePostal().' '.$adresseRequest->getVille();
    $cabinet .= '<span class="subWrapper"><span class="itemprop postalCode"><span class="type" title="Postal Code">'.sfContext::getInstance()->getI18N()->__("Postal Code").'</span><span class="separator">&nbsp;:&nbsp;</span><span class="value" itemprop="postalCode">'.$adresseRequest->getCodePostal().'</span>';
    $cabinet .= '<span class="itemprop addressLocality"><span title="Localité" class="type"> '.sfContext::getInstance()->getI18N()->__("Locality").'</span><span class="separator">&nbsp;:&nbsp;</span><span itemprop="addressLocality" class="value">'.$adresseRequest->getVille().'</span></span></span></div>';
    // vérif si tél existe
    ($adresseRequest->getTel() != NULL) ? $tel = '<p>Tél : '.$adresseRequest->getTel() : $tel = '';
    ($adresseRequest->getTel() != NULL) ? $cabinet .= '<span class="itemprop telephone"><span title="Téléphone" class="type">Téléphone</span><span class="separator"> : </span><span itemprop="telephone" class="value">'.$adresseRequest->getTel().'</span></span>' : $cabinet .= '';
    // vérif si fax existe
    ($adresseRequest->getFax() !=NULL) ? $fax = ' - Fax : '.$adresseRequest->getFax().'</p>' : $fax = '</p>';
    ($adresseRequest->getFax() !=NULL) ? $cabinet .= '<span class="itemprop faxNumber"><span class="type" title="Fax">Fax</span><span class="separator">&nbsp;:&nbsp;</span><span class="value" itemprop="faxNumber">'.$adresseRequest->getFax().'</span></span></div>' : $cabinet .= '';
    // si l'adresse à afficher est le siège social, alors on affiche le titreBloc, 
    //sinon on n'affiche rien dans le titreBloc car normalement la première adresse est tjrs celle du siège social
    ($adresseRequest->siege_social == true ) ? $titreBloc = '<h2 class="title">'.  sfContext::getInstance()->getI18N()->__('Map').'</h2>' : $titreBloc ='' ;
    // construction de la chaîne html
    

    //$tag = $titreBloc.'<div style="text-align: center"><p><b>'.$adresseRequest->getTitle().'</b>'.$cabinet.'<br />'.$adresseCabinet.'</p>'.$tel.$fax.'</div><div'.$this->convertAttributesToHtml($preparedAttributes).'>'.$splash.'</div>';
    $tag = $titreBloc.'<div'.$this->convertAttributesToHtml($preparedAttributes).'>'.$splash.'</div>'.$cabinet;
    
    return $tag;
	 * 
	 */
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
