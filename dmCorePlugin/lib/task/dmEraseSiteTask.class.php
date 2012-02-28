<?php

class dmEraseSiteTask extends lioshiBaseTask {
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
        $this->name = 'erase-site';
        $this->briefDescription = 'Erase a site';
        $this->detailedDescription = <<<EOF
The [erase-site|INFO] Erase a site. Can erase file created by installer and file created by apache.
Call it with:

  [php symfony dm:erase-site |INFO]
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
            'Effacer le site completement ? ATTENTION IRREVERSIBLE (y/n)'
        ) , 'QUESTION_LARGE', true)) {
            if ($this->askConfirmation(array(
                'Vraiment sur ? (y/n)'
            ) , 'QUESTION_LARGE', true)) {
                
                // récupération du nom de domaine du site via la table dmSetting
                $settings = dmDb::query('DmSetting s')
                ->withI18n($options['lang'])
                ->where('s.name = ?', 'base_urls')
                ->limit(1)
                ->fetchRecords();
                
                foreach ($settings as $setting) {
                    // une liste json des url (les controleurs) utilisées dans le site, pour chaque app et environnement accédés via un navigateur
                    $siteEnvsUrl = json_decode($setting->Translation[$options['lang']]->value);
                }
                $envsIndex = get_object_vars($siteEnvsUrl);
                // on récupère le premier controleur disponible
                
                foreach ($envsIndex as $key => $value) {
                    $fileDeleteUrl = $value;
                    break;
                }
                // url du fichier delete.php
                $fileDeleteUrl = substr($fileDeleteUrl, 0, strrpos($fileDeleteUrl, '/')) . '/delete.php';
                /****************************************************************************************************
                 ************ suppression des fichiers ayant comme propriétaire apache, ou www-user... etc ***********
                 *****************************************************************************************************/
                // création d'un fichier /htdocs/delete.php contenant la routine de suppression des fichiers créé par apache via appel du navigateur
                $fileDelete = getcwd() . "/htdocs/delete.php";
                $f = fopen($fileDelete, "w");
                $fileDeleteContent = <<<EOF
<?php
	ini_set('display_errors', 0);
	ini_set('log_errors', 0);
    exec('chmod -R 777 '.getcwd().'/../');
    echo json_encode(array('success' => true));
?>
EOF;
                fputs($f, $fileDeleteContent . "\n");
                fclose($f);
                // appel de la page delete.php via wget pour simuler une utilisation d'un navigateur
                $result = json_decode(file_get_contents($fileDeleteUrl),true);
                if (isset($result['success']) && $result['success']){
                  $this->logBlock('Clear site from apache user via wget successed', 'INFO');
                } else {
                  $this->logBlock('Clear site from apache user via wget failed', 'ERROR');          
                }
                unlink($fileDelete);
                /****************************************************************************************************
                 ********* suppression des fichiers créé par l'utilisateur lors de l'installation du site ************
                 *****************************************************************************************************/
                $command = "rm -Rf *";
                $this->logBlock('Suppression totale via rm -rf *', 'INFO');
                exec($command, $output);
            } else {
                $this->logBlock('Annulation', 'ERROR');
            }
        } else {
            $this->logBlock('Annulation', 'ERROR');
        }
    }
}
