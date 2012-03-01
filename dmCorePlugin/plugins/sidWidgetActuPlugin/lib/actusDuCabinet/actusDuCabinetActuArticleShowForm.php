<?php

class actusDuCabinetActuArticleShowForm extends dmWidgetPluginForm {

    public function configure() {
        
        parent::configure();
        
        // on rajoute ou on surcharge les éléments de dmWidgetPluginForm
        
        $this->widgetSchema['type'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidActuType',
                ));
        $this->validatorSchema['type'] = new sfValidatorDoctrineChoice(array(
                    'required' => true,
                    'model' => 'SidActuType'
                ));
        $this->widgetSchema->setHelp('type' , 'Le type de l\'article');
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