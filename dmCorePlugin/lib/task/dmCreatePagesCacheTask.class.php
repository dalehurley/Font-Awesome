<?php

class dmCreatePagesCacheTask extends dmContextTask {
    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array());
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name') ,
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev') ,
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine') ,
            new sfCommandOption('lang', null, sfCommandOption::PARAMETER_REQUIRED, 'The language of site', 'fr') ,
            // add your own options here
            
        ));
        $this->namespace = 'dm';
        $this->name = 'create-pages-cache';
        $this->briefDescription = 'Create all the pages cache';
        $this->detailedDescription = <<<EOF
The [create-pages-cache|INFO] Create all the pages cache.
Call it with:

  [php symfony dm:create-pages-cache |INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        if ($this->askConfirmation(array(
            'Effacer le site completement ? ATTENTION IRREVERSIBLE (y/n)'
        ) , 'QUESTION_LARGE', true)) {
            $this->logSection('Create all cache page', '...');
        } else {
            $this->logSection('Annulation', '...');
        }
    }
}
