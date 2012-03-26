<?php

class implantationCabinetListImplantationForm extends dmWidgetPluginForm {

    public function configure() {


        parent::configure();
        $this->widgetSchema['resume_town'] = new sfWidgetFormInputCheckbox(array('default'=> true, 'label' => 'Affiche la présentation de l\'implantation'));
        $this->validatorSchema['resume_town']  = new sfValidatorBoolean();
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
//            'sidWidgetCoordonneesExpertPlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('implantationCabinet', 'listImplantationForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_coordonnees_expert_list_implantation_cabinet_' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}
