<?php

class missionsMissionsContextuelForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText(array('label' => 'Titre du bloc'));
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => false
                ));

        $this->widgetSchema['titreLien'] = new sfWidgetFormInputText(array('label' => 'Titre du lien','default' => 'Toutes nos missions'));
        $this->validatorSchema['titreLien'] = new sfValidatorString(array(
                    'required' => true
                ));

        $this->widgetSchema['nb'] = new sfWidgetFormInputText(array('label' => 'Nbre d\'article', 'default' => 1));
        $this->validatorSchema['nb'] = new sfValidatorInteger(array(
                    'required' => true,
                    'min' => 1
                ));

        $this->widgetSchema['length'] = new sfWidgetFormInputText(array('default' => 0, 'label' => 'Longueur du texte'));
        $this->validatorSchema['length'] = new sfValidatorInteger(array(
                    'required' => false
                ));


        $this->widgetSchema['chapo'] = new sfWidgetFormSelectRadio(array('choices' => array('chapeau', 'texte'), 'default' => 1));
        $this->validatorSchema['chapo'] = new sfValidatorChoice(array('choices' => array(0, 1), 'multiple' => false));

//        $this->widgetSchema['titreMission'] = new sfWidgetFormInputCheckbox(array('default'=> true));
//        $this->validatorSchema['titreMission']  = new sfValidatorBoolean();

        $this->widgetSchema->setHelps(array(
            'titreBloc' => 'Le titre optionnel du bloc. Si null, le titre de la mission s\'affichera dans le titre du bloc',
            'titreLien' => 'Le libellé du lien vers toutes les missions.',
            'nb' => 'Le nombre maximum de missions affichées. mini 1',
            'length' => 'Longueur du texte à afficher (si 0 : texte en entier)',
            'chapo' => 'Choisir si on veut afficher le résumé de la page ou le contenu entier de la page'
        ));

        parent::configure();
    }

    public function getStylesheets() {
        return array(
            'lib.ui-tabs'
        );
    }

    public function getJavascripts() {
        return array(
            'lib.ui-tabs',
            'core.tabForm',
            'sidWidgetBePlugin.widgetShowForm'
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