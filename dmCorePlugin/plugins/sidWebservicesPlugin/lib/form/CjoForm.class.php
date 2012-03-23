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
		  'nbjours1'    => 'Jours ouvrables (30 jours maxi)',
		  'nbjours2'   => 'Jours ouvr&eacute;s (25 jours maxi)',
		 
		));

	     $this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'nbjours1'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le Jours ouvrables est obligatoire.',
	      		'invalid'=>'Le Jours ouvrables doit être numérique et 30 jours maxi',
		      )),

	      'nbjours2'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant des remboursements est obligatoire.',
	      		'invalid'=>'Le Jours ouvr&eacute;s doit etre numérique et 25 jours maxi',
		      )),
    )); 

	     parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}



}