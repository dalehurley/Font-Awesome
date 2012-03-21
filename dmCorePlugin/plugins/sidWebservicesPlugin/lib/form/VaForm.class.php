<?php

class VaForm extends BaseForm
{
	protected static $listeGenre = array(1=>'Personnel (VP)'
									,0=>'Utilitaire (VU)'
									);
	protected static $listeFonctionnement = array(1=>'Normal'
									,0=>'GPL, &eacute;lectricit&eacute; ou gaz naturel'
									);	



	 public function configure()
	{

       	$this->setWidgets(array(
       	'departement'   => new sfWidgetFormInputText(),
        'puissance'   => new sfWidgetFormInputText(),
        'jma'   => new sfWidgetFormInputText(),
	    'genre' => new sfWidgetFormSelect(array('choices' => self::$listeGenre)),
	    'fonctionnement' => new sfWidgetFormSelect(array('choices' => self::$listeFonctionnement)), 
	      
	    ));

	    $this->widgetSchema->setLabels(array(
		  'departement'    => 'D&eacute;partement',
		  'puissance'    => 'Puissance (en CV)',
		  'jma'    => 'Mise en circulation',
		  'genre'    => 'Genre du v&eacute;hicule',
		  'fonctionnement'    => 'Fonctionnement',
		));
		$this->widgetSchema->setNameFormat('maform[%s]');
        $this->setValidators(array(
	      '' => new sfValidatorChoice(array('choices' => array_keys(self::$listePuissance))),
	      'departement' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'Puissance (en CV) est obligatoire.',
	      		'invalid'=>'Puissance (en CV) doit etre numérique',
		      )),
		  'puissance' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'D&eacute;partement est obligatoire.',
	      		'invalid'=>'D&eacute;partement doit etre numérique',
		      )),
		  'jma' => new sfValidatorNumber(array('required' => true),array(
	      		'required'=>'La date mise en circulation est obligatoire.',
	      		'invalid'=>'La date mise en circulation doit etre numérique',
		      )),
		  'genre' => new sfValidatorChoice(array('choices' => array_keys(self::$listeGenre))),
		  'fonctionnement' => new sfValidatorChoice(array('choices' => array_keys(self::$listeFonctionnement))),
    )); 
   
	}



}