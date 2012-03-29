<?php

class CceForm extends dmForm
{
	protected static $listePeriodicite = array(12=>'Mois',1=>'An(s)', 4=>'Trimestre(s)', 2=>'Semestre(s)');
	protected static $listeVersement = array(0=>'Fin de période', 1=>'Début de période');

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

	     $this->setValidators(array(
	      'remboursements'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant des remboursements est obligatoire.',
	      		'invalid'=>'Le montant des remboursements doit être numérique (exemple: 12.5)',
		      )),

	      'nbremboursements'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le nombre de remboursements est obligatoire.',
	      		'invalid'=>'Le nombre de remboursements doit être numérique (exemple: 12.5)',
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