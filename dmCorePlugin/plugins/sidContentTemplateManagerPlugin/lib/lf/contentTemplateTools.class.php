<?php

/**
 * class contentTemplateTools
 *
 */
class contentTemplateTools {

    public static $dumpExtension = 'dump';  // ATTENTION: utilisé dans l'installer.php
    public static $desiredTables = array(// les tables désirées dans le dump pour le template de contenu
        'dm_auto_seo',
        'dm_auto_seo_translation',
        // traductions
        'dm_catalogue',
        'dm_trans_unit',
        // page 
        'dm_page',
        'dm_page_translation',
        // layout
        'dm_layout',
        'dm_page_view',
        'dm_area',
        'dm_zone',
        // widget
        'dm_widget',
        'dm_widget_translation',
        // dmTag
        'dm_tag',
        // sidWidgetBePlugin
        'sid_rubrique',
        'sid_rubrique_translation',
        'sid_section',
        'sid_section_translation',
        'sid_article',
        'sid_article_dm_tag',
        'sid_article_translation',
        // sidWidgetBandeauPlugin
        'sid_groupe_bandeau',
        'sid_groupe_bandeau_translation',
        'sid_bandeau',
        'sid_bandeau_translation',
        'sid_bandeau_translation_version',
        // sid Sites Utiles
        'sid_index_sites_utiles',
        'sid_index_sites_utiles_translation',
        'sid_groupe_sites_utiles',
        'sid_groupe_sites_utiles_translation',
        'sid_sites_utiles',
        'sid_sites_utiles_translation',
        'sid_sites_utiles_translation_version',
        // nouveaux plugins
        'sid_actu_article',
        'sid_actu_article_translation',
        'sid_actu_article_translation_version',
        'sid_actu_type',
        'sid_actu_type_translation',
        'sid_actu_type_translation_version',
        'sid_actu_type_article',
        'sid_cabinet_equipe',
        'sid_cabinet_equipe_translation',
        'sid_cabinet_equipe_translation_version',
        'sid_cabinet_mission',
        'sid_cabinet_mission_translation',
        'sid_cabinet_mission_translation_version',
        'sid_cabinet_page_cabinet',
        'sid_cabinet_page_cabinet_translation',
        'sid_cabinet_page_cabinet_translation_version',
        'sid_cabinet_recrutement',
        'sid_cabinet_recrutement_translation',
        'sid_cabinet_recrutement_translation_version',
        'sid_coord_name',
        'sid_coord_name_version',
        // media
        'dm_media_folder',
        'dm_media',
        'dm_media_translation',
        // constantes
        'sid_constantes',
        'sid_constantes_version',
        // tables N:N en lien vers sid_rubrique
        'sid_actu_article_sid_rubrique',
        'sid_cabinet_equipe_sid_rubrique',
        'sid_cabinet_mission_sid_rubrique'

// old        
//        'sid_blog_article',
//        'sid_blog_article_dm_tag',
//        'sid_blog_article_translation',
//        'sid_blog_article_translation_version',
//        'sid_blog_rubrique',
//        'sid_blog_rubrique_translation',
//        'sid_blog_rubrique_translation_version',
//        'sid_blog_type',
//        'sid_blog_type_article',
//        'sid_blog_type_translation',
//        'sid_blog_type_translation_version',
//        'sid_coord_name',
//        'sid_coord_name_version',
//              'dm_contact_me',
//              'dm_error',
//              'dm_group',
//              'dm_group_permission',
//              'dm_layout',
//              'dm_lock',
//              'dm_mail_template',
//              'dm_mail_template_translation',
//              'dm_permission',
//              'dm_redirect',
//              'dm_remember_key',
//              'dm_sent_mail',
//              'dm_setting',
//              'dm_setting_translation',
//              'dm_user',
//              'dm_user_group',
//              'dm_user_permission',
//              'migration_version'
    );

    /**
     * 
     *   Retourne la config de la base de données dans un tableau avec les entrées:
     *  - user
     *  - pwd
     *  - dbname
     */
    public static function configBD() {
        $databaseConf = sfYaml::load(sfConfig::get('sf_config_dir') . '/databases.yml');
        $user = $databaseConf ['all']['doctrine']['param']['username'];
        $pwd = $databaseConf ['all']['doctrine']['param']['password'];
        $dsn = $databaseConf ['all']['doctrine']['param']['dsn'];

        if (preg_match('/dbname=.*;/', $dsn, $matches)) {   // on récupère le nom de la base de données à dumper, la base locale au site
            $dbname = str_replace('dbname=', '', $matches[0]);
            $dbname = str_replace(';', '', $dbname);
        } else {
            $return[$i]['ERROR'] = 'Impossible de récupérer le nom de la base dans config/database.yml';
        }

        if (preg_match('/host=.*;dbname/', $dsn, $matchesHost)) {   // on récupère le host de la base de données à dumper, la base locale au site
            $hostDb = str_replace('host=', '', $matchesHost[0]);
            $hostDb = str_replace(';dbname', '', $hostDb);
        } else {
            $return[$i]['ERROR'] = 'Impossible de récupérer le host de la base dans config/database.yml';
        }

        $config['user'] = $user;
        $config['pwd'] = $pwd;
        $config['dbname'] = $dbname;
        $config['dbhost'] = $hostDb;

        return $config;
    }

    /**
     * 
     * Effectue une sauvegarde des données de contenu d'un site, au format sql
     */
    public static function dumpDB($file) {

        //$dbTables = dmDb::pdo('SHOW TABLES')->fetchAll();  // toutes les tables
        $listTables = '';   // les tables à extraire
        // config base
        $config = self::configBD();
        $user = $config['user'];
        $pwd = $config['pwd'];
        $dbname = $config['dbname'];
        $dbhost = $config['dbhost'];

        $i = 1;
        foreach (self::$desiredTables as $desiredTable) {
            $listTables .= $desiredTable . ' ';
            //$return[$i]['dumpDB'] = $desiredTable . ' ajoutée au dump ';
            $i++;
        }
        //$return[]['dumpDB'] = $listTables;
        // dump de la base
        $fileOUT = $file . "." . $dbname . "." . self::$dumpExtension;
        // option -c pour ajouter les champs dans la requete INSERT
        $output = exec("mysqldump -t -c --host=" . $dbhost . " --user=" . $user . " --password=" . $pwd . " " . $dbname . " " . $listTables . "> " . $fileOUT);
        $return[]['dumpDB'] = 'base ' . $dbname . ' -> ' . $fileOUT . '(' . filesize($fileOUT) . ' o)';

        // save du dossier uploads
        $fileOUTassets = $file . "." . $dbname . "." . self::$dumpExtension . ".assets.tgz";
        // le nom du dossier web
        $webDirName = substr(sfConfig::get('sf_web_dir'), strrpos(sfConfig::get('sf_web_dir'), '/') + 1);
        $output = exec("cd " . $webDirName . "/uploads; tar -czvf " . $fileOUTassets . " *; cd ..; cd ..;");
        $return[]['dumpDB'] = 'base ' . $dbname . ' -> ' . $fileOUTassets . '(' . filesize($fileOUTassets) . ' o)';

        // save du dossier apps/front/modules
        $fileOUTmodules = $file . "." . $dbname . "." . self::$dumpExtension . ".modules.tgz";
        $output = exec("cd apps/front/modules; tar -czvf " . $fileOUTmodules . " *; cd ..; cd ..; cd ..; cd ..;");
        $return[]['dumpDB'] = 'base ' . $dbname . ' -> ' . $fileOUTmodules . '(' . filesize($fileOUTmodules) . ' o)';

        return $return;
    }

    /**
     * 
     *  Charge les données d'une sauvegarde effectuée par self::dumpDB
     */
    public static function loadDB($file) {

        $ext = substr($file, strlen($file) - strlen(self::$dumpExtension), strlen(self::$dumpExtension));
        if ($ext != self::$dumpExtension) {
            $return[]['ERROR'] = 'Le fichier : ' . $file . ' n\'a pas la bonne extension ' . self::$dumpExtension;
            return $return;
        }

        // config base
        $config = self::configBD();
        $user = $config['user'];
        $pwd = $config['pwd'];
        $dbname = $config['dbname'];
        $dbhost = $config['dbhost'];

        // truncate des futures tables à intégrer
        $i = 1;

        // mise en berne des clefs étrangères pour faire des truncate tranquilum...
        dmDb::pdo('SET FOREIGN_KEY_CHECKS = 0');

        foreach (self::$desiredTables as $desiredTable) {
            // on vide les tables qui vont être remplies par le dump
            dmDb::pdo('TRUNCATE TABLE ' . $desiredTable);
            //    $return[$i]['dumpDB'] = $desiredTable . ' vidée ';
            $i++;
        }

        // mise en berne des clefs étrangères pour faire des truncate tranquilum...
        dmDb::pdo('SET FOREIGN_KEY_CHECKS = 1');

        // dump de la base
        $fileINdb = $file;
        // save du dossier uploads
        $fileINassets = $file . ".assets.tgz";
        // save du dossier uploads
        $fileINmodule = $file . ".modules.tgz";

        // load datas from DB
        $fileOUT = $file . "." . $dbname . "";
        // The '2>&1' is for redirecting errors to the standard IO (http://php.net/manual/fr/function.exec.php)
        // le '2>&1' est pour rediriger les erreur vers la sortie standard
        $command = 'mysql --host=' . $dbhost . ' --user=' . $user . ' --password=' . $pwd . ' ' . $dbname . ' < ' . $fileINdb . ' 2>&1';
        $return[]['dumpDB'] = $command;

        $out = array();
        exec($command, $out);
//        print_r($out);

        if (count($out) == 0)
            $return[]['dumpDB'] = $file . ' -> ' . 'base ' . $dbname;

        foreach ($out as $outLine) {
            if (strpos($outLine, 'ERROR') === false) {
                // 
            } else {
                $return[]['ERROR'] = $outLine;
                $return[]['ERROR'] = 'Le fichier ' . $file . ' n\'est pas en cohérence avec la base ' . $dbname;
                $return[]['ERROR'] = 'Verifiez le modele de donnees du fichier ' . $file . ' avec le modele de donnees de la base ' . $dbname . '.';
            }
        }

        // load du dossier uploads
        // le dossier web
        $webDirName = substr(sfConfig::get('sf_web_dir'), strrpos(sfConfig::get('sf_web_dir'), '/') + 1);
        $output = exec("cd " . $webDirName . "/uploads; tar -xzvf " . $fileINassets . "; cd ..; cd ..;");

        // load du dossier apps/front/modules
        $output = exec("cd apps/front/modules; tar -xzvf " . $fileINmodule . "; cd ..; cd ..; cd ..;");

        return $return;
    }

    // doctrine:data-dump et data-load abandonnés au profit de mysqldump
//        /**
//     * 
//     *
//     */
//    public static function parseFixtures() {
//        $fileIn = '/data/templatesContenu/demo2.yml';
//        $fileOut = '/data/templatesContenu/demo2_traite.yml';
//
//        // chargement du fichier d'entrée
//        $loader = sfYaml::load($fileIn);
//
//        // traitement du tableau $loader
//        $nonDesiredTables = array(    // les tables non désirées dans le chargement du template
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
//        // créer les dossiers (mkdir) de dmMediaFolder sous upload
//        // ajouter http://www.doctrine-project.org/documentation/manual/1_2/data-fixtures/data-fixtures#fixtures-for-nested-sets
//        // 
// 
//    }
}

