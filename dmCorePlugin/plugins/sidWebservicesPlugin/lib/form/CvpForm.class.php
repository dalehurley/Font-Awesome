<?php

class CvpForm extends dmForm
{
	protected static $listePeriodicite = array(12=>'Mois',1=>'Ans', 4=>'Trimestre', 2=>'Semestres');
	protected static $listeVersement = array(1=>'Versements fin', 0=>'Début de péiode');

	 public function setup()
	{

		$this->setWidgets(array(
	      'capital'    => new sfWidgetFormInputText(),
	      'nbremboursements'   => new sfWidgetFormInputText(),
	      'periodicite' => new sfWidgetFormSelect(array('choices' => self::$listePeriodicite)),
	      'debut'   => new sfWidgetFormSelect(array('choices' => self::$listeVersement)),
	      'taux'   => new sfWidgetFormInputText(),
	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'capital'    => 'Capital emprunt&eacute;',
		  'nbremboursements'   => 'Nombre des remboursements',
		  'periodicite' => 'Périodicité',
		  'debut' => 'Versements',
		  'taux' => 'Taux proportionnel annuel',
		));

		$this->widgetSchema->setNameFormat('maform[%s]');

	    $this->setValidators(array(
	      'capital'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le Capital emprunt&eacute; est obligatoire.',
	      		'invalid'=>'Le Capital emprunt&eacute;doit etre numérique',
		      )),

	      'nbremboursements'    => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Le nombre des remboursements est obligatoire.',
	      		'invalid'=>'La nombre des remboursements doit etre numérique',
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