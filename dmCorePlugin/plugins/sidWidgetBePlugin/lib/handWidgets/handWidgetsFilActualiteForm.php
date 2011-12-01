<?php

class handWidgetsFilActualiteForm extends dmWidgetPluginForm {

    public function setup() {

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

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText();
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => true
                ));

        $this->widgetSchema['titreLien'] = new sfWidgetFormInputText();
        $this->validatorSchema['titreLien'] = new sfValidatorString(array(
                    'required' => true
                ));

        $this->widgetSchema['longueurTexte'] = new sfWidgetFormInputText(array('default' => 0));
        $this->validatorSchema['longueurTexte'] = new sfValidatorInteger(array(
                    'required' => false
                ));

        $this->widgetSchema['nbArticle'] = new sfWidgetFormInputText(array('default' => 1));
        $this->validatorSchema['nbArticle'] = new sfValidatorInteger(array(
                    'required' => true
                ));


        $this->widgetSchema->setHelps(array(
            'titreBloc' => 'Le titre OBLIGATOIRE du bloc.',
            'titreLien' => 'Le titre OBLIGATOIRE du lien vers les articles de la section.',
            'longueurTexte' => 'Longueur du texte avant de la tronquer',
            'nbArticle' => 'Le nbre d\'articles à afficher',
            'section' => 'La section à afficher'
        ));

        parent::setup();
    }

    public function getStylesheets() {
        return array(
            'lib.ui-tabs'
        );
    }

    public function getJavascripts() {
        return array(
            'lib.ui-tabs',
            'core.tabForm',
            'sidWidgetBePlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('handWidgets', 'filActualiteForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_fil_actualite_' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}