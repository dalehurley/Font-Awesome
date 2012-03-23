<?php

class loadArticlesTask extends lioshiBaseTask {

    protected function configure() {
        // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('quiet', sfCommandArgument::OPTIONAL, 'Quiet task'),
            new sfCommandArgument('rubriques', sfCommandArgument::OPTIONAL, 'Loading rubriques(for first time launch)'),
            new sfCommandArgument('sections', sfCommandArgument::OPTIONAL, 'Loading sections & articles (for update news)'),            
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
            // question pour rubriques à importer
            // On scanne les noms des rubriques pour les afficher en tableau 
            $answers = baseEditorialeTools::answerRubriqueJson();
            $verif = array();
            $arrayValids = array();
            if (!in_array("quiet", $arguments)) {
                $this->logBlock('Rubriques disponibles :', 'INFO_LARGE');
                foreach ($answers as $i=>$answer) {
                    // mise en tableau des valeurs pour vérification avant validation des rubriques
                    $verif[$i] = $answer['RUBRIQUE'];
                    // affichage des rubriques de la base
                    $this->log($i.' - '.$answer['RUBRIQUE']);
                }
                // On pose la question pour choisir les rubriques
                $text = <<<EOF
Choisissez les rubriques à importer dans le site
en séparant chaque N° de rubrique par un espace
EOF;
                $response = $this->ask($text, 'QUESTION_LARGE');
                // tableau pour remplacer plusieurs espace par un seul
                $arraySpace = array('    ','   ','  ',' ');
                // on remplace 1 espace par le symbole | dans la chaîne de caractère
                $response = str_replace($arraySpace, '|', $response);
                // mis en tableau de la chaîne
                $arrayResponses = explode('|', $response);
                // initialisation de la variable de vérification
                $valid = false;
                // je vérifie que la saisie est bonne en comparant la valeur du tableau de sélection avec les clés du tableau global des rubriques
                foreach($arrayResponses as $k=>$value){
                    if(array_key_exists($value, $verif)) {
                        $valid = true;
                        $arrayValids[$value] = $verif[$value];
                    }
                    else {$valid == false; break;};
                }
                // si un numéro ne correspond pas à une rubrique
                if($valid == false){$this->logBlock('Erreur de saisie','ERROR_LARGE');}
                // si tout vrai
                else if($valid == true){
                    $this->logBlock('Vous avez choisi les rubriques suivantes :','INFO_LARGE');
                    foreach ($arrayValids as $key => $arrayValid) {
                        $this->log($key.' - '.$arrayValid,'INFO');
                    }
                    if(!$this->askConfirmation(array(
                    'Vous confirmez la sélection des rubriques ? (y/n) (par defaut y)',
                    ), 'QUESTION_LARGE', true))
                    {
                    $this->logBlock('Insertion sql annulée', 'ERROR_LARGE');
                    exit;
                    }
                    // importer rubriques sélectionnées
                    $results = baseEditorialeTools::loadRubriqueJson($arrayValids);

                    if (!in_array("quiet", $arguments)) {
                        $this->logSection('### loadArticles', 'Chargement des rubriques de la base editoriale.');
                       foreach ($results as $result) {
                            foreach ($result as $log => $desc) {
                               $this->logSection($log, $desc,null,$log);
                           }
                       }
                    }   
                }

            }

        }elseif  (in_array("sections", $arguments)) {
            $results = baseEditorialeTools::loadSectionJson();

            if (!in_array("quiet", $arguments)) {
                $this->logSection('### loadArticles', 'Chargement des sections des rubriques du site.');
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
            
            if (!in_array("quiet", $arguments)) {
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

                if (!in_array("quiet", $arguments)) {
                    $this->logSection('### loadArticles', 'Désactivation des rubriques et sections n\'ayant pas d\'enfants.');
                    foreach ($results as $result) {
                        foreach ($result as $log => $desc) {
                            $this->logSection($log, $desc, null, $log);
                        }
                    }
                }
            }
            
            //------------------------------------------------------------------------------------------------------------
            //    Replacement des name/slug/title et description des dmPages juste synchronisées
            //------------------------------------------------------------------------------------------------------------            
            $results = baseEditorialeTools::renameDmPages();

            if (!in_array("quiet", $arguments)) {
                $this->logSection('### loadArticles', 'Renommage des pages automatiques.');
                foreach ($results as $result) { 
                    foreach ($result as $log => $desc) {
                        $this->logSection($log, $desc,null,$log);
                    }
                }
            }
            //------------------------------------------------------------------------------------------------------------
            //    Synchronisation des pages automatiques
            //------------------------------------------------------------------------------------------------------------            
            $results = baseEditorialeTools::syncPages();

            if (!in_array("quiet", $arguments)) {
                $this->logSection('### loadArticles', 'Synchronisation des pages automatiques.');
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
