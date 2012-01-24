<?php

class dumpDBTask extends sfBaseTask {
    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'Output file') ,
        ));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name') ,
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev') ,
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine') ,
            // add your own options here
            
        ));
        $this->namespace = 'db';
        $this->name = 'dumpDB';
        $this->briefDescription = 'Dump local diem Database into .dump file.';
        $this->detailedDescription = <<<EOF
The [dumpDB|INFO] dump local diem Database into file.
Call it with:

  [php symfony dumpDB /data/save.sql|INFO] 
EOF;
        
    }
    protected function execute($arguments = array() , $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        // add your code here
        $results = contentTemplateTools::dumpDB($arguments['file']);
        $this->logSection('### dumpDB', 'Dump de la base locale');
        
        foreach ($results as $result) {
            
            foreach ($result as $log => $desc) {
                $this->logSection(utf8_decode($log) , utf8_decode($desc) , null, utf8_decode($log));
            }
        }
    }
}
