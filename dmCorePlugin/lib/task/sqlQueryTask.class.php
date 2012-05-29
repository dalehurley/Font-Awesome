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
  WARNING: replace " with \" to work
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

// query() or exec()?
// --------------
// After the connection to database is successfully created and the PDO object instance is set, the object can be used to perform SQL queries.
// The SQL queries with PDO can be made in two ways:
//         - directly using "exec()", and "query()" methods,
//         - or with the prepare() ... execute() statement.
// The first variant is more simple, in this lesson it's presented the exec method.

// • The queries that modify rows in the table, but not return a result set with rows and columns (INSERT, UPDATE, and DELETE), are send with exec(), this method returns the number of affected rows, or FALSE on error.
// $count = $conn->exec("SQL Query");

// • Queries that select rows (SELECT) and return a result set with rows and columns are sent with the query() method. In case of error, returns FALSE.
// $res = $conn->query("SQL Query");

        switch (strtolower(substr($query,0,6))) {
            case 'select':
                $res = $connection->query($query);
                $displayPDOStatement = true;
                break;

            case 'update':
                $res = $connection->exec($query);
                $displayPDOStatement = false;
                break;  

            case 'insert':
                $res = $connection->exec($query);
                $displayPDOStatement = false;
                break; 

            case 'delete':
                $res = $connection->exec($query);
                $displayPDOStatement = false;
                break;                                               
            
            default:
                $this->logBlock('Invalid query : '.$query." => ONLY SELECT, INSERT, UPDATE, and DELETE", 'ERROR_LARGE');
                exit; 
                break;
        }

        $this->logBlock('Sql query OK : '.$query, 'INFO');

        if ($displayPDOStatement){    
            foreach ($res as $line) {
                foreach ($line as $key => $value) {
                    if (!is_int($key)) $this->logBlock(" ".$key. " : ".$value, 'HELP'); 
                }
                echo "\n";
            }
        } else {
            $libLigneAffected = ($res>1)?" lignes affectées":" ligne affectée";
            $this->logBlock($res . $libLigneAffected, 'HELP'); 
        }
        
        

         

    }
}
