<?php

class FkForm extends dmForm
{
	protected static $listePuissance = array(3=>'3 CV et moins'
									,4=>'4 CV'
									,5=>'5 CV'
									,6=>'6 CV'
									,7=>'7 CV'
									,8=>'8 CV'
									,9=>'9 CV'
									,10=>'10 CV'
									,11=>'11 CV'
									,12=>'12 CV'
									,13=>'13 CV et plus');



	 public function setup()
	{

		$this->setWidgets(array(
	      'puissance' => new sfWidgetFormSelect(array('choices' => self::$listePuissance)),
	      'distance'   => new sfWidgetFormInputText(),
	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'puissance'    => 'P&eacute;riodicit&eacute;',
		  'distance'   => 'Distance parcourue',
		));

		$this->widgetSchema->setNameFormat('maform[%s]');

        $this->setValidators(array(
	      'puissance' => new sfValidatorChoice(array('choices' => array_keys(self::$listePuissance))),
	      'distance' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'La distance parcourue est obligatoire.',
	      		'invalid'=>'La distance parcourue  doit etre numérique',
		      )),
    )); 

        parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}



}