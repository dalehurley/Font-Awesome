<?php

class isNddTask extends lioshiBaseTask {
    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addArguments(array());
        $this->addOptions(array(
            // application positionnée sur front afin d'avoir accès aux app.yml du front
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'front') ,
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod') ,
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('ndd', null, sfCommandOption::PARAMETER_REQUIRED, 'The ndd string search', '')              
        ));
        $this->namespace = 'dm';
        $this->name = 'is-ndd';
        $this->briefDescription = 'Return infos-site if ndd match ndd\'s site';
        $this->detailedDescription = <<<EOF
The [dm:is-ndd|INFO] task that returns infos-site if ndd match ndd\'s site.
Call it with:

  [php symfony dm:is-ndd|INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        if (strpos(dmConfig::get('site_ndd'),$options['ndd']) === false ){
            $this->logBlock('"'.$options['ndd'].'" is not part the ndd of the site', 'ERROR');            
        } else {
            $this->logBlock('"'.$options['ndd'].'" is part of the ndd of the site', 'INFO'); 
            $this->runTask('dm:infos-site');
        }
        exit;       

    }
}
