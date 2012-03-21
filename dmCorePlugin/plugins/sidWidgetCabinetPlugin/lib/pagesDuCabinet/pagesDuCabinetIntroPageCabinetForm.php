<?php

class pagesDuCabinetIntroPageCabinetForm extends dmWidgetPluginForm {


    protected static $dmPageList = array();
    public function configure() {

        parent::configure();
        
        $pageCabinets = dmDb::table('SidCabinetPageCabinet') //->findAllBySectionId($vars['section']);
                ->createQuery('p')
                ->where('p.is_active = ?',true)
                ->orderBy('position')
                ->execute();
        
       foreach($pageCabinets as $pageCabinet){
           self::$dmPageList[$pageCabinet->id] = $pageCabinet->getTitle();
           
       }
        
        $this->widgetSchema['page'] = new sfWidgetFormChoice(array('choices' => self::$dmPageList)); 
        $this->validatorSchema['page'] = new sfValidatorChoice(array('choices' => array_keys(self::$dmPageList)));

        $this->widgetSchema['lien']->setDefault('Vers la page du cabinet');
        
        $this->widgetSchema->setHelp('page' ,'Choisir une page du cabinet');
          

        
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
//            'sidWidgetCabinetPlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('pagesDuCabinet', 'introPageCabinetForm', array(
            'form' => $this,
            'id' => 'sid_widget_intro_page cabinet_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}