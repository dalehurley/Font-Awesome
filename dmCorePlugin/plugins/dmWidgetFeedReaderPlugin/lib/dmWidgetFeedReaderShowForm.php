<?php

class dmWidgetFeedReaderShowForm extends dmWidgetPluginForm
{
  public function configure()
  {
      parent::configure();
      
    $this->widgetSchema['url'] = new sfWidgetFormInputText();
    $this->validatorSchema['url'] = new dmValidatorLinkUrl(array(
      'required' => true
    ));

// modfi stef   $this->widgetSchema['nb_items'] = new sfWidgetFormInputText();
    $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
      'min' => 0,
      'max' => 100
    ));

    $this->widgetSchema['life_time'] = new sfWidgetFormInputText();
    $this->validatorSchema['life_time'] = new sfValidatorInteger(array(
      'min' => 0
    ));
    
    // ajout stef le 27-09-2011
    $this->widgetSchema['title'] = new sfWidgetFormInputText();
    $this->validatorSchema['title'] = new sfValidatorString(array('required' => false));
    
    $this->widgetSchema['logo_les_echos'] = new sfWidgetFormInputCheckbox(array('default'=> true, 'label' => 'Affiche le logo des Echos dans le bloc du titre'));
    $this->validatorSchema['logo_les_echos']  = new sfValidatorBoolean();
    
    // fin ajout stef le 27-09-2011
    
    $this->widgetSchema->setHelp('life_time', 'Cache life time in seconds');

    if(!$this->getDefault('nbArticles'))
    {
      $this->setDefault('nbArticles', 10);
    }

    if(!$this->getDefault('life_time'))
    {
      $this->setDefault('life_time', 86400);
    }
    
    
  }
// ajout stef le 27-02/2012 
  protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('dmWidget', 'dmWidgetFeedReaderShowForm', array(
            'form' => $this,
            'id' => 'dm_widget_feed_reader_' . $this->dmWidget->get('id')
        ));
    }
// ajout stef le 27-02/2012 
}