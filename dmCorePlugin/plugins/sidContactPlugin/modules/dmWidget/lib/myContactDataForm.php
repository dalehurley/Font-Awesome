<?php

/**
 * le formulaire de paramètrage du widget "form"
 */
class myContactDataForm extends dmWidgetProjectForm
{
  public function configure()
  {
    
    parent::configure();

    $this->widgetSchema['contactForm']  = new sfWidgetFormDoctrineChoice(
    	array(	  
        'multiple' => false, 
    		'model' => 'sidContactForm',
        'add_empty' => ''
    	)
    );

    $this->validatorSchema['contactForm'] = new sfValidatorDoctrineChoice(
    	array(	
        'multiple' => true, 
    		'model' => 'sidContactForm', 
    		'required' => false
    	)
    );

  }
}