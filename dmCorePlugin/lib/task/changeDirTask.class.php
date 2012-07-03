<?php

class changeDirTask extends lioshiBaseTask {
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
        $this->name = 'change-dir';
        $this->briefDescription = 'Change directory of site';
        $this->detailedDescription = <<<EOF
The [dm:change-dir|INFO] task that changes directory of the site.
Call it with:

  [php symfony dm:change-dir|INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $actualDir = getcwd(); 
        $dirSite = substr(sfConfig::get('sf_root_dir'),strrpos(sfConfig::get('sf_root_dir'), '/')+1);  // le dossier web necessaire pour le httpd.conf
        $dirWeb = substr(sfConfig::get('sf_web_dir'),strrpos(sfConfig::get('sf_web_dir'), '/')+1);  // le dossier web necessaire pour le httpd.conf
        $this->logblock('directory actuel : '.$actualDir, 'HELP');

        $dir = $this->askAndValidate(array('', 'Le nouveau répertoire CONTENANT le repertoire du site? (exemple: /data/www/sitesv3)', ''), new sfValidatorString());

        if (is_dir($dir) && is_writable($dir)){
            /****** changement du directory dans le fichier de conf *******/
            // formattage des dirs : ajout du web dir
            $actualDirFormat = str_replace('/', '\/' , $actualDir.'/'.$dirWeb); 
            $dirFormat = str_replace('/', '\/' , $dir.'/'.$dirSite.'/'.$dirWeb); 

            // Conf de Apache (Vhost et rewrite)         
            $fileConf = sfConfig::get('sf_root_dir').'/conf/httpd.conf';   // httpd.conf interne au site
            if (file_exists($fileConf)) {
                // on modifie l'ancien dir par le nouveau
                $this->getFilesystem()->execute('find "'.$fileConf.'" -print | xargs perl -pi -e \'s/'.$actualDirFormat.'/' . $dirFormat . '/g\'');
                $this->logBlock('APACHE : Le fichier "'.$fileConf.'" est modifie.', 'INFO_LARGE');
            }

            $fileConfExt = '/data/conf/httpd-'.dmProject::getKey().'.conf';   // httpd.conf externe au site 
            if (file_exists($fileConfExt)) {
                // on modifie l'ancien dir par le nouveau
                $this->getFilesystem()->execute('find "'.$fileConfExt.'" -print | xargs perl -pi -e \'s/'.$actualDirFormat.'/' . $dirFormat . '/g\'');
                $this->logBlock('APACHE : Le fichier "'.$fileConfExt.'" est modifie.', 'INFO_LARGE');
            }

            /******* Copie du dossier du site ****/
            $commandCp = 'cp -R '.$actualDir.' ' . $dir;
            $out = $err = null;
            $this->getFilesystem()->execute($commandCp, $out, $err);

            /******* all is ok? then delete directory ****************/
            if (!$this->askConfirmation('La copie du site a ete effectuee avec succes? (y/n)')) {
                $this->logBlock('Aborted', 'ERROR_LARGE');
                exit;
            } else {
                // delete ol dir
                $commandRm = 'rm -Rf '.$actualDir;
                $out = $err = null;
                $this->getFilesystem()->execute($commandRm, $out, $err);
                // on new dir : cc
                $commandRm = fileTools::getPhpCli().' '.$dir.'/'.$dirSite.'/symfony cc';
                $out = $err = null;
                $this->getFilesystem()->execute($commandRm, $out, $err);
                // on new dir : ccc 
                $commandRm = fileTools::getPhpCli().' '.$dir.'/'.$dirSite.'/symfony ccc';
                $out = $err = null;
                $this->getFilesystem()->execute($commandRm, $out, $err);                

                /******* Redemarrage Apache ****/
                $commands = array(
                     'Restart apache (sudo service apache restart)' => 'sudo service apache restart graceful',
                     'Restart apache (sudo service httpd restart)' => 'sudo service httpd restart graceful',
                     'Restart apache (sudo apachectl gracefull)' => 'sudo apachectl graceful',
                    );
                foreach ($commands as $libCommand => $command) {
                    $out = $err = null;
                    try {
                        $out = $err = null;
                        $this->getFilesystem()->execute($command, $out, $err);
                    } catch (exception $e) {
                        $this->logBlock('Error: '.$libCommand, 'ERROR');
                    }
                }
            }

        } else {
            $this->logBlock('Error: '.$dir.' is not a directory writable', 'ERROR');
        }
    }
}
