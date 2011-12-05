<?php

class specifiquesBaseEditorialeArticlesContextuelForm extends dmWidgetPluginForm {

    public function setup() {
        
        

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText(array('default' => 'Actualités'));
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => false
                ));
        
        $this->widgetSchema['m_rubriques_list_1'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidSection',
                    'method' => 'show_rubrique_section',
                    'multiple' => false,
                    'expanded' => false,
                    'add_empty' => '-- choisir rubrique --',
                    'label' => 'Rubrique'));
        $this->validatorSchema['m_rubriques_list_1'] = new sfValidatorDoctrineChoice(array('model' => 'SidSection', 'multiple' => false, 'required' => false, 'empty_value' => '0'));
//	
        $this->widgetSchema['titreLien_1'] = new sfWidgetFormInputText(array('label' => 'Titre du lien'));
        $this->validatorSchema['titreLien_1'] = new sfValidatorString(array(
                    'required' => false
                ));
        
        $this->widgetSchema['m_rubriques_list_2'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidSection',
                    'method' => 'show_rubrique_section',
                    'multiple' => false,
                    'expanded' => false,
                    'add_empty' => '-- choisir rubrique --',
            'label' => 'Rubrique'));
        $this->validatorSchema['m_rubriques_list_2'] = new sfValidatorDoctrineChoice(array('model' => 'SidSection','multiple' => false, 'required' => false, 'empty_value' => '0'));
//        $this->validatorSchema['m_rubriques_list_2'] = new sfValidatorInteger(array('required' => false));
//	
        $this->widgetSchema['titreLien_2'] = new sfWidgetFormInputText(array('label' => 'Titre du lien'));
        $this->validatorSchema['titreLien_2'] = new sfValidatorString(array(
                    'required' => false
                ));
//        
        $this->widgetSchema['m_rubriques_list_3'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidSection',
                    'method' => 'show_rubrique_section',
                    'multiple' => false,
                    'expanded' => false,
                    'add_empty' => '-- choisir rubrique --',
            'label' => 'Rubrique'));
        $this->validatorSchema['m_rubriques_list_3'] = new sfValidatorDoctrineChoice(array('model' => 'SidSection', 'multiple' => false, 'required' => false, 'empty_value' => '0'));
//	
        $this->widgetSchema['titreLien_3'] = new sfWidgetFormInputText(array('label' => 'Titre du lien'));
        $this->validatorSchema['titreLien_3'] = new sfValidatorString(array(
                    'required' => false
                ));
        
        $this->widgetSchema['longueurTexte'] = new sfWidgetFormInputText(array('default' => 0));
        $this->validatorSchema['longueurTexte'] = new sfValidatorInteger(array(
                    'required' => false
                ));
//        
        $this->widgetSchema['photo'] = new sfWidgetFormInputCheckbox(array('default'=> true));
        $this->validatorSchema['photo']  = new sfValidatorBoolean();
//        
//        $this->widgetSchema['chapo'] = new sfWidgetFormSelectRadio(array('choices' => array('chapeau','texte'),'default'=>1));
//        $this->validatorSchema['chapo']  = new sfValidatorChoice(array('choices' =>array(0,1),'multiple' => false));
//        
        $this->widgetSchema->setHelps(array(
//            'm_rubriques_list' => 'Vous pouvez choisir les rubriques des article à afficher en page d\'accueil ou hors contexte',
            'titreBloc' => 'Le titre optionnel du bloc.', 
            'titreLien' => "Le libellé du lien vers tous les articles actus.",	    
//            'nbArticles' => 'Le nombre maximum d\'articles affichés.',            
            'longueurTexte' => 'Longueur du texte avant de la tronquer',
            'photo' => 'affiche ou pas la photo',
//            'chapo' => 'Choisir si on veut afficher le résumé de la page ou le contenu entier de la page'
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
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'articlesContextuelForm', array(
            'form' => $this,
            'id' => 'sid_widget_articles_contextuel_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}