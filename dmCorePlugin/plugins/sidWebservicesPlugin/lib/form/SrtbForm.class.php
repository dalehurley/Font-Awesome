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
		  'chargestruct'   => 'Les charges de structures annuelles (en % du CA HT)',
		  'remuneration'   => 'Rémunération annuelle de l\'exploitant (en euros)(seulement pour les entreprises individuelles)',
		  'ca'   => 'Chiffre d\'Affaires annuel réalisé actuellement',
		 
		));

		$this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'marge'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le taux de marge brute (en % du CA HT) est obligatoire.',
	      		'invalid'=>'Le taux de marge brute (en % du CA HT) être numérique',
		      )),

	      'chargevar'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Les charges variables (en % du CA HT) est obligatoire.',
	      		'invalid'=>'Les charges variables (en % du CA HT) doit être numérique (exemple: 12.5) ',
		      )),
		   'chargestruct'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Les charges de structures annuelles (en euros HT) est obligatoire.',
	      		'invalid'=>'Les charges de structures annuelles (en euros HT) doit être numérique (exemple: 12.5) ',
		      )),

		    'remuneration'    => new sfValidatorNumber(array('required' => false)),
		    'ca'    => new sfValidatorNumber(array('required' => false)),
		   
    )); 

	     parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}
}


