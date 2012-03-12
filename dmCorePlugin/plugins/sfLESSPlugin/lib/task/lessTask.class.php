<?php

class lessTask extends lioshiBaseTask {
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
        $this->namespace = 'less';
        $this->name = 'compile-all';
        $this->briefDescription = 'Create css file with less compilater';
        $this->detailedDescription = <<<EOF
The [less:compile-all|INFO] task creates less.
Call it with:

  [php symfony less:compile-all|INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();      

        $timerTask = new sfTimer;

        // Generation des fichiers CSS a partir des less
        $this->logBlock('Compilation des .less -> .css','COMMENT_LARGE');
        $arguments = array();
        $options = array (
          'application' => 'front',
          'debug' ,
          'clean' 
          );
        $this->runTask('less:compile', $arguments, $options);

        //$this->logBlock('Task '. $this->namespace . ':' . $this->name .' time : '. round($timerTask->getElapsedTime() , 3) . ' s', 'INFO_LARGE');

    }
}
