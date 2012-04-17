<?php

class changeNddTask extends lioshiBaseTask {
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
        $this->name = 'change-ndd';
        $this->briefDescription = 'Change NDD site';
        $this->detailedDescription = <<<EOF
The [dm:change-ndd|INFO] task that change NDD of the site.
Call it with:

  [php symfony dm:change-ndd|INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        if (dmConfig::get('site_ndd') != ''){
            $actualNdd = dmConfig::get('site_ndd');
            $this->logblock('NDD actuel : '.$actualNdd, 'HELP');
        } else {
            $this->runTask('dm:setup');
            $this->logblock('dm:change-ndd : NDD inconnu en Base de données, merci d\'ajouter le champ dm_setting.site_ndd', 'ERROR');
            exit;
        }

        $ndd = $this->askAndValidate(array('', 'Le nouveau nom de domaine? (format: example.com)', ''), new sfValidatorRegex(
                        array('pattern' => '/^([a-z0-9-]+\.)+[a-z]{2,6}$/',
                        'required' => true),
                        array('invalid' => 'Le nom de domaine est invalide')
        ));

        $queryMajSiteNdd = 'UPDATE `dm_setting_translation` t JOIN  `dm_setting` s on s.id = t.id SET value = \''.$ndd.'\' WHERE s.name = \'site_ndd\';';
        $connection->query($queryMajSiteNdd);

        // changement du nom de domaine dans le fichier de conf
        // Conf de Apache (Vhost et rewrite) 
        $fileConf = sfConfig::get('sf_root_dir').'/conf/httpd.conf';   // httpd.conf interne au site
        if (file_exists($fileConf)) {
            // on modifie l'ancien NDD par le nouveau
            $this->getFilesystem()->execute('find ' . $dirTheme . ' "'.$fileConf.'" -print | xargs perl -pi -e \'s/'.$actualNdd.'/' . $ndd . '/g\'');
            $this->logBlock('APACHE : Le fichier "'.$fileConf.'" est modifie.', 'INFO_LARGE');
        }

        $fileConfExt = '/data/conf/httpd-'.dmProject::getKey().'.conf';   // httpd.conf externe au site 
        if (file_exists($fileConfExt)) {
            // on modifie l'ancien NDD par le nouveau
            $this->getFilesystem()->execute('find ' . $dirTheme . ' "'.$fileConfExt.'" -print | xargs perl -pi -e \'s/'.$actualNdd.'/' . $ndd . '/g\'');
            $this->logBlock('APACHE : Le fichier "'.$fileConfExt.'" est modifie.', 'INFO_LARGE');
        }

        $commands = array(
             'Restart apache (sudo service apache restart)' => 'sudo service apache restart graceful',
             'Restart apache (sudo service httpd restart)' => 'sudo service httpd restart graceful',
             'Restart apache (sudo apachectl gracefull)' => 'sudo apachectl graceful',
            );

        foreach ($commands as $libCommand => $command) {
            $out = $err = null;
            try {
                //$this->logBlock($libCommand, 'INFO_LARGE');  
          $out = $err = null;
          $this->getFilesystem()->execute($command, $out, $err);
            } catch (exception $e) {
          $this->logBlock('Error: '.$libCommand, 'ERROR');
            }
        }

        

    }
}
