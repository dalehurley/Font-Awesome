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
            new sfCommandOption('settings', null, sfCommandOption::PARAMETER_REQUIRED, 'Save settings?', false) ,            
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

            //$this->logBlock('Vous avez choisi le thème : ' . $nomTemplateChoisi. ' ('.$nomVersionChoisi.')', 'CHOICE_LARGE');

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
                
            if (!is_dir($dirDbDump)) {
                $this->logBlock('Dossier ' . $dirDbDump . ' inexistant. Création.', 'INFO');
                mkdir($dirDbDump);
            }

            $arrayTemplateDumps = scandir($dirDbDump);

            $i = 0;
            $dispoTemplateDumps = array();
            
            foreach ($arrayTemplateDumps as $dump) {
                if ($dump != '.' && $dump != '..' && substr($dump, (strlen($dump)) - 5) == '.dump') {
                    $i++;
                    $dispoTemplateDumps[$i] = $dump;
                }
            }
            // on affiche les dumps existants
            $this->logBlock('Dump existants du theme ' . $nomTemplateChoisi . ' :', 'INFO_LARGE');
            
            foreach ($dispoTemplateDumps as $k => $dispoTemplateDump) {
                $this->log($dispoTemplateDump);
            }
            // on demande le nom du fichier choisi
            $dumpNameAuto = date("Y-m-d-H:i:s") . '-' . $nomTemplateChoisi; // nom automatique dans le suffixe Theme
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
        
        // on sauvegarde en plus les tables settins (dmSettings et dmSettingsTranslation)
        if ($options['settings']) {
            $settings = true;
        } else {
            $settings = false;
        }
        $results = contentTemplateTools::dumpDB($file,$settings);
        $this->logSection('### dumpDB', 'Dump de la base locale');
        
        foreach ($results as $result) {
            
            foreach ($result as $log => $desc) {
                $this->logSection(utf8_decode($log) , utf8_decode($desc) , null, utf8_decode($log));
            }
        }
    }
}
