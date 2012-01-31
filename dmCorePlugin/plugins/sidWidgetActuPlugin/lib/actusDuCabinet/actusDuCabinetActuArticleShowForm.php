<?php

class actusDuCabinetActuArticleShowForm extends dmWidgetPluginForm {

    public function configure() {
        $this->widgetSchema['type'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidActuType'
                ));
        $this->validatorSchema['type'] = new sfValidatorDoctrineChoice(array(
                    'required' => true,
                    'model' => 'SidActuType'
                ));

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText();
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => false
                ));
        
        $this->widgetSchema->setHelps(array(
            'type' => 'Le type de l\'article',
            'titreBloc' => 'Le titre optionnel du bloc. Si vide, le traitement se fera de manière automaitique', 
        ));

        parent::configure();
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
        return $this->getHelper()->renderPartial('actusDuCabinet', 'actuArticleShowForm', array(
            'form' => $this,
            'id' => 'sid_widget_actu_article_show_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}