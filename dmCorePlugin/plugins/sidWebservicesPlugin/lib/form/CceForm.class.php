<?php

class CceForm extends dmForm
{
	protected static $listePeriodicite = array(12=>'Mois',1=>'Ans', 4=>'Trimestre', 2=>'Semestres');
	protected static $listeVersement = array(0=>'Fin de p&eacute;riode', 1=>'Début de p&eacute;riode');

	 public function setup()
	{

		$this->setWidgets(array(
	      'remboursements'    => new sfWidgetFormInputText(),
	      'nbremboursements'   => new sfWidgetFormInputText(),
	      'periodicite' => new sfWidgetFormSelect(array('choices' => self::$listePeriodicite)),
	      'debut'   => new sfWidgetFormSelect(array('choices' => self::$listeVersement)),
	      'taux'   => new sfWidgetFormInputText(),
	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'remboursements'    => 'Montant des remboursements',
		  'nbremboursements'   => 'Nombre de remboursements',
		  'periodicite' => 'Périodicité',
		  'debut' => 'Versements',
		  'taux' => 'Taux proportionnel annuel',
		));

		$this->widgetSchema->setNameFormat('maform[%s]');

	     $this->setValidators(array(
	      'remboursements'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant des remboursements est obligatoire.',
	      		'invalid'=>'Le montant des remboursements doit etre numérique',
		      )),

	      'nbremboursements'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le nombre de remboursements est obligatoire.',
	      		'invalid'=>'Le nombre de remboursements doit etre numérique',
		      )),
	      'periodicite' => new sfValidatorChoice(array('choices' => array_keys(self::$listePeriodicite))),
	      'debut' => new sfValidatorChoice(array('choices' => array_keys(self::$listeVersement))),
	      'taux' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le taux proportionnel annuel est obligatoire.',
	      		'invalid'=>'Le taux proportionnel annuel doit etre numérique',
		      )),
    ));

    parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul... 
   
	}



}