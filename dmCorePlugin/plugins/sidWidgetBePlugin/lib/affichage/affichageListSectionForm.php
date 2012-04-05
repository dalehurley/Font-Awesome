<?php

class affichageListSectionForm extends dmWidgetPluginForm {
    
    public function configure() {
        
        parent::configure();
        /*
         * Record id
        */
        $this->widgetSchema['recordId'] = new sfWidgetFormDoctrineChoice(array(
            'model' => 'SidRubrique',
            'add_empty' => sprintf('(%s) %s', $this->__('contextual') , $this->getAutoRecord()->__toString()) // affichage du libellé de l'objet
            
        ));
        $this->widgetSchema['recordId']->setLabel('Article');
        $this->validatorSchema['recordId'] = new sfValidatorDoctrineChoice(array(
            'model' => 'SidRubrique',
            'required' => false
        ));

        $this->widgetSchema['nbSections'] = new sfWidgetFormInputText(array('label' => 'Nbre de sections', 'default' => 1));
        $this->validatorSchema['nbSections'] = new sfValidatorInteger(array(
                    'required' => false,
                    ));

        //    $this->setDefaults($this->getDefaultsFromLastUpdated());
        //
        //    if (!$this->allowAutoRecordId() && !$this->getDefault('recordId'))
        //    {
        //      $this->setDefault('recordId', dmArray::first(array_keys($this->widgetSchema['recordId']->getChoices())));
        //    }
        
        $this->widgetSchema->setHelps(array(
                'nbArticles' => 'Le nombre maximum d\'articles affichés pour chaque section.'
            ));        
        
    }
    /*
     * return record linked to the page
    */
    protected function getAutoRecord() {
        $record = sfcontext::getInstance()->getPage() ? sfcontext::getInstance()->getPage()->getRecord() : false;
        // return ancestor of
        
        return $record ? $record->getAncestorRecord('SidRubrique') : false;
    }
    public function getStylesheets() {
        
        return array(
            'lib.ui-tabs'
        );
    }
    public function getJavascripts() {
        
        return array(
            'lib.ui-tabs',
            'core.tabForm'
        );
    }
    protected function renderContent($attributes) {
        
        return $this->getHelper()->renderPartial('affichage', 'listSectionForm', array(
            'form' => $this,
            'id' => 'sid_widget_affichage_list_section' . $this->dmWidget->get('id')
        ));
    }
    public function getWidgetValues() {
        $values = parent::getWidgetValues();
        
        return $values;
    }
}
