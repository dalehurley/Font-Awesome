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

        $this->logBlock('Vous avez choisi de dupliquer le template : ' . $nomTemplateChoisi. ' ('.$nomVersionChoisi.')', 'CHOICE_LARGE');


        // choix du nouveau nom de theme 
        $newThemeName = $this->askAndValidate(array('', 'Le nom du nouveau thème? (en minuscule sans espace, de 3 à 15 caractères)', ''), new sfValidatorRegex(
                        array('pattern' => '/^[a-z]{3,15}$/',
                        'required' => true),
                        array('invalid' => 'Le nom du thème est invalide')
        ));

        switch ($nomVersionChoisi) {
            case 'v1':
                $newThemeName = $newThemeName . 'Theme'; // on ajoute Theme pour les v1
                $duplicateThemeDir = dirname ( __FILE__ ).'/../../data/_templates/'.$nomTemplateChoisi;
                $newThemeDir = dirname ( __FILE__ ).'/../../data/_templates/'.$newThemeName;
                break;
            case 'v2':
                $duplicateThemeDir = dirname ( __FILE__ ).'/../../lib/vendor/_themes/'.$nomTemplateChoisi;
                $newThemeDir = dirname ( __FILE__ ).'/../../lib/vendor/_themes/'.$newThemeName;
                break;            
            default:
                # code...
                break;
        }
            
        if (!is_dir($newThemeDir)){
            // duplication
            $this->logBlock('Copie du dossier ' . $duplicateThemeDir . ' en '. $newThemeDir, 'INFO');
            mkdir($newThemeDir);
            $this->getFilesystem()->execute('cp -r ' . $duplicateThemeDir .'/* ' . $newThemeDir, $out, $err);

            if ($nomVersionChoisi == 'v1'){ // pas besoin de tout ça pour les themes v2
                // changement du nom du theme dans le code des fichiers .less
                $this->logBlock('Renommage dans les fichiers', 'INFO');
                $this->getFilesystem()->execute('find '. $newThemeDir .' -name "*.less" -print | xargs perl -pi -e \'s/'.$nomTemplateChoisi.'/'.$newThemeName.'/g\'');

                // renommage du fichier racine du template qui doit avoir le nom du theme $newThemeDir.'/_'.$nomTemplateChoisi.'.less' en $newThemeDir.'/_'.$newThemeName.'.less'
                $this->logBlock('Renommage du fichier racine', 'INFO');
                $this->getFilesystem()->execute('mv '.$newThemeDir.'/_'.$nomTemplateChoisi.'.less ' .$newThemeDir.'/_'.$newThemeName.'.less');
            }

            // suppression des dumps du dossier $newThemeDir.'/Externals/db'
            $this->logBlock('Suppression des dumps existants', 'INFO');
            $this->getFilesystem()->execute('rm -rf '.$newThemeDir.'/Externals/db/*');        
        } else {
            $this->logBlock('Template déjà existant : ' . $newThemeName, 'ERROR');
            exit;
        } 
    }
}
