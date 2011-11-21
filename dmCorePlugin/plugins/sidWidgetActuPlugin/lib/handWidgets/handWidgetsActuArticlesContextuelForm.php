<?php

class handWidgetsActuArticlesContextuelForm extends dmWidgetPluginForm {

    public function configure() {
        $this->widgetSchema['type'] = new sfWidgetFormDoctrineChoice(array(
                    'model' => 'SidActuType'
                ));
        $this->validatorSchema['type'] = new sfValidatorDoctrineChoice(array(
                    'required' => true,
                    'model' => 'SidActuType'
                ));

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText();
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => false
                ));
	
        $this->widgetSchema['titreLien'] = new sfWidgetFormInputText();
        $this->validatorSchema['titreLien'] = new sfValidatorString(array(
                    'required' => false
                ));	

        $this->widgetSchema['nbArticles'] = new sfWidgetFormInputText();
        $this->validatorSchema['nbArticles'] = new sfValidatorInteger(array(
                    'min' => 1,
                    'required' => true
                ));
        
        $this->widgetSchema['longueurTexte'] = new sfWidgetFormInputText(array('default' => 0));
        $this->validatorSchema['longueurTexte'] = new sfValidatorInteger(array(
                    'required' => false
                ));
        
        $this->widgetSchema['photo'] = new sfWidgetFormInputCheckbox(array('default'=> true));
        $this->validatorSchema['photo']  = new sfValidatorBoolean();
        
        
        $this->widgetSchema->setHelps(array(
            'type' => 'Le type de l\'article',
            'titreBloc' => 'Le titre optionnel du bloc.', 
            'titreLien' => "Le libellé du lien vers tous les articles actus.",	    
            'nbArticles' => 'Le nombre maximum d\'articles affichés.',            
            'longueurTexte' => 'Longueur du texte avant de la tronquer',
            'photo' => 'affiche ou pas la photo'
        ));

        parent::configure();
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
        return $this->getHelper()->renderPartial('handWidgets', 'actuArticlesContextuelForm', array(
            'form' => $this,
            'id' => 'sid_widget_actu_articles_contextuel_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}