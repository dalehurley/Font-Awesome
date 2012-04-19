<?php

class loadBETask extends lioshiBaseTask {

    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('verbose', sfCommandArgument::OPTIONAL, 'Verbose task'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'front'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('auto', null, sfCommandOption::PARAMETER_REQUIRED, 'Mode auto, no confirmation?', false)  
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
//    recuperation WGET des XML
//-------------------------------------------------------------------------------------	
	// chargement des XML et images de LEA ?
	if ($options['auto'] || $this->askConfirmation(array('Charger les fichiers XML dans le repertoire local ? (y/n)'), 'QUESTION_LARGE', true)) {
	    $results = baseEditorialeTools::recupFilesXmlLEA();
	    $this->logSection('>>', 'Chargement des XML ...');
	} else {
	    $this->logSection('>>', 'Pas de chargement des XML.');
	}	
	
//-------------------------------------------------------------------------------------
//    nettoyage des répertoires contenant les XML 
//-------------------------------------------------------------------------------------	
	if ($options['auto'] || $this->askConfirmation(array('Nettoyage du repertoire local ? (y/n)'), 'QUESTION_LARGE', true)) {
	    $results = baseEditorialeTools::nettoyageRepLocal();
	    $this->logSection('>>', 'Nettoyage du repertoire local...');
	} else {
	    $this->logSection('>>', 'Pas de nettoyage du repertoire local.');
	}        
	
//-------------------------------------------------------------------------------------
//    recuperation WGET des images 
//-------------------------------------------------------------------------------------	
	// chargement des images de LEA ?
	if ($options['auto'] || $this->askConfirmation(array('Charger les fichiers images dans le repertoire local ? (y/n)'), 'QUESTION_LARGE', true)) {
	    $results = baseEditorialeTools::recupFilesImagesLEA();
	    $this->logSection('>>', 'Chargement des images...');
	} else {
	    $this->logSection('>>', 'Pas de chargement des images.');
	}
       
//-------------------------------------------------------------------------------------
//    recuperation WGET des dessins de la semaine
//    rep-local-dessin-semaine: /data/www/_lib/baseEditoriale/dessinSemaine/
//    xml-dessin: source_dessin.xml 
//-------------------------------------------------------------------------------------	
	// chargement des images de LEA ?
	if ($options['auto'] || $this->askConfirmation(array('Charger les fichiers dessins de la semaine dans le repertoire local ? (y/n)'), 'QUESTION_LARGE', true)) {
	    $results = baseEditorialeTools::recupFilesDessins();
	    $this->logSection('>>', 'Chargement des dessins de la semaine...');
	} else {
	    $this->logSection('>>', 'Pas de chargement des dessins de la semaine.');
	}
        
//-------------------------------------------------------------------------------------
//    Ecriture des rubriques/sections en base ?
//-------------------------------------------------------------------------------------	

    if ($options['auto'] || $this->askConfirmation(array("Ecriture rubriques/sections (a partir du repertoire " . sfConfig::get('app_rep-local') . ") dans la base de donnees locale ? (y/n)"), 'QUESTION_LARGE', true)) {
        $beginTime = microtime(true);
        $results = baseEditorialeTools::recupRubriqueSection();
        $this->logSection('### loadBE', "Ecriture rubriques/sections en base de donnees." . " ->" . (microtime(true) - $beginTime) . " s");
        if (in_array("verbose", $arguments)) {
            foreach ($results as $result) {
                foreach ($result as $log => $desc) {
                    $this->logSection($log, $desc,null,$log);
                }
            }
        }
    } else {
        $this->logSection('>>', 'Pas de chargement des rubriques en base de donnees locale.');
    }

//-------------------------------------------------------------------------------------
//    Ecriture des articles en base ?
//-------------------------------------------------------------------------------------	
	if ($options['auto'] || $this->askConfirmation(array('Lecture des fichiers XML locaux pour creer les articles dans la base de donnees locale? (y/n)'), 'QUESTION_LARGE', true)) {

	    $beginTime = microtime(true);
	    $results = baseEditorialeTools::recupArticlesLEA();
	    $this->logSection('### loadBE', 'Chargement des articles en XML de LEA dans la base editoriale.' . ' ->' . (microtime(true) - $beginTime) . ' s');
	    if (in_array("verbose", $arguments)) {

		foreach ($results as $result) {
		    foreach ($result as $log => $desc) {
			$this->logSection($log, $desc,null,$log);
		    }
		}
	    }
	} else {
	    $this->logSection('>>', 'La base de donnees locale ne sera pas modifiee.');
	}

//-------------------------------------------------------------------------------------
//    ecriture des JSON ?
//-------------------------------------------------------------------------------------	
	if ($options['auto'] || $this->askConfirmation(array('Ecriture des fichiers JSON utilises par les sites ? (y/n)'), 'QUESTION_LARGE', true)) {
	    $beginTime = microtime(true);
	    $results = baseEditorialeTools::exportArticlesJson();
	    $this->logSection('### loadBE', 'Export des articles par rubrique au format Json.' . ' ->' . (microtime(true) - $beginTime) . ' s');
	    if (in_array("verbose", $arguments)) {

		foreach ($results as $result) {
		    foreach ($result as $log => $desc) {
			$this->logSection($log, $desc,null,$log);
		    }
		}
	    }
	} else {
	    $this->logSection('>>', 'Pas de fichiers JSON genérés.');
	}	
    }
}
