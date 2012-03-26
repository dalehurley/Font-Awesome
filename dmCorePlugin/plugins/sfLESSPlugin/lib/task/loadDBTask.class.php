<?php

class loadDBTask extends lioshiBaseTask {

    protected function configure() {
        // // add your own arguments here
         $this->addArguments(array(
           new sfCommandArgument('file', sfCommandArgument::OPTIONAL, 'Input file with dump extension'),
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

  [php symfony loadDB [file] --settings=false |INFO]
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

        if (!isset($arguments['file'])) {
            // recuperation des differentes maquettes du coeur
            $dispoTemplates = contentTemplateTools::dispoThemes();

            // on affiche les choix
            $choices = array();
            $this->logBlock('Themes disponibles :', 'INFO_LARGE');
            foreach ($dispoTemplates as $version => $arrayDispoTemplates) {     
                // on affiche les entètes de version
                $this->logblock('Thèmes '.$version, 'HELP');
                foreach ($arrayDispoTemplates as $k => $dispoTemplate) {
                    $this->logSection($k, $dispoTemplate);
                    $choices[] = $k;
                }
            }

            // choix de la maquette du coeur
            $numTemplate = $this->askAndValidate(array(
                '',
                'Le numero du theme choisi?',
                ''
            ) , new sfValidatorChoice(array(
                'choices' => $choices,
                'required' => true
            ) , array(
                'invalid' => 'Le template n\'existe pas'
            )));

            foreach ($dispoTemplates as $version => $arrayDispoTemplates) {     
                if (isset($arrayDispoTemplates[$numTemplate])){
                    $nomTemplateChoisi = $arrayDispoTemplates[$numTemplate];
                    $nomVersionChoisi = $version;
                }
            }

            // Affichages des dump existants pour ce template
           // scan du dossier Externals/db
            switch ($nomVersionChoisi) {
                case 'v1':
                    $dirDbDump = dirname(__FILE__).'/../../data/_templates/' . $nomTemplateChoisi . '/Externals/db';
                    break;
                case 'v2':
                    $dirDbDump = dirname(__FILE__).'/../../lib/vendor/_themes/' . $nomTemplateChoisi . '/Externals/db';
                    break;                
                default:
                    # code...
                    break;
            }

            if (is_dir($dirDbDump)) {
                $arrayTemplateDumps = scandir($dirDbDump);
            } else {
                $this->logBlock('Dossier ' . $dirDbDump . ' inexistant. Annulation.', 'ERROR');
                exit;
            }
            $i = 0;
            $dispoTemplateDumps = array();
            
            foreach ($arrayTemplateDumps as $dump) {
                // on recupere les dumps
                if ($dump != '.' && $dump != '..' && substr($dump, (strlen($dump)) - 5) == '.dump') {
                    $i++;
                    $dispoTemplateDumps[$i] = $dump;
                }
            }
            // on affiche les dumps existants
            $this->logBlock('Dump existants du theme ' . $nomTemplateChoisi . ' :', 'INFO_LARGE');
            
            foreach ($dispoTemplateDumps as $k => $dispoTemplateDump) {
                $this->logSection($k, $dispoTemplateDump);
            }

            // choix du dump
            $dumpName = $this->askAndValidate(array(
                '',
                'Le numero du dump choisi?',
                ''
            ) , new sfValidatorChoice(array(
                'choices' => array_keys($dispoTemplateDumps) ,
                'required' => true
            ) , array(
                'invalid' => 'Le dump n\'existe pas'
            )));

            $file = $dirDbDump . '/' . $dispoTemplateDumps[$dumpName];
        } else {
            $file = $arguments['file'];
        }


        $results = contentTemplateTools::loadDB($file);
        // on purge le cache
        $this->runTask('ccc');
        // on remet les permissions
        $this->runTask('dm:permissions');

        $this->logBlock('Chargement de la base locale','INFO');
        foreach ($results as $result) {
            foreach ($result as $log => $desc) {
                $this->logBlock(utf8_decode($desc), utf8_decode($log));
            }
        }
    }

}
