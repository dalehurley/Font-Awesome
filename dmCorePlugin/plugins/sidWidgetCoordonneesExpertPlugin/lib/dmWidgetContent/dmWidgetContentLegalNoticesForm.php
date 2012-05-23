<?php

class dmWidgetContentLegalNoticesForm extends dmWidgetPluginForm {

    public function configure() {

        parent::configure();
        
        $this->widgetSchema['defaultInfos'] = new sfWidgetFormInputCheckbox(array('default'=> true, 'label' => 'Afficher les infos par défauts'));
        $this->validatorSchema['defaultInfos']  = new sfValidatorBoolean();
        
        $this->widgetSchema['text'] = new sfWidgetFormTextarea(array('default' => ''));
        $this->validatorSchema['text'] = new sfValidatorString(array('required' => false));

        $this->widgetSchema->setHelp('defaultInfos', 'sinon ressaisir ENTIEREMENT les notices légales');
        
    }

    public function getStylesheets() {
        return array(
//      'lib.ui-tabs',
      'lib.markitup',
      'lib.markitupSet',
//      'lib.ui-resizable'
        );
    }

    public function getJavascripts() {
        return array(
//      'lib.ui-tabs',
      'lib.markitup',
      'lib.markitupSet',
//      'lib.ui-resizable',
//      'lib.fieldSelection',
//      'core.tabForm',
      'core.markdown'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('dmWidgetContent', 'legalNoticesForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_coordonnees_expert' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}
