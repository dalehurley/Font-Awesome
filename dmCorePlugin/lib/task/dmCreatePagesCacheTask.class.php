<?php

class dmCreatePagesCacheTask extends lioshiBaseTask {
    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array());
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name') ,
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod') ,
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine') ,
            new sfCommandOption('lang', null, sfCommandOption::PARAMETER_REQUIRED, 'The language of site', 'fr') ,
            new sfCommandOption('nb', null, sfCommandOption::PARAMETER_REQUIRED, 'Number of pages to make in cache (in datatbase order)', 0) ,
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
        if (isset($options['nb'])) {
            $nbPages = array(
                1 => '1',
                2 => '10',
                3 => '50',
                4 => '100',
                5 => 'toutes'
            );
            $this->logBlock('Nombre de pages a generer ?', 'QUESTION_LARGE');
            
            foreach ($nbPages as $k => $nbPage) {
                $this->logSection($k, $nbPage);
            }
            // choix du dump
            $options['nb'] = $this->askAndValidate(array(
                '',
                'Le nombre choisi?',
                ''
            ) , new sfValidatorChoice(array(
                'choices' => array_keys($nbPages) ,
                'required' => true
            ) , array(
                'invalid' => 'choix inconnu'
            )));
        }
        // message de gnération
        
        switch ($options['nb']) {
            case 5:
                $message = 'Générer le cache entier du site';
                break;

            default:
                $message = 'Générer le cache des ' . $nbPages[$options['nb']] . ' premières pages du site';
                break;
        }
        if ($this->askConfirmation(array(
            $message . ' ? (y/n)'
        ) , 'QUESTION_LARGE', true)) {
            $pageCacheConfig = sfConfig::get('dm_performance_page_cache');
            if ($pageCacheConfig && $pageCacheConfig['enabled']) {
                $this->logSection('Total cache activated', '...');
            }
            // récupération du nom de domaine du site via la table dmSetting
            $settings = dmDb::query('DmSetting s')->withI18n($options['lang'])->where('s.name = ?', 'base_urls')->limit(1)->fetchRecords();
            
            foreach ($settings as $setting) {
            //    $this->logBlock($setting->Translation[$options['lang']]->value, 'HELP');
                
                // une liste json des url (les controleurs) utilisées dans le site, pour chaque app et environnement accédés via un navigateur
                $siteEnvsUrl = json_decode($setting->Translation[$options['lang']]->value);
            }
            if (!is_object($siteEnvsUrl)) {
                $this->logBlock('Impossible de trouver le nom de domaine du site. Merci de naviguer sur le site.', 'ERROR');
                exit;
            }
            $envsIndex = get_object_vars($siteEnvsUrl);
            // on récupère le premier controleur disponible
            
            foreach ($envsIndex as $key => $value) {
                $this->logBlock('Contrôleur : '.$value, 'HELP');

                $firstController = $value;
                break;
            }
            // http root
            $rootUrl = substr($firstController, 0, strrpos($firstController, '/'));
            // récupération des pages du sites
            $dmPages = dmDb::query('DmPage p')->withI18n($options['lang'])->where('pTranslation.is_active = true')->andWhere('p.action != ?', array(
                'error404'
            ))->fetchRecords();
            $nb = 0;
            $timeBegin = microtime(true);
            $execTimeGlobal = 0;
            
            foreach ($dmPages as $dmPage) {
                // nb de pages à traiter
                if ($nb >= $nbPages[$options['nb']] && $nbPages[$options['nb']] != 0) break;

                $nb++;
                $timeBeginPage = microtime(true);
                try {
                    // affichage de la page
                    $dmPageUrl = $rootUrl . '/' . $dmPage->Translation[$options['lang']]->get('slug');
                    // chargement de la page
                    $pageSite = file_get_contents($dmPageUrl);
                    $execTime = substr((microtime(true) - $timeBeginPage) , 0, 5);
                    $execTimeGlobal = $execTimeGlobal + $execTime;
                    $this->logSection($nb . ' (' . substr($execTime, 0, 5) . 's)', $dmPageUrl);
                }
                catch(Exception $e) {
                    $this->logSection('Soucis sur la page', $dmPageUrl);
                }
            }
            // compte rendu global
            $this->logSection('Nb pages', $nb);
            $this->logSection('Execution time total', substr((microtime(true) - $timeBegin) , 0, 5) . 's');
            $this->logSection('Execution avg time by page', substr($execTimeGlobal / $nb, 0, 5) . 's/page');
        } else {
            $this->logSection('Annulation', '...');
        }
    }
}
