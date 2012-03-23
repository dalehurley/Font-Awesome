<?php

class VtForm extends dmForm
{
	
	 public function setup()
	{

		$this->setWidgets(array(
	      'departement'    => new sfWidgetFormInputText(),
	      
	     	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'departement'    => 'Le num&eacute;ro de votre d&eacute;partement',
		  
		 
		));
		$this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'departement'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le num&eacute;ro de votre d&eacute;partement est obligatoire.',
	      		'invalid'=>'Le num&eacute;ro de votre d&eacute;partement doit être',
		      )),

	     
    )); 

	     parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}



}