<?php

class CtrForm extends dmForm
{
	protected static $listePeriodicite = array(12=>'Mois',1=>'Ans', 4=>'Trimestre', 2=>'Semestres');
	protected static $listeVersement = array(1=>' D&eacute;but de p&eacute;riode', 0=>'Fin de p&eacute;riode');

	 public function setup()
	{

		$this->setWidgets(array(
	      'montant'    => new sfWidgetFormInputText(),
	      'depot'   => new sfWidgetFormInputText(),
	      'remboursements'   => new sfWidgetFormInputText(),
	      'nbloyers'   => new sfWidgetFormInputText(),
	      'periodicite' => new sfWidgetFormSelect(array('choices' => self::$listePeriodicite)),
	      'debut'   => new sfWidgetFormSelect(array('choices' => self::$listeVersement)),
	      'valeur'   => new sfWidgetFormInputText(),
	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'montant'    => 'Montant financ&eacute; du cr&eacute;dit-bail',
		  'depot'   => 'D&eacute;p&ocirc;t initial',
		  'remboursements' => 'Montant des remboursements',
		  'nbloyers' => 'Mombre de loyers',
		  'periodicite' => 'Périodicité',
		  'debut' => 'Versements',
		  'valeur' => 'Valeur de rachat',
		  
		));
		$this->widgetSchema->setNameFormat('maform[%s]');
				
	     $this->setValidators(array(
	      'montant'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant financ&eacute; du cr&eacute;dit-bail est obligatoire.',
	      		'invalid'=>'Le montant financ&eacute; du cr&eacute;dit-bail doit etre numérique',
		      )),

	      'depot'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le d&eacute;p&ocirc;t initial est obligatoire.',
	      		'invalid'=>'Le d&eacute;p&ocirc;t initial doit etre numérique',
		      )),
	      'periodicite' => new sfValidatorChoice(array('choices' => array_keys(self::$listePeriodicite))),
	      'debut' => new sfValidatorChoice(array('choices' => array_keys(self::$listeVersement))),
	      'remboursements' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant des remboursements est obligatoire.',
	      		'invalid'=>'Le montant des remboursements doit etre numérique',
		      )),
		  'nbloyers' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le mombre de loyers est obligatoire.',
	      		'invalid'=>'Le mombre de loyers doit etre numérique',
		      )),
		  'valeur' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'La valeur de rachat est obligatoire.',
	      		'invalid'=>'La valeur de rachatdoit etre numérique',
		      )),
    )); 

parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}



}