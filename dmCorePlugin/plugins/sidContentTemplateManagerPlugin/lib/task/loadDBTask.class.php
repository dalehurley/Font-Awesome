<?php

class loadDBTask extends sfBaseTask {

    protected function configure() {
        // // add your own arguments here
         $this->addArguments(array(
           new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'Input file with dump extension'),
         ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = 'db';
        $this->name = 'loadDB';
        $this->briefDescription = 'Load a .dump file into local diem DB';
        $this->detailedDescription = <<<EOF
The [loadDB|INFO] load a dump file into local diem Database.
Call it with:

  [php symfony loadDB /data/save.sql |INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        // add your code here

        $config = contentTemplateTools::configBD();

//        if (!$this->askConfirmation(array(' ','Charger les donnees du fichier ' . $arguments['file'] . ' dans la base ' . $config['dbname'] . ' ? (y/n)',' '))) {
//            $this->log('Annulation...');
//            exit;
//        }

        $results = contentTemplateTools::loadDB($arguments['file']);

        $this->logSection('### loadDB', 'Load de la base locale');
        foreach ($results as $result) {
            foreach ($result as $log => $desc) {
                $this->logSection(utf8_decode($log), utf8_decode($desc), null, utf8_decode($log));
            }
        }
    }

}
