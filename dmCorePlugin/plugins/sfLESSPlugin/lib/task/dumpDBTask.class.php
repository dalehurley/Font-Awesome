<?php

class dumpDBTask extends lioshiBaseTask {
    protected function configure() {
        // // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('file', sfCommandArgument::OPTIONAL, 'Output file') ,
        ));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name') ,
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev') ,
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine') ,
            // add your own options here
            
        ));
        $this->namespace = 'db';
        $this->name = 'dumpDB';
        $this->briefDescription = 'Dump local diem Database into .dump file.';
        $this->detailedDescription = <<<EOF
The [dumpDB|INFO] dump local diem Database into file.
Call it with:

  [php symfony dumpDB [file]|INFO] 
EOF;
        
    }
    protected function execute($arguments = array() , $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        // add your code here
        if (!isset($arguments['file'])) {
            // recuperation des differentes maquettes du coeur
            // scan du dossier _templates

            
            $arrayTemplates = scandir(dirname(__FILE__).'/../../data/_templates');
            $i = 0;
            $dispoTemplates = array();
            
            foreach ($arrayTemplates as $template) {
                // on affiche les themes non precedes par un "_" qui correspondent aux themes de test ou obsoletes
                if ($template != '.' && $template != '..' && substr($template, 0, 1) != '_' && substr($template, -5) == 'Theme') {
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
            $dirDbDump = dirname(__FILE__).'/../../data/_templates/' . $dispoTemplates[$numTemplate] . '/Externals/db';
            if (!is_dir($dirDbDump)) {
                $this->logBlock('Dossier ' . $dirDbDump . ' inexistant. Création.', 'INFO');
                mkdir($dirDbDump);
            }

            $arrayTemplateDumps = scandir($dirDbDump);

            $i = 0;
            $dispoTemplateDumps = array();
            
            foreach ($arrayTemplateDumps as $dump) {
                // on affiche les themes non precedes par un "_" qui correspondent aux themes de test ou obsoletes
                if ($dump != '.' && $dump != '..' && substr($dump, (strlen($dump)) - 5) == '.dump') {
                    $i++;
                    $dispoTemplateDumps[$i] = $dump;
                }
            }
            // on affiche les dumps existants
            $this->logBlock('Dump existants du theme ' . $dispoTemplates[$numTemplate] . ' :', 'INFO_LARGE');
            
            foreach ($dispoTemplateDumps as $k => $dispoTemplateDump) {
                $this->log($dispoTemplateDump);
            }
            // on demande le nom du fichier choisi
            $dumpNameAuto = date("Y-m-d-H:i:s") . '-' . str_replace('Theme', '', $dispoTemplates[$numTemplate]); // nom automatique dans le suffixe Theme
            $dumpName = $this->ask(array(
                '',
                'Nom du dump a effectuer, sans extension .'.contentTemplateTools::dumpExtension.'? (par defaut: ' . $dumpNameAuto . ')',
                ''
            ));
            $dumpName = empty($dumpName) ? $dumpNameAuto : $dumpName;
            if (in_array($dumpName . '.' . contentTemplateTools::dumpExtension, $dispoTemplateDumps)) {
                if (!$this->askConfirmation(array(
                    '',
                    'Le fichier ' . $dirDbDump . '/' . $dumpName . '.' . contentTemplateTools::dumpExtension . ' existe. Continuer en le remplaçant? (y/n)',
                    ''
                ))) {
                    $this->logBlock('Annulation. Fichier existant.', 'ERROR');
                    exit;
                }
            }
            $file = $dirDbDump . '/' . $dumpName;
        } else {
            $file = $arguments['file'];
        }
        
        $results = contentTemplateTools::dumpDB($file);
        $this->logSection('### dumpDB', 'Dump de la base locale');
        
        foreach ($results as $result) {
            
            foreach ($result as $log => $desc) {
                $this->logSection(utf8_decode($log) , utf8_decode($desc) , null, utf8_decode($log));
            }
        }
    }
}
