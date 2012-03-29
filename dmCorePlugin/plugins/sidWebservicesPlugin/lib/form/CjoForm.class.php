<?php

class cjoForm extends dmForm
{
	
	 public function setup()
	{

		$this->setWidgets(array(
	      'nbjours1'    => new sfWidgetFormInputText(),
	      'nbjours2'   => new sfWidgetFormInputText(),
	     	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'nbjours1'    => 'Nombre de jours ouvrables (30 jours maxi)',
		  'nbjours2'   => 'Nombre de jours ouvrés (25 jours maxi)',
		 
		));

	     $this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'nbjours1'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le nombre de jours ouvrables est obligatoire.',
	      		'invalid'=>'Le nombre de jours ouvrables doit être numérique et 30 jours maxi',
		      )),

	      'nbjours2'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le nombre de jours ouvrés est obligatoire.',
	      		'invalid'=>'Le nombre de jours ouvrés doit être numérique (exemple: 12.5) et 25 jours maxi',
		      )),
    )); 

	     parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}



}