<?php

class HsForm extends dmForm
{
	
	 public function setup()
	{

		$this->setWidgets(array(
	      'heures'    => new sfWidgetFormInputText(),
	      'salaire'   => new sfWidgetFormInputText(),
	     	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'heures'    => 'Nombre d\'heures effectu&eacute;es dans le mois',
		  'salaire'   => 'Salaire brut mensuel (base 151,67 heures)',
		 
		));
		$this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'heures'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le nombre d\'heures effectu&eacute;es dans le mois est obligatoire.',
	      		'invalid'=>'Le nombre d\'heures effectu&eacute;es dans le mois doit être numérique',
		      )),

	      'salaire'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le salaire brut mensuel (base 151,67 heures) est obligatoire.',
	      		'invalid'=>'Le salaire brut mensuel (base 151,67 heures) doit etre numérique ',
		      )),
    )); 

	     parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}



}