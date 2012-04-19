<?php

class infosSiteTask extends lioshiBaseTask {
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
        $this->namespace = 'dm';
        $this->name = 'infos-site';
        $this->briefDescription = 'Change NDD site';
        $this->detailedDescription = <<<EOF
The [dm:infos-site|INFO] task that give infos on the site.
Call it with:

  [php symfony dm:infos-site|INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        if (dmConfig::get('site_ndd') != ''){
            $actualNdd = dmConfig::get('site_ndd');
            echo 'Nom de domaine : '.$actualNdd."\n";
        } 

        echo 'Dossier du site : '.getcwd()."\n";

    }
}
