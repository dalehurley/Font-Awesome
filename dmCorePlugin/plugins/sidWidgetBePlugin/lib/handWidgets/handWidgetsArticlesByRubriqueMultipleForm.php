<?php

class handWidgetsArticlesByRubriqueMultipleForm extends dmWidgetPluginForm {

    public function configure() {

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText();
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => false
                ));

        $this->widgetSchema['rubrique'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidRubrique',
                    'method' => 'show_list_rubrique',
                    'multiple' => true,
                    //'expanded' => true,   pour avoir des cases à cocher
                    'add_empty' => '-- Rubrique --'
                ));
        $this->validatorSchema['rubrique'] = new sfValidatorDoctrineChoice(array(
                    'model' => 'SidRubrique',
                    'multiple' => true,
                    'required' => true
                ));

        $this->widgetSchema['nbArticles'] = new sfWidgetFormInputText();
        $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
                    'min' => 4,
                    'required' => true
                ));
        
        $this->widgetSchema->setHelps(array(
            'titreBloc' => 'Le titre optionnel du bloc.',            
            'rubrique' => 'Choisissez la ou les rubriques à afficher.',
            'nbArticles' => 'Le nombre total d\'articles affichés.',            
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
        return $this->getHelper()->renderPartial('handWidgets', 'articlesByRubriqueMultipleForm', array(
            'form' => $this,
            'id' => 'sid_widget_articles_by_rubrique_multiple_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}