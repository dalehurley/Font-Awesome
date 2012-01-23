<?php

class dmCreatePagesCacheTask extends dmContextTask {
    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array());
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name') ,
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev') ,
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine') ,
            new sfCommandOption('lang', null, sfCommandOption::PARAMETER_REQUIRED, 'The language of site', 'fr') ,
            // add your own options here
            
        ));
        $this->namespace = 'dm';
        $this->name = 'create-pages-cache';
        $this->briefDescription = 'Create all the pages cache';
        $this->detailedDescription = <<<EOF
The [create-pages-cache|INFO] Create all the pages cache.
Call it with:

  [php symfony dm:create-pages-cache |INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        if ($this->askConfirmation(array(
            'Générer le cache entier du site ? (y/n)'
        ) , 'QUESTION_LARGE', true)) {
            $pageCacheConfig = sfConfig::get('dm_performance_page_cache');
            if (!$pageCacheConfig || !$pageCacheConfig['enabled']) {
                $this->logSection('No cache activated', '...');
            }
            $this->logSection('Create all cache page', '...');
            // récupération du nom de domaine du site via la table dmSetting
            $settings = dmDb::query('DmSetting s')->withI18n($options['lang'])->where('s.name = ?', 'base_urls')->limit(1)->fetchRecords();
            
            foreach ($settings as $setting) {
                // une liste json des url (les controleurs) utilisées dans le site, pour chaque app et environnement accédés via un navigateur
                $siteEnvsUrl = json_decode($setting->Translation[$options['lang']]->value);
            }
            $envsIndex = get_object_vars($siteEnvsUrl);
            // on récupère le premier controleur disponible
            
            foreach ($envsIndex as $key => $value) {
                $firstController = $value;
                break;
            }
            // http root
            $rootUrl = substr($firstController, 0, strrpos($firstController, '/'));
            // récupération des pages du sites
            $dmPages = dmDb::query('DmPage p')->withI18n($options['lang'])->where('pTranslation.is_active = true')->andWhere('p.action != ?', array(
                'error404'
            ))->fetchRecords();
            $nb = 1;
            $timeBegin = microtime(true);
            
            foreach ($dmPages as $dmPage) {
                //if ($nb > 5) break;
                $timeBeginPage = microtime(true);
                try {
                    // affichage de la page
                    $dmPageUrl = $rootUrl . '/' . $dmPage->Translation[$options['lang']]->get('slug');
                    // chargement de la page
                    $pageSite = file_get_contents($dmPageUrl);

                    $this->logSection($nb . ' (' . substr((microtime(true) - $timeBeginPage),0,5) . 's)', $dmPageUrl);
                    $nb++;
                }
                catch(Exception $e) {
                    $this->logSection('Soucis sur la page', $dmPageUrl);
                }
            }
            $this->logSection('Execution time', (microtime(true) - $timeBegin) . 's');
        } else {
            $this->logSection('Annulation', '...');
        }
    }
}
