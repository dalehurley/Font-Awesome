<?php

/**
 * contactFieldAdmin admin form
 *
 * @package    sitediem
 * @subpackage contactFieldAdmin
 * @author     Your name here
 */
class SidContactFieldAdminForm extends BaseSidContactFieldForm
{
  public function configure()
  {
    parent::configure();

    $typeChoices = array(
    		'text' => 'text' ,
    		'textarea' => 'textarea' ,
    		'radio' => 'radio' ,
    		'checkbox' => 'checkbox' ,
    		'select' => 'select' 
    	);

   $this->widgetSchema['type'] = new sfWidgetFormChoice(array(
      'choices' => $typeChoices
    ));
    $this->validatorSchema['type'] = new sfValidatorChoice(array(
      'choices' => array_keys($typeChoices),
      'required' => true
    ));


  }
}