<?php

class vacpicpddForm extends dmForm {
    protected static $listePeriodicite = array(
        12 => 'Mois',
        1 => 'Ans',
        4 => 'Trimestre',
        2 => 'Semestres'
    );
    protected static $listeVersement = array(
        1 => 'Versements fin',
        0 => 'Début de péiode'
    );
    public function configure() {
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
            'capital' => 'Monant capital placé',
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
                'invalid' => 'Le taux proportionnel annuel doit etre numérique',
            )) ,
        ));
    }
}
