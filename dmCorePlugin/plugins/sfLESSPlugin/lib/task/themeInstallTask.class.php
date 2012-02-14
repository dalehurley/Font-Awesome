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
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
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
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
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
            'Le numero du theme choisi?',
            ''
        ) , new sfValidatorChoice(array(
            'choices' => array_keys($dispoTemplates) ,
            'required' => true
        ) , array(
            'invalid' => 'Le template n\'existe pas'
        )));
        $nomTemplateChoisi = $dispoTemplates[$numTemplate];
        $this->logBlock('Vous avez choisi le thème : ' . $nomTemplateChoisi, 'CHOICE_LARGE');
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
        // on cree le lien symbolique vers le dossier du _framework
        $this->getFilesystem()->execute('ln -s ' . $pluginDataDir . '/_framework/ ' . sfConfig::get('sf_web_dir') . '/theme/less/_framework', $out, $err);


        // recherche des templates -> XXXSuccess.php
        $dirPageSuccessFile = $pluginDataDir . '/_templates/' . $nomTemplateChoisi . '/Externals/php/layouts';
        // on créé les répertoires s'ils n'existent pas
        $dirDmFront = sfConfig::get('sf_root_dir') . '/apps/front/modules/dmFront';
        $dirDmFrontTemplate = $dirDmFront . '/templates';
        if (!is_dir($dirDmFront)) mkdir($dirDmFront);
        if (!is_dir($dirDmFrontTemplate)) mkdir($dirDmFrontTemplate);
        // Copie des xxxSuccess.php du theme sur le site
        $this->getFilesystem()->execute('cp ' . $dirPageSuccessFile . '/*Success.php ' . $dirDmFrontTemplate, $out, $err);
        // Sauvegarde du nom du theme choisi
        // choix de la langue
        $arrayLangs = sfConfig::get('dm_i18n_cultures');
        // on supprime l'entrée de key = 0 car le zéro est interprété comme null en cli
        // la première clef du tableau devient donc 1
        array_unshift($arrayLangs, "");
        unset($arrayLangs[0]);

        if (count($arrayLangs) > 1){
        // on affiche les choix de langue
        $this->logBlock('Langues disponibles :', 'INFO_LARGE');
        
        foreach ($arrayLangs as $k => $arrayLang) {
            $this->logSection($k, $arrayLang);
        }
        $lang = $this->askAndValidate(array(
            '',
            'Quelle langue pour ce theme?',
            ''
        ) , new sfValidatorChoice(array(
            'choices' => array_keys($arrayLangs) ,
            'required' => true
        ) , array(
            'invalid' => 'La langue n\'existe pas'
        )));
        } else {
            $lang = 1;
        }
        // sauvegarde du site_theme dans la table dmSetting
        $configSiteTheme = array(
            'type' => 'text',
            'default_value' => ' ',
            'value' => $nomTemplateChoisi,
            'description' => 'Site current theme',
            'group_name' => 'site',
            'lang' => $arrayLangs[$lang]
        );
        $setting = dmDB::table('dmSetting')->findOneByName('site_theme');
        if (is_object($setting)) {
            $settingTranslation = dmDB::table('dmSettingTranslation')->findOneByIdAndLang($setting->id, $arrayLangs[$lang]);
            $settingTranslation->set('value', $nomTemplateChoisi);
            $settingTranslation->save();
        } else {
            $setting = new DmSetting;
            $setting->set('name', 'site_theme');
            $setting->fromArray($configSiteTheme);
            $setting->save();
        }
        $this->logBlock('Le theme : ' . $nomTemplateChoisi . ' est installe.', 'INFO_LARGE');
    }
}
