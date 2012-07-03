<?php

class missionsMissionsListForm extends dmWidgetPluginForm {

    public function configure() {
        
        parent::configure();

/*        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText();
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => false
                ));*/

        $this->widgetSchema['nbImagesMissions'] = new sfWidgetFormInputText(array('label' => 'Nb d\'images à afficher pour listing'));
        $this->validatorSchema['nbImagesMissions'] = new sfValidatorInteger(array(
                    'required' => false,
                ));
        $this->widgetSchema->setHelp('nbImagesMissions', '0 pour afficher toutes les images');
/*        
        $this->widgetSchema['longueurTexte'] = new sfWidgetFormInputText(array('default' => 0));
        $this->validatorSchema['longueurTexte'] = new sfValidatorInteger(array(
                    'required' => false
                ));*/
        
//        $this->widgetSchema['chapo'] = new sfWidgetFormSelectRadio(array('choices' => array('chapeau','texte'),'default'=>0));
//        $this->validatorSchema['chapo']  = new sfValidatorChoice(array('choices' =>array(0,1),'multiple' => false));
        
 /*       $this->widgetSchema->setHelps(array(
            'titreBloc' => 'Le titre optionnel du bloc.',  
            'nbMissions' => 'Le nombre maximum d\'articles affichés (0 pour infini).',            
            'longueurTexte' => 'Longueur du texte avant de la tronquer',
//            'chapo' => 'Choisir si on veut afficher le résumé de la page ou le contenu entier de la page'
        ));*/

        
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
        return $this->getHelper()->renderPartial('missions', 'missionsListForm', array(
            'form' => $this,
            'id' => 'sid_widget_missions_list_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}