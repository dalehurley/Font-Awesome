<?php

class controlTask extends lioshiBaseTask {
    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addArguments(array());
        $this->addOptions(array(
            // application positionnée sur front afin d'avoir accès aux app.yml du front
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'front') ,
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod') ,
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')              
        ));
        $this->namespace = '';
        $this->name = 'controls';
        $this->briefDescription = 'Control tasks for webmaster';
        $this->detailedDescription = <<<EOF
The [control|INFO] task show all controls available for webmaster.
Call it with:

  [php symfony control|INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        // on affiche les choix d'environnemnts pour les valeurs par defaut
        $dispoTasks = array(
            array(
              'separator' => 'Contenu',
              'command' => 'db:load',
              'libelle' => 'DB: Chargement du contenu.',
              'arguments' => array(),
              'options' => array()                
              ),
            array(
              'command' => 'db:dump',
              'libelle' => 'DB: Sauvegarde du contenu.',
              'arguments' => array(),
              'options' => array()                
              ),
            array(
              'command' => 'db:dump',
              'libelle' => 'DB: Sauvegarde du contenu avec SETTINGS.',
              'arguments' => array(),
              'options' => array('settings' => 'true')                
              ),            
            array(
              'separator' => 'Style',
              'command' => 'less:compile-all', 
              'libelle' => 'Compilation des .less en .css',
              'arguments' => array(),
              'options' => array()                
              ),
            array(
              'command' => 'less:sprite',
              'libelle' => 'Génération des sprites',
              'arguments' => array('verbose'),
              'options' => array()  
              ),
            array(
              'separator' => 'Base editoriale',              
              'command' => 'be:loadArticles',
              'libelle' => 'BE: Chargement des rubriques',
              'arguments' => array('rubriques', 'verbose'),
              'options' => array()                
              ),
            array(
              'command' => 'be:loadArticles', 
              'libelle' => 'BE: Chargement des sections',
              'arguments' => array('sections', 'verbose'),
              'options' => array()                
              ),
            array(
              'command' => 'be:loadArticles',
              'libelle' => 'BE: Chargement des articles',
              'arguments' => array('articles', 'verbose'),
              'options' => array()                
              ),
            array(
              'command' => 'be:report',
              'libelle' => 'BE: Rapport sur les articles',
              'arguments' => array(),
              'options' => array()                
              ),
            array(
              'separator' => 'Gestion du cache',              
              'command' => 'cc',
              'libelle' => 'Purge du cache',
              'arguments' => array(),
              'options' => array(),
              'credentials' => 'dev'                
              ),  
            array(
              'command' => 'dm:create-pages-cache',
              'libelle' => 'Créer le cache des pages',
              'arguments' => array(),
              'options' => array()                
              ),                
            array(
              'separator' => 'Themes [actuel: '.dmConfig::get('site_theme').' '.dmConfig::get('site_theme_version').']',              
              'command' => 'theme:install',
              'libelle' => 'Installation du thème (Attention, suppression des styles personnalisés du site)',
              'arguments' => array(),
              'options' => array()
              ),              
            array(
              'command' => 'theme:duplicate',
              'libelle' => 'Duplication du thème',
              'arguments' => array(),
              'options' => array()
              ),
            array(
              'separator' => 'Outils',              
              'command' => 'dm:erase-site',
              'libelle' => 'ATTENTION: suppression du site',
              'arguments' => array(),
              'options' => array()                
              ),  
            array(
              'command' => 'dm:setup',
              'libelle' => 'Setup du site',
              'arguments' => array(),
              'options' => array()                
              ), 
            array(
              'command' => 'dm:change-ndd',
              'libelle' => 'Changer le ndd du site ['.dmConfig::get('site_ndd').']',
              'arguments' => array(),
              'options' => array()                
              ),             
            array(
              'separator' => 'Moteur de recherche (Lucene)', 
              'command' => 'dm:search-update',
              'libelle' => 'Mise à jour index de recherche',
              'arguments' => array(),
              'options' => array()                
              ),                                              
        );

        // on supprime l'entrée de key = 0 car le zéro est interprété comme null en cli
        array_unshift($dispoTasks, "");
        unset($dispoTasks[0]);

        // suppression de la tache less:sprite pour les autres theme version que v1
        if (dmConfig::get('site_theme_version') != 'v1'){
          foreach ($dispoTasks as $k => $dispoTask) {
            if ($dispoTask['command'] == 'less:sprite'){
              unset($dispoTasks[$k]); // sprite
            }
          }
          // réorganiser les taches pour avoir une sequence de numéros correcte
          $j = 1;
          foreach ($dispoTasks as $dispoTask) {
            $dispoTasksReorg[$j]= $dispoTask;
            $j++;
          }  
          $dispoTasks = $dispoTasksReorg;        
        }

        $this->logBlock('Tâches disponibles :', 'INFO_LARGE');
        foreach ($dispoTasks as $k => $dispoTask) {
          if (isset($dispoTask['separator'])){
            // entetes HELP de largeur égale
            $helpSeparatorWidth = 70;
            $helpSeparator = $dispoTask['separator'].str_repeat(' ', $helpSeparatorWidth - strlen($dispoTask['separator']));
            $this->logblock($helpSeparator, 'HELP');
          } 
          $this->logSection($k, $dispoTask['libelle']);
        }
        
        $messageAccueil = 'Vous pouvez choisir une tâche. Faîtes Ctrl+c pour sortir.';
        $this->logBlock($messageAccueil, 'HELP');

        // choix de la tâche
        $numTask = $this->askAndValidate(array(
            'Le numéro de la tâche à lancer?'
        ) , new sfValidatorChoice(array(
            'choices' => array_keys($dispoTasks) ,
            'required' => true
        ) , array(
            'invalid' => 'Numéro de tâche invalide'
        )),
            array('style' => 'QUESTION_LARGE')
        );

        // traitement de la commande
        $argumentsLib = '';
        foreach ($dispoTasks[$numTask]['arguments'] as $key => $value) {
          $argumentsLib .= $value.' ';
        }
        $optionsLib = '';
        foreach ($dispoTasks[$numTask]['options'] as $key => $value) {
          $optionsLib .= '--'.$key.'='.$value.' ';
        }

        $messageCommande = $dispoTasks[$numTask]['libelle'].'  >> '.$dispoTasks[$numTask]['command'].' '.$argumentsLib.' '.$optionsLib.' ';
        $this->logBlock($messageCommande, 'INFO_LARGE');

        $timerTask = new sfTimer();        
        $this->runTask($dispoTasks[$numTask]['command'], $dispoTasks[$numTask]['arguments'], $dispoTasks[$numTask]['options']);
        
        $this->logBlock('Execution time  '. round($timerTask->getElapsedTime() , 3) . ' s', 'INFO_LARGE');
    }
}
