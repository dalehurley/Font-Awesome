<?php

class themeNewTask extends lioshiBaseTask {
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
        $this->namespace = 'theme';
        $this->name = 'duplicate';
        $this->briefDescription = 'Duplicate a theme';
        $this->detailedDescription = <<<EOF
The [theme:duplicate|INFO] task duplicate a theme.
Call it with:

  [php symfony theme:duplicate|INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {
        $out = $err = null;
        //---------------------------------------------------------------------------------
        //        recuperation des differentes maquettes du coeur
        //---------------------------------------------------------------------------------
        // scan du dossier /data/_templates du plugin
        $pluginDataDir = dirname ( __FILE__ ).'/../../data/_templates';
        $arrayTemplates = scandir($pluginDataDir);

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
        $this->logBlock('Themes disponibles pour duplication:', 'INFO_LARGE');
        
        foreach ($dispoTemplates as $k => $dispoTemplate) {
            $this->logSection($k, $dispoTemplate);
        }
        // choix de la maquette du coeur
        $numTemplate = $this->askAndValidate(array(
            '',
            'Le numero du template a dupliquer choisi?',
            ''
        ) , new sfValidatorChoice(array(
            'choices' => array_keys($dispoTemplates) ,
            'required' => true
        ) , array(
            'invalid' => 'Le template n\'existe pas'
        )));
        $nomTemplateChoisi = $dispoTemplates[$numTemplate];
        $this->logBlock('Vous avez choisi de dupliquer le template : ' . $nomTemplateChoisi, 'CHOICE_LARGE');


        // choix du nouveau nom de theme 
        $newThemeName = $this->askAndValidate(array('', 'Le nom du nouveau thème? (en minuscule sans espace, de 3 à 15 caractères)', ''), new sfValidatorRegex(
                        array('pattern' => '/^[a-z]{3,15}$/',
                        'required' => true),
                        array('invalid' => 'Le nom du thème est invalide')
        ));
        // on suffixe par Theme
        $newThemeName = $newThemeName . 'Theme';
        // duplication du thème
        $duplicateThemeDir = dirname ( __FILE__ ).'/../../data/_templates/'.$nomTemplateChoisi;
        $newThemeDir = dirname ( __FILE__ ).'/../../data/_templates/'.$newThemeName;

        if (!is_dir($newThemeDir)){
            // duplication
        mkdir($newThemeDir);
        $this->getFilesystem()->execute('cp -r ' . $duplicateThemeDir .'/* ' . $newThemeDir, $out, $err);
        // changement du nom du theme dans le code des fichiers .less
         $this->getFilesystem()->execute('find '. $newThemeDir .' -name "*.less" -print | xargs perl -pi -e \'s/'.$nomTemplateChoisi.'/'.$newThemeName.'/g\'');
        } else {
            $this->logBlock('Template déjà existant : ' . $newThemeName, 'ERROR');
            exit;
        } 
    }
}
