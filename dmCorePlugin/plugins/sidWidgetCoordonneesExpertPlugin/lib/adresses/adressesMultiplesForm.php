<?php

class adressesMultiplesForm extends dmWidgetPluginForm {

    public function configure() {

        parent::configure();
        
        $this->widgetSchema['gridColumns'] = new sfWidgetFormInputText(array('label' => 'Nb de colonne par adresse', 'default' => 10));
        $this->validatorSchema['gridColumns'] = new sfValidatorInteger(array(
                    'required' => false,
                    ));
        if (dmConfig::get('site_theme_version') != 'v1'){
            $helpGridColumns = 'Le nombre de colonnes de la grille pour un bloc adresses. Exemple: il y a 3 cabinets, la grille fait 12 colonnes, vous notez 4.';
        } else {
            $helpGridColumns = 'WARNING: Indisponible (seulement en theme de v2)';
        }
        $this->widgetSchema->setHelps(array(
                'gridColumns' => $helpGridColumns
            )); 
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
        return $this->getHelper()->renderPartial('adresses', 'multiplesForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_adresses_multiples' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}
