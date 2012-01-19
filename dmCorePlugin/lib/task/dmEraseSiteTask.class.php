<?php

class dmEraseSiteTask extends dmContextTask {

    protected function configure() {
        // // add your own arguments here
         $this->addArguments(array(
           
         ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
            new sfCommandOption('lang', null, sfCommandOption::PARAMETER_REQUIRED, 'The language of site', 'fr'),
                // add your own options here
        ));

        $this->namespace = 'dm';
        $this->name = 'erase-site';
        $this->briefDescription = 'Erase a site';
        $this->detailedDescription = <<<EOF
The [erase-site|INFO] Erase a site.
Call it with:

  [php symfony dm:erase-site |INFO]
EOF;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

	// chargement des images de LEA ?
	if ($this->askConfirmation(array('Effacer le site completement ? ATTENTION IRREVERSIBLE (y/n)'), 'QUESTION_LARGE', true)) {
	    if ($this->askConfirmation(array('Vraiment sur ? (y/n)'), 'QUESTION_LARGE', true)) {
		
		$this->logSection('Erase', '...');


		// récupération du nom de domaine du site via la table dmSetting
		$settings = dmDb::query('DmSetting s')
    		->withI18n($options['lang'])
    		->where('s.name = ?', 'base_urls')
    		->limit(1)
    		->fetchRecords();

		foreach ($settings as $setting) {
				$siteEnvsUrl = json_decode($setting->Translation[$options['lang']]->value);		
		}
		
		echo $siteEnvsUrl->{'front-prod'};

		// suppression des fichiers ayant comme propriétaire apache, ou www-user... etc
		// création d'un fichier /htdocs/delete.php contenant la routine de suppression des fichiers créé par apache via appel du navigateur
		


<? $Fnm = "mon_dossier/mon_fichier.ext"; ?>


Ouvrir le fichier en mode écriture
créé si inexistant
<? $inF = fopen($Fnm,"w"); ?>

ou en mode "append"
créé si inexistant
<? $inF = fopen($Fnm,"a"); ?>

ou en mode "mixte"
lecture et écriture
<? $inF = fopen($Fnm,"r+"); ?>

Eventuellement positionner le pointeur

La position du pointeur est dans :
<? $ptr = ftell($inF); ?>

et est modifiable par :
<? $ptr = fseek($inF,$ptr-10); ?>



Ensuite écrire simplement
<? fwrite($inF,$texte);
// ou
fputs($inF,$texte); ?>

pour passer à la ligne, écrire \n
<? fputs($inF,$texte."\n"); ?>


Enfin fermer le fichier
<? fclose($inF); ?>
		
		"<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

function recursive_rmdir($dirname, $followLinks = false) {
    if (is_dir($dirname) && !is_link($dirname)) {
        if (!is_writable($dirname)) echo 'ko'; //throw new Exception('You do not have renaming permissions!');
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirname) , RecursiveIteratorIterator::CHILD_FIRST);
        
        while ($iterator->valid()) {
            if (!$iterator->isDot()) {
                if (!$iterator->isWritable()) {
                    echo 'ko';
                    //throw new Exception(sprintf('Permission Denied: %s.', $iterator->getPathName()));
                }
                if ($iterator->isLink() && false === (boolean)$followLinks) {
                    $iterator->next();
                }
                if ($iterator->isFile()) {
                    unlink($iterator->getPathName());
                } else if ($iterator->isDir()) {
                    rmdir($iterator->getPathName());
                }
            }
            $iterator->next();
        }
        unset($iterator); // Fix for Windows.
        
        return rmdir($dirname);
    } else {
        throw new Exception(sprintf('Directory %s does not exist!', $dirname));
    }
}

echo 'toto';
recursive_rmdir('/data/www/sitediem/');
echo 'totoss';

?>"

		
		// appel de la page delete.php
		$command = "wget '".."'";
		//exec($command, $output);	

		// suppression des fichiers créé par l'utilisateur lors de l'installation du site
		$command = "rm -Rf *";
		//exec($command, $output);		
		
	    } else {
		$this->logSection('Annulation', '...');
	    }
	} else {
	    $this->logSection('Annulation','...');
	}
    }

}