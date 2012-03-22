<?php

class clForm extends dmForm
{
	protected static $listePeriodicite = array(12=>'Mois',1=>'Ans', 4=>'Trimestre', 2=>'Semestres');
	protected static $listeVersement = array(0=>'Fin de p&eacute;riode', 1=>'Début de p&eacute;riode');

	 public function setup()
	{

		$this->setWidgets(array(
	      'montant'    => new sfWidgetFormInputText(),
	      'depot'   => new sfWidgetFormInputText(),
	      'nbloyers'   => new sfWidgetFormInputText(),
	      'periodicite' => new sfWidgetFormSelect(array('choices' => self::$listePeriodicite)),
	      'debut'   => new sfWidgetFormSelect(array('choices' => self::$listeVersement)),
	      'valeur'   => new sfWidgetFormInputText(),
	      'taux'   => new sfWidgetFormInputText(),
	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'montant'    => 'Montant financ&eacute;',
		  'depot'   => 'D&eacute;p&ocirc;t initial',
		  'nbloyers'   => 'Nombre de loyers',
		  'periodicite' => 'Périodicité',
		  'debut' => 'Versements',
		  'valeur' => 'Valeur de rachat',
		  'taux' => 'Taux proportionnel annuel',
		));

	
	     $this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'montant'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant financ&eacute; est obligatoire.',
	      		'invalid'=>'Le montant financ&eacute; doit etre numérique',
		      )),

	      'depot'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le d&eacute;p&ocirc;t initial est obligatoire.',
	      		'invalid'=>'Le d&eacute;p&ocirc;t initial doit etre numérique',
		      )),
		  'nbloyers'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le nombre de loyers est obligatoire.',
	      		'invalid'=>'Le nombre de loyers doit etre numérique',
		      )),
	      'periodicite' => new sfValidatorChoice(array('choices' => array_keys(self::$listePeriodicite))),
	      'debut' => new sfValidatorChoice(array('choices' => array_keys(self::$listeVersement))),
	      
	      'valeur' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'La valeur de rachat est obligatoire.',
	      		'invalid'=>'La valeur de rachat doit etre numérique',
		      )),
	      'taux' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le taux proportionnel annuel est obligatoire.',
	      		'invalid'=>'Le taux proportionnel annuel doit etre numérique',
		      )),
    )); 

parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}



}