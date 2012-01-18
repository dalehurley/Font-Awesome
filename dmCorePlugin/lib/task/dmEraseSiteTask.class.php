<?php

class dmEraseSiteTask extends dmContextTask {

    protected function configure() {
        // // add your own arguments here
         $this->addArguments(array(
           
         ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = 'dm';
        $this->name = 'erase-site';
        $this->briefDescription = 'Erase a site';
        $this->detailedDescription = <<<EOF
The [erase-site|INFO] Erase a site.
Call it with:

  [php symfony dm:erase-site |INFO]
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

	// chargement des images de LEA ?
	if ($this->askConfirmation(array('Effacer le site completement ? ATTENTION IRREVERSIBLE (y/n)'), 'QUESTION_LARGE', true)) {
	    if ($this->askConfirmation(array('Vraiment sur ? (y/n)'), 'QUESTION_LARGE', true)) {
		
		$this->logSection('Erase', '...');

		// récupération du nom de domaine du site via la table dmSetting
		$settings = dmDb::table('DmSetting')->findAll();
		foreach ($settings as $setting) {
			if ($setting->name == 'base_urls'){
				echo '>>>>'.$setting->getValue();		
			}			
		}




		// suppression des fichiers ayant comme propriétaire apache, ou www-user... etc
		// création d'un fichier /htdocs/delete.php contenant la routine de suppression des fichiers créé par apache via appel du navigateur
		
		// appel de la page delete.php
		$command = "wget ''";
		//exec($command, $output);	

		// suppression des fichiers créé par l'utilisateur lors de l'installation du site
		$command = "rm -Rf *";
		//exec($command, $output);		
		
	    } else {
		$this->logSection('Annulation', '...');
	    }
	} else {
	    $this->logSection('Annulation','...');
	}
    }

}