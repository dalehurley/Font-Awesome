<?php

class TrcForm extends dmForm
{
	protected static $listePeriodicite = array(12=>'Mois',1=>'Ans', 4=>'Trimestre', 2=>'Semestres');
	protected static $listeVersement = array(1=>'Versements fin', 0=>'Début de péiode');

	 public function setup()
	{

		$this->setWidgets(array(
	      'capital'    => new sfWidgetFormInputText(),
	      'duree'   => new sfWidgetFormInputText(),
	      'periodicite' => new sfWidgetFormSelect(array('choices' => self::$listePeriodicite)),
	      'debut'   => new sfWidgetFormSelect(array('choices' => self::$listeVersement)),
	      'capitalacquis'   => new sfWidgetFormInputText(),
	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'capital'    => 'Monant capital placé',
		  'duree'   => 'Dur&eacute;e du placement (en nombre de p&eacute;riodes)',
		  'periodicite' => 'Périodicité',
		  'debut' => 'Versements',
		  'capitalacquis' => 'Capital acquis',
		));

		$this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'capital'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant capital placé est obligatoire.',
	      		'invalid'=>'Le montant capital doit etre numérique',
		      )),

	      'duree'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'La durée du placement est obligatoire.',
	      		'invalid'=>'La durée du placement doit etre numérique',
		      )),
	      'periodicite' => new sfValidatorChoice(array('choices' => array_keys(self::$listePeriodicite))),
	      'debut' => new sfValidatorChoice(array('choices' => array_keys(self::$listeVersement))),
	      'capitalacquis' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le capital acquis est obligatoire.',
	      		'invalid'=>'Le capital acquis doit etre numérique',
		      )),
    ));

    parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul... 
   
	}



}