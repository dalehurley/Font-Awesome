<?php

class variablesJsonTask extends sfBaseTask {
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
        $this->logSection('Variables json' . sfConfig::get('app_sf_less_plugin_css_path', '/css/') , 'Generation');
        sidSPLessCss::loadLessParameters();
    }
}
