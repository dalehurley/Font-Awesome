<?php

class SrtbForm extends dmForm
{
	
	 public function setup()
	{

		$this->setWidgets(array(
	      'marge'    => new sfWidgetFormInputText(),
	      'chargevar'   => new sfWidgetFormInputText(),
	      'chargestruct'   => new sfWidgetFormInputText(),
	      'remuneration'   => new sfWidgetFormInputText(),
	      'ca'   => new sfWidgetFormInputText(),
	     	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'marge'    => 'Taux de marge brute (en % du CA HT)',
		  'chargevar'   => 'Charges variables (en % du CA HT)',
		  'chargestruct'   => 'Salaire brut mensuel (base 151,67 heures)',
		  'remuneration'   => 'R&eacute;mun&eacute;ration annuelle de l\'exploitant (en euros)(seulement pour les entreprises individuelles)',
		  'ca'   => 'Chiffre d\'Affaires annuel r&eacute;alis&eacute; actuellement',
		 
		));

		$this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'marge'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le taux de marge brute (en % du CA HT) est obligatoire.',
	      		'invalid'=>'Le taux de marge brute (en % du CA HT) être numérique',
		      )),

	      'chargevar'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Les charges variables (en % du CA HT) est obligatoire.',
	      		'invalid'=>'Les charges variables (en % du CA HT) doit etre numérique ',
		      )),
		   'chargestruct'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Les charges de structures annuelles (en euros HT) est obligatoire.',
	      		'invalid'=>'Les charges de structures annuelles (en euros HT) doit etre numérique ',
		      )),

		    'remuneration'    => new sfValidatorNumber(array('required' => false)),
		    'ca'    => new sfValidatorNumber(array('required' => false)),
		   
    )); 

	     parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}
}


