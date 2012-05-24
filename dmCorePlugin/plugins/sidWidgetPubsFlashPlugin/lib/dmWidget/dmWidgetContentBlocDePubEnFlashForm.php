<?php

class dmWidgetContentBlocDePubEnFlashForm extends dmWidgetPluginForm {

    
    public function configure() {
        
        // requète pour trouver le répertoire des pubs flash
        $arrayRepPub = array();
        $requestRepPubs = dmDb::table('DmMediaFolder')
                ->createQuery('a')
                ->select('a.id')
                ->where('a.rel_path IN (\''.implode('\',\'', sfConfig::get('app_pub-flash_rep')).'\')')
                ->execute();
        
        // mise en tableau du résultat
        foreach($requestRepPubs as $requestRepPub){
            $arrayRepPub[$requestRepPub->id] = $requestRepPub->id;
        }
        
        // requète pour trouver les fichiers des pubs flash
        $pubFlashs = array();
        $requestPubFlashs = Dmdb::table('DmMedia')
                ->createQuery('a')
                ->where('a.dm_media_folder_id IN (\''.implode('\',\'', $arrayRepPub).'\')')
                ->execute();
        
        // mise en tableau du résultat
        foreach($requestPubFlashs as $requestPubFlash){
            $pubFlashs[$requestPubFlash->getFile()] = $requestPubFlash->getFile();
        }
        
        $this->widgetSchema['pubsId'] = new sfWidgetFormSelectCheckbox(array(
            'choices'=>$pubFlashs,
            'label'=> 'Choisir les pubs'
            ));
        
        $this->validatorSchema['pubsId'] = new sfValidatorChoice(array(
                        'choices' => array_keys($pubFlashs),
                        'required'=>true,
                        'multiple' => true
                        ));
//        $this->widgetSchema['pubsId'] = new sfWidgetFormDoctrineChoice(array(
//                    'model'=>'DmMediaFolder',
//                    'method'=>'getDmMediasByFileName',
//                    'label'=> 'Répertoire des pubs'
//                    ));
//        $this->validatorSchema['pubsId'] = new sfValidatorDoctrineChoice(array(
//                        'model' => 'DmMediaFolder',
//                        'required'=>true,
//                        ));
        
        $this->widgetSchema['width'] = new sfWidgetFormInputText(array('default' => 250)) ;
        $this->validatorSchema['width'] = new sfValidatorInteger(array('required' => true));
        
        $this->widgetSchema['height'] = new sfWidgetFormInputText(array('default' => 250)) ;
        $this->validatorSchema['height'] = new sfValidatorInteger(array('required' => true));
    
        $this->widgetSchema->setHelps(array(
            'pubsId' => 'Choisir les fichiers des pubs', 
            'width' => 'Choisir la largeur de la pub', 
            'height' => 'Choisir la largeur de la pub', 
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
//            'sidWidgetPubsFlashPlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('dmWidgetContentBlocDePubEnFlash', 'blocDePubsEnFlashForm', array(
            'form' => $this,
            'id' => 'sid_widget_pubs_flash_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}