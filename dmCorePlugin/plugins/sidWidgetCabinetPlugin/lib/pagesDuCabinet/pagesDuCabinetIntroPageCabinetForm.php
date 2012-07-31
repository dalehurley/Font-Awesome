<?php

class pagesDuCabinetIntroPageCabinetForm extends dmWidgetPluginForm {


    protected static $dmPageList = array();
    protected static $dmPagePosition = array();
    public function configure() {

        parent::configure();
        
        $pageCabinets = dmDb::table('SidCabinetPageCabinet') //->findAllBySectionId($vars['section']);
                ->createQuery('p')
                ->where('p.is_active = ?',true)
                ->orderBy('position ASC')
                ->execute();
        
       foreach($pageCabinets as $pageCabinet){
           self::$dmPageList[$pageCabinet->id] = $pageCabinet->getTitle();
           self::$dmPagePosition[$pageCabinet->position] = $pageCabinet->getTitle();
       }
       ksort(self::$dmPagePosition);
       reset(self::$dmPagePosition);
        
        $this->widgetSchema['page'] = new sfWidgetFormChoice(array('choices' => self::$dmPageList,'default' => current(self::$dmPagePosition))); 
        $this->validatorSchema['page'] = new sfValidatorChoice(array('required' => false,'choices' => array_keys(self::$dmPageList)));

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