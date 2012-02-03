<?php

class controlTask extends sfBaseTask {
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
        $this->name = 'control';
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
        $dispoEnvs = array(
            1 => 'less:compile-all',
            2 => 'serveur de production 91.194.100.239'
        );
        $this->logBlock('Environnements disponibles :', 'INFO_LARGE');
        
        foreach ($dispoEnvs as $k => $dispoEnv) {
            $this->log($k . ' - ' . $dispoEnv);
        }
        // choix du dump
        $numEnv = $this->askAndValidate(array(
            '',
            'Le numero de l\'environnement choisi?',
            ''
        ) , new sfValidatorChoice(array(
            'choices' => array_keys($dispoEnvs) ,
            'required' => true
        ) , array(
            'invalid' => 'L\'environnement n\'existe pas'
        )));


        
        $this->runTask('less:compile-all');
        $this->runTask('less:sprite');
        $timerTask = new sfTimer;
        // Generation des fichiers CSS a partir des less
        $this->logBlock('Compilation des .less -> .css', 'COMMENT_LARGE');
        $arguments = array();
        $options = array(
            'application' => 'front',
            'debug',
            'clean'
        );
        $this->runTask('less:compile', $arguments, $options);
        // génération du fichier des variables less, indispensable au spriteInit
        $variablesFile = sidSPLessCss::getVariableFileJson();
        $this->logBlock('Generation du fichier des variables : ' . $variablesFile, 'COMMENT_LARGE');
        $arguments = array();
        $options = array();
        $this->runTask('less:variables', $arguments, $options);
        $this->logBlock('Task ' . $this->namespace . ':' . $this->name . ' time : ' . round($timerTask->getElapsedTime() , 3) . ' s', 'COMMENT_LARGE');
    }
}
