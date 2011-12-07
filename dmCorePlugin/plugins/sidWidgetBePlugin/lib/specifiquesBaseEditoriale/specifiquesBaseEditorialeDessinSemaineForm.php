<?php

class specifiquesBaseEditorialeDessinSemaineForm extends dmWidgetPluginForm {
    
    protected static $effects = array(
            'slide-hori' => 'slide horizontal', 
            'slide-vert' => 'slide vertical', 
            'fade' => 'fondu', 
            'resize' => 'redimensionnement',             
            'none' => 'aucun'
            );

    public function configure() {

        $this->widgetSchema['title'] = new sfWidgetFormInputText(array('default' => 'Dessin de la semaine'));
        $this->validatorSchema['title'] = new sfValidatorString(array('required' => true));

        //effect: 'fade' // or 'slide-hori', 'slide-vert', 'fade', or 'resize', 'none'
        $this->widgetSchema['effect'] = new sfWidgetFormchoice(array('choices' => self::$effects)); 
        $this->validatorSchema['effect'] = new sfValidatorChoice(array('choices' => array_keys(self::$effects)));
        
        $this->widgetSchema->setHelps(array(
            'title' => 'Personnaliser le titre du widget',
            'effect' => "L'effet de transition",
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
            'core.tabForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'dessinSemaineForm', array(
            'form' => $this,
            'id' => 'sid_widget_dessin_semaine_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}