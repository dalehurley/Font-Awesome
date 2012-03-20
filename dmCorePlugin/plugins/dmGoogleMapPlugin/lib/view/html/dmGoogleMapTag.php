
<?php

class dmGoogleMapTag extends dmHtmlTag {
    public function __construct(array $options = array()) {
        $this->initialize($options);
    }
    public function initialize(array $options = array()) {
        parent::initialize($options);
        $this->addAttributeToRemove(array(
            'splash'
        ))->addClass('dm_google_map')->setOption('mapTypeId', 'hybrid')->setOption('zoom', 14)->setOption('splash', '');
    }
    public function getDefaultOptions() {
        
        return array_merge(parent::getDefaultOptions() , array(
            'address' => null,
            'center' => null,
            'idCabinet' => null,
            'length' => null,
            'withResume' => null
        ));
    }
    /*
     * Change the splash
    */
    public function splash($splash) {
        
        return $this->setOption('splash', (string)$splash);
    }
    public function address($location) {
        
        return $this->setOption('address', (string)$location);
    }
    public function markers(array $markers) {
        
        return $this->setOption('markers', $markers);
    }
    public function center($latitude, $longitude) {
        
        return $this->setOption('center', array(
            $latitude,
            $longitude
        ));
    }
    public function mapTypeId($mapType) {
        
        return $this->setOption('mapTypeId', (string)$mapType);
    }
    public function zoom($zoom) {
        
        return $this->setOption('zoom', (int)$zoom);
    }
    // rajout par stef
    public function idCabinet($idCabinet) {
        
        return $this->setOption('idCabinet', (int)$idCabinet);
    }
    public function titreBloc($titreBloc) {
        
        return $this->setOption('titreBloc', (string)$titreBloc);
    }
    public function length($length) {
        
        return $this->setOption('length', (int)$length);
    }
    public function withResume($withResume) {
        
        return $this->setOption('withResume', (bool)$withResume);
    }
    // fin rajout
    public function navigationControl($bool) {
        
        return $this->setOption('navigationControl', (bool)$bool);
    }
    public function mapTypeControl($bool) {
        
        return $this->setOption('mapTypeControl', (bool)$bool);
    }
    public function scaleControl($bool) {
        
        return $this->setOption('scaleControl', (bool)$bool);
    }
    public function render() {
        
      $preparedAttributes = $this->prepareAttributesForHtml($this->options);

      $splash = $preparedAttributes['splash'];
      unset($preparedAttributes['splash']);
      // initialisation des variables
      $adresseCabinet = '';
      $titreBloc ='';
      $cabinet='';
      //$adresseCabinet est l'adresse du cabinet récupéré en base

      $adresseRequest = DmDb::table('SidCoordName')->findOneByIdAndIsActive($preparedAttributes['idCabinet'],true);
      if(is_object($adresseRequest)){
//      $addressOpts = array(
//            'name' => $adresseRequest->getTitle(),
//            'addressLocality' => $adresseRequest->getVille(),
//            'postalCode' => $adresseRequest->getCodePostal(),
//            'faxNumber' => $adresseRequest->getFax(),
//            'telephone' => $adresseRequest->getTel(),
//            'container' => 'div.mapAddress'
//          );
    
      //insertion du partial d'organization
      $cabinet .= '<div xmlns="http://www.w3.org/1999/xhtml" itemtype="http://schema.org/Organization" itemscope="itemscope" class="mapAddress itemscope Organization">
                      <span itemprop="name" class="itemprop name">'.$adresseRequest->getTitle().'</span>';
      // fabrication de l'adresse
      $adresseCabinet = $adresseRequest->getAdresse();
      //vérification de adresse2
      ($adresseRequest->getAdresse2() != NULL) ? $adresseCabinet .='-'.$adresseRequest->getAdresse2() : $adresseCabinet .='';
      // fin de la fabrication de l'adresse
      $cabinet .= '   <div itemtype="http://schema.org/PostalAddress" itemscope="itemscope" class="address itemscope PostalAddress" itemprop="address">
                          <span class="itemprop streetAddress">
                              <span title="Rue" class="type">'.sfContext::getInstance()->getI18N()->__("Street").'</span>
                              <span class="separator">&nbsp;:&nbsp;</span>
                              <span itemprop="streetAddress" class="value">'.$adresseCabinet.'</span>
                          </span>';
      //$adresseCabinet .= ' - '.$adresseRequest->getCodePostal().' '.$adresseRequest->getVille();
      $cabinet .= '       <span class="subWrapper">
                              <span class="itemprop postalCode">
                                  <span class="type" title="Postal Code">'.sfContext::getInstance()->getI18N()->__("Postal Code").'</span>
                                  <span class="separator">&nbsp;:&nbsp;</span>
                                  <span class="value" itemprop="postalCode">'.$adresseRequest->getCodePostal().'</span>
                              </span>';
      $cabinet .= '           <span class="itemprop addressLocality">
                                  <span title="Localité" class="type"> '.sfContext::getInstance()->getI18N()->__("Locality").'</span>
                                  <span class="separator">&nbsp;:&nbsp;</span>
                                  <span itemprop="addressLocality" class="value">'.$adresseRequest->getVille().'</span>
                              </span>
                          </span>
                      </div>';
      // vérif si tél existe
      ($adresseRequest->getTel() != NULL) ? $cabinet .= '<span class="itemprop telephone">
                                                              <span title="Téléphone" class="type">Téléphone</span>
                                                              <span class="separator">&nbsp;:&nbsp;</span>
                                                              <span itemprop="telephone" class="value">'.$adresseRequest->getTel().'</span>
                                                          </span>' : $cabinet .= '';
      // vérif si fax existe
      ($adresseRequest->getFax() !=NULL) ? $cabinet .= '<span class="itemprop faxNumber">
                                                          <span class="type" title="Fax">Fax</span>
                                                          <span class="separator">&nbsp;:&nbsp;</span>
                                                          <span class="value" itemprop="faxNumber">'.$adresseRequest->getFax().'</span>
                                                      </span>' : $cabinet .= '';
      
      $cabinet .= '</div>'; 
      }
      ($preparedAttributes['titreBloc'] == '' ) ? $titreBloc =   sfContext::getInstance()->getI18N()->__('Map') : $titreBloc = $preparedAttributes['titreBloc'] ;
      if($preparedAttributes['withResume'] == TRUE && $adresseRequest->getResumeTown() != NULL){
         
          $length = ($preparedAttributes['length'] == 0) ? '': $preparedAttributes['length'];
          $resumeTown = '<div>'. stringTools::str_truncate($adresseRequest->getResumeTown(), $length, '', true, true).'</div>';
          }
      else $resumeTown='';
      // construction de la chaîne html
      
      $tag = '<h2 class="title">'.$titreBloc.'</h2>'.$resumeTown.'<div'.$this->convertAttributesToHtml($preparedAttributes).'>'.$splash.'</div>'.$cabinet;
      
      return $tag;
    }
    protected function prepareAttributesForHtml(array $attributes) {
        
        return $this->jsonifyAttributes($attributes);
    }
    protected function jsonifyAttributes(array $attributes) {
        $jsonAttributes = array();
        
        foreach ($this->getJsonAttributes() as $jsonAttribute) {
            $jsonAttributes[$jsonAttribute] = dmArray::get($attributes, $jsonAttribute);
            unset($attributes[$jsonAttribute]);
        }
        // ease unit tests
        ksort($jsonAttributes);
        $attributes['class'][] = json_encode($jsonAttributes);
        
        return $attributes;
    }
    protected function getJsonAttributes() {
        
        return array(
            'address',
            'center',
            'mapTypeId',
            'zoom',
            'navigationControl',
            'mapTypeControl',
            'scaleControl',
            'markers'
        );
    }
    public function getJavascripts() {
        
        return array(
            'dmGoogleMapPlugin.dmGoogleMap',
            'dmGoogleMapPlugin.launcher'
        );
    }
    public function getStylesheets() {
        
        return array();
    }
}
