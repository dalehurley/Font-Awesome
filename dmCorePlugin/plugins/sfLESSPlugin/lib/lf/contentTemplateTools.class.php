<?php

/**
 * class contentTemplateTools
 *
 */
class contentTemplateTools {

    const dumpExtension = 'dump';  // ATTENTION: utilise dans l'installer.php
    public static $undesiredTables = array(// les tables non desirees dans le dump pour le template de contenu
        'dm_catalogue',
        'dm_contact_me', 
        'dm_error',
        'dm_group',
        'dm_group_permission',
        'dm_permission',
        'dm_remember_key',
        'dm_sent_mail',
        'dm_setting',
        'dm_setting_translation',
        'dm_trans_unit',
        'migration_version'
    );

    public static $undesiredTablesWithoutSettings = array(// les tables non desirees dans le dump pour le template de contenu
        'dm_catalogue',
        'dm_contact_me', 
        'dm_error',
        'dm_group',
        'dm_group_permission',
        'dm_permission',
        'dm_remember_key',
        'dm_sent_mail',
        'dm_trans_unit',
        'migration_version'
    );    

    /**
     * 
     *   Retourne la config de la base de donnees dans un tableau avec les entrees:
     *  - user
     *  - pwd
     *  - dbname
     */
    public static function configBD() {
        $databaseConf = sfYaml::load(sfConfig::get('sf_config_dir') . '/databases.yml');
        $user = $databaseConf ['all']['doctrine']['param']['username'];
        $pwd = $databaseConf ['all']['doctrine']['param']['password'];
        $dsn = $databaseConf ['all']['doctrine']['param']['dsn'];

        if (preg_match('/dbname=.*;/', $dsn, $matches)) {   // on recupere le nom de la base de donnees à dumper, la base locale au site
            $dbname = str_replace('dbname=', '', $matches[0]);
            $dbname = str_replace(';', '', $dbname);
        } else {
            $return[$i]['ERROR'] = 'Impossible de recuperer le nom de la base dans config/database.yml';
        }

        if (preg_match('/host=.*;dbname/', $dsn, $matchesHost)) {   // on recupere le host de la base de donnees à dumper, la base locale au site
            $hostDb = str_replace('host=', '', $matchesHost[0]);
            $hostDb = str_replace(';dbname', '', $hostDb);
        } else {
            $return[$i]['ERROR'] = 'Impossible de recuperer le host de la base dans config/database.yml';
        }

        $config['user'] = $user;
        $config['pwd'] = $pwd;
        $config['dbname'] = $dbname;
        $config['dbhost'] = $hostDb;

        return $config;
    }

    /**
     * 
     *   Retourne la config de la base de donnees dans un tableau avec les entrees:
     *  - user
     *  - pwd
     *  - dbname
     */
    public static function ndd() {
        return dmConfig::get('site_name');
    }
    /**
     * 
     * Effectue une sauvegarde des donnees de contenu d'un site, au format sql
     */
    public static function dumpDB($file,$settings=false) {

        $listTables = '';   // les tables à extraire
        // config base
        $config = self::configBD();
        $user = $config['user'];
        $pwd = $config['pwd'];
        $dbname = $config['dbname'];
        $dbhost = $config['dbhost'];

        // liste des tables de la base
        $dbTables = dmDb::pdo('SHOW TABLES')->fetchAll();  // toutes les tables
        
        // on enleve les tables undesired
        if ($settings){ // si l'argument "settings" est présent alors on sauvegarde en plus les tables de settings
            $undesiredTables = self::$undesiredTablesWithoutSettings;
        } else {
            $undesiredTables = self::$undesiredTables;
        }
        
        $i = 1;
        foreach ($dbTables as $dbTable) {
            if (!in_array($dbTable[0], $undesiredTables)) {
                $listTables .= $dbTable[0] . ' ';
                //echo $dbTable[0] . " ajoutee au dump \n";
                $i++;
            }
        }

        //$return[]['dumpDB'] = $listTables;
        // dump de la base
        if ($settings){
            $fileOUT = $file . ".". self::ndd() .".settings." . self::dumpExtension; // on ajoute un prefixe à l'extension pour reconnaitre lors du loadDB les dump avec settings
            $dirOUTassets = $file . "." . self::ndd() .".settings." . self::dumpExtension . ".assets";
            $dirOUTmodules = $file . "." . self::ndd() .".settings." .self::dumpExtension . ".modules";
            $dirOUTtheme = $file . "." . self::ndd() .".settings." .self::dumpExtension . ".theme";
        } else {
            $fileOUT = $file . "." . self::dumpExtension;
            $dirOUTassets = $file . "." . self::dumpExtension . ".assets";
            $dirOUTmodules = $file . "." . self::dumpExtension . ".modules";
            $dirOUTtheme = $file . "." . self::dumpExtension . ".theme";
        }
        
        // option -c pour ajouter les champs dans la requete INSERT
        $output = exec("mysqldump -t -c --host=" . $dbhost . " --user=" . $user . " --password=" . $pwd . " " . $dbname . " " . $listTables . "> " . $fileOUT);
        $return[]['dumpDB'] = 'base ' . $dbname . ' -> ' . $fileOUT . '(' . filesize($fileOUT) . ' o)';

        // save du dossier uploads
        // le nom du dossier web
        $webDirName = sfConfig::get('sf_web_dir');
        
        $command = "rm -rf " . $dirOUTassets . ";";
        $command .= "mkdir " . $dirOUTassets .";";
        $command .= "mkdir " . $dirOUTassets."/uploads" .";";        
        $command .= "cp -R ". $webDirName . "/uploads/* " . $dirOUTassets ."/uploads;";

        $output = exec($command);
        // nettoyage du dossier assets en supprimant les dossiers .thumbs
        $command = 'find '. $dirOUTassets .' -name ".thumbs" -type d -exec rm -rf {} \;';
        $output = exec($command);

        $return[]['dumpDB'] = 'copie des assets';

        // save du dossier apps/front/modules/main
        if (is_dir($dirOUTmodules)) {
            $command = "cp -R apps/front/modules/main " . $dirOUTmodules . "/;";
        } else {
            $command = "mkdir " . $dirOUTmodules .";cp -R apps/front/modules/main " . $dirOUTmodules . "/;";
        }
        $output = exec($command);
        $return[]['dumpDB'] = 'copie du module main du front';


        // save du dossier uploads
        // le nom du dossier web
        $webDirName = sfConfig::get('sf_web_dir');
        
        $command = "rm -rf " . $dirOUTtheme . ";";
        $command .= "mkdir " . $dirOUTtheme .";";
        $command .= "mkdir " . $dirOUTtheme."/theme" .";";     
        $command .= "mkdir " . $dirOUTtheme."/theme/less" .";";              
        $command .= "cp ". $webDirName . "/theme/less/* " . $dirOUTtheme ."/theme/less;";

        $command .= "mkdir " . $dirOUTtheme."/theme/images" .";";              
        $command .= "cp ". $webDirName . "/theme/images/* " . $dirOUTtheme ."/theme/images;";

        // on supprime les fichiers import.less (v2) et _ConfigGeneral.less (v1) du dump effectué
        $command .= "rm ". $dirOUTtheme ."/theme/less/_ConfigGeneral.less;";
        $command .= "rm ". $dirOUTtheme ."/theme/less/import.less;";

        $output = exec($command);
        $return[]['dumpDB'] = 'copie des fichiers theme';

        return $return;
    }

    /**
     * 
     *  Charge les donnees d'une sauvegarde effectuee par self::dumpDB
     */
    public static function loadDB($file) {

        $ext = substr($file, strlen($file) - strlen(self::dumpExtension), strlen(self::dumpExtension));
        if ($ext != self::dumpExtension) {
            $return[]['ERROR'] = 'Le fichier : ' . $file . ' n\'a pas la bonne extension ' . self::dumpExtension;
            return $return;
        }

        // config base
        $config = self::configBD();
        $user = $config['user'];
        $pwd = $config['pwd'];
        $dbname = $config['dbname'];
        $dbhost = $config['dbhost'];

        // sauvegarde des donnees du dmUser admin superAdmin
        $adminUser = dmDb::table('dmUser')->findOneByIsSuperAdmin(true);
        $adminUserSaveAlgorithm = $adminUser->algorithm;
        $adminUserSaveSalt = $adminUser->salt;
        $adminUserSavePassword = $adminUser->password;
        $adminUserSaveEmail = $adminUser->email;  
        $return[]['COMMENT'] = 'User admin sauvegarde';        
                     

        // truncate des futures tables à integrer
        $i = 1;

        // mise en berne des clefs etrangeres pour faire des truncate tranquilum...
        dmDb::pdo('SET FOREIGN_KEY_CHECKS = 0');

        // liste des tables de la base
        $dbTables = dmDb::pdo('SHOW TABLES')->fetchAll();  // toutes les tables
        
        // on vide les toutes les tables sauf les tables undesired
        $ext = substr($file, strlen($file) - strlen(".settings.".self::dumpExtension), strlen(".settings.".self::dumpExtension));

        if ($ext == ".settings.".self::dumpExtension){ // si l'extension du fichier contient ".settings" alors on charge en plus les tables de settings
            $undesiredTables = self::$undesiredTablesWithoutSettings;
        } else {
            $undesiredTables = self::$undesiredTables;
        }         
        
        $i = 1;
        foreach ($dbTables as $dbTable) {
            if (!in_array($dbTable[0], $undesiredTables)) {
                dmDb::pdo('TRUNCATE TABLE ' . $dbTable[0]);
                //$return[]['COMMENT'] = $dbTable[0] . ' videe ';                
                $i++;
            }
        }        

        // mise en berne des clefs etrangeres pour faire des truncate tranquilum...
        dmDb::pdo('SET FOREIGN_KEY_CHECKS = 1');

        // dump de la base
        $fileINdb = $file;
        // save du dossier uploads
        $dirINassets = $file . ".assets";
        // save du dossier uploads
        $dirINmodule = $file . ".modules";
        // save du dossier theme
        $dirINtheme = $file . ".theme";        

        // load datas from DB
        $fileOUT = $file . "." . $dbname . "";
        // The '2>&1' is for redirecting errors to the standard IO (http://php.net/manual/fr/function.exec.php)
        // le '2>&1' est pour rediriger les erreur vers la sortie standard
        $command = 'mysql --host=' . $dbhost . ' --user=' . $user . ' --password=' . $pwd . ' ' . $dbname . ' < ' . $fileINdb . ' 2>&1';
        //$return[]['dumpDB'] = $command;

        $out = array();
        exec($command, $out);
//        print_r($out);

        if (count($out) == 0)
            $return[]['COMMENT'] = $file . ' ===> BD ' . $dbname;

        foreach ($out as $outLine) {
            if (strpos($outLine, 'ERROR') === false) {
                // 
            } else {
                $return[]['ERROR'] = $outLine;
                $return[]['ERROR'] = 'Le fichier ' . $file . ' n\'est pas en coherence avec la base ' . $dbname;
                $return[]['ERROR'] = 'Verifiez le modele de donnees du fichier ' . $file . ' avec le modele de donnees de la base ' . $dbname . '.';
            }
        }

        // récupération des données sauvegardées du dmUser admin
        $return[]['COMMENT'] = 'User admin recuperation';
        $adminUser = dmDb::table('dmUser')->findOneByIsSuperAdmin(true);
        dmDb::pdo('UPDATE dm_user u SET algorithm = \''.$adminUserSaveAlgorithm.'\', password = \''.$adminUserSavePassword.'\', salt= \''.$adminUserSaveSalt.'\' , email = \''.$adminUserSaveEmail.'\' WHERE id = \''.$adminUser->id.'\' ;');

        // load du dossier uploads
        // le dossier web
        $webDirName = substr(sfConfig::get('sf_web_dir'), strrpos(sfConfig::get('sf_web_dir'), '/') + 1);
        //echo "cp -R " . $dirINassets ."/* ". $webDirName . "/;";
        exec("cp -R " . $dirINassets ."/* ". $webDirName . "/;");
        $return[]['COMMENT'] = 'copie des assets ';

        // load du dossier theme
        exec("cp -R " . $dirINtheme ."/* ". $webDirName . "/;");
        $return[]['COMMENT'] = 'copie des fichiers theme';        
        
        // load du dossier apps/front/modules/main
        exec("cp -R " . $dirINmodule ."/* apps/front/modules/;");
        $return[]['COMMENT'] = 'copie du module main du front ';        

        return $return;
    }

    /**
     * Liste des themes graphiques disponibles
     * Le tableau de retour est de la forme
     * ['v1']
     *   ['themeZ'] 
     * ['v2']
     *   ['themeX']
     *   ['themeY']            
     * @return array 
     */
    public static function dispoThemes() {
        //---------------------------------------------------------------------------------
        //        recuperation des differentes maquettes du coeur
        //---------------------------------------------------------------------------------
        // scan du dossier /data/_templates du plugin
        $pluginDataDir = dirname(__FILE__) . '/../../data/_templates';   // les themes V1
        $pluginLibDirTheme = dirname(__FILE__) . '/../../lib/vendor/_themes';         //les themes v2
        $arrayTemplatesV1 = scandir($pluginDataDir);
        $arrayTemplatesV2 = scandir($pluginLibDirTheme);
        $dispoTemplates = array();
        $dispoTemplatesV1 = array();
        $dispoTemplatesV2 = array();
        
        foreach ($arrayTemplatesV1 as $template) {
            // on affiche les themes non precedes par un "_" qui correspondent aux themes de test ou obsoletes
            if ($template != '.' && $template != '..' && substr($template, 0, 1) != '_' && substr($template, 0, 1) != '.') {
                    $dispoTemplatesV1[] = $template;
            }
        }

        foreach ($arrayTemplatesV2 as $template) {
            // on affiche les themes non precedes par un "_" qui correspondent aux themes de test ou obsoletes
            if ($template != '.' && $template != '..' && substr($template, 0, 1) != '_' && substr($template, 0, 1) != '.') {
                    $dispoTemplatesV2[] = $template;
            }
        }

        // on stocke tous les themes ensemble
        $i = 1;
        foreach ($dispoTemplatesV1 as $templateV1) {
            $dispoTemplates['v1'][$i] = $templateV1;
            $i++;
        }
        foreach ($dispoTemplatesV2 as $templateV2) {
            $dispoTemplates['v2'][$i] = $templateV2;
            $i++;
        }

        return $dispoTemplates;

    }

    // doctrine:data-dump et data-load abandonnes au profit de mysqldump
//        /**
//     * 
//     *
//     */
//    public static function parseFixtures() {
//        $fileIn = '/data/templatesContenu/demo2.yml';
//        $fileOut = '/data/templatesContenu/demo2_traite.yml';
//
//        // chargement du fichier d'entree
//        $loader = sfYaml::load($fileIn);
//
//        // traitement du tableau $loader
//        $nonDesiredTables = array(    // les tables non desirees dans le chargement du template
////            'dm_contact_me',
////            'dm_error',
////            'dm_group',
////            'dm_group_permission',
////            'dm_mail_template',
////            'dm_mail_template_translation',
////            'dm_permission',
////            'dm_redirect',
////            'dm_remember_key',
////            'dm_sent_mail',
////            'dm_setting',
////            'dm_setting_translation',
////            'dm_trans_unit', 
//            'migration_version',
//
//            'sid_coord_name',
//            'sid_coord_name_version',
////            
////            'sid_blog_article',
////            'sid_blog_article_dm_tag',
////            'sid_blog_article_translation',
//            'sid_blog_article_translation_version',
////            'sid_blog_rubrique',
////            'sid_blog_rubrique_translation',
//            'sid_blog_rubrique_translation_version',
////            'sid_blog_type',
////            'sid_blog_type_article',
////            'sid_blog_type_translation',
//            'sid_blog_type_translation_version',
////            
////          'sid_bandeau',
////          'sid_bandeau_translation',
//            'sid_bandeau_translation_version',
////
////            'dm_media',            
////            'dm_media_folder',
//            
//        );
//
//        $i = 1;
//        foreach($nonDesiredTables as $nonDesiredTable){
//            unset($loader[dmString::camelize($nonDesiredTable)]);
//            $return[$i]['table non desirees'] = $nonDesiredTable;
//            $i++;
//        }
//        
//        // enregistrement dans le fichier de sortie
//        $yaml = sfYaml::dump($loader);
//        file_put_contents($fileOut, $yaml);
//
//        $return[0]['parseFixtures'] = $fileIn.' -> '.$fileOut.' OK';
//
//        return $return;
//    }
//    
//        /**
//     * 
//     *
//     */
//    public static function loadFixtures() {
//        
//        // ne pas charger les version
//        // creer les dossiers (mkdir) de dmMediaFolder sous upload
//        // ajouter http://www.doctrine-project.org/documentation/manual/1_2/data-fixtures/data-fixtures#fixtures-for-nested-sets
//        // 
// 
//    }
}

