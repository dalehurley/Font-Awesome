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
        // scan du dossier /data/_templates du plugin
        $pluginDataDir = dirname(__FILE__) . '/../../data/_templates';
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
        //$pluginDataDir = dm::getDir() . '/dmCorePlugin/plugins/sfLESSPlugin/data';
        $pluginDataDir = dirname(__FILE__) . '/../../data';
        $dirTheme = sfConfig::get('sf_web_dir') . '/theme';
        // Copie du dossier diem/themesFmk/theme
        exec('rm -rf ' . $dirTheme);
        mkdir($dirTheme);
        $this->getFilesystem()->execute('cp -r ' . $pluginDataDir . '/theme/* ' . $dirTheme, $out, $err);
        // on remplace dans le dossier $dirTheme les ##THEME## par le $nomTemplateChoisi
        $this->getFilesystem()->execute('find ' . $dirTheme . ' -name "*.less" -print | xargs perl -pi -e \'s/##THEME##/' . $nomTemplateChoisi . '/g\'');
        // on cree le lien symbolique vers le dossier des _templates
        $this->getFilesystem()->execute('ln -s ' . $pluginDataDir . '/_templates/ ' . sfConfig::get('sf_web_dir') . '/theme/less/_templates', $out, $err);
        // recherche des templates -> XXXSuccess.php
        $dirPageSuccessFile = $pluginDataDir . '/_templates/' . $nomTemplateChoisi . '/Externals/php/layouts';
        // on créé les répertoires s'ils n'existent pas
        $dirDmFront = sfConfig::get('sf_root_dir') . '/apps/front/modules/dmFront';
        $dirDmFrontTemplate = $dirDmFront . '/templates';
        if (!is_dir($dirDmFront)) mkdir($dirDmFront);
        if (!is_dir($dirDmFrontTemplate)) mkdir($dirDmFrontTemplate);
        // Copie des xxxSuccess.php du theme sur le site
        $this->getFilesystem()->execute('cp ' . $dirPageSuccessFile . '/*Success.php ' . $dirDmFrontTemplate, $out, $err);
    }
}
