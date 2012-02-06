<?php

class reportTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array(
           
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'front'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
                // add your own options here
        ));

        $this->namespace = 'be';
        $this->name = 'report';
        $this->briefDescription = 'Several reports on local Base Edotoriale';
        $this->detailedDescription = <<<EOF
The [report|INFO] task does things.
This task report data from locale Base Editoriale.
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        

//-------------------------------------------------------------------------------------
//    Stats sur les tataux
//-------------------------------------------------------------------------------------	

            $beginTime = microtime(true);
            $results = baseEditorialeTools::rapportTotal();
            $this->logBlock("Rapport sur les totaux" . " (" . (microtime(true) - $beginTime) . " s)", 'COMMENT');

            foreach ($results as $result) {
                foreach ($result as $log => $desc) {
                    $this->logSection($log, $desc, null, $log);
                }
            }
                    
//-------------------------------------------------------------------------------------
//    Stats sur les dossiers
//-------------------------------------------------------------------------------------	

            $beginTime = microtime(true);
            $results = baseEditorialeTools::rapportDossier();
            $this->logBlock("Rapport sur les dossiers" . " (" . (microtime(true) - $beginTime) . " s)", 'COMMENT');

            foreach ($results as $result) {
                foreach ($result as $log => $desc) {
                    $this->logSection($log, $desc, null, $log);
                }
            }
    
    }
}
