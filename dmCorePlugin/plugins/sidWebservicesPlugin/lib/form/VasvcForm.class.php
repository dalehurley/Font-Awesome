<?php

class VasvcForm extends dmForm
{
	protected static $listePeriodicite = array(12=>'Mois',1=>'An(s)', 4=>'Trimestre(s)', 2=>'Semestre(s)');
	protected static $listeVersement = array(1=>'Fin de période', 0=>'Début de période');

	 public function setup()
	{

		$this->setWidgets(array(
	      'versements'    => new sfWidgetFormInputText(),
	      'nbversements'   => new sfWidgetFormInputText(),
	      'periodicite' => new sfWidgetFormSelect(array('choices' => self::$listePeriodicite)),
	      'debut'   => new sfWidgetFormSelect(array('choices' => self::$listeVersement)),
	      'taux'   => new sfWidgetFormInputText(),
	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'versements'    => 'Montant des versements',
		  'nbversements'   => 'Nombre de versements',
		  'periodicite' => 'Périodicité',
		  'debut' => 'Versements',
		  'taux' => 'Taux proportionnel annuel',
		));

	     $this->setValidators(array(
	      'versements'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant des versements est obligatoire.',
	      		'invalid'=>'Le montant des versements doit être numérique (exemple: 12.5)',
		      )),

	      'nbversements'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le nombre de versements est obligatoire.',
	      		'invalid'=>'Le nombre de versements doit être numérique (exemple: 12.5)',
		      )),
	      'periodicite' => new sfValidatorChoice(array('choices' => array_keys(self::$listePeriodicite))),
	      'debut' => new sfValidatorChoice(array('choices' => array_keys(self::$listeVersement))),
	      'taux' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le taux proportionnel annuel est obligatoire.',
	      		'invalid'=>'Le taux proportionnel annuel doit être numérique (exemple: 12.5)',
		      )),
    )); 

	     parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
 	}
}