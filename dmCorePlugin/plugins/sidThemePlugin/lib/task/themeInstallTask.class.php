<?php

class themeInstallTask extends lioshiBaseTask {
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
        $this->name = 'install';
        $this->briefDescription = 'Install a new theme';
        $this->detailedDescription = <<<EOF
The [theme:install|INFO] task installs theme.
Call it with:

  [php symfony theme:install|INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {
        //---------------------------------------------------------------------------------
        //        recuperation des differentes maquettes du coeur
        //---------------------------------------------------------------------------------
        // scan du dossier /data/templates du plugin
        $arrayTemplates = scandir(dm::getDir() . '/dmCorePlugin/plugins/sidThemePlugin/data/_themes');
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
            $this->logSection($k, $dispoTemplate);
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
        $nomTemplateChoisi = $dispoTemplates[$numTemplate];
        $this->logBlock('Vous avez choisi le template : ' . $nomTemplateChoisi, 'CHOICE_LARGE');
        //$this->logBlock('Execution time  ' . round($timerTask->getElapsedTime() , 3) . ' s', 'INFO_LARGE');
        //---------------------------------------------------------------------------------
        //        installation du theme
        //---------------------------------------------------------------------------------
        $pluginDataDir = dm::getDir() . '/dmCorePlugin/plugins/sidThemePlugin/data';
        $dirTheme = sfConfig::get('sf_web_dir') . '/theme';

        // Copie du dossier diem/themesFmk/theme
        if (!is_dir($dirTheme)) mkdir($dirTheme);
        $this->getFilesystem()->execute('cp -r ' . $pluginDataDir.'/theme/* ' . $dirTheme, $out, $err);
        // on remplace dans le dossier $dirTheme les ##THEME## par le $nomTemplateChoisi
        $this->getFilesystem()->execute('find ' . $dirTheme . ' -name "*.less" -print | xargs perl -pi -e \'s/##THEME##/' . $nomTemplateChoisi . '/g\'');
        // on cree le lien symbolique
        $this->getFilesystem()->execute('ln -s ' . $pluginDataDir.'/_themes/ ' . sfConfig::get('sf_web_dir') . '/theme/less/_themes', $out, $err);

// les fichiers less de _theme ne seront pas pris en compte par sfless
// TODO : Modifier : public function getCssPathOfLess($lessFile) pour qu'il accepte le dossier _themes...
// proprement.... en appelant une fonction sfLess::setNewChemin for less files...
/*  //debug chemins des liens symboliques
    $token = 'themesFmk';
    if(strpos($lessFile, $token)){
      //on récupère la portion se situant après themesFmk
      $lessFile = self::getConfig()->getLessPaths() . substr($lessFile, strrpos($lessFile, $token) + strlen($token) + 1);
    }
*/

// TODO: intégrer le plugin sidThemePlugin dans sfLessPlugin, avec comme ça un dossier _themes où les less peuvent être compiler en css déposés localement sur le site...



        // recherche des templates -> XXXSuccess.php
        $dirPageSuccessFile = $pluginDataDir.'/_themes/' . $nomTemplateChoisi . '/Externals/php/layouts';
        // on créé les répertoires s'ils n'existent pas
        $dirDmFront = sfConfig::get('sf_root_dir') . '/apps/front/modules/dmFront';
        $dirDmFrontTemplate = $dirDmFront . '/templates';
        if (!is_dir($dirDmFront)) mkdir($dirDmFront);
        if (!is_dir($dirDmFrontTemplate)) mkdir($dirDmFrontTemplate);
        // Copie des xxxSuccess.php du theme sur le site
        $this->getFilesystem()->execute('cp ' . $dirPageSuccessFile . '/*Success.php ' . $dirDmFrontTemplate, $out, $err);
    }
}
