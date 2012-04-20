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

        $this->logBlock('Vous avez choisi le thème : ' . $nomTemplateChoisi. ' ('.$nomVersionChoisi.')', 'CHOICE_LARGE');
       

        if ($nomVersionChoisi == 'v1'){                                                                                                // installation theme V1
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
            
            // on cree le lien symbolique vers le dossier des _templates/$nomTemplateChoisi
            $dirThemeTemplates = $dirTheme.'/less/_templates';
            mkdir($dirThemeTemplates);
            $this->getFilesystem()->execute('ln -s ' . $pluginDataDir . '/_templates/'. $nomTemplateChoisi . ' ' . $dirThemeTemplates .'/'.$nomTemplateChoisi, $out, $err);
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
        } else {                                                                                                                       // installation du theme V2
            $pluginLibDir = dirname(__FILE__) . '/../../lib/vendor';
            $dirTheme = sfConfig::get('sf_web_dir') . '/theme';
            // Copie du dossier diem/themesFmk/theme
            exec('rm -rf ' . $dirTheme);
            mkdir($dirTheme);
            $this->getFilesystem()->execute('cp -r ' . $pluginLibDir . '/theme/* ' . $dirTheme, $out, $err);
            // on remplace dans le dossier $dirTheme les ##THEME## par le $nomTemplateChoisi
            $this->getFilesystem()->execute('find ' . $dirTheme . ' -name "*.less" -print | xargs perl -pi -e \'s/##THEME##/' . $nomTemplateChoisi . '/g\'');

            // on cree le lien symbolique vers le dossier des _templates/$nomTemplateChoisi
            $dirThemes = $dirTheme.'/less/_themes';
            mkdir($dirThemes);
            $this->getFilesystem()->execute('ln -s ' . $pluginLibDir . '/_themes/'. $nomTemplateChoisi . ' ' . $dirThemes .'/'.$nomTemplateChoisi, $out, $err);
            // on cree le lien symbolique vers le dossier bootstrap
            $this->getFilesystem()->execute('ln -s ' . $pluginLibDir . '/bootstrap/ ' . sfConfig::get('sf_web_dir') . '/theme/less/bootstrap', $out, $err);

            // recherche des templates -> XXXSuccess.php
            $dirPageSuccessFile = $pluginLibDir . '/_themes/' . $nomTemplateChoisi . '/Externals/php/layouts';
            // on créé les répertoires s'ils n'existent pas
            $dirDmFront = sfConfig::get('sf_root_dir') . '/apps/front/modules/dmFront';
            $dirDmFrontTemplate = $dirDmFront . '/templates';
            if (!is_dir($dirDmFront)) mkdir($dirDmFront);
            if (!is_dir($dirDmFrontTemplate)) mkdir($dirDmFrontTemplate);
            // Copie des xxxSuccess.php du theme sur le site
            $this->getFilesystem()->execute('cp ' . $dirPageSuccessFile . '/*Success.php ' . $dirDmFrontTemplate, $out, $err);
        }


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
            $lang = 1; // only one? we take the 1
        }

        // sauvegarde du site_theme dans la table dmSetting
        $queryMajSiteTheme = 'UPDATE `dm_setting_translation` t JOIN  `dm_setting` s on s.id = t.id SET value = \''.$nomTemplateChoisi.'\' WHERE t.lang = \''.$arrayLangs[$lang].'\' AND s.name = \'site_theme\';';
        $connection->query($queryMajSiteTheme);

        // $configSiteTheme = array(
        //     'type' => 'text',
        //     'default_value' => ' ',
        //     'value' => $nomTemplateChoisi,
        //     'description' => 'Site current theme',
        //     'group_name' => 'site',
        //     'lang' => $arrayLangs[$lang]
        // );
        // $setting = dmDB::table('dmSetting')->findOneByName('site_theme');
        // if (is_object($setting)) {
        //     $setting->delete(); // delete entry site_theme
        // } 
        // $setting = new DmSetting;
        // $setting->set('name', 'site_theme');
        // $setting->fromArray($configSiteTheme);
        // $setting->save();
        
        // sauvegarde du site_theme_version dans la table dmSetting
        $queryMajSiteThemeVersion = 'UPDATE `dm_setting_translation` t JOIN  `dm_setting` s on s.id = t.id SET value = \''.$nomVersionChoisi.'\' WHERE t.lang = \''.$arrayLangs[$lang].'\' AND s.name = \'site_theme_version\';';
        $connection->query($queryMajSiteThemeVersion);

        // $configSiteThemeVersion = array(
        //     'type' => 'text',
        //     'default_value' => ' ',
        //     'value' => $nomVersionChoisi,
        //     'description' => 'Site current theme version',
        //     'group_name' => 'site',
        //     'lang' => $arrayLangs[$lang]
        // );
        // $setting = dmDB::table('dmSetting')->findOneByName('site_theme_version');
        // if (is_object($setting)) {
        //     $setting->delete(); // delete entry site_theme_version
        // } 
        // $setting = new DmSetting;
        // $setting->set('name', 'site_theme_version');
        // $setting->fromArray($configSiteThemeVersion);
        // $setting->save();
       
        $this->logBlock('Le theme : ' . $nomTemplateChoisi. ' ('.$nomVersionChoisi.')' . ' est installe.', 'INFO_LARGE');
    }
}
