<?php

class sqlQueryTask extends lioshiBaseTask {
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
            new sfCommandOption('query', null, sfCommandOption::PARAMETER_REQUIRED, 'Sql query string', false) ,               
        ));
        $this->namespace = 'doctrine';
        $this->name = 'sql-query';
        $this->briefDescription = 'Put sql query in default database';
        $this->detailedDescription = <<<EOF
The [doctrine:sql-query|INFO] task that sql query in default database.
Call it with:

  [php symfony doctrine:sql-query "select * from test"|INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        if ($options['query']==''){
            $query = $this->askAndValidate(array(
                '',
                'La requete a lancer?',
                ''
            ) , new sfValidatorString(array(
                'required' => true
            ) , array(
                'invalid' => 'La requete est invalide'
            )));
        } else {
            $query = $options['query'];
        }

        $this->logBlock('Sql query : '.$query, 'INFO_LARGE');

        $res = $connection->query($query);


        // $res = Doctrine_Core::getTable('dmSetting')
        //                 ->createQuery('s')
        //                 ->execute();


        // BUG si affichage
        // foreach ($res as $line) {
        //     foreach ($line as $value) {
        //         echo $value."\n";
        //     }
        // }
        

         

    }
}
