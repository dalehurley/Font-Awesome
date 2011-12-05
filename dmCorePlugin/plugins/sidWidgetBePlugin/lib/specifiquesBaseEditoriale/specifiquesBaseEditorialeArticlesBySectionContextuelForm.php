<?php

class specifiquesBaseEditorialeArticlesBySectionContextuelForm extends dmWidgetPluginForm {

    public function setup() {
        
        

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText(array());
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => false
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

//        
        $this->widgetSchema->setHelps(array(
//            'm_rubriques_list' => 'Vous pouvez choisir les rubriques des article à afficher en page d\'accueil ou hors contexte',
            'titreBloc' => 'Le titre optionnel du bloc.', 
            'titreLien' => "Le libellé du lien vers tous les articles actus.",	    
            'nbArticle' => 'Le nombre maximum d\'articles affichés.',            
            'longueurTexte' => 'Longueur du texte avant de la tronquer',
//            'photo' => 'affiche ou pas la photo',
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