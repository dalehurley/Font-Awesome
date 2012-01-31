<?php

/** @var sfGenerateProjectTask This file runs in the context of sfGenerateProjectTask::execute() */ $this;

sfConfig::set('dm_core_dir', realpath(dirname(__FILE__) . '/..'));

require_once(sfConfig::get('dm_core_dir') . '/lib/core/dm.php');
require_once(sfConfig::get('dm_core_dir') . '/lib/basic/dmString.php');
require_once(sfConfig::get('dm_core_dir') . '/lib/os/dmOs.php');
require_once(sfConfig::get('dm_core_dir') . '/lib/project/dmProject.php');
require_once(sfConfig::get('dm_core_dir') . '/lib/task/dmServerCheckTask.class.php');


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
    $this->log($k . ' - ' . $dispoEnv);
}
// choix du dump
$numEnv = $this->askAndValidate(array('', 'Le numero de l\'environnement choisi?', ''), new sfValidatorChoice(
                        array('choices' => array_keys($dispoEnvs), 'required' => true),
                        array('invalid' => 'L\'environnement n\'existe pas')
        ));
switch ($numEnv) {
  case 1:
    $apacheDirLog = '/data/logs/sitesv3';
    $webDirDefault = 'htdocs';
    $dbHost = 'localhost';
    $dbPrefixe = 'sitev3';
    $dbUser = 'root';
    $dbPwd = 'root';
    break;

  case 2:
    $apacheDirLog = '/data/logs/sitesv3';
    $webDirDefault = 'htdocs';
    $dbHost = 'db.local';
    $dbPrefixe = 'sitev3';
    $dbUser = 'uSitesv3';
    $dbPwd = 'SADY1234';    
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

// le nom du dossier du site
$projectKey = dmProject::getKey();

$ndd = $this->askAndValidate(array('', 'Le nom de domaine? (format: example.com)', ''), new sfValidatorRegex(
                        array('pattern' => '/^([a-z0-9-]+\.)+[a-z]{2,6}$/'),
                        array('required' => true),
                        array('invalid' => 'Le nom de domaine n\'est pas valide')
        ));
$settings['ndd'] = $ndd;

//// le nom du projet
//$projectName = $ndd;
//$projectName = substr($projectName, strrpos($projectName, '//'));
//$projectName = dmString::slugify($projectName);
//
//$projectNameAsk = $this->askAndValidate(array('', 'Le nom du site? (par defaut: '.$projectName.')', ''), new sfValidatorString());
//// par dÃ©faut $projectName
//$projectName = empty($projectNameAsk) ? $projectName : $projectNameAsk;

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

//  $this->logBlock('Votre configuration est valide', 'INFO_LARGE');

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

try {
    if ('/' !== DIRECTORY_SEPARATOR) {
        throw new Exception('Automatic install disabled for windows servers');
    }

    $this->logBlock('Installation de ' . $projectKey . '. Ceci peut etre long.', 'INFO_LARGE');

    $out = $err = null;
    $this->getFilesystem()->execute(sprintf(
                    '%s "%s" %s', sfToolkit::getPhpCli(), sfConfig::get('sf_root_dir') . '/symfony', 'dm:setup --no-confirmation'
            ), $out, $err);

} catch (Exception $e) {
    $this->logBlock('Un soucis...  Lancez "php symfony dm:setup"', 'ERROR_LARGE');
}

/*
 * Mise en cron de la gÃ©nÃ©ration :
 *  - du fichier sitemap.xml
 *  - l'index de recherche
 */
//$crontabFile = fopen('/etc/crontab', 'a');
//if ($crontabFile) {
//    $this->logBlock('Mise en cron @daily de la generation du fichier sitemap.xml.', 'INFO_LARGE');
//    fputs($crontabFile, '@daily /usr/bin/php ' . sfConfig::get('sf_root_dir') . '/symfony dm:sitemap-update ' . $settings['ndd'] . '
//');
//    $this->logBlock('Mise en cron @daily de la generation de l\'index de recherche.', 'INFO_LARGE');
//    fputs($crontabFile, '@daily /usr/bin/php ' . sfConfig::get('sf_root_dir') . '/symfony dm:search-update
//');
//    fclose($crontabFile);
//}

// sitemap.xml
//chmod(sfConfig::get('sf_root_dir').'/'.$settings['web_dir_name'].'/sitemap.xml', 0666);

/*
 * GÃ©nÃ©ration du fichier de conf
 */

//$this->logBlock('APACHE : Creation du fichier '.sfConfig::get('sf_root_dir').'/conf/httpd.conf', 'INFO_LARGE');
//$ip = $this->ask(array('', 'IP du serveur? (par defaut : '.$ipDefault.')', ''));
//$port = $this->ask(array('', 'Port du serveur? (par defaut :'.$portDefault.')', ''));
//
//$ip = empty($ip) ? $ipDefault : $ip;
//$port = empty($port) ? $portDefault : $port;


$confFileContent = '<VirtualHost '.$settings['ndd'].' >
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

// pour le serveur de dev
if (is_dir('/data/conf')){ 
 //   cp /data/www/xxxxxxxx/conf/httpd.conf /data/conf/
    $this->logBlock('Copie httpd.conf du site dans /data/conf/httpd-'.$projectKey.'.conf', 'INFO_LARGE');
    $this->getFilesystem()->execute('cp '.sfConfig::get('sf_root_dir').'/conf/httpd.conf /data/conf/', $out, $err);
    $this->getFilesystem()->execute('mv /data/conf/httpd.conf /data/conf/httpd-'.$projectKey.'.conf', $out, $err);
}


$diemLibConfigDir = dirname(__FILE__); // dossier  diem-5.1.3-SID/dmCorePlugin/config


$this->logBlock('Lien symbolique photos.', 'INFO_LARGE');
$out = $err = null;
// $diemLibConfigDir .'/../../../ => on sort de diem pour atterir dans le dossier _www_lib
//MACOSX : modifs syntaxe
$this->getFilesystem()->execute('ln -s '.$diemLibConfigDir .'/../../../baseEditoriale/_images/ '.sfConfig::get('sf_root_dir').'/'.$settings['web_dir_name'].'/_images', $out, $err);

 
//-------------------------------------------------------------------------------------
//    inclusion theme
//-------------------------------------------------------------------------------------

//if ($this->askConfirmation(array(
//            dirname(__FILE__).'Inclusion du themeFmk - Arreter ? (y/n)'), 'QUESTION_LARGE', true)
//) {
//    exit;
//}

// recuperation des differentes maquettes du coeur
//scan du dossier _templates
$arrayTemplates = scandir($diemLibConfigDir . '/../../themesFmk/_templates');
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
    $this->log($k . ' - ' . $dispoTemplate);
}

// choix de la maquette du coeur
$numTemplate = $this->askAndValidate(array('', 'Le numero du template choisi?', ''), new sfValidatorChoice(
                        array('choices' => array_keys($dispoTemplates), 'required' => true),
                        array('invalid' => 'Le template n\'existe pas')
        ));
$settings['numTemplate'] = $numTemplate;
$nomTemplateChoisi = $dispoTemplates[$settings['numTemplate']];
$this->logBlock('Vous avez choisi le template : ' . $nomTemplateChoisi, 'INFO_LARGE');

// on integre tout le framework
// 

// on est dans le dossier htdocs:
$this->logBlock('Copie du dossier diem/themesFmk/theme ', 'INFO_LARGE'); 

$dirTheme = sfConfig::get('sf_root_dir').'/'.$settings['web_dir_name'].'/theme';
if (!is_dir($dirTheme)) mkdir ($dirTheme);

$this->getFilesystem()->execute('cp -r ' . $diemLibConfigDir . '/../../themesFmk/theme/* '.sfConfig::get('sf_root_dir').'/'.$settings['web_dir_name'].'/theme', $out, $err);
// on remplace dans le dossier    sfConfig::get('sf_root_dir').'/$settings['web_dir_name']/theme  les ##THEME## par le $nomTemplateChoisi
//MACOSX : modifs syntaxe
$this->getFilesystem()->execute('find '.sfConfig::get('sf_root_dir').'/'.$settings['web_dir_name'].'/theme -name "*.less" -print | xargs perl -pi -e \'s/##THEME##/'.$nomTemplateChoisi.'/g\'');
// on cree les liens symboliques
//MACOSX : modifs syntaxe
$this->getFilesystem()->execute('ln -s ' . $diemLibConfigDir . '/../../themesFmk/_framework/ '.sfConfig::get('sf_root_dir').'/'.$settings['web_dir_name'].'/theme/less/_framework', $out, $err);
$this->getFilesystem()->execute('ln -s ' . $diemLibConfigDir . '/../../themesFmk/_templates/ '.sfConfig::get('sf_root_dir').'/'.$settings['web_dir_name'].'/theme/less/_templates', $out, $err);
//liaison vers le dossier templates contenant les partials du coeur
$this->getFilesystem()->execute('ln -s ' . $diemLibConfigDir . '/../../dmFrontPlugin/templates/ '.sfConfig::get('sf_root_dir').'/apps/front/templates', $out, $err);

//// Cas particulier d'Opera 
//if ($nomTemplateChoisi == 'OperaTheme') {
//    //Changer la ligne de widgetNavigationMenu.less : mettre en commentaire l'import du fichier less du menuNavigation
//    $this->getFilesystem()->execute('find '.sfConfig::get('sf_root_dir').'/$settings['web_dir_name']/theme/less -name "widgetNavigationMenu.less" -print | xargs sed -i \'s/@import "_templates\/OperaTheme\/Widgets\/NavigationMenu\/_NavigationMenu.less";/\/\/@import "_templates\/OperaTheme\/Widgets\/NavigationMenu\/_NavigationMenu.less";/g\'');
//
//    //Changer la ligne de style.less : supprime l'import du framework complet et le remplace par l'import du fichier de _style.less
//    $this->getFilesystem()->execute('find '.sfConfig::get('sf_root_dir').'/$settings['web_dir_name']/theme/less -name "style.less" -print | xargs sed -i \'s/@import "_framework\/SPLessCss\/_SPLessCss.less";/@import "_templates\/OperaTheme\/_style.less";/g\'');
//}

// recherche des templates -> XXXSuccess.php
$dirPageSuccessFile = $diemLibConfigDir . '/../../themesFmk/_templates/'.$nomTemplateChoisi.'/Externals/php/layouts';
$this->logBlock('Copie des xxxSuccess.php du theme sur le site ', 'INFO_LARGE');
$this->getFilesystem()->execute('cp ' . $dirPageSuccessFile .'/*Success.php '.sfConfig::get('sf_root_dir').'/apps/front/modules/dmFront/templates', $out, $err);

//-------------------------------------------------------------------------------------
//    chown users 
//-------------------------------------------------------------------------------------
/*
$commands = array(
    'Chown for user wsid' => 'chown -R wsid .', 
    'Chown for user lionel' => 'chown -R lionel .'
    );

foreach ($commands as $libCommand => $command) {
    $out = $err = null;
    try {
  //$this->logBlock($libCommand, 'INFO_LARGE');  // pour le serveur de dev
  $out = $err = null;
  $this->getFilesystem()->execute($command, $out, $err);
    } catch (exception $e) {
  $this->logBlock('Error for :'.$libCommand, 'ERROR_LARGE');
    }
}
*/
//-------------------------------------------------------------------------------------
//    loadDB
//-------------------------------------------------------------------------------------

// recuperation des differents dumps du coeur
//scan du dossier _dumpContent
$dirDumpContentTheme = $diemLibConfigDir . '/../../themesFmk/_templates/'.$nomTemplateChoisi.'/Externals/db';
$arrayDumps = scandir($dirDumpContentTheme);
$i = 0;
$dispoDumps = array();
$libelleEmptyDump = '(empty Dump)';
$extensionDump = 'dump'; // ATTENTION : utilise dans contentTemplateTools.class.php

foreach ($arrayDumps as $dump) {
    // on affiche les themes non precedes par un "_" qui correspondent aux themes de test ou obsoletes
    if ($dump != '.' && $dump != '..' && substr($dump, 0, 1) != '_') {
      if (substr($dump, strlen($dump) - strlen($extensionDump)) == $extensionDump){ // on cherche les fichiers d'extension dump
        $i++;
        $dispoDumps[$i] = str_replace('.'.$extensionDump, '', $dump); // on retire l'extension      
      } 
    }
}
// on ajoute le dump vide
$i++;
$dispoDumps[$i] = $libelleEmptyDump; // dump vide

// on affiche les choix
$this->logBlock('Dump disponibles :', 'INFO_LARGE');
foreach ($dispoDumps as $k => $dispoDump) {
    $this->log($k . ' - ' . $dispoDump);
}

// choix du dump
$numDump = $this->askAndValidate(array('', 'Le numero du dump choisi?', ''), new sfValidatorChoice(
                        array('choices' => array_keys($dispoDumps), 'required' => true),
                        array('invalid' => 'Le dump n\'existe pas')
        ));
$settings['numDump'] = $numDump;
$nomDumpChoisi = $dispoDumps[$settings['numDump']];
$this->logBlock('Vous avez choisi le dump : ' . $nomDumpChoisi, 'INFO_LARGE');

if ($nomDumpChoisi != $libelleEmptyDump) { // on fait un loadDB si on a choisi un dump autre que le dump vide
    $out = $err = null;
    $this->getFilesystem()->execute(sprintf(
        '%s %s %s', sfToolkit::getPhpCli(), sfConfig::get('sf_root_dir') . '/symfony', 'db:loadDB ' . $dirDumpContentTheme . '/' . $nomDumpChoisi . '.' . $extensionDump
      ), $out, $err);
    
}
$this->logBlock(sprintf('%s %s %s', sfToolkit::getPhpCli(), sfConfig::get('sf_root_dir') . '/symfony', 'db:loadDB ' . $dirDumpContentTheme . '/' . $nomDumpChoisi . '.' . $extensionDump
      ), 'INFO_LARGE');
//-------------------------------------------------------------------------------------
//    Les permissions
//-------------------------------------------------------------------------------------
$this->logBlock('Chmod -R 777 sur le dossier "'.sfConfig::get('sf_root_dir') . '/' . $settings['web_dir_name'] . '/theme" pour laisser php ecrire via lessPlugin et sprite_init', 'INFO_LARGE');
$out = $err = null;
$this->getFilesystem()->execute('chmod -R 777 ' . sfConfig::get('sf_root_dir') . '/' . $settings['web_dir_name'] . '/theme', $out, $err);


$this->logBlock('Les permissions (dm:permissions)', 'INFO_LARGE');
$out = $err = null;
$this->getFilesystem()->execute(sprintf(
                '%s %s %s', sfToolkit::getPhpCli(), sfConfig::get('sf_root_dir') . '/symfony', 'dm:permissions'
        ), $out, $err);

$this->logBlock('Chmod 777 sur le dossier "'.sfConfig::get('sf_root_dir') . '/' . $settings['web_dir_name'] . '/uploads" pour laisser php ecrire via dmMedia', 'INFO_LARGE');
$out = $err = null;
$this->getFilesystem()->execute('chmod -R 777 ' . sfConfig::get('sf_root_dir') . '/' . $settings['web_dir_name'] . '/uploads', $out, $err);

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
  $this->logBlock('Error: '.$libCommand, 'ERROR_LARGE');
    }
}

//-------------------------------------------------------------------------------------
//    Lancement d'un premier search update d'initialisation afin de créer le dossier 
//    data/dm/index et qu'il soit propriété de l'installer, et non d'apache lorsqu'on 
//    fera le premier appel
//-------------------------------------------------------------------------------------
$this->logBlock('Generation arborescence lucene /data/dm/index/', 'INFO_LARGE');
$out = $err = null;
$this->getFilesystem()->execute(sprintf(
    '%s %s %s', sfToolkit::getPhpCli(), sfConfig::get('sf_root_dir') . '/symfony', 'dm:search-update --init=true'
  ), $out, $err);

//-------------------------------------------------------------------------------------
//    Creation des fichier .css a partir des .less
//    On execute un : php symfony less:compile --application="front" --debug --clean 
//-------------------------------------------------------------------------------------
$this->logBlock('Generation des fichiers CSS a partir des less.', 'INFO_LARGE');
$out = $err = null;
$this->getFilesystem()->execute(sprintf(
    '%s %s %s', sfToolkit::getPhpCli(), sfConfig::get('sf_root_dir') . '/symfony', 'less:compile --application="front" --debug --clean'
  ), $out, $err);

//-------------------------------------------------------------------------------------
//    Lecture de la page $settings['ndd'] afin de creer l'entrée base_url dans la table dmSettings
//-------------------------------------------------------------------------------------
$siteUrl='http://'.$settings['ndd'];
$site = file_get_contents($siteUrl);
if ($site==''){
  $this->logBlock('Page d\'accueil '.$siteUrl .' introuvable. Vérifiez les paramètres d\'apache et du nom de domaine.', 'ERROR');
} else {
  $this->logBlock('Test de la page d\'accueil '.$siteUrl.' Ok', 'INFO_LARGE');
}

//-------------------------------------------------------------------------------------
//    The END.
//-------------------------------------------------------------------------------------
$this->logBlock('Le site ' . $projectKey . ' est pret. Accedez-y via ' . $settings['ndd'] . '/admin.php.', 'INFO_LARGE');
$this->logBlock('Votre login est "admin" et votre mot de passe est "' . $settings['database']['password'] . '"', 'INFO_LARGE');
$this->logBlock('Merci.', 'INFO_LARGE');
exit;
