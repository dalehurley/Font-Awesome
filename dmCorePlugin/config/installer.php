<?php

/** @var sfGenerateProjectTask This file runs in the context of sfGenerateProjectTask::execute() */ 
//$this;

/**
 * parametres globaux
 * sfConfig inaccessible ici...
 * 
 */
sfConfig::set('dm_core_dir', realpath(dirname(__FILE__) . '/..'));

require_once(sfConfig::get('dm_core_dir') . '/lib/core/dm.php');
require_once(sfConfig::get('dm_core_dir') . '/lib/basic/dmString.php');
require_once(sfConfig::get('dm_core_dir') . '/lib/os/dmOs.php');
require_once(sfConfig::get('dm_core_dir') . '/lib/project/dmProject.php');
require_once(sfConfig::get('dm_core_dir') . '/lib/task/dmServerCheckTask.class.php');

// On redéfini le formatter utilisé avec celui de lioshiBaseTask
require_once(sfConfig::get('dm_core_dir') . '/plugins/lioshiPlugin/lib/task/lioshiBaseTask.class.php');
$lioshiTask = new lioshiBaseTask($this->dispatcher, $this->formatter);
$this->setFormatter($lioshiTask->formatter);


//$this->logBlock(DIEM_VERSION . '-SID installer', 'INFO_LARGE');
//$this->logSection('Site V3', 'Bienvenue dans l\'installeur des sites V3.');
//$this->logSection('Diem', 'We will now check if your server matches Symfony '.SYMFONY_VERSION.' and Diem '.DIEM_VERSION.' requirements.');
//$this->logBlock('Verification du systeme.', 'INFO_LARGE');
//usleep(1000000);
//$this->askConfirmation(array('','Pressez ENTREE pour commencer.',''));

$serverCheck = new dmServerCheckTask($this->dispatcher, $this->formatter);
$serverCheck->setCommandApplication($this->commandApplication);

//
//if ($this->askConfirmation(array(
//            dirname(__FILE__).' Arreter ? (y/n)'), 'QUESTION_LARGE','')
//) {
//    exit;
//}

//try {
//    //$serverCheck->run();
//} catch (dmServerCheckException $e) {
//    if (!$this->askConfirmation('Continuer l\'installation? (y/n)')) {
//        $this->log('Aborted.');
//        exit;
//    }
//}

$this->logBlock('Diem Sid - Installation','HELP_LARGE');

/**
 *  Valeurs par defaut 
 */
// on affiche les choix d'environnemnts pour les valeurs par defaut
$dispoEnvs = array (
  1 => 'developpement en local',
  2 => 'serveur de production 91.194.100.239'
);
$this->logBlock('Environnements disponibles :', 'INFO_LARGE');
foreach ($dispoEnvs as $k => $dispoEnv) {
    $this->logSection($k,$dispoEnv);
}
// choix du dump
$numEnv = $this->askAndValidate(array('', 'Le numero de l\'environnement choisi?', ''), new sfValidatorChoice(
                        array(
                          'choices' => array_keys($dispoEnvs), 
                          'required' => true),
                        array(
                          'invalid' => 'L\'environnement n\'existe pas'
                          )
        ));
switch ($numEnv) {
  case 1:
    $apacheDirLog = '/data/logs/sitesv3';
    $webDirDefault = 'htdocs';
    $dbHost = 'localhost';
    $dbPrefixe = 'sitev3';
    $dbUser = 'root';
    $dbPwd = 'root';
    $beDirImages = '/data/www/_lib/baseEditoriale/_images/';
    break;

  case 2:
    $apacheDirLog = '/data/logs/sitesv3';
    $webDirDefault = 'htdocs';
    $dbHost = 'db.local';
    $dbPrefixe = 'sitev3';
    $dbUser = 'uSitesv3';
    $dbPwd = 'SADY1234';  
    $beDirImages = '/data/www/_lib/baseEditoriale/_images/';
    break;

  default:
    throw new Exception('Environnement incomprehensible...');
    break;
}

/*
 * INITIALIZATION
 */
$settings = array();

if ('Doctrine' != $this->options['orm']) {
    throw new Exception('We are sorry, but Diem ' . DIEM_VERSION . ' supports only Doctrine for ORM.');
}

$projectKey = dmProject::getKey();  // le nom du dossier du site

$ndd = $this->askAndValidate(array('', 'Le nom de domaine? (format: example.com)', ''), new sfValidatorRegex(
                        array('pattern' => '/^([a-z0-9-]+\.)+[a-z]{2,6}$/',
                        'required' => true),
                        array('invalid' => 'Le nom de domaine est pas invalide')
        ));
$settings['ndd'] = $ndd;

/*
 * QUESTIONS
 */

//$this->logSection($projectKey, 'Merci de repondre a quelques questions pour configurer le site '.$projectKey."\n");


/*
  $culture = $this->askAndValidate(array('', 'Choisissez la langue principale du site (defaut: fr )', ''), new sfValidatorRegex(
  array('pattern' => '/^[\w\d-]+$/', 'max_length' => 2, 'min_length' => 2, 'required' => false),
  array('invalid' => 'Language must contain two alphanumeric characters')
  ));
  $settings['culture'] = empty($culture) ? 'fr' : $culture;
 */
$settings['culture'] = 'fr';


$webDirName = $this->askAndValidate(array('', 'Le nom du dossier web public? (par defaut : '.$webDirDefault.' )', ''), new sfValidatorAnd(array(
                    new sfValidatorRegex(
                            array('pattern' => '/^[\w\d-]+$/', 'required' => false),
                            array('invalid' => 'Seulement des caracteres alphanumeriques.')
                    ),
                    new sfValidatorRegex(
                            array('pattern' => '/^(apps|lib|config|data|cache|log|plugins|test)$/', 'must_match' => false, 'required' => false),
                            array('invalid' => 'Deja utilise par symfony')
                    )
                        ), array('required' => false)), array('required' => false));
$settings['web_dir_name'] = empty($webDirName) ? $webDirDefault : $webDirName;


// le dossier de logs Apache
$apache_dir_log = $this->ask(array('', 'Dossier des logs Apache? (par defaut : '.$apacheDirLog.')', ''));
$settings['apache_dir_log'] = empty($apache_dir_log) ? $apacheDirLog : $apache_dir_log;

// on attend que le dossier de logs apache soit cree
do {
    if (!is_dir($settings['apache_dir_log'])) {
        $this->logBlock('Il faut creer le dossier "' . $settings['apache_dir_log'] . '". Merci.', 'ERROR_LARGE');
        $this->ask(array('', 'Si le dossier "' . $settings['apache_dir_log'] . '" est cree tapez ENTER.', ''));
    }
} while (!is_dir($settings['apache_dir_log']));
// on attend que le dossier de logs apache du site puisse être cree
//mkdir($settings['apache_dir_log'] . '/' . $projectKey); 
do {
    if (!is_dir($settings['apache_dir_log'] . '/' . $projectKey)) {
        mkdir($settings['apache_dir_log'] . '/' . $projectKey);
        if (!is_dir($settings['apache_dir_log'] . '/' . $projectKey)) {
            $this->logBlock('Le dossier de logs Apache "' . $settings['apache_dir_log'] . '/' . $projectKey . '" ne peut se creer... Verifiez les droits.', 'ERROR_LARGE');
            $this->ask(array('', 'Verifiez les droits pour la creation du dossier "' . $settings['apache_dir_log'] . '/' . $projectKey . '". Ou bien creez le manuellement puis taper ENTER.', ''));
        }
    }
} while (!is_dir($settings['apache_dir_log'] . '/' . $projectKey));

do {
    $defaultDbName = dmString::underscore(str_replace('-', '_', $projectKey));
    $isDatabaseOk = false; 

    /*
      $dbm = $this->askAndValidate(array('', 'What kind of database will be used? ( mysql | pgsql | sqlite )', ''), new sfValidatorChoice(array(
      'choices' => array('mysql', 'pgsql', 'sqlite')
      )));
     */
    $dbm = 'mysql';

    if ('sqlite' !== $dbm) {
        $settings['database'] = array(
//      'name'      => $this->ask(array('', 'Le nom de la base de donnees? ( defaut: '.$defaultDbName.' )', ''), 'QUESTION', $defaultDbName),
      'prefixe'      => $this->ask(array('', 'DB prefixe? (par defaut: '.$dbPrefixe.')', '')),            
      'host'      => $this->ask(array('', 'DB host? (par defaut: '.$dbHost.')', '')),
      'user'      => $this->ask(array('', 'DB utilisateur? (par defaut: '.$dbUser.')', '')),
      'password'  => $this->ask(array('', 'DB mot de passe? (par defaut: '.$dbPwd.')', ''))
        );
        $settings['database']['prefixe'] = empty($settings['database']['prefixe']) ? $dbPrefixe : $settings['database']['prefixe'];        
        $settings['database']['name'] = $settings['database']['prefixe'].'_'.$defaultDbName;
        $settings['database']['host'] = empty($settings['database']['host']) ? $dbHost : $settings['database']['host'];
        $settings['database']['user'] = empty($settings['database']['user']) ? $dbUser : $settings['database']['user'];
        $settings['database']['password'] = empty($settings['database']['password']) ? $dbPwd : $settings['database']['password'];
    } else {
        $settings['database'] = array(
            'name' => $defaultDbName,
            'user' => null,
            'password' => null
        );
    }

    switch ($dbm) {
        case "mysql":
            $settings['database']['dsn'] = sprintf('mysql:host=%s;dbname=%s;', $settings['database']['host'], $settings['database']['name']);
            break;
        case "pgsql":
            $settings['database']['dsn'] = sprintf('pgsql:host=%s;dbname=%s;', $settings['database']['host'], $settings['database']['name'], $settings['database']['user'], $settings['database']['password']);
            break;
        case "sqlite":
            $dbFile = dmOs::join(sfConfig::get('sf_data_dir'), $defaultDbName . '.sqlite');
            $settings['database']['dsn'] = sprintf('sqlite:%s', $dbFile);
            touch($dbFile);
            break;
        default:
            $isDatabaseOk = false;
            $this->logBlock('Diem 5.0 only supports mysql, pgsql and sqlite', 'ERROR_LARGE');
            $this->log('');
    }

    if (isset($settings['database']['dsn'])) {
        // we try to connect only if the user chose a valid database
        $isDatabaseExist = true;

        try {
            $dbh = new PDO($settings['database']['dsn'], $settings['database']['user'], $settings['database']['password']);
            $isDatabaseOk = true;
        } catch (PDOException $e) {
            if (in_array($dbm, array('mysql', 'pgsql')) && false !== strpos($e->getMessage(), 'Unknown database')) {
                try {
                    $this->log('');
                    $this->log('La base de donnees n\'existe pas, creation...');
                    $isDatabaseExist = false;

                    $dbh = new PDO($dbm . ':host=' . $settings['database']['host'], $settings['database']['user'], $settings['database']['password']);

                    if ('mysql' == $dbm) {
                        $dbh->query(sprintf('CREATE DATABASE `%s` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;', $settings['database']['name']));
                    } else {
                        $dbh->query(sprintf("CREATE DATABASE %s WITH ENCODING 'UNICODE';", $settings['database']['name']));
                    }

                    // verify that we can connect to the database
                    $dbh = new PDO($settings['database']['dsn'], $settings['database']['user'], $settings['database']['password']);
                    $isDatabaseOk = true;

                    $this->log(sprintf('Base de donnees %s cree.', $settings['database']['name']));
                    $this->log('');
                } catch (PDOException $x) {
                    $isDatabaseOk = false;
                    $this->logBlock('Could not automatically create the database. PDO says : ' . $x->getMessage(), 'ERROR_LARGE');
                    $this->log('');
                }
            } else {
                $isDatabaseOk = false;
                $this->logBlock('The database configuration looks wrong. PDO says : ' . $e->getMessage(), 'ERROR_LARGE');
                $this->log('');
            }
        }
    }
} while (!$isDatabaseOk);

/*
 * APPLY
 */

usleep(1000000);

//$sendReports = $this->askConfirmation(array('Send anonymous reports about plugins used to improve http://diem-project.org/plugins (Y/n)'), 'QUESTION_LARGE', true);
$sendReports = 'n';

if ($isDatabaseExist) {
    if (!$this->askConfirmation(array(
                'La base de donnees ' . $settings['database']['name'] . ' existe deja, elle correspond a un autre site. L\'effacer? (y/n) (par defaut y)',
                    ), 'QUESTION_LARGE', true)
    ) {
        //$this->logSection('Site V3', 'Installation annulee');
        $this->logBlock('Installation annulee', 'ERROR_LARGE');
        exit;
    }
}

$this->filesystem->mirror(
        dmOs::join(sfConfig::get('dm_core_dir'), 'data/skeleton'), sfConfig::get('sf_root_dir'), sfFinder::type('any')->discard('.sf'), array('override' => true)
);

$this->replaceTokens(sfConfig::get('sf_config_dir'), array(
    'SYMFONY_CORE_AUTOLOAD' => $symfonyCoreAutoload,
    'DIEM_CORE_STARTER' => var_export(dmOs::join(sfConfig::get('dm_core_dir'), 'lib/core/dm.php'), true),
    'DIEM_WEB_DIR' => "sfConfig::get('sf_root_dir').'/" . $settings['web_dir_name'] . "'",
    'DIEM_CULTURE' => var_export($settings['culture'], true),
    'SEND_REPORTS' => var_export($sendReports, true)
));

$this->filesystem->remove(array(
    dmProject::rootify('web/css'),
    dmProject::rootify('web/css/main.css'),
    dmProject::rootify('web/images'),
    dmProject::rootify('data/fixtures'),
    dmProject::rootify('data/fixtures/fixtures.yml')
));

if ('web' != $settings['web_dir_name']) {
    $this->filesystem->rename(dmProject::rootify('web'), dmProject::rootify($settings['web_dir_name']));
}

$db = $settings['database'];
$this->runTask('configure:database', array(
    'dsn' => $db['dsn'],
    'username' => $db['user'],
    'password' => $db['password']
));

//-----------------------------------------------------------------------------------------------
//      Generation du fichier de conf
//-----------------------------------------------------------------------------------------------

$confFileContent = '<VirtualHost *:80>
  ServerName    ' . $settings['ndd'] . '
  DocumentRoot  '.sfConfig::get('sf_root_dir').'/'.$settings['web_dir_name'].'
  ErrorLog        '.$settings['apache_dir_log'].'/'.$projectKey.'/error.log
    CustomLog       '.$settings['apache_dir_log'].'/'.$projectKey.'/access.log CompletSimple
  AddType         application/x-httpd-php .php
  DirectoryIndex  index.php

<Directory '.sfConfig::get('sf_root_dir').'/'.$settings['web_dir_name'].'>
Options +FollowSymLinks +ExecCGI

# Add expiration dates to static content
# sudo a2enmod expires && sudo apache2ctl restart
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/gif "access plus 30 days"
  ExpiresByType image/png "access plus 30 days"
  ExpiresByType image/jpg "access plus 30 days"
  ExpiresByType image/jpeg "access plus 30 days"
  ExpiresByType image/png "access plus 30 days"
  ExpiresByType image/x-icon "access plus 30 days"
  ExpiresByType text/css "access plus 30 days"
  ExpiresByType text/javascript "access plus 30 days"
  ExpiresByType application/x-Shockwave-Flash "access plus 30 days"
</IfModule>
 
<IfModule mod_rewrite.c>
  # SEND GZIPPED CONTENT TO COMPATIBLE BROWSERS
  # RemoveType .gz
  # RemoveOutputFilter .css .js
  # AddEncoding x-gzip .gz
  # AddType "text/css;charset=utf-8" .css
  # AddType "text/javascript;charset=utf-8" .js
  # RewriteCond %{HTTP:Accept-Encoding} gzip
  # RewriteCond %{REQUEST_FILENAME}.gz -f
  # RewriteRule ^(.*)$ $1.gz [L,QSA]
  # END GZIPPED CONTENT

  RewriteEngine On
  # uncomment the following line, if you are having trouble
  # getting no_script_name to work
  
  #RewriteBase /
  
  #RewriteRule ^(.+)/$ $1 [R=301,L]

  # we skip all files with .something
  RewriteCond %{REQUEST_URI} \..+$
  #RewriteCond %{REQUEST_URI} !\.html$
  RewriteRule .* - [L]

  # we check if the .html version is here (caching)
  #RewriteRule ^$ index.html [QSA]
  #RewriteRule ^([^.]+)$ $1.html [QSA]
  #RewriteCond %{REQUEST_FILENAME} !-f

  # no, so we redirect to our front web controller
  RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
</Directory>
</VirtualHost>
';

// Conf de Apache (Vhost et rewrite) 
// nous inserons le contenu du .htaccess dans le fichier de conf.
// si le fichier de conf est inclu dans le httpd.conf d'apache alors plus besoin du .htaccess, doc sur le sujet (http://httpd.apache.org/docs/2.2/fr/sections.html)
$fileConf = sfConfig::get('sf_root_dir').'/conf/httpd.conf';
if (file_exists($fileConf)) {
    unlink($fileConf);
}
$confFile = fopen($fileConf, 'a');
fputs($confFile, $confFileContent);
fclose($confFile);
$this->logBlock('APACHE : Le fichier "'.$fileConf.'" a inclure dans la configuration d\'Apache est cree.', 'INFO_LARGE');

//------------------------------------------------------------------------------------
// pour le serveur de dev, ou, si votre apache va scanner le repertoire /data/conf 
// pour y trouver les httpd.conf de chaque site
// -----------------------------------------------------------------------------------
if (is_dir('/data/conf')){ 
 //   cp /data/www/xxxxxxxx/conf/httpd.conf /data/conf/
    //$this->logSection('>>','Copie httpd.conf du site dans /data/conf/httpd-'.$projectKey.'.conf');
    $out = $err = null;
    $this->getFilesystem()->execute('cp '.sfConfig::get('sf_root_dir').'/conf/httpd.conf /data/conf/', $out, $err);
    $this->getFilesystem()->execute('mv /data/conf/httpd.conf /data/conf/httpd-'.$projectKey.'.conf', $out, $err);
}

$diemLibConfigDir = dirname(__FILE__); // dossier config du corePlugin

//-------------------------------------------------------------------------------------
//    Lien symbolique des images de la base editoriale
//-------------------------------------------------------------------------------------
$this->logBlock('Lien symbolique images.', 'INFO_LARGE');
$out = $err = null;
$this->getFilesystem()->execute('ln -s '.$beDirImages. ' '.sfConfig::get('sf_root_dir').'/'.$settings['web_dir_name'].'/_images', $out, $err);
 

//-------------------------------------------------------------------------------------
//    liaison vers le dossier templates contenant les partials du coeur
//-------------------------------------------------------------------------------------
$this->getFilesystem()->execute('ln -s ' . $diemLibConfigDir . '/../../dmFrontPlugin/templates/ '.sfConfig::get('sf_root_dir').'/apps/front/templates', $out, $err);

//-------------------------------------------------------------------------------------
//    dmsetup
//-------------------------------------------------------------------------------------
try {
    if ('/' !== DIRECTORY_SEPARATOR) {
        throw new Exception('Automatic install disabled for windows servers');
    }

    $this->logBlock('Installation de ' . $projectKey . '. Ceci peut être long.', 'INFO_LARGE');

    $out = $err = null;
    $this->getFilesystem()->execute(sprintf(
                    '%s "%s" %s', sfToolkit::getPhpCli(), sfConfig::get('sf_root_dir') . '/symfony', 'dm:setup'// --no-confirmation'
            ), $out, $err);

} catch (Exception $e) {
    $this->logBlock('Un soucis...  Lancez "php symfony dm:setup"', 'ERROR_LARGE');
}

//-------------------------------------------------------------------------------------
//    Redemarrage Apache
//-------------------------------------------------------------------------------------
$commands = array(
     'Restart apache (sudo service apache restart)' => 'sudo service apache restart graceful',
     'Restart apache (sudo service httpd restart)' => 'sudo service httpd restart graceful',
     'Restart apache (sudo apachectl gracefull)' => 'sudo apachectl graceful',
    );

foreach ($commands as $libCommand => $command) {
    $out = $err = null;
    try {
        //$this->logBlock($libCommand, 'INFO_LARGE');  
  $out = $err = null;
  $this->getFilesystem()->execute($command, $out, $err);
    } catch (exception $e) {
  $this->logBlock('Error: '.$libCommand, 'ERROR');
    }
}

//-------------------------------------------------------------------------------------
//    Lancement d'un premier search update d'initialisation afin de créer le dossier 
//    data/dm/index et qu'il soit propriété de l'installer, et non d'apache lorsqu'on 
//    fera le premier appel
//-------------------------------------------------------------------------------------
$this->logBlock('Initialisation arborescence lucene /data/dm/index/', 'INFO');
$out = $err = null;
$this->getFilesystem()->execute(sprintf(
    '%s %s %s', sfToolkit::getPhpCli(), sfConfig::get('sf_root_dir') . '/symfony', 'dm:search-update --init=true'
  ), $out, $err);

//-------------------------------------------------------------------------------------
//    Lecture de la page $settings['ndd'] afin de creer l'entrée base_url dans la table dmSettings
//    Utile pour la task create-pages-cache
//-------------------------------------------------------------------------------------
$siteUrl='http://'.$settings['ndd'];
$site = file_get_contents($siteUrl);
if ($site==''){
  $this->logBlock('Page d\'accueil '.$siteUrl .' introuvable. Vérifiez les paramètres d\'apache et du nom de domaine.', 'ERROR');
} else {
  $this->logBlock('Test de la page d\'accueil '.$siteUrl.' reussi', 'INFO');
}

// Les paramètres
//-------------------------------------------------------------------------------------
$this->logBlock('Les paramètres suivants seront modifiables dans l\'administration du site > Système > Configuration > Paramètres' ,'HELP_LARGE');

//-------------------------------------------------------------------------------------
//    Update the dmSetting site_name
//-------------------------------------------------------------------------------------
// le nom du site
$projectName = $ndd;
$projectName = substr($projectName, strrpos($projectName, '//'));
$projectName = dmString::slugify($projectName);
$projectNameAsk = $this->askAndValidate(array('', 'Le nom du site qui apparaitra dans l\'administration? (par defaut: '.$projectName.')', ''), new sfValidatorString(
                        array('required' => false)
    ));
// par défaut $projectName
$projectName = empty($projectNameAsk) ? $projectName : $projectNameAsk;

// sauvegarde du site_name dans la table dmSetting
$queryMajSiteName = 'UPDATE `dm_setting_translation` t JOIN  `dm_setting` s on s.id = t.id SET value = \''.$projectName.'\' WHERE s.name = \'site_name\';';
$dbh->query($queryMajSiteName);

//-------------------------------------------------------------------------------------
//    Create the dmSetting client_type
//-------------------------------------------------------------------------------------
// on affiche les choix d'environnemnts pour les valeurs par defaut
$dispoTypes = array (
  1 => 'ec',
  2 => 'cgp',
  3 => 'aga',
  4 => 'copilotes'  
);
$this->logBlock('Types de client disponibles:', 'INFO_LARGE');
foreach ($dispoTypes as $k => $dispoType) {
    $this->logSection($k,$dispoType);
}
// choix du dump
$numType = $this->askAndValidate(array('', 'Le numero du type de client choisi?', ''), new sfValidatorChoice(
                        array(
                          'choices' => array_keys($dispoTypes), 
                          'required' => true),
                        array(
                          'invalid' => 'Le type de client n\'existe pas'
                          )
        ));

// sauvegarde du site_name dans la table dmSetting
$queryMajclientType = 'UPDATE `dm_setting_translation` t JOIN  `dm_setting` s on s.id = t.id SET value = \''.$dispoTypes[$numType].'\' WHERE s.name = \'client_type\';';
$dbh->query($queryMajclientType);

//-------------------------------------------------------------------------------------
//    Create the dmSetting site_type
//-------------------------------------------------------------------------------------
// on affiche les choix d'environnemnts pour les valeurs par defaut
$dispoTypes = array (
  1 => 'site',
  2 => 'basedoc'
);
$this->logBlock('Types de site disponibles:', 'INFO_LARGE');
foreach ($dispoTypes as $k => $dispoType) {
    $this->logSection($k,$dispoType);
}
// choix du dump
$numType = $this->askAndValidate(array('', 'Le numero du type de site choisi?', ''), new sfValidatorChoice(
                        array(
                          'choices' => array_keys($dispoTypes), 
                          'required' => true),
                        array(
                          'invalid' => 'Le type de site n\'existe pas'
                          )
        ));

// sauvegarde du site_name dans la table dmSetting
$queryMajSiteType = 'UPDATE `dm_setting_translation` t JOIN  `dm_setting` s on s.id = t.id SET value = \''.$dispoTypes[$numType].'\' WHERE s.name = \'site_type\';';
$dbh->query($queryMajSiteType);

//-------------------------------------------------------------------------------------
//    Create the dmSetting site_email_sender
//-------------------------------------------------------------------------------------
$appConf = sfYaml::load(sfConfig::get('dm_core_dir') . '/config/app.yml');
$siteEmailSender = $appConf ['all']['email']['default-sender'];

$siteEmailSenderAsk = $this->askAndValidate(array('', 'L\'adresse email de l\'expéditeur? (par defaut: '.$siteEmailSender.')', ''), new sfValidatorEmail(
                        array('required' => false)
    ));
$siteEmailSender = empty($siteEmailSenderAsk) ? $siteEmailSender : $siteEmailSenderAsk;

// sauvegarde du site_email_sender dans la table dmSetting
$queryMajSiteEmailsender = 'UPDATE `dm_setting_translation` t JOIN  `dm_setting` s on s.id = t.id SET value = \''.$siteEmailSender.'\' WHERE s.name = \'site_email_sender\';';
$dbh->query($queryMajSiteEmailsender);

//-------------------------------------------------------------------------------------
//    Create the dmSetting site_email
//-------------------------------------------------------------------------------------
$siteEmail = '';
$siteEmail = $this->askAndValidate(array('', 'L\'adresse email du site (le destinataire des contacts)?', ''), new sfValidatorEmail(
                        array('required' => true)
    ));

// sauvegarde du site_email_sender dans la table dmSetting
$queryMajSiteEmail = 'UPDATE `dm_setting_translation` t JOIN  `dm_setting` s on s.id = t.id SET value = \''.$siteEmail.'\' WHERE s.name = \'site_email\';';
$dbh->query($queryMajSiteEmail);

//-------------------------------------------------------------------------------------
//    The END.
//-------------------------------------------------------------------------------------
$this->logBlock('
  Le site '.$projectName.' est pret. Accedez-y via '.$settings['ndd'].'/admin.php. 
  Votre login est "admin" et votre mot de passe est "'. $settings['database']['password'] .'". 
  Lancer maintenant la commande "php symfony controls" afin de :
  - installer un thème
  - charger un dump de contenu
','HELP');
exit;


