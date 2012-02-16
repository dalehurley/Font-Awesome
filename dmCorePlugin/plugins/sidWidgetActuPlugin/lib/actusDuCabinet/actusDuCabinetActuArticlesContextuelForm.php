<?php

class actusDuCabinetActuArticlesContextuelForm extends dmWidgetPluginForm {

    public function configure() {
        $this->widgetSchema['type'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidActuType'
                ));
        $this->validatorSchema['type'] = new sfValidatorDoctrineChoice(array(
                    'required' => true,
                    'model' => 'SidActuType'
                ));

        $this->widgetSchema['title_page'] = new sfWidgetFormInputText();
        $this->validatorSchema['title_page'] = new sfValidatorString(array(
                    'required' => false
                ));
	
        $this->widgetSchema['lien'] = new sfWidgetFormInputText(array('default' => 'TOUTES LES ACTUALITES DU CABINET'));
        $this->validatorSchema['lien'] = new sfValidatorString(array(
                    'required' => true
                ));	

        $this->widgetSchema['nbArticles'] = new sfWidgetFormInputText();
        $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
                    'min' => 1,
                    'required' => true
                ));
        
        $this->widgetSchema['length'] = new sfWidgetFormInputText(array('default' => 0));
        $this->validatorSchema['length'] = new sfValidatorInteger(array(
                    'required' => false
                ));
        
        $this->widgetSchema['chapo'] = new sfWidgetFormSelectRadio(array('choices' => array('chapeau','texte'),'default'=>1));
        $this->validatorSchema['chapo']  = new sfValidatorChoice(array('choices' =>array(0,1),'multiple' => false));
        
        $this->widgetSchema->setHelps(array(
            'type' => 'Le type de l\'article',
            'title_page' => 'Le titre optionnel du bloc.', 
            'lien' => "Le libellé du lien vers tous les articles actus.",	    
            'nbArticles' => 'Le nombre maximum d\'articles affichés.',            
            'length' => 'Longueur du texte avant de la tronquer',
            'withImage' => 'affiche ou pas la photo',
            'chapo' => 'Choisir si on veut afficher le résumé de la page ou le contenu entier de la page'
        ));

        parent::configure();
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
        return $this->getHelper()->renderPartial('actusDuCabinet', 'actuArticlesContextuelForm', array(
            'form' => $this,
            'id' => 'sid_widget_actu_articles_contextuel_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}