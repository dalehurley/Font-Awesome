<?php

class variablesJsonTask extends lioshiBaseTask {
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
        $this->namespace = 'less';
        $this->name = 'variables';
        $this->briefDescription = 'Create a json file with less variables';
        $this->detailedDescription = <<<EOF
The [less:variables|INFO] task creates a json file with less variables.
Call it with:

  [php symfony less:variables|INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {
        $this->logSection('Variables json', 'Generation...');
        if (sidSPLessCss::loadLessParameters()){
          $this->logSection('Variables json' , 'Ok');
        } else {
          $this->logBlock('Probleme execution variables json' , 'ERROR');
        }
    }
}
