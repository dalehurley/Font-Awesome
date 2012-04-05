<?php

class clForm extends dmForm
{
	protected static $listePeriodicite = array(12=>'Mois',1=>'An(s)', 4=>'Trimestre(s)', 2=>'Semestre(s)');
	protected static $listeVersement = array(0=>'Fin de période', 1=>'Début de période');

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
		  'depot'   => 'Dépôt initial',
		  'nbloyers'   => 'Nombre de loyers',
		  'periodicite' => 'Périodicité',
		  'debut' => 'Versements',
		  'valeur' => 'Valeur de rachat',
		  'taux' => 'Taux proportionnel annuel',
		));

	
	     $this->setValidators(array(
	      'montant'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant financ&eacute; est obligatoire.',
	      		'invalid'=>'Le montant financ&eacute; doit être numérique (exemple: 12.5)',
		      )),

	      'depot'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le dépôt initial est obligatoire.',
	      		'invalid'=>'Le dépôt initial doit être numérique (exemple: 12.5)',
		      )),
		  'nbloyers'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le nombre de loyers est obligatoire.',
	      		'invalid'=>'Le nombre de loyers doit être numérique (exemple: 12.5)',
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