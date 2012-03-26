<?php

class TrsvcForm extends dmForm
{
	protected static $listePeriodicite = array(12=>'Mois',1=>'Ans', 4=>'Trimestre', 2=>'Semestres');
	protected static $listeVersement = array(1=>'Versements fin', 0=>'Début de péiode');

	 public function setup()
	{

		$this->setWidgets(array(
	      'versements'    => new sfWidgetFormInputText(),
	      'nbversements'   => new sfWidgetFormInputText(),
	      'periodicite' => new sfWidgetFormSelect(array('choices' => self::$listePeriodicite)),
	      'debut'   => new sfWidgetFormSelect(array('choices' => self::$listeVersement)),
	      'capital'   => new sfWidgetFormInputText(),
	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'versements'    => 'Montant des versements',
		  'nbversements'   => 'Nombre des versements',
		  'periodicite' => 'Périodicité',
		  'debut' => 'Versements',
		  'capital' => 'Capital acquis',
		));

		$this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'versements'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant des versements est obligatoire.',
	      		'invalid'=>'Le montant des versements doit etre numérique',
		      )),

	      'nbversements'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le nombre des versements  est obligatoire.',
	      		'invalid'=>'Le nombre des versements  doit etre numérique',
		      )),
	      'periodicite' => new sfValidatorChoice(array('choices' => array_keys(self::$listePeriodicite))),
	      'debut' => new sfValidatorChoice(array('choices' => array_keys(self::$listeVersement))),
	      'capital' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le capital acquis est obligatoire.',
	      		'invalid'=>'Le capital acquis doit etre numérique',
		      )),
    )); 

	     parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}



}