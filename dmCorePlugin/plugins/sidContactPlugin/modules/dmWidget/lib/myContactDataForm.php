<?php

/**
 * le formulaire de paramètrage du widget "form"
 * dmWidgetProjectForm est le père de :
 *              - dmWidgetFormForm (pour les form)
 *              - dmWidgetShowForm (pour les show)
 *              - dmWidgetListForm (pour les list)
 */
class myContactDataForm extends dmWidgetFormForm
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