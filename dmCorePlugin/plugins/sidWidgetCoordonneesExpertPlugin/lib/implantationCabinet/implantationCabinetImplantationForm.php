<?php

class implantationCabinetImplantationForm extends dmWidgetPluginForm {

    public function configure() {

        parent::configure();
        
        $this->widgetSchema['civ'] = new sfWidgetFormInputCheckbox(array('default'=> true, 'label' => 'Affiche la civililté du collaborateur'));
        $this->validatorSchema['civ']  = new sfValidatorBoolean();
        
        $this->widgetSchema['resume_team'] = new sfWidgetFormInputCheckbox(array('default'=> true, 'label' => 'Affiche la présentation de l\'équipe'));
        $this->validatorSchema['resume_team']  = new sfValidatorBoolean();

        $this->widgetSchema['widthImagePhoto'] = new sfWidgetFormInputText(array('label' => "Largeur de la photo de l'implantation en px",'default' => 622));
        $this->validatorSchema['widthImagePhoto'] = new dmValidatorCssSize(array(
                  'required' => false
                ));

        $this->widgetSchema->setHelp('withImage','Cocher pour affiche la photo ou la silhouette du collaborateur');
        $this->widgetSchema->setLabel('withImage','Afficher la photo ou la silhouette');
        $this->widgetSchema->setHelp('widthImage','largeur en px de la photo du collaborateur');
        $this->widgetSchema->setLabel('widthImage','largeur de la photo du collaborateur');
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
        return $this->getHelper()->renderPartial('implantationCabinet', 'implantationForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_coordonnees_expert_implantation_cabinet_' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}
