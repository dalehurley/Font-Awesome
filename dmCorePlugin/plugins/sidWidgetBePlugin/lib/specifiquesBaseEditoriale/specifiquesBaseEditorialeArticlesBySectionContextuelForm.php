<?php

class specifiquesBaseEditorialeArticlesBySectionContextuelForm extends dmWidgetPluginForm {

    public function setup() {
        
        

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText(array('default' => 'Dossiers'));
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => true
                ));
        
        $this->widgetSchema['titreLien'] = new sfWidgetFormInputText(array());
        $this->validatorSchema['titreLien'] = new sfValidatorString(array(
                    'required' => false
                ));
        
        $this->widgetSchema['section'] = new sfWidgetFormDoctrineChoice(array(
            'model' => 'SidSection',
            'method' => 'show_section_rubrique',
            'multiple' => true,
            'expanded' => true,
            'table_method' => 'order_by_title'));
        $this->validatorSchema['section'] = new sfValidatorDoctrineChoice(array('model' => 'SidSection', 'multiple' => true, 'required' => true));
        
        $this->widgetSchema['longueurTexte'] = new sfWidgetFormInputText(array('default' => 0));
        $this->validatorSchema['longueurTexte'] = new sfValidatorInteger(array(
                    'required' => false
                ));
        
        $this->widgetSchema['nbArticle'] = new sfWidgetFormInputText(array('default' => 0));
        $this->validatorSchema['nbArticle'] = new sfValidatorInteger(array(
                    'required' => false
                ));

        $this->widgetSchema['isDossier'] = new sfWidgetFormInputCheckbox(array('default'=> false, 'label' => 'Afficher uniquement des dossiers'));
        $this->validatorSchema['isDossier']  = new sfValidatorBoolean();
        
        $this->widgetSchema['visibleInDossier'] = new sfWidgetFormInputCheckbox(array('default'=> false, 'label' => 'Visible dans page dossier'));
        $this->validatorSchema['visibleInDossier']  = new sfValidatorBoolean();
//        
        $this->widgetSchema->setHelps(array(
//            'm_rubriques_list' => 'Vous pouvez choisir les rubriques des article à afficher en page d\'accueil ou hors contexte',
            'titreBloc' => 'Le titre OBLIGATOIRE du bloc', 
            'titreLien' => "Le libellé du lien vers la liste de tous les contenus de la section choisie dans la rubrique affichée(contextuel).",	    
            'nbArticle' => 'Le nombre maximum d\'articles affichés.',            
            'longueurTexte' => 'Longueur du texte avant de la tronquer',
            'isDossier' => 'Pour afficher UNIQUEMENT des dossiers dans le bloc contextuel et ne le faire apparaitre que dans les pages article/show quand le DATATYPE de l\'article est ARTICLE UNIQUEMENT',
            'visibleInDossier' => 'Pour afficher ce bloc dans la page article/show quand le DATATYPE de l\'article est DOSSIER UNIQUEMENT'
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
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'articlesBySectionContextuelForm', array(
            'form' => $this,
            'id' => 'sid_widget_articles_contextuel_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}