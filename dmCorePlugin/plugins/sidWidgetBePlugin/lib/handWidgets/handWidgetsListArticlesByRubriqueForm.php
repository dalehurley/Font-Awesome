<?php

class handWidgetsListArticlesByRubriqueForm extends dmWidgetPluginForm {

    public function configure() {


        $this->widgetSchema['rubrique'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidRubrique',
                    'add_empty' => '-- Rubrique --',
                    'method' => 'show_list_rubrique_without_agenda'
                ));
        $this->validatorSchema['rubrique'] = new sfValidatorDoctrineChoice(array(
                    'model' => 'SidRubrique',
                    'required' => true
                ));

        $this->widgetSchema['nbArticles'] = new sfWidgetFormInputText();
        $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
                    'max' => 4,
                    'min' => 1,
                    'required' => true
                ));
        
        $this->widgetSchema['title'] = new sfWidgetFormInputText();
        $this->validatorSchema['title'] = new sfValidatorString(array('required' => false));
        
        // photo
        $this->widgetSchema['photo']       = new sfWidgetFormInputCheckbox(array('default'=> false));
        $this->validatorSchema['photo']    = new sfValidatorBoolean();
        
        // Titre bloc
        $this->widgetSchema['titreBloc']       = new sfWidgetFormInputCheckbox(array('default'=> false));
        $this->validatorSchema['titreBloc']    = new sfValidatorBoolean();
        
        $this->widgetSchema->setHelps(array(
            'rubrique' => 'Choisissez la rubrique à afficher.',
            'nbArticles' => 'entre 1 et 4',
            'title' => 'Personnaliser le titre du widget',
            'photo' => 'Choisir d\'afficher la photo de l\'article',
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
        return $this->getHelper()->renderPartial('handWidgets', 'listArticlesByRubriqueForm', array(
            'form' => $this,
            'id' => 'sid_widget_list_articles_by_rubrique_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}