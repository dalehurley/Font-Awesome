<?php

/**
 * class baseEditorialTools
 *
 */
class baseEditorialeTools {

    /**
     * création d'un fichier Json des articles actifs de toutes les rubriques
     *
     */
    public static function exportArticlesJson() {
        
        $nbArticleTotal = 0;

        if (sfConfig::get('app_ftp-password') == '' || sfConfig::get('app_ftp-image-password') == '') {
            $return[0]['ERROR'] = 'Seule la base éditoriale peut récupérer les articles de LEA. Vérifier que le apps/front/config/app.yml ait les bonnes variables.';
            return $return;
        }  
        
        $i = 0;

        $rubriques = Doctrine_Core::getTable('SidRubrique')->findByIsActive(true);
        //$rubriques = Doctrine_Core::getTable('SidRubrique')->allRubrique();
        foreach ($rubriques as $rubrique) {

            // les languages
            $arrayLangs = sfConfig::get('dm_i18n_cultures');

            if (!$rubrique->isActive) {
                $return[$i]['KO : Rubrique non active'] = $rubrique;
            } else {
                $sidSections = Doctrine_Core::getTable('SidSection')->findByRubriqueId($rubrique->id);

                foreach ($sidSections as $sidSection) {

                    // on récupère les articles de cette section
                    $articles = Doctrine_Core::getTable('SidArticle')->findBySectionId($sidSection->id);

                    $arrayJson = array();
                    $j = 0;
                    foreach ($articles as $article) {
                        $arrayJson[$j]['filename'] = $article->filename;
                        $arrayJson[$j]['isActive'] = $article->getIsActive();
                        $arrayJson[$j]['createdAt'] = $article->createdAt;
                        $arrayJson[$j]['updatedAt'] = $article->updatedAt;
                        foreach ($arrayLangs as $lang) {
                            $arrayJson[$j]['title'][$lang] = $article->getTranslation()->$lang->title;
                            $arrayJson[$j]['chapeau'][$lang] = $article->getTranslation()->$lang->chapeau;
                        }
                        // récup des tags
                        $listTags = '';
                        foreach ($article->getTags() as $tag){
                            $listTags .= $tag.',';
                        }

                        $arrayJson[$j]['tags'] = $listTags;
                        //$return[$j]['tags >'] = $listTags;
                        
                        $j++;
                    }

                    /*
                     * Ecrire dans un fichier json
                     */
                    try {
                        if (sfConfig::get('app_rep-local-json') != '') {
                            $repBaseEditoriale = sfConfig::get('app_rep-local-json');
                        } else {
                            $return[$j]['KO : Merci de spécifier la variable app_rep-local'] = '';
                        }

                        $rubriqueDir = $repBaseEditoriale . $rubrique->Translation[$arrayLangs[0]]->title;
                        if (!is_dir($rubriqueDir)) {
                            mkdir($rubriqueDir);
                            $return[$j]['DIR+'] = $rubriqueDir;
                        }
                        $fileRubriqueName = $rubriqueDir . '/' . $sidSection->Translation[$arrayLangs[0]]->title . '.json';

                        if (is_file($fileRubriqueName))
                            unlink($fileRubriqueName);

                        $fileRubrique = fopen($fileRubriqueName, 'a'); // création et ouverture en écriture seule

                        fputs($fileRubrique, json_encode($arrayJson));
                        fclose($fileRubrique);

                        $return[$j]['OK : Fichier généré'] = $fileRubriqueName.' ('.count($arrayJson).')';
                        $nbArticleTotal += count($arrayJson);
                    } catch (Exception $e) {

                        $return[$j]['ERROR : Exception reçue pour le fichier ' . $fileRubriqueName] = $e->getMessage();
                    }
                }
            }
        $i++;
        }

        $return[$j]['OK : Fichiers générés - Total articles :'] = $nbArticleTotal;
        return $return;
    }

    /*
     * récupération des rubriques
     */

    public static function loadRubriqueSectionJson() {
        $tabRubrique = array(); // stockage du nom des rubriques
        $return = array(); // array de logs
        // les languages
        $arrayLangs = sfConfig::get('dm_i18n_cultures');

        if (sfConfig::get('app_rep-local-json') == '') {
            $return[0]['ERROR'] = 'Merci de spécifier la variable app_rep-local-json dans le app.yml.';
        } else {

            // POUR INTERROGER le rep local de la base editoriale : rubriques
            $localRubriquesJson = transfertTools::scandirServeur(sfConfig::get('app_rep-local-json'));

            $i = 1;
            foreach ($localRubriquesJson as $j => $localRubrique) {


                // VERIFICATION SI LE NOM DE LA RUBRIQUE EXISTE EN BASE
                $bdRubrique = Doctrine_Core::getTable('SidRubrique')->findOneByTitle($localRubrique);

                if ($bdRubrique->isNew()) { // création de la rubrique en base
                    $bdRubrique->Translation[$arrayLangs[0]]->title = $localRubrique;  // On insère dans la langue par défaut
                    $bdRubrique->save();
                    $return[$i]['Rubrique+'] = $localRubrique;
                } else {
                    $return[$i]['Rubrique existe dejà en base'] = $localRubrique;
                }
                $i++;

                // POUR INTERROGER le rep local de la base editoriale : sections de la rubrique en cours
                $localSectionsJson = transfertTools::scandirServeur(sfConfig::get('app_rep-local-json') . '/' . $localRubrique);

                foreach ($localSectionsJson as $k => $localSection) {

                    // Formatage de la section
                    if (substr($localSection, -5) == '.json') {
                        $localSection = substr($localSection, 0, -5);

                        // VERIFICATION SI LE NOM DE LA Section EXISTE EN BASE
                        $bdSection = Doctrine_Core::getTable('SidSection')->findOneByTitleAndRubriqueId($localSection, $bdRubrique->id); 

                        if ($bdSection->isNew()) { // création de la section en base
                            $bdSection->Translation[$arrayLangs[0]]->title = $localSection;  // On insère dans la langue par défaut
                            $bdSection->rubrique_id = $bdRubrique->id;
                            $bdSection->save();
                            $return[$i]['SECTION+'] = $localRubrique . '/' . $localSection;
                        } else {
                            $return[$i]['Section existe dejà en base'] = $localRubrique . '/' . $localSection;
                        }
                        $i++;
                    }
                }
            }
        }

        return $return;
    }

    /*
     * Chargement dans la base du site des fichiers json de toutes les rubriques auxquelles le site est abonné
     */

    public static function loadArticlesJson($mode = 'total') {
        
        ini_set("memory_limit", '256M'); // allocation de mémoire nécessaire pour init des articles (beaucoup d'insert)

        $return = array();
        $timeBegin = microtime(true);
        $i = 1;

        $return[$i]['MODE'] = $mode; 
        
        // Recherche des rubriques abonnées par le site
        $rubriques = Doctrine::getTable('SidRubrique')->findByIsActive(true);

        if (count($rubriques) == 0) {
            $return[$i]['ERROR'] = 'Aucune rubrique. Veuillez lancer la commande "php symfony loadArticles rubriques verbose"';
        }


        if (sfConfig::get('app_rep-local-json') == '') {
            $return[$i]['ERROR'] = 'Merci de spécifier la variable app_rep-local-json dans le app.yml.';
        } else {

            // les languages
            $arrayLangs = sfConfig::get('dm_i18n_cultures');

            // boucle sur les rubriques
            foreach ($rubriques as $rubrique) {
                //$return[$i]['Traitement de la rubrique'] = $rubrique->Translation[$arrayLangs[0]]->title;

                if (!$rubrique->isActive) {

                    $return[$i]['WARNING'] = 'Rubrique : ' . $rubrique->Translation[$arrayLangs[0]]->title . ' non active.';
                } else {

                    $sidSections = Doctrine_Core::getTable('SidSection')->findByRubriqueId($rubrique->id);
                    if (count($sidSections) == 0) {
                        $return[$i]['WARNING'] = 'La rubrique : ' . $rubrique->Translation[$arrayLangs[0]]->title . ' ne contient pas de section.';
                    }

                    $k = 1;
                    foreach ($sidSections as $sidSection) {


                        if (!$sidSection->isActive) {

                            $return[$i]['WARNING - '.$k] = 'Section : ' . $rubrique->Translation[$arrayLangs[0]]->title . ' > ' . $sidSection->Translation[$arrayLangs[0]]->title . ' non active.';
 
                        } else {
                            //$return[$i]['-> Traitement section - '.$k] = 'Section : ' . $rubrique->Translation[$arrayLangs[0]]->title . ' > ' . $sidSection->Translation[$arrayLangs[0]]->title ;

                            // Récupération du json de la rubrique
                            $repBaseEditoriale = sfConfig::get('app_rep-local-json');
                            $fileRubriqueJsonName = $repBaseEditoriale . $rubrique->Translation[$arrayLangs[0]]->title . '/' . $sidSection->Translation[$arrayLangs[0]]->title . '.json';

                            if (!file_exists($fileRubriqueJsonName)) {
                                $return[$i]['WARNING - '.$k] = 'Le fichier ' . $fileRubriqueJsonName . ' est absent.';
                            } else {
                                $arrayArticlesBaseEditoriale = json_decode(file_get_contents($fileRubriqueJsonName));
                                

                                // trier le tableau json pour n'avoir que les articles depuis le dernier updatedAt des articles
                                $lastUpdatedDate = Doctrine_Core::getTable('SidArticle')->getMaxUpdatedAtBySection($sidSection->id);
                                //$lastUpdatedDate = Doctrine_Core::getTable('SidArticle')->getMaxUpdatedAt();
                                //$return[$i]['-> Nb Articles total - '.$k] = count($arrayArticlesBaseEditoriale);
                                
                                $arrayArticlesBaseEditorialeSorted = array();
                                foreach ($arrayArticlesBaseEditoriale as $article){
                                    //$return[$i]['-------------------->'.$k] = $article->updatedAt .'>'. $lastUpdatedDate->updatedAt;
                                    if ($article->updatedAt > $lastUpdatedDate){
                                        $arrayArticlesBaseEditorialeSorted[]=$article;
                                       // $return[$i]['-------------------->'.$k] = $article->updatedAt .'>'. $lastUpdatedDate .' =>'.$article->filename;
                                        $k++;
                                    } else {
                                       //$return[$i]['-------------------->'.$k] = $article->updatedAt .'>'. $lastUpdatedDate .' =>'.$article->filename;
                                       $k++;
                                    }
                                }
                          
                                //$return[$i]['-> Nb Articles nouveaux - '.$k] = count($arrayArticlesBaseEditorialeSorted);
                               
                                
                                // Insertion des articles
                                $j = 1;
                                $nbInsert = 0;
                                $nbMaj = 0;
                                $nbInchange = 0; 
                                $nbMajDesactivation = 0;
                                
                                // parametre a ajouter
                                if ($mode == 'total'){
                                    $arrayArticlesBaseEditorialeChoice = $arrayArticlesBaseEditoriale; // (total)
                                } else {
                                    $arrayArticlesBaseEditorialeChoice = $arrayArticlesBaseEditorialeSorted; // (incremental)
                                }
                                
                                
                                foreach ($arrayArticlesBaseEditorialeChoice as $articleBE) {

                                    $article = Doctrine::getTable('SidArticle')->findOneByFilenameAndSectionId($articleBE->filename,$sidSection->id);
                                    if ($article->isNew()) { // l'article n'existe pas en base
                                        $nbInsert++;
                                        foreach ($arrayLangs as $lang) {
                                            if (isset($articleBE->title->$lang))
                                                $article->Translation[$lang]->title = $articleBE->title->$lang;
                                            if (isset($articleBE->chapeau->$lang))
                                                $article->Translation[$lang]->chapeau = $articleBE->chapeau->$lang;
                                        }

                                        $article->sectionId = $sidSection->id;  // la rubrique/section en cours de traitement
                                        $article->filename = $articleBE->filename;
                                        $article->isActive = $articleBE->isActive;

                                        $article->createdAt = $articleBE->createdAt;
                                        $article->save();
                                        // on lance une seconde foit la sauvegarde pour mettre à jour le updatedAt, car lors de l'insert d'un objet on ne peut écraser le updatedAt
                                        $article->updatedAt = $articleBE->updatedAt;
                                        $article->save();

                                        // maj des tags de l'article à partir des données Json
                                        $article->removeAllTags();
                                        $article->setTags($articleBE->tags);
                                        $article->save();

                                        $articleName = $articleBE->title->$arrayLangs[0];

                                        //$return[$i]['Insertion article ' . $article->filename . ' - ' . $article->id] =  $articleName;
                                    } elseif ($article->updatedAt < $articleBE->updatedAt) {    // l'article doit etre mis à jour 
                                        foreach ($arrayLangs as $lang) {
                                            if (isset($articleBE->title->$lang))
                                                $article->Translation[$lang]->title = $articleBE->title->$lang;
                                            if (isset($articleBE->chapeau->$lang))
                                                $article->Translation[$lang]->chapeau = $articleBE->chapeau->$lang;
                                        }

                                        $article->isActive = $articleBE->isActive;

                                        // on lance une seconde foit la sauvegarde pour mettre à jour le updatedAt, car lors de l'insert d'un objet on ne peut écraser le updatedAt
                                        $article->updatedAt = $articleBE->updatedAt;
                                        $article->save();
                                        
                                        // maj des tags de l'article à partir des données Json
                                        $article->removeAllTags();
                                        $article->setTags($articleBE->tags);
                                        $article->save();

                                        $articleName = $articleBE->title->$arrayLangs[0];

                                        if ($articleBE->isActive) {
                                            $nbMaj++;
                                            //$return[$i]['MAJ article ' . $article->filename . ' - ' . $article->id] =  $articleName;
                                        } else {
                                            $nbMajDesactivation++;
                                            //$return[$i]['MAJ article (désactivation)' . $article->filename . ' - ' . $article->id] =  $articleName;
                                        }                                

                                    } else {
                                        // article inchangé
                                        $nbInchange++;
                                    }
                                    
                                    $j++;
                                }
                                
                                // infos section
                                if ($nbInchange != 0) {
                                    $inchange = $nbInchange . ' inchangés | ';
                                } else {
                                    $inchange = '';
                                }
                                if ($nbMaj != 0) {
                                    $maj = $nbMaj . ' maj | ';
                                } else {
                                    $maj = '';
                                }
                                if ($nbInsert != 0) {
                                    $insert = $nbInsert . ' inserés | ';
                                } else {
                                    $insert = '';
                                }
                                if ($nbMajDesactivation != 0) {
                                    $majDesactivation = $nbMajDesactivation . ' maj désactivation ';
                                } else {
                                    $majDesactivation = '';
                                }
                                $infos = 'Total: ' . count($arrayArticlesBaseEditoriale).' => ';
                                if (count($arrayArticlesBaseEditorialeSorted)!=0){
                                    $infos .= ' (' . count($arrayArticlesBaseEditorialeSorted) . ' new) ';
                                } elseif ($mode == 'incremental') {
                                    $infos .= ' Pas de nouveaux articles. ';
                                }
                                        
                                
                                $return[$i]['Section : ' . $rubrique->Translation[$arrayLangs[0]]->title . ' > ' . $sidSection->Translation[$arrayLangs[0]]->title] =
                                $infos.$inchange.$maj.$majDesactivation.$insert;
                                //return $return;
                            }
                        }
                        $k++;
                    }
                }
                $i++;
            }

            // mise à jour des pages automatiques sur le site
            $timeBeginSyncPages = microtime(true);
            $return[$i]['Sync pages'] = exec('php symfony dm:sync-pages').'-->'.(microtime(true) - $timeBeginSyncPages) . ' s';

            // mise à jour de l'index
            $timeBeginSearchUpdate = microtime(true);
           // $return[$i]['Maj indexation'] = exec('php symfony dm:search-update').'-->'.(microtime(true) - $timeBeginSearchUpdate) . ' s';

            $return[$i]['Execution'] = (microtime(true) - $timeBegin) . ' s';
        }
        return $return;
    }

    /*
     * récupération des rubriques
     */

    public static function recupRubriqueSection() {
        $tabRubrique = array(); // stockage du nom des rubriques
        $return = array(); // array de logs
        // les languages
        $arrayLangs = sfConfig::get('dm_i18n_cultures');
        
        if (sfConfig::get('app_ftp-password') == '' || sfConfig::get('app_ftp-image-password') == '') {
            $return[0]['ERROR'] = 'Seule la base éditoriale peut récupérer les articles de LEA. Vérifier que le apps/front/config/app.yml ait les bonnes variables.';
            return $return;
        }         
        

        if (sfConfig::get('app_rep-local') == '') {
            $return[0]['ERROR'] = 'Merci de spécifier la variable app_rep-local dans le app.yml.';
        } else {
            
            //$return[0]['OK'] = '................';

            // POUR INTERROGER SERVEUR FTP : rubriques
            $FTPrubriques = transfertTools::scandirFtp(
                            sfConfig::get('app_ftp-login'), sfConfig::get('app_ftp-password'), sfConfig::get('app_ftp-host'), sfConfig::get('app_ftp-rep')
            );

            $i = 1;
            foreach ($FTPrubriques as $j => $FTPrubrique) {
                
                // Vérification présence du dossier
                $localRubrique = transfertTools::scandirServeur(sfConfig::get('app_rep-local'));
                if (!in_array($FTPrubrique, $localRubrique)) {
                    $rubriqueDir = sfConfig::get('app_rep-local') . $FTPrubrique;
                    if (!is_dir($rubriqueDir)) {
                        mkdir($rubriqueDir);
                        $return[$i]['DIR+'] = $FTPrubrique;
                    } else {
                        $return[$i]['Répertoire existant rubrique'] = $FTPrubrique;
                    }
                    $i++;
                }

                // VERIFICATION SI LE NOM DE LA RUBRIQUE EXISTE EN BASE
                $bdRubrique = Doctrine_Core::getTable('SidRubrique')->findOneByTitleAndIsActive($FTPrubrique);

                if ($bdRubrique->isNew()) { // création de la rubrique en base
                    $bdRubrique->Translation[$arrayLangs[0]]->title = $FTPrubrique;  // On insère dans la langue par défaut
                    $bdRubrique->save();
                    $return[$i]['Rubrique+'] = $FTPrubrique;
                } else {
                    $return[$i]['Rubrique existe dejà en base'] = $FTPrubrique;
                }
                $i++;

                // POUR INTERROGER SERVEUR FTP : section de la rubrique en cours
                $FTPsections = transfertTools::scandirFtp(
                                sfConfig::get('app_ftp-login'), sfConfig::get('app_ftp-password'), sfConfig::get('app_ftp-host'), sfConfig::get('app_ftp-rep') . $FTPrubrique
                );

                foreach ($FTPsections as $k => $FTPsection) {
                    // Vérification présence du dossier
                    $localSection = transfertTools::scandirServeur(sfConfig::get('app_rep-local') . $FTPrubrique);
                    if (!in_array($FTPsection, $localRubrique)) {
                        $sectionDir = sfConfig::get('app_rep-local') . $FTPrubrique . '/' . $FTPsection;
                        if (!is_dir($sectionDir)) {
                            mkdir($sectionDir);
                            $return[$i]['DIR+'] = $FTPrubrique . '/' . $FTPsection;
                        } else {
                            $return[$i]['Répertoire existant section'] = $FTPrubrique . '/' . $FTPsection;
                        }
                        $i++;
                    }

                    // VERIFICATION SI LE NOM DE LA Section EXISTE EN BASE
                    // Warning : La section peut exister et etre inactive
                    $bdSection = Doctrine_Core::getTable('SidSection')->findOneByTitleAndRubriqueId($FTPsection, $bdRubrique->id);

                    if ($bdSection->isNew()) { // création de la section en base
                        $bdSection->Translation[$arrayLangs[0]]->title = $FTPsection;  // On insère dans la langue par défaut
                        $bdSection->rubrique_id = $bdRubrique->id;
                        $bdSection->save();
                        $return[$i]['SECTION+'] = $FTPrubrique . '/' . $FTPsection;
                    } else {
                        $return[$i]['Section existe dejà en base'] = $FTPrubrique . '/' . $FTPsection;
                    }
                    $i++;
                }
            }
        }

        return $return;
    }
    
    
    /*
     * récupération des fichiers XMl de LEA
     */
    public static function recupFilesXmlLEA() {    
            // récupération des articles de la rubrique effectuée par la commande :
            // -q : quiet
            // -A.xml : que les fichiers XML
            // -c :  en continu, reprise de téléchargement précédent
            // -r : recursive
            // -nH : plus de dossier par défaut
            // --cut-dirs=1 : on supprime le premier dossier pour l'arbo de copie (ie: flux_sid)
            // -N : estampille Timestamp, verifie date
            // count du nombre de / pour savoir combien de niveaux de répertoire il faut zapper pour que les images soient à la racine 
            $nbDirToCut = substr_count(sfConfig::get('app_ftp-rep'), '/');
            $command = "wget -A.xml -c -N -r -nH -nv --cut-dirs=1 ftp://" . self::convertStringForWget(sfConfig::get('app_ftp-login')) . ":" . self::convertStringForWget(sfConfig::get('app_ftp-password')) . "@" . sfConfig::get('app_ftp-host') . "/" . sfConfig::get('app_ftp-rep') . " -P " . sfConfig::get('app_rep-local');

	    exec($command, $output);
}    
    
   /*
     * récupération des fichiers images de LEA
     */
    public static function recupFilesImagesLEA() {    
            // récupération des articles de la rubrique effectuée par la commande :
            // -q : quiet
            // -A.xml : que les fichiers XML
            // -c :  en continu, reprise de téléchargement précédent
            // -r : recursive
            // -nH : plus de dossier par défaut
            // --cut-dirs=1 : on supprime le premier dossier pour l'arbo de copie (ie: flux_sid)
            // -N : estampille Timestamp, verifie date
            // count du nombre de / pour savoir combien de niveaux de répertoire il faut zapper pour que les images soient à la racine 

            // récupération des images des articles :
            if (!is_dir(sfConfig::get('app_rep-local-images'))) {
                mkdir(sfConfig::get('app_rep-local-images'));
            }
            // count du nombre de / pour savoir combien de niveaux de répertoire il faut zapper pour que les images soient à la racine 
            $nbDirToCut = substr_count(sfConfig::get('app_ftp-image-rep'), '/');

            $command = "wget -A.jpg -c -N -r -nH -nv --cut-dirs=" . $nbDirToCut . " ftp://" . self::convertStringForWget(sfConfig::get('app_ftp-image-login')) . ":" . self::convertStringForWget(sfConfig::get('app_ftp-image-password')) . "@" . sfConfig::get('app_ftp-image-host') . "/" . sfConfig::get('app_ftp-image-rep') . " -P " . sfConfig::get('app_rep-local-images');

	    exec($command, $output);

}        

    /*
     * récupération des articles de LEA
     * Rubriques : social, fiscal...
     * Section : Actualités
     */

    public static function recupArticlesLEA($idArticlePlusVieux, $wgetActive=false) {
        
        if (sfConfig::get('app_ftp-password') == '' || sfConfig::get('app_ftp-image-password') == '') {
            $return[0]['ERROR'] = 'Seule la base éditoriale peut récupérer les articles de LEA. Vérifier que le apps/front/config/app.yml ait les bonnes variables.';
            return $return;
        }  

        ini_set("memory_limit", '1024M'); // allocation de mémoire nécessaire pour init des articles (beaucoup d'insert)
        error_reporting(0); // quelques Warning peuvent apparaitre dans les XML, ça n'empêche pas de les traiter...

        $resultQuery = array(); // [numéro id rubrique de la bdd] [nom rubrique bdd]
        $fichier = array(); // idem $resultQuery
        $return = array(); // array de logs
        $j = 2;
        $nbInsert = 0;
        $nbMaj = 0;
        // les languages
        $arrayLangs = sfConfig::get('dm_i18n_cultures');

        /**
         * 
         * récupération des différentes rubriques dans la table rubrique de la bdd
         * 
         */
        $bdRubriques = Doctrine_Core::getTable('SidRubrique')->findAll();

        $beginTimeRubriques = microtime(true);
        foreach ($bdRubriques as $bdRubrique) {
            //récupération des dossiers du répertoire de la rubrique
            $dossiersSections = transfertTools::scandirServeur(sfConfig::get('app_rep-local') . $bdRubrique->Translation[$arrayLangs[0]]->title);

            foreach ($dossiersSections as $dossiersSection) {
                $section = Doctrine_Core::getTable('SidSection')->findOneByTitleAndIsActiveAndRubriqueId($dossiersSection, $bdRubrique->id);
                if ($section->isNew()) {
                    $return[$j]['Section en base introuvable ou inactive pour le dossier'] = $dossiersSection;
                } else {
                    // récupération des fichiers du dossier de section
                    $fichierArticles = transfertTools::scandirServeur(sfConfig::get('app_rep-local') . $bdRubrique->Translation[$arrayLangs[0]]->title . '/' . $dossiersSection);
                    foreach ($fichierArticles as $fichierArticle) {

                        $beginTime = microtime(true);

                        if (substr($fichierArticle, -4) == '.xml') {  // on en traite que les fichier XML

                            if (intval(str_replace('.xml', '', $fichierArticle))  > $idArticlePlusVieux) {  // on ne traite que les articles depuis l'idArticlePlusVieux
                                
                                // j'explore le xml pour récupérer le titre, le chapeau, le n° article de léa(code)
                                $xml = new DOMDocument();
                                $xmlFile = sfConfig::get('app_rep-local') . $bdRubrique->Translation[$arrayLangs[0]]->title . '/' . $dossiersSection . '/' . $fichierArticle;
 
                                // validation XML    
                                /*
                                  if (baseEditorialeTools::validateXmlWithDtd($xmlFile,sfConfig::get('app_dtd-article'))) {
                                  $return[$j]['Article -> xml validation' . $filename] = 'ok';
                                  } else {
                                  $return[$j]['Article -> xml validation' . $filename] = 'ko';
                                  }
                                 */


                                if ($xml->load($xmlFile)) {

                                    $filename = $xml->getElementsByTagName('Code')->item(0)->nodeValue; // l'id LEA est aussi le nom du fichier XML
                                    $titre = $xml->getElementsByTagName('Headline')->item(0)->nodeValue;  //titre
                                    $chapo = $xml->getElementsByTagName('Head')->item(0)->nodeValue; // chapo
                                    // cas particulier de l'agenda
                                    if ($chapo == ''){
                                       $chapo = $xml->getElementsByTagName('Section')->item(0)->nodeValue;
                                       // on supprime le premier saut de ligne
                                       if (substr($chapo, 0, 1) == CHR(10)){
                                           $chapo = substr($chapo, 1);
                                       } 
                                    }
                                    $date_update = $xml->getElementsByTagName('UpdateDate')->item(0)->nodeValue;
                                    // la date de publication
                                    $date_publication = $xml->getElementsByTagName('PublicationDate')->item(0)->getElementsByTagName('ISO')->item(0)->nodeValue;
                                    //$return[$j]['>>>>'] = $date_publication;                                    
                                    
                                    
                                    // récupération des <keywords><keyword> du XML dans un tableau
                                    // de la forme $tagsString = 'tag1, tag2, tag3';
                                    $articleKeywordsNodes = $xml->getElementsByTagName('Keyword'); // la premiere (et seule normalement) balise Keywords
                                    $articleKeywordsNodeLength = $articleKeywordsNodes->length; // this value will also change
                                    $tagsString = '';
                                    for ($i = 0; $i < $articleKeywordsNodeLength; $i++) {
                                        $tagsString .= $articleKeywordsNodes->item($i)->nodeValue . ',';
                                    }
                                    //$return[$j]['Article ' . $fichierArticle] = 'Tags article: '.$tagsString;
                                    

                                    // je vérifie si l'article est présent dans la base, pour la section en cours (il peut y avoir des doublons sur le filename, un article étant potentiellement présent dans plusieurs rubrique)
                                    $article = Doctrine_Core::getTable('SidArticle')->findOneByFilenameAndSectionId($filename, $section->id);

                                    if ($article->isNew()) { // l'article n'existe pas, on le crée
                                        // j'envoie les données id_lea, rubrique et titre dans bdd
                                        $article->Translation[$arrayLangs[0]]->title = $titre;
                                        $article->Translation[$arrayLangs[0]]->chapeau = $chapo;
                                        
                                        $article->setSectionId($section->id);
                                        $article->setFilename($filename);
                                        $article->createdAt = $date_publication;
                                        $article->save();
                                        // on lance une seconde foit la sauvegarde pour mettre à jour le updatedAt, car lors de l'insert d'un objet on ne peut écraser le updatedAt
                                        $article->updatedAt = $date_update;
                                        $article->save();

                                        //$return[$j]['Article ' . $filename] = 'Insertion dans la base ->' . (microtime(true) - $beginTime) . ' s';
                                        $nbInsert++;
                                    } else {  // l'article existe
                                        // if ($date_update_bdd->filename == "113632"){
                                        // $return[$j]['>>>'] = $date_update_xml.' - '.$date_update_bdd->updated_at;
                                        // }
                                        // Si le xml est plus récent que celui de la bdd, alors j'update le titre, le chapeau et la rubrique
                                        if ($date_update > $article->updated_at) {
                                            $filename = $update->getElementsByTagName('Code')->item(0)->nodeValue;
                                            $titre = $update->getElementsByTagName('Headline')->item(0)->nodeValue;
                                            $chapo = $update->getElementsByTagName('Head')->item(0)->nodeValue;

                                            $article->Translation[$arrayLangs[0]]->title = $titre;
                                            $article->Translation[$arrayLangs[0]]->chapeau = $chapo;

                                            $article->setSectionId($section->id);

                                            $article->updatedAt = $date_update;

                                            $article->save();

                                            //$return[$j]['Article ' . $filename] = 'Mise à jour dans la base ->' . (microtime(true) - $beginTime) . ' s';
                                            $nbMaj++;
                                        } else {
                                            //  $return[$j]['Article ' . $filename] = 'Pas de modification ->'.(microtime(true) - $beginTime).' s';
                                        }
                                    }
                                    // enregistrement des tags
                                    $article->removeAllTags();
                                    $article->setTags($tagsString)->save();
                                    
                                } else {
                                    $return[$j]['ERREUR : XML invalide ' . $xmlFile] = $xmlFile . '.xml Invalide';
                                }
                            } else {
                                
                               // $return[$j]['Article ' . $fichierArticle] = 'Article trop vieux < '.$idArticlePlusVieux;
                            }

                            $j++;
                            //if ($j > 10) return $return;
                        }
                    }
                }
            }
        }
        $return[0]['Article Insérés dans la base'] = $nbInsert;
        $return[1]['Article MAJ dans la base'] = $nbMaj;
        $return[$j]['Tous les articles'] = 'Mise à jour / insertion dans la base ->' . (microtime(true) - $beginTimeRubriques) . ' s';

        // VERIFICATION SI ARTICLE DANS LA BDD SONT PRESENTS en XML, et désactivation si absent
        foreach ($bdRubriques as $bdRubrique) {

            $sidSections = Doctrine_Core::getTable('SidSection')->findByRubriqueId($bdRubrique->id);

            foreach ($sidSections as $sidSection) {

                $sidArticles = Doctrine_Core::getTable('SidArticle')->findBySectionId($sidSection->id);

                // on scanne les fichiers du serveurs, les absents sont inactifs
                $repArticle = transfertTools::scandirFtp(sfConfig::get('app_ftp-login'), sfConfig::get('app_ftp-password'), sfConfig::get('app_ftp-host'), sfConfig::get('app_ftp-rep') . $bdRubrique->Translation[$arrayLangs[0]]->title . '/' . $sidSection->Translation[$arrayLangs[0]]->title);
                $k = 1;
                foreach ($sidArticles as $sidArticle) {
                    if (!in_array($sidArticle->filename . '.xml', $repArticle)) {
                        if ($sidArticle->isActive) {
                            $sidArticle->setIsActive(false);
                            $sidArticle->save();
                            $return[$j][$bdRubrique->Translation[$arrayLangs[0]]->title . '/' . $sidSection->Translation[$arrayLangs[0]]->title . ' - ' . $k . ' - Article désactivé'] = $sidArticle->filename;
                        }
                    } elseif (!$sidArticle->getIsActive()) {
                        $sidArticle->setIsActive(true);
                        $sidArticle->save();
                        $return[$j][$bdRubrique->Translation[$arrayLangs[0]]->title . '/' . $sidSection->Translation[$arrayLangs[0]]->title . ' - ' . $k . ' - Article réactivé'] = $sidArticle->filename;
                    } else {
                        //$return[$j][$rubrique . ' - ' . $k . ' - Article déjà actif'] = $sidArticle->filename;
                    }

                    $k++;
                }
            }
        }

        return $return;
    }

   /**
     * Désactivation des rubriques et sections si elles n'ont pas d'enfants
     */
    public static function RubriquesSectionsDeactivation() {

        ini_set("memory_limit", '256M'); // allocation de mémoire nécessaire pour init des articles (beaucoup d'insert)

        $return = array();
        $timeBegin = microtime(true);
        $i = 1;

        // Recherche des sections abonnées par le site
        $sections = Doctrine::getTable('SidSection')->findAll();

        if (count($sections) == 0) {
            $return[$i]['WARNING'] = 'Aucune section.';
        }

        foreach ($sections as $section) {

            // Recherche des sections filles
            $articles = Doctrine::getTable('SidArticle')->findBySectionIdAndIsActive($section->id,true);
            if (count($articles) == 0 || !$section->is_active) {
                $section->delete();
                $return[$i]['Section ' . $section . ' supprimée : nb articles'] = count($articles);
            } 
            $i++;
        }

        // Recherche des rubriques abonnées par le site
        $rubriques = Doctrine::getTable('SidRubrique')->findAll();

        if (count($rubriques) == 0) {
            $return[$i]['WARNING'] = 'Aucune rubrique.';
        }

        foreach ($rubriques as $rubrique) {

            // Recherche des sections filles
            $sections = Doctrine::getTable('SidSection')->findByRubriqueIdAndIsActive($rubrique->id, true);
            if (count($sections) == 0 || !$rubrique->is_active) {
                $rubrique->delete();
                $return[$i]['Rubrique ' . $rubrique . ' supprimée : nb sections'] = count($sections);
            } 
            $i++;
        }

        return $return;
    }

    /*
     * Validation de XML par dtd
     *
     * @return : boolean
     */

    public static function validateXmlWithDtd($xml, $dtd) {
        $dom = new DOMDocument;
        $dom->Load($xml);

        if (!$dom->doctype) { // on ajoute un doctype s'il n'est pas présent
            $creator = new DOMImplementation;
            $doctype = $creator->createDocumentType("Publishing", null, $dtd); // creating real doctype
            $output = $creator->createDocument('', '', $doctype);
            $output->appendChild($output->importNode($dom->documentElement, true));
        }

        if ($output->validate()) {
            return false;
        } else {
            return true;
        }
    }
    
     /*
     * Conversion caracteres pour ligne de commande
     *
     * @return : string
     */

    public static function convertStringForWget($text) {

//        #	%23
//        space	%20
//        @	%40

        $text = str_replace('#', '%23', $text);
        $text = str_replace(' ', '%20', $text);
        $text = str_replace('@', '%40', $text);

        return $text;
    }

}

