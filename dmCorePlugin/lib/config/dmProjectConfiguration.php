<?php

class dmProjectConfiguration extends sfProjectConfiguration {

    public function setup() {
        parent::setup();

        $this->setDmPluginPaths();

        $this->enablePlugins(array(
            'sfDoctrinePlugin',
            'dmCorePlugin',
            'dmUserPlugin',
            'sfWebBrowserPlugin',
            'sfImageTransformPlugin',
            'sfFeed2Plugin',
            'sfFormExtraPlugin',
            // les plugins additionnels
            'lfKrumoPlugin',
            'dmWidgetFeedReaderPlugin',
            'dmWidgetExcelPlugin', // plugin Excel
            'dmWidgetGalleryPlugin',
            'sfLESSPlugin',
            'dmContactMePlugin', // extension de dmcontactPlugin avec qaptcha
            'dmWidgetXmlPlugin', // plugin d'affichage de données Xml
            //'dmCkEditorPersoPlugin', // administrable par le client
            'dmFlowPlayerPlugin',
            'dmMediaUploadifyerPlugin',
            'dmBotPlugin',
            'dmWidgetExternalVideoPlugin',
            'dmCoreTranslatorPlugin',
            'sidWidgetBePlugin', // la base editoriale
            'dmWidgetGalleryBackgroundPlugin',
            'dmTagPlugin',
            'dmCkEditorPlugin',
            'dmWidgetNivoGalleryPlugin',
            'sidWidgetBandeauPlugin',
            // 'sidWidgetBlogPlugin',  // Finalisé mais inutile pour le moment
            'sidContentTemplateManagerPlugin', // plugin de gestion des template de contenu
            'sidWidgetConstantesPlugin', // plugin de gestion des constantes du site
            'sidWidgetSitesUtilesPlugin',
            'dmGoogleMapPlugin',
            'sidWidgetActuPlugin',
            'sidWidgetCabinetPlugin',
            'sidWidgetCoordonneesExpertPlugin',
            'sidSPLessCssPlugin',
            'sidWebservicesPlugin',
            'ckWebservicePlugin',
            'sidSiteMapPlugin',
            'sidWidgetPubsFlashPlugin',
            'sidWidgetSocialNetworkPlugin',
            'lioshiPlugin'            
            
        ));
    }

    protected function setDmPluginPaths() {
        $baseDir = dm::getDir();

        foreach (array('dmCorePlugin', 'dmAdminPlugin', 'dmFrontPlugin') as $rootPlugin) {
            $this->setPluginPath($rootPlugin, $baseDir . '/' . $rootPlugin);
        }

        foreach (array(
    'dmUserPlugin',
    'dmAlternativeHelperPlugin',
    'sfWebBrowserPlugin',
    'sfImageTransformPlugin',
    'sfFeed2Plugin',
    'sfFormExtraPlugin',
    'lfKrumoPlugin',
    'dmWidgetFeedReaderPlugin',
    'dmWidgetExcelPlugin',
    'dmWidgetGalleryPlugin',
    'sfLESSPlugin',
    'dmContactMePlugin',
    'dmWidgetXmlPlugin',
    //'dmCkEditorPersoPlugin',
    'dmFlowPlayerPlugin',
    'dmMediaUploadifyerPlugin',
    'dmBotPlugin',
    'dmWidgetExternalVideoPlugin',
    'dmCoreTranslatorPlugin',
    'sidWidgetBePlugin', // la base editoriale
    'dmWidgetGalleryBackgroundPlugin',
    'dmTagPlugin',
    'dmCkEditorPlugin',
    'dmWidgetNivoGalleryPlugin',
    'sidWidgetBandeauPlugin',
    //'sidWidgetBlogPlugin', 
    'sidContentTemplateManagerPlugin',
    'sidWidgetConstantesPlugin',
    'sidWidgetSitesUtilesPlugin',
    'dmGoogleMapPlugin',
    'sidWidgetActuPlugin',
    'sidWidgetCabinetPlugin',
    'sidWidgetCoordonneesExpertPlugin',
    'sidSPLessCssPlugin',
    'sidWebservicesPlugin',            
            'ckWebservicePlugin',
            'sidSiteMapPlugin',
            'sidWidgetPubsFlashPlugin',
            'sidWidgetSocialNetworkPlugin',
            'lioshiPlugin'              
        ) as $embeddedPlugin) {
            $this->setPluginPath($embeddedPlugin, $baseDir . '/dmCorePlugin/plugins/' . $embeddedPlugin);
        }
    }

    /*
     * @deprecated
     * Please use $this->setWebDir instead
     */

    public function setWebDirName($webDirName) {
        return $this->setWebDir(sfConfig::get('sf_root_dir') . '/' . $webDirName);
    }

    public function configureDoctrine(Doctrine_Manager $manager) {
        Doctrine_Core::debug(sfConfig::get('dm_debug'));

        /*
         * Configure inheritance
         */
        $manager->setAttribute(Doctrine_Core::ATTR_TABLE_CLASS, 'myDoctrineTable');
        $manager->setAttribute(Doctrine_Core::ATTR_QUERY_CLASS, 'myDoctrineQuery');
        $manager->setAttribute(Doctrine_Core::ATTR_COLLECTION_CLASS, 'myDoctrineCollection');

        /*
         * Configure charset
         */
        $manager->setCharset('utf8');
        $manager->setCollate('utf8_unicode_ci');

        /*
         * Configure hydrators
         */
        $manager->registerHydrator('dmFlat', 'Doctrine_Hydrator_dmFlatDriver');

        /*
         * Configure builder
         */
        sfConfig::set('doctrine_model_builder_options', array(
            'generateTableClasses' => true,
            'baseClassName' => 'myDoctrineRecord',
            'baseTableClassName' => 'myDoctrineTable',
            'suffix' => '.class.php'
        ));
    }

    protected function configureDoctrineCache(Doctrine_Manager $manager) {
        if (sfConfig::get('dm_orm_cache_enabled', true) && dmAPCCache::isEnabled()) {
            $driver = new Doctrine_Cache_Apc(array('prefix' => dmProject::getNormalizedRootDir() . '/doctrine/'));

            $manager->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, $driver);
            $manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE, $driver);
            $manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE_LIFESPAN, sfConfig::get('dm_orm_cache_lifespan', 3600));
        }
    }

}