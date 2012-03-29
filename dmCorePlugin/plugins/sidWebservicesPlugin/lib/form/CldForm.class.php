<?php

class CldForm extends dmForm
{
	protected static $listePeriodicite = array(12=>'Mois',1=>'An(s)', 4=>'Trimestre(s)', 2=>'Semestre(s)');
	protected static $listeVersement = array(0=>'Fin de période', 1=>'Début de période');

	 public function setup()
	{

		$this->setWidgets(array(
	      'montant'    => new sfWidgetFormInputText(),
	      'depot'   => new sfWidgetFormInputText(),
	      'remboursements'   => new sfWidgetFormInputText(),
	      'periodicite' => new sfWidgetFormSelect(array('choices' => self::$listePeriodicite)),
	      'debut'   => new sfWidgetFormSelect(array('choices' => self::$listeVersement)),
	      'valeur'   => new sfWidgetFormInputText(),
	      'taux'   => new sfWidgetFormInputText(),
	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'montant'    => 'Montant financ&eacute;',
		  'depot'   => 'Dépôt initial',
		  'remboursements'   => 'Montant des remboursements',
		  'periodicite' => 'Périodicité',
		  'debut' => 'Versements',
		  'valeur' => 'Valeur de rachat',
		  'taux' => 'Taux proportionnel annuel',
		));

	     $this->widgetSchema->setNameFormat('maform[%s]');
	     $this->setValidators(array(
	      'montant'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant financé est obligatoire.',
	      		'invalid'=>'Le montant financé doit être numérique (exemple: 12.5)',
		      )),

	      'depot'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le dépôt initial est obligatoire.',
	      		'invalid'=>'Le dépôt initial doit être numérique (exemple: 12.5)',
		      )),
		  'remboursements'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant des remboursements est obligatoire.',
	      		'invalid'=>'Le montant des remboursements doit être numérique (exemple: 12.5)',
		      )),
	      'periodicite' => new sfValidatorChoice(array('choices' => array_keys(self::$listePeriodicite))),
	      'debut' => new sfValidatorChoice(array('choices' => array_keys(self::$listeVersement))),
	      
	      'valeur' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'La valeur de rachat est obligatoire.',
	      		'invalid'=>'La valeur de rachat doit être numérique (exemple: 12.5)',
		      )),

	      'taux' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le taux proportionnel annuel est obligatoire.',
	      		'invalid'=>'Le taux proportionnel annuel doit être numérique (exemple: 12.5)',
		      )),
    )); 
parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...
   
	}



}