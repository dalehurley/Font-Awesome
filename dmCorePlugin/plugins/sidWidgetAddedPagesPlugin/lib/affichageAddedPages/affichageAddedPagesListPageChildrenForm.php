<?php

class affichageAddedPagesListPageChildrenForm extends dmWidgetPluginForm {
    public function configure() {
        
        parent::configure();
        
        $this->widgetSchema['nbArticles']->setDefault(0);
        $this->widgetSchema->setHelp('nbArticles','0 pour tout afficher');
        $this->widgetSchema['withDate'] = new sfWidgetFormInputCheckbox(array('default'=> true, 'label' => 'Date de publication visible'));
        $this->validatorSchema['withDate']  = new sfValidatorBoolean();
        $this->widgetSchema->setHelp('withDate' , 'Affichage de la date de publication');

        $this->widgetSchema['withResume'] = new sfWidgetFormInputCheckbox(array('default'=> true, 'label' => 'Résumé visible'));
        $this->validatorSchema['withResume']  = new sfValidatorBoolean();
        $this->widgetSchema->setHelp('withResume' , 'Affichage du résumé');
        
        $this->widgetSchema['nbImages'] = new sfWidgetFormInputText(array('label' => 'Nbre d\'image dans les listing', 'default' => 0));
        $this->validatorSchema['nbImages'] = new sfValidatorInteger(array(
                'required' => false,
                ));
        $this->widgetSchema->setHelp('nbImages','0 pour mettre des images à tous les lists');
       
        switch (dmConfig::get('site_theme')) {
                    case 'BaseTheme': $mapWidth = 238;$mapHeight = 85;
                        break;
                    case 'copilotesTheme': $mapWidth = 238;$mapHeight = 85;
                        break;
                    case 'operaTheme': $mapWidth = 238;$mapHeight = 101;
                        break;
                    case 'maestroTheme': $mapWidth = 174;$mapHeight = 73;
                        break;
                    //largeur du plus petit par défaut
                    default: $mapWidth = 238;$mapHeight = 85;
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
        
        return $this->getHelper()->renderPartial('affichageAddedPages', 'listPageChildrenForm', array(
            'form' => $this,
            'id' => 'sid_widget_added_pages' . $this->dmWidget->get('id')
        ));
    }
    public function getWidgetValues() {
        $values = parent::getWidgetValues();
        
        return $values;
    }
}
