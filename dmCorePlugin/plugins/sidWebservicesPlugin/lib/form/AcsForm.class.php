<?php

class AcsForm extends dmForm
{
	public function setup()
	{

		$this->setWidgets(array(
	      'nbjours'    => new sfWidgetFormInputText(),
	         	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'nbjours'    => 'Entrez le nombre de jours d\'absence du salari&eacute;',
		  		 
		));
        $this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'nbjours'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le nombre de jours d\'absence du salari&eacute; est obligatoire.',
	      		'invalid'=>'Le nombre de jours d\'absence du salari&eacute; doit être numérique',
		      )),

	   
    )); 

	parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}



}