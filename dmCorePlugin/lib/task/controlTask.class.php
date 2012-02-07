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

        // on affiche les choix d'environnemnts pour les valeurs par defaut
        $dispoTasks = array(
            1 => array(
              'command' => 'db:load',
              'libelle' => 'DB: Chargement du contenu',
              'arguments' => array(),
              'options' => array()                
              ),
            2 => array(
              'command' => 'db:dump',
              'libelle' => 'DB: Sauvegarde du contenu',
              'arguments' => array(),
              'options' => array()                
              ),
            3 => array(
              'command' => 'cc',
              'libelle' => 'Purge du cache',
              'arguments' => array(),
              'options' => array(),
              'credentials' => 'dev'                
              ),
            4 => array(
              'command' => 'less:compile-all', 
              'libelle' => 'Compilation des .less en .css + fichier de variables .json',
              'arguments' => array(),
              'options' => array()                
              ),
            5 => array(
              'command' => 'less:sprite',
              'libelle' => 'Génération des sprites',
              'arguments' => array('verbose'),
              'options' => array()  
              ),
            6 => array(
              'command' => 'dm:setup',
              'libelle' => 'Setup du site',
              'arguments' => array(),
              'options' => array()                
              ),
            7 => array(
              'command' => 'be:loadArticles',
              'libelle' => 'BE: Chargement des rubriques',
              'arguments' => array('rubriques', 'verbose'),
              'options' => array()                
              ),
            8 => array(
              'command' => 'be:loadArticles',
              'libelle' => 'BE: Chargement des sections',
              'arguments' => array('sections', 'verbose'),
              'options' => array()                
              ),
            9 => array(
              'command' => 'be:loadArticles',
              'libelle' => 'BE: Chargement des articles',
              'arguments' => array('articles', 'verbose'),
              'options' => array()                
              ),
            10 => array(
              'command' => 'be:report',
              'libelle' => 'BE: Rapport sur les articles',
              'arguments' => array(),
              'options' => array()                
              ),
            11 => array(
              'command' => 'dm:erase-site',
              'libelle' => 'ATTENTION: suppression du site',
              'arguments' => array(),
              'options' => array()                
              )  
        );

        $this->logBlock('Tâches disponibles :', 'INFO_LARGE');
        
        foreach ($dispoTasks as $k => $dispoTask) {
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
        $messageCommande = $dispoTasks[$numTask]['libelle'].'  ['.$dispoTasks[$numTask]['command'].']';
        $this->logBlock($messageCommande, 'INFO_LARGE');

        $timerTask = new sfTimer();        
        $this->runTask($dispoTasks[$numTask]['command'], $dispoTasks[$numTask]['arguments'], $dispoTasks[$numTask]['options']);
        
        $this->logBlock('Execution time  '. round($timerTask->getElapsedTime() , 3) . ' s', 'INFO_LARGE');
    }
}
