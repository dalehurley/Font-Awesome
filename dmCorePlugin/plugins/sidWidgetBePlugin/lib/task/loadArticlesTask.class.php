<?php

class loadArticlesTask extends sfBaseTask {

    protected function configure() {
        // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('verbose', sfCommandArgument::OPTIONAL, 'Verbose task'),
            new sfCommandArgument('rubriques', sfCommandArgument::OPTIONAL, 'Loading rubriques & sections (for first time launch)'),            
            new sfCommandArgument('articles', sfCommandArgument::OPTIONAL, 'Loading articles (incremental mode)'),
            new sfCommandArgument('total', sfCommandArgument::OPTIONAL, 'with argument "articles" : Total mode'),
            new sfCommandArgument('deactivation', sfCommandArgument::OPTIONAL, 'with argument "deactivation" : deactivation rubrique and section empty'),            
        ));


        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'front'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = 'be';
        $this->name = 'loadArticles';
        $this->briefDescription = 'Load rubriques, sections and articles from Base Editoriale. It reads only JSON files, no DB connect to Base Editoriale.';
        $this->detailedDescription = <<<EOF
The [loadArticles|INFO] task does things.
  This task loads rubriques, sections and articles from Base Editoriale, it loads only articles from actives rubriques. It reads only JSON files, no DB connect to Base Editoriale.
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        // add your code here

       /* if (!$this->askConfirmation('Charger les rubriques et sections? (y/n)')) {
            $this->log('Annulation...');
            exit;
        }*/
        
        if (in_array("articles", $arguments) && in_array("rubriques", $arguments)){
            // argument articles et rubriques ensemble : pas possible
            $this->logSection('ERROR', 'Les arguments Rubriques et Articles ne peuvent etre lances ensemble. Il faut :', null, 'ERROR');
            $this->logSection('ERROR', '- Charger dans un premier temps toutes les rubriques : "loadArticles verbose rubriques"', null, 'ERROR');
            $this->logSection('ERROR', '- Aller dans l\'administration pour supprimer les rubriques et/ou sections non souhaitees', null, 'ERROR');
            $this->logSection('ERROR', '- Lancer "loadArticles articles" pour charger les articles relatifs aux rubriques et sections choisies', null, 'ERROR');
            exit;
        }        

     $beginTimeTotal = microtime(true);
        
//------------------------------------------------------------------------------------------------------------
//    Chargement des rubriques et sections dans la base de donnees locale (en fonction des fichiers json)
//------------------------------------------------------------------------------------------------------------
        if (in_array("rubriques", $arguments)) {
            $results = baseEditorialeTools::loadRubriqueSectionJson();

            if (in_array("verbose", $arguments)) {
                $this->logSection('### loadArticles', 'Chargement des rubriques/sections de la base editoriale.');
                foreach ($results as $result) {
                    foreach ($result as $log => $desc) {
                        $this->logSection($log, $desc,null,$log);
                    }
                }
            }
        } elseif (in_array("articles", $arguments)) {
           /* if (!$this->askConfirmation('Charger les articles? (y/n)')) {
                $this->log('Annulation...');
                exit;
            }*/
            if (in_array("total", $arguments)) {
                $results = baseEditorialeTools::loadArticlesJson('total');
            } else {
                $results = baseEditorialeTools::loadArticlesJson('incremental');
            }
            
            if (in_array("verbose", $arguments)) {
                $this->logSection('### loadArticles', 'Chargements des articles.');
                foreach ($results as $result) {
                    foreach ($result as $log => $desc) {
                        $this->logSection($log, $desc,null,$log);
                    }
                }
            }

            //------------------------------------------------------------------------------------------------------------
            //    désactivation des rubriques et section sans enfants
            //------------------------------------------------------------------------------------------------------------  
            if (in_array("deactivation", $arguments)) {
                $results = baseEditorialeTools::RubriquesSectionsDeactivation();

                if (in_array("verbose", $arguments)) {
                    $this->logSection('### loadArticles', 'Désactivation des rubriques et sections n\'ayant pas d\'enfants.');
                    foreach ($results as $result) {
                        foreach ($result as $log => $desc) {
                            $this->logSection($log, $desc, null, $log);
                        }
                    }
                }
            }
            //------------------------------------------------------------------------------------------------------------
            //    Synchronisation des pages automatiques
            //------------------------------------------------------------------------------------------------------------            
            $results = baseEditorialeTools::syncPages();

            if (in_array("verbose", $arguments)) {
                $this->logSection('### loadArticles', 'Synchronisation des pages automatiques.');
                foreach ($results as $result) { 
                    foreach ($result as $log => $desc) {
                        $this->logSection($log, $desc,null,$log);
                    }
                }
            }            

            //------------------------------------------------------------------------------------------------------------
            //    Replacement des name/slug/title et description des dmPages juste synchronisées
            //------------------------------------------------------------------------------------------------------------            
            $results = baseEditorialeTools::renameDmPages();

            if (in_array("verbose", $arguments)) {
                $this->logSection('### loadArticles', 'Renommage des pages automatiques.');
                foreach ($results as $result) { 
                    foreach ($result as $log => $desc) {
                        $this->logSection($log, $desc,null,$log);
                    }
                }
            }  
            
        } else {
            // rien... Ni load articles ni load rubriques...
        } 
        
        $this->logSection('### loadArticles ===> ', 'Execution totale' . ' ->' . (microtime(true) - $beginTimeTotal) . ' s');
        
    }
}
