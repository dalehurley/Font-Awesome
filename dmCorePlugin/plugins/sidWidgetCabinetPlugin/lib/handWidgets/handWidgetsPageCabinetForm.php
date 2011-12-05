<?php

class handWidgetsPageCabinetForm extends dmWidgetPluginForm {

    public function configure() {
       
       $this->widgetSchema['title_page'] = new sfWidgetFormInputText();
        $this->validatorSchema['title_page'] = new sfValidatorString(array(
                    'required' => false
                ));
        $this->widgetSchema['lien'] = new sfWidgetFormInputText();
        $this->validatorSchema['lien'] = new sfValidatorString(array(
                    'required' => false
                ));
        
        $this->widgetSchema->setHelps(array(
            'title_page'=> 'Titre du bloc',
            'lien'=> 'Titre du lien pour aller sur la page contact'
            
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
            'sidWidgetCabinetPlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('handWidgets', 'pageCabinetForm', array(
            'form' => $this,
            'id' => 'sid_widget_page cabinet_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}