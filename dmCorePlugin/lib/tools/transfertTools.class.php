<?php
/**
 * Description of transfertTools
 *
 * @author smeron
 */
class transfertTools {

    /**
     * scanne et met en tableau le contenu d'un rep sans les . et ..
     *
     * @param string $dir       nom du répertoire
     *
     * @return array $tabDir    tableau contenant les noms des fichiers du répertoire $dir
     */
    public static function scandirServeur($dir){

        $arrayDirs = scandir($dir);
        $tabDir = array();
        foreach ($arrayDirs as $j => $arrayDir) {
            if (($arrayDir != '.') && ($arrayDir != '..') && (substr($arrayDir,0,1) != '.')) {
               $tabDir[] = utf8_decode($arrayDir);
            }
        }
        return $tabDir;
    }

    /**
     * scanne et met en tableau le contenu d'un rep du servuer FTP sans les . et ..
     *
     * @param string $login     login FTP
     * @param string $password  password FTP
     * @param string $host      host FTP
     * @param string $rep       répertoire FTP
     *
     * @return array $tabRep    tableau comprenant les noms de fichiers du répertoire FTP exploré
     */
    public static function scandirFtp($login, $password, $host, $rep) {
        $ftpRubrique = 'ftp://'
                . $login . ':'
                . $password . '@'
                . $host . '/'
                . $rep;
        
        $repFtp = dir($ftpRubrique);
        $tabRep = array();
        while (($entry = $repFtp->read()) !== false) {
            if ($entry != '.' && $entry != '..' && (substr($arrayDir,0,1) != '.')) {
            $tabRep[] = utf8_decode($entry);
            }
        };
        $repFtp->close();

        return $tabRep;
    }

    /**
     * copie les fichiers d'un dossier
     *
     * @param string $source    dossier source
     * @param string $target    dossier cible
     *
     * @return void
     */
    public static function copyDir($source, $target, $recursive=false) {
        if (is_dir($source)) {
            @mkdir($target);
            $d = dir($source);

            stream_set_timeout($d, 600);

            while (FALSE !== ( $entry = $d->read() )) {
				try {
					if ($entry == '.' || $entry == '..') {
						continue;
					}
					$Entry = $source . '/' . $entry;
					if (is_dir($Entry) && $recursive) {
						self::copyDir($Entry, $target . '/' . $entry);
						continue;
					} else if (is_dir($Entry)){
						// rien
					} else {
						copy($Entry, $target . '/' . $entry);
					}
				}
				catch (Exception $e) {
						return 'Exception reçue : '. $e->getMessage();
				}
            }

            $d->close();
        } else {
            copy($source, $target);
        }

		return true;
    }

	 /**
     * vérifie si une URL existe
     *
     *
     * @return boolean
     */
    public static function url_exists($url_a_tester) {
        $F = @fopen($url_a_tester, "r");

        return ($F) ? 1 : 0;
    }
}
?>
