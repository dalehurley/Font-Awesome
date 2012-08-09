<?php

class specifiquesBaseEditorialeListArticleGridImagesForm extends dmWidgetPluginForm {

    public function configure() {

        parent::configure();
        
        $this->widgetSchema['nbImagesByLine'] = new sfWidgetFormInputText(array('default' => 5));
        $this->validatorSchema['nbImagesByLine'] = new sfValidatorInteger(array(
                    'required' => true
                ));

        $this->widgetSchema['nbLines'] = new sfWidgetFormInputText(array('default' => 4));
        $this->validatorSchema['nbLines'] = new sfValidatorInteger(array(
                    'required' => true
                ));

        $this->widgetSchema['containerWidth'] = new sfWidgetFormInputText(array('default' => '500'));
        $this->validatorSchema['containerWidth'] = new sfValidatorInteger(array(
                    'required' => true
                ));

        $this->widgetSchema['interval'] = new sfWidgetFormInputText(array('default' => 500));
        $this->validatorSchema['interval'] = new sfValidatorInteger(array(
                    'required' => true
                ));   

        $this->widgetSchema['animSpeed'] = new sfWidgetFormInputText(array('default' => 500));
        $this->validatorSchema['animSpeed'] = new sfValidatorInteger(array(
                    'required' => true
                )); 

        $this->widgetSchema['maxStep'] = new sfWidgetFormInputText(array('default' => 3));
        $this->validatorSchema['maxStep'] = new sfValidatorInteger(array(
                    'required' => true
                ));         

        $this->widgetSchema->setHelps(array(
            'nbImagesByLine' => "Nombre d'images par ligne.", 
            'nbLines' => "Nombre de lignes.", 
            'containerWidth' => "La largeur du conteneur.",      
            'interval' => 'Intervalle entre chaque action (en ms).',
            'animSpeed' => "Vitesse de l'animation (defaut 500).",
            'maxStep' => 'Nombre maximum de changements simultanés.'                     
        ));


    }


    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'listArticlesGridImagesForm', array(
            'form' => $this,
            'id' => 'sid_widget_list_articles_grid_images_' . $this->dmWidget->get('id')
        ));
    }


}