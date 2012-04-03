<?php
/**
 * Install Diem
 */

class dmClearCacheTask extends dmContextTask {
    /**
     * @see sfTask
     */
    protected function configure() {
        parent::configure();

        $this->addOptions(array(
            new sfCommandOption('lang', null, sfCommandOption::PARAMETER_REQUIRED, 'The language of site', 'fr') 
        ));

        $this->aliases = array(
            'ccc'
        );
        $this->namespace = 'dm';
        $this->name = 'clear-cache and APC cache';
        $this->briefDescription = 'Remove all cache dir content and APC cache';
        $this->detailedDescription = <<<EOF
Will remove all cache dir content
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {
        if ($this->get('cache_manager')->clearAll()) {
            $this->logSection('diem', 'Cache successfully cleared');
            // clear APC cache via apache call
            self::ccApc($options);
        } else {
            $this->logSection('diem', 'Some files can not be deleted. Please check permissions in /cache dir');
        }
    }

/**
 * clear APC cache via appel d'une page php
 * @return [type]
 */
    protected function ccApc($options) {
        // récupération du nom de domaine du site via la table dmSetting
        $settings = dmDb::query('DmSetting s')->withI18n($options['lang'])->where('s.name = ?', 'base_urls')->limit(1)->fetchRecords();
        
        // si value dans la table dmsetting pour base_urls
        if (count($settings)!=0){
            foreach ($settings as $setting) {
                // une liste json des url (les controleurs) utilisées dans le site, pour chaque app et environnement accédés via un navigateur
                $siteEnvsUrl = json_decode($setting->Translation[$options['lang']]->value);
            }
            if (is_object($siteEnvsUrl)){
                $envsIndex = get_object_vars($siteEnvsUrl);
                // on récupère le premier controleur disponible
                
                foreach ($envsIndex as $key => $value) {
                    $fileDeleteUrl = $value;
                    break;
                }
                // url du fichier delete.php
                $fileDeleteUrl = substr($fileDeleteUrl, 0, strrpos($fileDeleteUrl, '/')) . '/ccApc.php';        
                // création d'un fichier /htdocs/delete.php contenant la routine de suppression des fichiers créé par apache via appel du navigateur
                $fileCcApc = getcwd() . "/htdocs/ccApc.php";
                $f = fopen($fileCcApc, "w");
                $fileDeleteContent = <<<EOF
<?php
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
ProjectConfiguration::getApplicationConfiguration('front', 'prod', false);

if (dmAPCCache::isEnabled()){
  if(dmAPCCache::clearAll()){
    echo json_encode(array('success' => true));
  }  
}
EOF;
                fputs($f, $fileDeleteContent . "\n");
                fclose($f);
                // appel de la page delete.php via wget pour simuler une utilisation d'un navigateur
                $result = json_decode(file_get_contents($fileDeleteUrl),true);
                if (isset($result['success']) && $result['success']){
                  $this->logBlock('-- Clear APC cache from apache user via wget successed', 'INFO');
                } else {
                  $this->logBlock('-- Clear APC cache from apache user via wget failed', 'ERROR');          
                }
                unlink($fileCcApc);
            }
        } else { // no base_urls fields
            $this->logBlock('-- Clear APC cache from apache user via wget failed - no base_urls field in dm_setting_translation table', 'ERROR'); 
        }
    }
}
