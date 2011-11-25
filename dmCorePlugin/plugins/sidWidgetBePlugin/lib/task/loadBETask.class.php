<?php

class loadBETask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('verbose', sfCommandArgument::OPTIONAL, 'Verbose task'),
            new sfCommandArgument('automatic', sfCommandArgument::OPTIONAL, 'Automatic, no confirmation'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'front'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('idArticlePlusVieux', null, sfCommandOption::PARAMETER_REQUIRED, 'idArticlePlusVieux', '0'), // l'id le plus vieux à prendre en compte
                // add your own options here
        ));

        $this->namespace = 'be';
        $this->name = 'loadBE';
        $this->briefDescription = 'Loads articles in DB. Load XML and pictures files from LEA into Base Editoriale dir (see app.yml for dir config)';
        $this->detailedDescription = <<<EOF
The [loadBE|INFO] task does things.
  This task loads all articles and XML files from LEA.
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
//-------------------------------------------------------------------------------------
//    Verification de la présence des dossiers
//-------------------------------------------------------------------------------------	

        if (!is_dir(sfConfig::get('app_rep-local'))) {
                $this->logSection('ERROR','Veuillez créer le dossier '.sfConfig::get('app_rep-local'), null, 'ERROR');
                exit;        
        }
        if (!is_dir(sfConfig::get('app_rep-local-json'))) {
                $this->logSection('ERROR','Veuillez créer le dossier '.sfConfig::get('app_rep-local-json'), null, 'ERROR');
                exit;        
        }
        if (!is_dir(sfConfig::get('app_rep-local-images'))) {
                $this->logSection('ERROR','Veuillez créer le dossier '.sfConfig::get('app_rep-local-images'), null, 'ERROR');
                exit;        
        }        

//-------------------------------------------------------------------------------------
//    Chargement des rubriques de LEA dans la base de donnees locale
//-------------------------------------------------------------------------------------	
	if (in_array("automatic", $arguments) || $this->askConfirmation(array("Chargement de l'arbo des dossiers rubrique/section de LEA \n dans le repertoire ".sfConfig::get('app_rep-local')." la base de donnees locale? (y/n)"), 'QUESTION_LARGE', true)) {
	    $beginTime = microtime(true);
	    $results = baseEditorialeTools::recupRubriqueSection();
	    $this->logSection('### loadBE', 'Chargement des rubriques de LEA dans la base de donnees locale.' . ' ->' . (microtime(true) - $beginTime) . ' s');
	    if (in_array("verbose", $arguments)) {

		foreach ($results as $result) {
		    foreach ($result as $log => $desc) {
			$this->logSection(utf8_decode($log), utf8_decode($desc));
		    }
		}
	    }
	} else {
	    $this->logSection('>>', 'Pas de chargement des rubriques en base de donnees locale.');
	}

	
//-------------------------------------------------------------------------------------
//    recuperation WGET des XML
//-------------------------------------------------------------------------------------	
	// chargement des XML et images de LEA ?
	if ($this->askConfirmation(array('Charger les fichiers  XML dans le repertoire local ? (y/n)'), 'QUESTION_LARGE', true)) {
	    $results = baseEditorialeTools::recupFilesXmlLEA();
	    $this->logSection('>>', 'Chargement des XML ...');
	} else {
	    $this->logSection('>>', 'Pas de chargement des XML.');
	}	
	
	
//-------------------------------------------------------------------------------------
//    recuperation WGET des images 
//-------------------------------------------------------------------------------------	
	// chargement des images de LEA ?
	if ($this->askConfirmation(array('Charger les fichiers images dans le repertoire local ? (y/n)'), 'QUESTION_LARGE', true)) {
	    $results = baseEditorialeTools::recupFilesImagesLEA();
	    $this->logSection('>>', 'Chargement des images...');
	} else {
	    $this->logSection('>>', 'Pas de chargement des images.');
	}

//-------------------------------------------------------------------------------------
//    ecriture des articles en base ?
//-------------------------------------------------------------------------------------	
	if ($this->askConfirmation(array('Lecture des fichiers XML locaux pour creer les articles dans la base de donnees locale? (y/n)'), 'QUESTION_LARGE', true)) {

	    $beginTime = microtime(true);
	    $results = baseEditorialeTools::recupArticlesLEA($options['idArticlePlusVieux'], $wgetActive);
	    $this->logSection('### loadBE', 'Chargement des articles en XML (filename > ' . $options['idArticlePlusVieux'] . ') de LEA dans la base editoriale.' . ' ->' . (microtime(true) - $beginTime) . ' s');
	    if (in_array("verbose", $arguments)) {

		foreach ($results as $result) {
		    foreach ($result as $log => $desc) {
			$this->logSection(utf8_decode($log), utf8_decode($desc));
		    }
		}
	    }
	} else {
	    $this->logSection('>>', 'La base de donnees locale ne sera pas modifiee.');
	}


//-------------------------------------------------------------------------------------
//    ecriture des JSON ?
//-------------------------------------------------------------------------------------	
	if ($this->askConfirmation(array('Ecriture des fichiers JSON utilises par les sites ? (y/n)'), 'QUESTION_LARGE', true)) {
	    $beginTime = microtime(true);
	    $results = baseEditorialeTools::exportArticlesJson();
	    $this->logSection('### loadBE', 'Export des articles par rubrique au format Json.' . ' ->' . (microtime(true) - $beginTime) . ' s');
	    if (in_array("verbose", $arguments)) {

		foreach ($results as $result) {
		    foreach ($result as $log => $desc) {
			$this->logSection(utf8_decode($log), utf8_decode($desc));
		    }
		}
	    }
	} else {
	    $this->logSection('>>', 'Pas de fichiers JSON generes.');
	}	
	

    }

}
