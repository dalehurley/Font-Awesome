<?php

class lienContactBlocVersPageContactForm extends dmWidgetPluginForm {

    public function configure() {
        
        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText(array('default' =>'Contact'));
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => true
                ));
        
        $this->widgetSchema['message'] = new sfWidgetFormInputText(array('default' => 'Vous recherchez un expert-comptable ?'));
        $this->validatorSchema['message'] = new sfValidatorString(array(
                    'required' => true
                ));
        
//        $this->widgetSchema['titreLien'] = new sfWidgetFormInputText(array('default' => 'Contactez-nous'));
//        $this->validatorSchema['titreLien'] = new sfValidatorString(array(
//                    'required' => true
//                ));
        
        $this->widgetSchema->setHelps(array(
            'message' => 'Le message qui apparaitra dans le bloc',
            'titreBloc' => 'Le titre du bloc.',
//            'titreLien' => 'Le titre du lien.',
        ));

        parent::configure();
    }

    public function getStylesheets() {
        return array(
    //        'lib.ui-tabs'
        );
    }

    public function getJavascripts() {
        return array(
        //    'lib.ui-tabs',
        //    'core.tabForm',
        //    'dmContactMePlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('lienContact', 'blocVersPageContactForm', array(
            'form' => $this,
            'id' => 'dm_contact_me_plugin_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}