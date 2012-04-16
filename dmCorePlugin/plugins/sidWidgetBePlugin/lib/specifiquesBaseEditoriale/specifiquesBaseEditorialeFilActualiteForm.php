<?php

class specifiquesBaseEditorialeFilActualiteForm extends dmWidgetPluginForm {

    public function configure() {
        
        parent::configure();

        $this->widgetSchema['section'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidSection',
                    'method' => 'show_section_rubrique',
//                    'group_by' => 'title',
//                    'order_by' => array('name', 'asc'),
                    'multiple' => true,
                    'expanded' => true,
                    'table_method' => 'order_by_title'
                ));

        $this->validatorSchema['section'] = new sfValidatorDoctrineChoice(array('model' => 'SidSection', 'multiple' => true, 'required' => true));

        $this->widgetSchema['titreBloc']->setDefault('Actualités');
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => true
                ));

        $this->widgetSchema['lien']->setDefault('Nos articles en');

      
        $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
                    'required' => true
                ));
                
        $this->widgetSchema['justTitle'] = new sfWidgetFormInputCheckbox(array('default'=> false, 'label' => 'Afficher UNIQUEMENT le titre (maestro)'));
        $this->validatorSchema['justTitle']  = new sfValidatorBoolean();
        
        $this->widgetSchema->setHelps(array(
            'titreBloc' => 'Le titre OBLIGATOIRE du bloc.',
            'section' => 'La section à afficher',
            'photo' => 'affiche ou pas la photo',
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
//            'sidWidgetBePlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'filActualiteForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_fil_actualite_' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}