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

  [php symfony loadDB [file] |INFO]
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
            // scan du dossier _templates
            $arrayTemplates = scandir(dm::getDir() . '/themesFmk/_templates');
            $i = 0;
            $dispoTemplates = array();
            
            foreach ($arrayTemplates as $template) {
                // on affiche les themes non precedes par un "_" qui correspondent aux themes de test ou obsoletes
                if ($template != '.' && $template != '..' && substr($template, 0, 1) != '_') {
                    $i++;
                    $dispoTemplates[$i] = $template;
                }
            }
            // on affiche les choix
            $this->logBlock('Themes disponibles :', 'INFO_LARGE');
            
            foreach ($dispoTemplates as $k => $dispoTemplate) {
                $this->log($k . ' - ' . $dispoTemplate);
            }
            // choix de la maquette du coeur
            $numTemplate = $this->askAndValidate(array(
                '',
                'Le numero du template choisi?',
                ''
            ) , new sfValidatorChoice(array(
                'choices' => array_keys($dispoTemplates) ,
                'required' => true
            ) , array(
                'invalid' => 'Le template n\'existe pas'
            )));

            // Affichages des dump existants pour ce template
            // scan du dossier _templates/themechoisi/Externals/db
            $dirDbDump = dm::getDir() . '/themesFmk/_templates/' . $dispoTemplates[$numTemplate] . '/Externals/db';
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
            $this->logBlock('Dump existants du theme ' . $dispoTemplates[$numTemplate] . ' :', 'INFO_LARGE');
            
            foreach ($dispoTemplateDumps as $k => $dispoTemplateDump) {
                $this->log($k . ' - ' . $dispoTemplateDump);
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
