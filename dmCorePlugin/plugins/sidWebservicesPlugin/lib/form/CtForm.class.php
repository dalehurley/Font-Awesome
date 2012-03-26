<?php

class CtForm extends dmForm
{
	protected static $listePeriodicite = array(12=>'Mois',1=>'Ans', 4=>'Trimestre', 2=>'Semestres');
	protected static $listeVersement = array(1=>' D&eacute;but de p&eacute;riode', 0=>'Fin de p&eacute;riode');

	 public function setup()
	{

		$this->setWidgets(array(
	      'capital'    => new sfWidgetFormInputText(),
	      'versements'   => new sfWidgetFormInputText(),
	      'nbversements'   => new sfWidgetFormInputText(),
	      'periodicite' => new sfWidgetFormSelect(array('choices' => self::$listePeriodicite)),
	      'debut'   => new sfWidgetFormSelect(array('choices' => self::$listeVersement)),
	      
	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'capital'    => 'Capital emprunt&eacute;',
		  'versements'   => 'Montant des versements',
		  'nbversements' => 'Nombre de versements',
		  'periodicite' => 'Périodicité',
		  'debut' => 'Versements',
		  
		));

		$this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'capital'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le Capital emprunt&eacute; est obligatoire.',
	      		'invalid'=>'Le Capital emprunt&eacute; doit etre numérique',
		      )),

	      'versements'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant des versements est obligatoire.',
	      		'invalid'=>'Le montant des versements doit etre numérique',
		      )),
	      'periodicite' => new sfValidatorChoice(array('choices' => array_keys(self::$listePeriodicite))),
	      'debut' => new sfValidatorChoice(array('choices' => array_keys(self::$listeVersement))),
	      'nbversements' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le nombre de versements est obligatoire.',
	      		'invalid'=>'Le nombre de versements doit etre numérique',
		      )),
    )); 
	     parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}



}