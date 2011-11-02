<?php

class handWidgetsArticlesBySectionForm extends dmWidgetPluginForm {

    public function configure() {


        $this->widgetSchema['section'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidSection',
                    'add_empty' => '-- Section --',
                    'method' => 'show_rubrique_section'
                ));
        $this->validatorSchema['section'] = new sfValidatorDoctrineChoice(array(
                    'model' => 'SidSection',
                    'required' => true
                ));

        $this->widgetSchema['nbArticles'] = new sfWidgetFormInputText();
        $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
                    'max' => 8,
                    'min' => 4,
                    'required' => true
                ));
        
        
        $this->widgetSchema['largeur'] = new sfWidgetFormInputText();
        $this->validatorSchema['largeur'] = new sfValidatorInteger(array(
                    'required' => true
                ));

        $this->widgetSchema['hauteur'] = new sfWidgetFormInputText();
        $this->validatorSchema['hauteur'] = new sfValidatorInteger(array(
                    'required' => true
                ));
        

        $this->widgetSchema->setHelps(array(
            'section' => 'Choisissez la section à afficher.',
            'largeur' => 'en px',
            'hauteur' => 'en px',
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
        return $this->getHelper()->renderPartial('handWidgets', 'articlesBySectionForm', array(
            'form' => $this,
            'id' => 'sid_widget_articles_by_section_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}