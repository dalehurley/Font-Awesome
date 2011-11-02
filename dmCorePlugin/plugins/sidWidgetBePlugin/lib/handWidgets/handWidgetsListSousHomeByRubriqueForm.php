<?php

class handWidgetsListSousHomeByRubriqueForm extends dmWidgetPluginForm {

    public function configure() {


//        $this->widgetSchema['section'] = new sfWidgetFormDoctrineChoice(array(
//                    'model' => 'SidSection',
//                    'add_empty' => '-- Section --',
//                    'method' => 'show_rubrique_section'
//                ));
//        $this->validatorSchema['section'] = new sfValidatorDoctrineChoice(array(
//                    'model' => 'SidSection',
//                    'required' => true
//                ));

//        $this->widgetSchema['nbArticles'] = new sfWidgetFormInputText(array('default'=> 2));
//        $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
//                    'max' => 8,
//                    'min' => 1,
//                    'required' => true
//                ));

//        $this->widgetSchema['title'] = new sfWidgetFormInputText();
//        $this->validatorSchema['title'] = new sfValidatorString(array('required' => false));
        
        // photo
        $this->widgetSchema['photo']       = new sfWidgetFormInputCheckbox(array('default'=> false));
        $this->validatorSchema['photo']    = new sfValidatorBoolean();

        // Titre bloc
        $this->widgetSchema['titreBloc']       = new sfWidgetFormInputCheckbox(array('default'=> false));
        $this->validatorSchema['titreBloc']    = new sfValidatorBoolean();
        
        $this->widgetSchema->setHelps(array(
            //'section' => 'Choisissez la section à afficher.',
            //'nbArticles' => 'entre 1 et 4',
            //'title' => 'Personnaliser le titre du widget',
            'photo' => 'Choisir d\'afficher la photo des articles',
            'titreBloc' => 'Choisir d\'afficher le titre de l\'article en lieu et place du titre du widget'
        
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
        return $this->getHelper()->renderPartial('handWidgets', 'listSousHomeByRubriqueForm', array(
            'form' => $this,
            'id' => 'sid_widget_list_sous_home_by_rubrique' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}