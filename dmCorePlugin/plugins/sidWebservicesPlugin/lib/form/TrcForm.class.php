<?php

class TrcForm extends dmForm
{
	protected static $listePeriodicite = array(12=>'Mois',1=>'An(s)', 4=>'Trimestre(s)', 2=>'Semestre(s)');
	protected static $listeVersement = array(1=>'Fin de période', 0=>'Début de période');

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
		  'capital'    => 'Montant capital placé',
		  'duree'   => 'Durée du placement (en nombre de périodes)',
		  'periodicite' => 'Périodicité',
		  'debut' => 'Versements',
		  'capitalacquis' => 'Capital acquis',
		));

	    $this->setValidators(array(
	      'capital'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le montant capital placé est obligatoire.',
	      		'invalid'=>'Le montant capital doit être numérique (exemple: 12.5)',
		      )),

	      'duree'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'La durée du placement est obligatoire.',
	      		'invalid'=>'La durée du placement doit être numérique (exemple: 12.5)',
		      )),
	      'periodicite' => new sfValidatorChoice(array('choices' => array_keys(self::$listePeriodicite))),
	      'debut' => new sfValidatorChoice(array('choices' => array_keys(self::$listeVersement))),
	      'capitalacquis' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le capital acquis est obligatoire.',
	      		'invalid'=>'Le capital acquis doit être numérique (exemple: 12.5)',
		      )),
    ));

    parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul... 
   
	}



}