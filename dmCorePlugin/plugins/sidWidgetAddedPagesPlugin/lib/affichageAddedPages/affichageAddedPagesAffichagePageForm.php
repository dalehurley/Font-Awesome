<?php

class affichageAddedPagesAffichagePageForm extends dmWidgetPluginForm {
    public function configure() {
        
        parent::configure();
        /*
         * Record id
        */
        $this->widgetSchema['recordId'] = new sfWidgetFormDoctrineChoice(array(
            'model' => 'SidAddedPages',
            'add_empty' => sprintf('(%s) %s', $this->__('contextual') , $this->getAutoRecord()->__toString()) // affichage du libellé de l'objet
            
        ));
        $this->widgetSchema['recordId']->setLabel('Page');
        $this->validatorSchema['recordId'] = new sfValidatorDoctrineChoice(array(
            'model' => 'SidAddedPages',
            'required' => false
        ));
        
        $this->widgetSchema['withDate'] = new sfWidgetFormInputCheckbox(array('default'=> true, 'label' => 'Date de publication visible'));
        $this->validatorSchema['withDate']  = new sfValidatorBoolean();
        $this->widgetSchema->setHelp('withDate' , 'Affichage de la date de publication');

        $this->widgetSchema['withResume'] = new sfWidgetFormInputCheckbox(array('default'=> true, 'label' => 'Résumé visible'));
        $this->validatorSchema['withResume']  = new sfValidatorBoolean();
        $this->widgetSchema->setHelp('withResume' , 'Affichage du résumé');
       
        switch (dmConfig::get('site_theme')) {
                    case 'BaseTheme': $mapWidth = 622;$mapHeight = 324;
                        break;
                    case 'copilotesTheme': $mapWidth = 650;$mapHeight = 324;
                        break;
                    case 'operaTheme': $mapWidth = 686;$mapHeight = 360;
                        break;
                    case 'maestroTheme': $mapWidth = 494;$mapHeight = 252;
                        break;
                    //largeur du plus petit par défaut
                    default: $mapWidth = 494;$mapHeight = 252;
                        break;
                }
            
        $this->widgetSchema['widthImage']->setDefault($mapWidth);
        $this->widgetSchema['heightImage']->setDefault($mapHeight);
        $this->widgetSchema->setHelp('heightImage','Hauteur de l\'image');
    }
    /*
     * return record linked to the page
    */
    protected function getAutoRecord() {
        $record = sfcontext::getInstance()->getPage() ? sfcontext::getInstance()->getPage()->getRecord() : false;
        // return ancestor of
        
        return $record ? $record->getAncestorRecord('SidAddedPages') : false;
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
        
        return $this->getHelper()->renderPartial('affichageAddedPages', 'affichagePageForm', array(
            'form' => $this,
            'id' => 'sid_widget_added_pages' . $this->dmWidget->get('id')
        ));
    }
    public function getWidgetValues() {
        $values = parent::getWidgetValues();
        
        return $values;
    }
}
