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

        // informations
        $null = '---';
        $infos['Dossier du site'] = getcwd();
        $infos['Nom de domaine'] = (dmConfig::get('site_ndd') != '')?dmConfig::get('site_ndd'):$null;
        $infos['Nom du site'] = (dmConfig::get('site_name') != '')?dmConfig::get('site_name'):$null;
        $infos['Theme'] = (dmConfig::get('site_theme') != '')?dmConfig::get('site_theme'):$null;
        $infos['Theme version'] = (dmConfig::get('site_theme_version') != '')?dmConfig::get('site_theme_version'):$null;
        $infos['Site indexable par robots'] = (dmConfig::get('site_indexable') != '' && dmConfig::get('site_indexable'))?'oui':'non';
        $infos['Site actif'] = (dmConfig::get('site_active') != '' && dmConfig::get('site_active'))?'oui':'non';
        $infos['Type du site'] = (dmConfig::get('site_type') != '')?dmConfig::get('site_type'):$null;
        $infos['Type du client'] = (dmConfig::get('client_type') != '')?dmConfig::get('client_type'):$null;
        $infos['Email du site'] = (dmConfig::get('site_email') != '')?dmConfig::get('site_email'):$null;
        $infos['Email sender du site'] = (dmConfig::get('site_email_sender') != '')?dmConfig::get('site_email_sender'):$null;
        $infos['Clef Analytics'] = (dmConfig::get('ga_key') != '')?dmConfig::get('ga_key'):$null;
        $infos['Clef Gwt'] = (dmConfig::get('gwt_key') != '')?dmConfig::get('gwt_key'):$null;


        // display infos
        $keys = array_keys($infos);
        // max lenght lib
        $lenghtMaxLib = 0; 
        foreach ($keys as $key) {
            if (strlen($key)>$lenghtMaxLib) $lenghtMaxLib = strlen($key); 
        }

        $this->logblock((dmConfig::get('site_ndd') != '')?dmConfig::get('site_ndd'):$null, 'INFO');
        foreach ($infos as $lib => $value) {
            // on ajoute des espaces pour que les valeurs soient alignées
            $this->logblock($lib.str_repeat(" ", $lenghtMaxLib - strlen($lib)) . " : " .$value, 'COMMENT');
        }
        

    }
}
