<?php

class missionsMissionsContextuelForm extends dmWidgetPluginForm {

    public function configure() {
        
        parent::configure();

        $this->widgetSchema['lien']->setDefault('Toutes nos missions');
        $this->validatorSchema['lien'] = new sfValidatorString(array(
                    'required' => true
                ));

        $this->widgetSchema['nbArticles']->setDefault(1);
        $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
                    'required' => true,
                    'min' => 1
                ));

        $this->widgetSchema['chapo'] = new sfWidgetFormSelectRadio(array('choices' => array('chapeau', 'texte'), 'default' => 1));
        $this->validatorSchema['chapo'] = new sfValidatorChoice(array('choices' => array(0, 1), 'multiple' => false));

//        $this->widgetSchema['titreMission'] = new sfWidgetFormInputCheckbox(array('default'=> true));
//        $this->validatorSchema['titreMission']  = new sfValidatorBoolean();

        $this->widgetSchema->setHelps(array(
            'lien' => 'Le libellé du lien vers toutes les missions.',
            'nbArticles' => 'Le nombre maximum de missions affichées. mini 1',
            'length' => 'Longueur du texte à afficher (si 0 : texte en entier)',
            'chapo' => 'Choisir si on veut afficher le résumé de la page ou le contenu entier de la page'
        ));


    }

    public function getStylesheets() {
        return array(
//            'lib.ui-tabs'
        );
    }

    public function getJavascripts() {
        return array(
//            'lib.ui-tabs',
//            'core.tabForm',
//            'sidWidgetBePlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('missions', 'missionsContextuelForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_missions_contextuel_' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}