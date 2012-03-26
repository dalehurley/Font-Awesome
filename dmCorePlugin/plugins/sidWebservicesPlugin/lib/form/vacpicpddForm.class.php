<?php

class vacpicpddForm extends baseForm {

    protected static $listePeriodicite = array(
        12 => 'Mois',
        1 => 'Ans',
        4 => 'Trimestre',
        2 => 'Semestres'
    );
    protected static $listeVersement = array(
        0 => 'Versements fin',
        1 => 'Début de période'
    );
    public function setup() {

        $this->setWidgets(array(
            'capital' => new sfWidgetFormInputText() ,
            'duree' => new sfWidgetFormInputText() ,
            'periodicite' => new sfWidgetFormSelect(array(
                'choices' => self::$listePeriodicite
            )) ,
            'debut' => new sfWidgetFormSelect(array(
                'choices' => self::$listeVersement
            )) ,
            'taux' => new sfWidgetFormInputText() ,
        ));
        $this->widgetSchema->setLabels(array(
            'capital' => 'Montant capital placé (en euros)',
            'duree' => 'Durée du placement',
            'periodicite' => 'Périodicité',
            'debut' => 'Versements',
            'taux' => 'Taux proportionnel annuel',
        ));
        $this->setValidators(array(
            'capital' => new sfValidatorNumber(array(
                'required' => true
            ) , array(
                'required' => 'Le montant capital placé est obligatoire.',
                'invalid' => 'Le montant capital doit etre numérique',
            )) ,
            'duree' => new sfValidatorNumber(array(
                'required' => true
            ) , array(
                'required' => 'La durée du placement est obligatoire.',
                'invalid' => 'La durée du placement doit etre numérique',
            )) ,
            'periodicite' => new sfValidatorChoice(array(
                'choices' => array_keys(self::$listePeriodicite)
            )) ,
            'debut' => new sfValidatorChoice(array(
                'choices' => array_keys(self::$listeVersement)
            )) ,
            'taux' => new sfValidatorNumber(array(
                'required' => true
            ) , array(
                'required' => 'Le taux proportionnel annuel est obligatoire.',
                'invalid' => 'Le taux proportionnel annuel doit être numérique',
            )) ,
        ));

        parent::setup(); // call setup of dmForm to have html templating dm_form_element li and ul...

    }
}
