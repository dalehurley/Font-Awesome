<?php
/**
 * class baseEditorialTools
 *
 */

class baseEditorialeTools {
    const memoryNeeded = '2048M';
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
        // les languages
        $arrayLangs = sfConfig::get('dm_i18n_cultures');
        $rubriques = Doctrine_Core::getTable('SidRubrique')->findByIsActive(true);
        //$rubriques = Doctrine_Core::getTable('SidRubrique')->findAll();
        $k = 0;
        $repBaseEditoriale = sfConfig::get('app_rep-local-json');
        // purge de tous les fichiers json
        exec('rm -R ' . $repBaseEditoriale);
        // création du dossier d'export
        exec('mkdir ' . $repBaseEditoriale);
        foreach ($rubriques as $rubrique) {
            $rubriqueDir = $repBaseEditoriale . $rubrique->Translation[$arrayLangs[0]]->title;
            if (!$rubrique->isActive) {
                $k++;
                $return[$k]['(' . $k . ') KO : Rubrique non active'] = $rubrique;
            } else {
                $sidSections = Doctrine_Core::getTable('SidSection')->findByRubriqueIdAndIsActive($rubrique->id,true);
                foreach ($sidSections as $sidSection) {
                    foreach ($arrayLangs as $lang) {
                        // le fichier json de la section en cours
                        $fileRubriqueName = $rubriqueDir . '/' . $sidSection->Translation[$lang]->title . '.json';
                        // on supprime le fichier .json
                        if (is_file($fileRubriqueName)) unlink($fileRubriqueName);
                        if (!$sidSection->isActive) {
                            $k++;
                            $return[$k]['(' . $k . ') KO : Section non active'] = $sidSection;
                        } else {
                            
                            // on récupère les articles de cette section
                            $articles = Doctrine_Core::getTable('SidArticle')->findBySectionIdAndIsActive($sidSection->id,true);

                            $arrayJson = array();
                            $j = 0;
                            
                            foreach ($articles as $article) {

                                //echo '----'.$article->filename;
                                $arrayJson[$j]['filename'] = $article->filename;
                                $arrayJson[$j]['isActive'] = $article->getIsActive();
                                $arrayJson[$j]['isDossier'] = $article->getIsDossier();
                                //                            $arrayJson[$j]['createdAt'] = $article->createdAt;
                                //                            $arrayJson[$j]['updatedAt'] = $article->updatedAt;
                                
                                foreach ($arrayLangs as $lang) {
                                    $arrayJson[$j]['title'][$lang] = $article->getTranslation()->$lang->title;
                                    $arrayJson[$j]['chapeau'][$lang] = $article->getTranslation()->$lang->chapeau;
                                    $arrayJson[$j]['createdAt'][$lang] = $article->getTranslation()->$lang->created_at;
                                    $arrayJson[$j]['updatedAt'][$lang] = $article->getTranslation()->$lang->updated_at;
                                }
                                // récup des tags
                                $listTags = '';
                                
                                foreach ($article->getTags() as $tag) {
                                    $listTags.= $tag . ',';
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
                                    if (count($arrayJson)) { // s'il y'a des articles
                                        $k++;
                                        if (!is_dir($rubriqueDir)) {
                                            mkdir($rubriqueDir);
                                            $return[$k]['DIR+'] = $rubriqueDir;
                                        }
                                        $fileRubrique = fopen($fileRubriqueName, 'a'); // création et ouverture en écriture seule
                                        fputs($fileRubrique, json_encode($arrayJson));
                                        fclose($fileRubrique);
                                        $return[$k]['(' . $k . ') OK : Fichier généré'] = $fileRubriqueName . ' (' . count($arrayJson) . ')';
                                        $nbArticleTotal+= count($arrayJson);
                                    }
                                    
                                } else {
                                    $k++;
                                    $return[$k]['KO : Merci de spécifier la variable app_rep-local'] = '';
                                }
                            }
                            catch(Exception $e) {
                                $k++;
                                $return[$k]['ERROR : Exception reçue pour le fichier ' . $fileRubriqueName] = $e->getMessage();
                            }
                        }
                    }
                }
            }
            $i++;
        }
        $k++;
        $return[$k]['Fichiers générés - Total articles :'] = $nbArticleTotal;
        
        return $return;
    }
    /*
     * sélection des rubriques
    */
    public static function answerRubriqueJson() {
        $tabRubrique = array(); // stockage du nom des rubriques
        $answer = array(); // array de logs
        // les languages
        $arrayLangs = sfConfig::get('dm_i18n_cultures');
        if (sfConfig::get('app_rep-local-json') == '') {
            $answer[0]['ERROR'] = 'Merci de spécifier la variable app_rep-local-json dans le app.yml.';
        } else {
            // POUR INTERROGER le rep local de la base editoriale : rubriques
            $localRubriquesJson = transfertTools::scandirServeur(sfConfig::get('app_rep-local-json'));
            $i = 1;
            
            foreach ($localRubriquesJson as $j => $localRubrique) {
                // VERIFICATION SI LE NOM DE LA RUBRIQUE EXISTE EN BASE
                $answer[$i]['RUBRIQUE'] = $localRubrique;
                $i++;
            }
        }
        
        return $answer;
    }
    /*
     * récupération des rubriques
    */
    public static function loadRubriqueJson($array) {
        //$tabRubrique = array(); // stockage du nom des rubriques
        $return = array(); // array de logs
        // les languages
        $arrayLangs = sfConfig::get('dm_i18n_cultures');
        $i = 1;
        
        foreach ($array as $j => $localRubrique) {
            // VERIFICATION SI LE NOM DE LA RUBRIQUE EXISTE EN BASE
            $bdRubrique = Doctrine_Core::getTable('SidRubrique')->findOneByTitle($localRubrique);
            if ($bdRubrique->isNew()) { // création de la rubrique en base
                $bdRubrique->Translation[$arrayLangs[0]]->title = $localRubrique; // On insère dans la langue par défaut
                $bdRubrique->save();
                $return[$i]['Rubrique+'] = $localRubrique;
            } else {
                $return[$i]['Rubrique existe dejà en base'] = $localRubrique;
            }
            $i++;
        }
        
        return $return;
    }
    /*
     * récupération des sections
    */
    public static function loadSectionJson() {
        ini_set("memory_limit", self::memoryNeeded); // allocation de mémoire nécessaire pour init des articles (beaucoup d'insert)
        $tabRubrique = array(); // stockage du nom des rubriques
        $return = array(); // array de logs
        // les languages
        $arrayLangs = sfConfig::get('dm_i18n_cultures');
        $i = 1;
        // VERIFICATION SI LE NOM DE LA RUBRIQUE EXISTE EN BASE
        $bdRubriques = Doctrine_Core::getTable('SidRubrique')->findByIsActive(true);
        
        foreach ($bdRubriques as $key => $bdRubrique) {
            // POUR INTERROGER le rep local de la base editoriale : sections de la rubrique en cours
            $localSectionsJson = transfertTools::scandirServeur(sfConfig::get('app_rep-local-json') . '/' . $bdRubrique->getTitle());
            
            foreach ($localSectionsJson as $k => $localSection) {
                // Formatage de la section
                if (substr($localSection, -5) == '.json') {
                    $localSection = substr($localSection, 0, -5);
                    // VERIFICATION SI LE NOM DE LA Section EXISTE EN BASE
                    $bdSection = Doctrine_Core::getTable('SidSection')->findOneByTitleAndRubriqueId($localSection, $bdRubrique->id);
                    if ($bdSection->isNew()) { // création de la section en base
                        $bdSection->Translation[$arrayLangs[0]]->title = $localSection; // On insère dans la langue par défaut
                        //$bdSection->Translation[$arrayLangs[0]]->created_at = date('Y-m-d h:m:s');
                        //$bdSection->Translation[$arrayLangs[0]]->updated_at = date('Y-m-d h:m:s');
                        $bdSection->rubrique_id = $bdRubrique->id;
                        $bdSection->save();
                        $return[$i]['SECTION+'] = $bdRubrique->getTitle() . '/' . $localSection;
                    } else {
                        $return[$i]['Section existe dejà en base'] = $bdRubrique->getTitle() . '/' . $localSection;
                    }
                    $i++;
                }
            }
        }
        
        return $return;
    }
    /*
     * Chargement dans la base du site des fichiers json de toutes les rubriques auxquelles le site est abonné
    */
    public static function loadArticlesJson($mode = 'total') {
        ini_set("memory_limit", self::memoryNeeded); // allocation de mémoire nécessaire pour init des articles (beaucoup d'insert)
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
            // repertoire loacl des json
            $repBaseEditoriale = sfConfig::get('app_rep-local-json');

            foreach ($arrayLangs as $lang) {
                // boucle sur les rubriques
                foreach ($rubriques as $rubrique) {
                    //$return[$i]['Traitement de la rubrique'] = $rubrique->Translation[$lang]->title;
                    if (!$rubrique->isActive) {
                        $return[$i]['WARNING'] = 'Rubrique : ' . $rubrique->Translation[$lang]->title . ' non active.';
                    } else {
                        $sidSections = Doctrine_Core::getTable('SidSection')->findByRubriqueIdAndIsActive($rubrique->id, true);
                        if (count($sidSections) == 0) {
                            $return[$i]['WARNING'] = 'La rubrique : ' . $rubrique->Translation[$lang]->title . ' ne contient pas de section.';
                        }
                        $k = 1;
                        foreach ($sidSections as $sidSection) {
                            if (!is_dir($repBaseEditoriale . $rubrique->Translation[$lang]->title)) {
                                $return[$i]['WARNING'] = 'Le repertoire ' . $repBaseEditoriale . $rubrique->Translation[$lang]->title . ' n\'existe pas.';
                                // on supprime alors la rubrique
                                $rubrique->delete();
                                $return[$i]['Rubrique ' . $rubrique . ' supprimé car ' . $repBaseEditoriale . $rubrique->Translation[$lang]->title . ' n\'existe pas.'] = " ";
                            } else {
                                if (!$sidSection->isActive) {
                                    $return[$i]['WARNING'] = 'Section : ' . $rubrique->Translation[$lang]->title . ' > ' . $sidSection->Translation[$lang]->title . ' non active.';
                                } else {
                                    //$return[$i]['-> Traitement section - '.$k] = 'Section : ' . $rubrique->Translation[$lang]->title . ' > ' . $sidSection->Translation[$lang]->title ;
                                    // Récupération du json de la rubrique
                                    $fileRubriqueJsonName = $repBaseEditoriale . $rubrique->Translation[$lang]->title . '/' . $sidSection->Translation[$lang]->title . '.json';
                                    if (!file_exists($fileRubriqueJsonName)) {
                                        $return[$i]['WARNING'] = 'Le fichier ' . $fileRubriqueJsonName . ' est absent.';
                                        // on supprime alors la section si le fichier json est absent
                                        $sidSection->delete();
                                        $return[$i]['Section ' . $sidSection . ' supprimée car ' . $fileRubriqueJsonName . ' absent'] = " ";
                                    } else {
                                        $arrayArticlesBaseEditoriale = json_decode(file_get_contents($fileRubriqueJsonName));
                                        // trier le tableau json pour n'avoir que les articles depuis le dernier updatedAt des articles
                                        $lastUpdatedDate = Doctrine_Core::getTable('SidArticle')->getMaxUpdatedAtBySection($sidSection->id, $lang);
                                        //$lastUpdatedDate = Doctrine_Core::getTable('SidArticle')->getMaxUpdatedAt();
                                        //$return[$i]['-> Nb Articles total - '.$k] = count($arrayArticlesBaseEditoriale);
                                        $arrayArticlesBaseEditorialeSorted = array();
                                        
                                        foreach ($arrayArticlesBaseEditoriale as $article) { // on ne garde que les articles récents
                                            //$return[$i]['-------------------->'.$k] = $article->updatedAt .'>'. $lastUpdatedDate->updatedAt;
                                            if ($article->updatedAt->$lang > $lastUpdatedDate) {
                                                $arrayArticlesBaseEditorialeSorted[] = $article;
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
                                        if ($mode == 'total') {
                                            $arrayArticlesBaseEditorialeChoice = $arrayArticlesBaseEditoriale; // (total)
                                            
                                        } else {
                                            $arrayArticlesBaseEditorialeChoice = $arrayArticlesBaseEditorialeSorted; // (incremental)
                                            
                                        }
                                        
                                        foreach ($arrayArticlesBaseEditorialeChoice as $articleBE) {
                                            $article = Doctrine::getTable('SidArticle')->findOneByFilenameAndSectionId($articleBE->filename, $sidSection->id);
                                            if ($article->isNew()) { // l'article n'existe pas en base
                                                $nbInsert++;
                                                
                                                foreach ($arrayLangs as $lang) {
                                                    if (isset($articleBE->title->$lang)) $article->Translation[$lang]->title = $articleBE->title->$lang;
                                                    if (isset($articleBE->chapeau->$lang)) $article->Translation[$lang]->chapeau = $articleBE->chapeau->$lang;
                                                }
                                                $article->sectionId = $sidSection->id; // la rubrique/section en cours de traitement
                                                $article->filename = $articleBE->filename;
                                                $article->isActive = $articleBE->isActive;
                                                $article->isDossier = $articleBE->isDossier;
                                                
                                                foreach ($arrayLangs as $lang) {
                                                    if (isset($articleBE->createdAt->$lang)) {
                                                        $article->Translation[$lang]->created_at = $articleBE->createdAt->$lang;
                                                    }
                                                    if (isset($articleBE->updatedAt->$lang)) {
                                                        $article->Translation[$lang]->updated_at = $articleBE->updatedAt->$lang;
                                                    }
                                                }
                                                //                                            $article->createdAt = $articleBE->createdAt;
                                                //                                            $article->save();
                                                //                                            // on lance une seconde foit la sauvegarde pour mettre à jour le updatedAt, car lors de l'insert d'un objet on ne peut écraser le updatedAt
                                                //                                            $article->updatedAt = $articleBE->updatedAt;
                                                //                                            $article->save();
                                                // maj des tags de l'article à partir des données Json
                                                $article->removeAllTags();
                                                $article->setTags($articleBE->tags);
                                                $article->save();
                                                $articleName = $articleBE->title->$lang;
                                                //$return[$i]['Insertion article ' . $article->filename . ' - ' . $article->id] =  $articleName;
                                                
                                            } elseif ($article->Translation[$lang]->updated_at < $articleBE->updatedAt->$lang) { // l'article doit etre mis à jour
                                                
                                                foreach ($arrayLangs as $lang) {
                                                    if (isset($articleBE->title->$lang)) $article->Translation[$lang]->title = $articleBE->title->$lang;
                                                    if (isset($articleBE->chapeau->$lang)) $article->Translation[$lang]->chapeau = $articleBE->chapeau->$lang;
                                                }
                                                $article->isActive = $articleBE->isActive;
                                                $article->isDossier = $articleBE->isDossier;
                                                //                                            // on lance une seconde foit la sauvegarde pour mettre à jour le updatedAt, car lors de l'insert d'un objet on ne peut écraser le updatedAt
                                                //                                            $article->updatedAt = $articleBE->updatedAt;
                                                //                                            $article->save();
                                                
                                                foreach ($arrayLangs as $lang) {
                                                    if (isset($articleBE->updatedAt->$lang)) {
                                                        $article->Translation[$lang]->updated_at = $articleBE->updatedAt->$lang;
                                                    }
                                                }
                                                // maj des tags de l'article à partir des données Json
                                                $article->removeAllTags();
                                                $article->setTags($articleBE->tags);
                                                $article->save();
                                                $articleName = $articleBE->title->$lang;
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
                                        $infos = 'Total: ' . count($arrayArticlesBaseEditoriale) . ' => ';
                                        if (count($arrayArticlesBaseEditorialeSorted) != 0) {
                                            $infos.= ' (' . count($arrayArticlesBaseEditorialeSorted) . ' new) ';
                                        } elseif ($mode == 'incremental') {
                                            $infos.= ' Pas de nouveaux articles. ';
                                        }
                                        $return[$i]['Section : ' . $rubrique->Translation[$lang]->title . ' > ' . $sidSection->Translation[$lang]->title] = $infos . $inchange . $maj . $majDesactivation . $insert;
                                        //return $return;
                                        
                                    }
                                }
                            }
                            $k++;

                            // purge des articles X derniers jours et Y articles 
                            if (in_array($sidSection->getTranslation()->$lang->title, sfConfig::get('app_sections_truncated',array()))){
                                
                                $articles = dmDb::query('SidArticle a')
                                    ->withI18n($lang)
                                    ->where("DATEDIFF(CURRENT_DATE, aTranslation.updated_at) < ".sfConfig::get('app_nb-jours-max-date-article',100))   // age max pour un article
                                    ->addWhere('section_id='.$sidSection->id)
                                    ->execute();

                                // on prend les X derniers articles    
                                if (count($articles) < sfConfig::get('app_nb-articles-min-par-section',10)){ 
                                    $articles = dmDb::query('SidArticle a')
                                    ->withI18n($lang)
                                    ->orderBy("aTranslation.updated_at desc")
                                    ->where('section_id='.$sidSection->id)
                                    ->limit(sfConfig::get('app_nb-articles-min-par-section',10))
                                    ->execute();

                                    $listId = '';
                                    foreach ($articles as $article) {
                                        $listId .= $article->id.',';
                                    }
                                    $listId = substr($listId,0,-1);
      
                                    // purge des autres articles que les Y derniers articles
                                    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                                    $query = "  DELETE FROM sid_article  
                                                WHERE 
                                                id not in (".$listId.") 
                                                and section_id= ".$sidSection->id;
                                    $result = $q->execute($query);

                                    $return[]['Purge section '.$rubrique->getTranslation()->$lang->title.'/ec-actualites : '] = 'Purge des articles autres que les '.sfConfig::get('app_nb-articles-min-par-section',10).' derniers articles';                                

                                } else {
                                    // purge des articles > X derniers mois
                                    $q = Doctrine_Manager::getInstance()->getCurrentConnection();
                                    $query = "  DELETE FROM sid_article  
                                                WHERE id in (SELECT id from sid_article_translation at
                                                                WHERE DATEDIFF(CURRENT_DATE, updated_at) > ".sfConfig::get('app_nb-jours-max-date-article',100)."
                                                            ) 
                                                and section_id= ".$sidSection->id;
                                    $result = $q->execute($query);

                                    $return[]['Purge section '.$rubrique->getTranslation()->$lang->title.'/ec-actualites : '] = 'Purge articles > '.sfConfig::get('app_nb-jours-max-date-article',100).' derniers jours';
                                }
                  
                            }  

                            //       

                        }

                        // mise à jour du champ position les sections de la rubrique ec_echeancier
                        $titleSection = 'ec_echeancier';
                        if ($rubrique->getTranslation()->$lang->title == $titleSection){
                            foreach ($sidSections as $sidSection) {   
                                $sectionsToSort[$sidSection->title]= $sidSection; // DB's section's title (i.e:201203) in key, id in value
                                // requete pour trier l'échéancier avec "délai variable" en premier
                                $sidArticles = dmDb::query('SidArticle a')
                                    ->where('section_id='.$sidSection->id)
                                    ->execute();
                                foreach($sidArticles as $sidArticle){
                                        $articlesToSort[$sidArticle->getTitle()] = $sidArticle;
                                }
                                natsort($articlesToSort);
                                foreach($articlesToSort as $articleToSort){
                                    if(strpos($articleToSort, 'variable')){
                                        array_unshift($articlesToSort, $articleToSort);
                                        array_splice($articlesToSort, $articleToSort);
                                    }
                                }
                                
                                $p = 1;
                                foreach ($articlesToSort as $article) {
                                    if ($article->position != $p){                               
                                        $return[]['Position article : '.$article->getTitle()] = 'changement de '.$article->position .' en '. $p; 
                                        $article->position = $p; // affect position in order of title in DB, not in order of position in related object table like default
                                        $article->save();
                                    }
                                    $p++;
                                }
                            }
                        
                            ksort($sectionsToSort); // sort by title
                            $p = 1;
                            foreach ($sectionsToSort as $section) {
                                if ($section->position != $p){                               
                                    $return[]['Position section : '.$titleSection.'/'.$section->title] = 'changement de '.$section->position .' en '. $p; 
                                    $section->position = $p; // affect position in order of title in DB, not in order of position in related object table like default
                                    $section->save();
                                }
                                $p++;
                            }
                        }

                    }
                    $i++;
                }
            }

            // mise à jour de l'index
            $timeBeginSearchUpdate = microtime(true);
            // $return[$i]['Maj indexation'] = exec('php symfony dm:search-update').'-->'.(microtime(true) - $timeBeginSearchUpdate) . ' s';
            $return[$i]['Execution'] = (microtime(true) - $timeBegin) . ' s';
        }
        
        return $return;
    }
    /*
     * Mise à jour des pages automatiques
    */
    public static function syncPages() {
        // mise à jour des pages automatiques sur le site
        $return[]['Sync pages'] = exec('php symfony dm:sync-pages');
        
        return $return;
    }
    /*
     * Replacement des name/slug/title et description des dmPages en fonction du tableau présent dans app.yml
     *
    */
    public static function renameDmPages() {
        // retour
        $return = array();
        // les languages
        $arrayLangs = sfConfig::get('dm_i18n_cultures');
        $nbPagesModified = 0;
        
        foreach ($arrayLangs as $lang) { // pour chaque lang utilisées et définies dans le fichier config/dm/config.yml
            $renames = sfConfig::get('app_dm-pages_rename-' . $lang);
            // gestion AGENDA/ECHEANCIER des dates du style 201112 à transformer en décembre 2011
            // ajouter les entrées du type 201112 => 'Décembre 2012' dans le tableau renames
            $pages = dmDb::query('DmPage p')->withI18n($lang) // la langue par défaul seuelemnt (@todo à rendre multilingue)
            //->where("pTranslation.name like '2%'")
            ->execute();
            
            foreach ($pages as $page) {
                if (preg_match("/[0-9]{6}/", $page->name, $matches)) {
                    $renames[$page->name] = stringTools::dateNumericToString($page->name);
                }
            }
            
            foreach ($renames as $old => $new) {
                $beginTime = microtime(true);
                // on met en minuscule les valeurs
                $old = strtolower($old);
                $new = strtolower($new);
                // AUTO_MOD
                // au début il est égal à snthdk [slug name title h1 description keyword]
                // on met auto_mod = 'hk' pour que la tache sync-pages n'aie plus d'effet sur les sntd les cette page, car modifiée manuellement
                //
                // gestion des name / title / description
                $pagesNotRenamed = dmDb::query('DmPage p')->withI18n($lang)->where("LOWER(pTranslation.name) like '%" . $old . "%' OR LOWER(pTranslation.title) like '%" . $old . "%' OR LOWER(pTranslation.description) like '%" . $old . "%' OR LOWER(pTranslation.slug) like '%" . $old . "%'")->execute();
                //echo "count ".$old."   ".count($pagesNotRenamed)."\n";
                
                foreach ($pagesNotRenamed as $page) {
                    $page->Translation[$lang]->name = str_replace($old, ucfirst($new) , strtolower($page->Translation[$lang]->name));
                    $page->Translation[$lang]->title = str_replace($old, ucfirst($new) , strtolower($page->Translation[$lang]->title));
                    $page->Translation[$lang]->description = str_replace($old, $new, strtolower($page->Translation[$lang]->description));
                    $page->Translation[$lang]->auto_mod = 'hk'; // plus de sntd modifiable par sync-pages
                    // gestion slug
                    $newSlug = str_replace($old, dmString::slugify($new) , $page->Translation[$lang]->slug); // le nouveau slug
                    if (!$page->getTable()->isSlugUniqueByLang($newSlug, $page->get('id') , $lang)) { // on vérifie qu'il est unique
                        $page->Translation[$lang]->slug = $page->getTable()->createUniqueSlugByLang($newSlug, $page->get('id') , null, $lang); // on crée un slug unique
                        
                    } else {
                        $page->Translation[$lang]->slug = $newSlug;
                    }
                    $page->save();
                }
                $return[]['Rename dmPages : ' . $old] = " -> " . $new . " [" . (microtime(true) - $beginTime) . " s]";
            }
        }
        
        return $return;
    }
    
    /*
     * Affectation à un groupe des dmPages en fonction du tableau présent dans app.yml pour le kit création
     *
    */
    public static function affectGroupDmPages() {
        // retour
        $return = array();
        // les languages
        $arrayLangs = sfConfig::get('dm_i18n_cultures');
        $nbPagesModified = 0;
        
        foreach ($arrayLangs as $lang) { // pour chaque lang utilisées et définies dans le fichier config/dm/config.yml
            // je récupère dans le app de dmCorePlugin/config les tableaux des groupes à affectés aux pages
            $arrayGroups = sfConfig::get('app_groups_rubriques');
            // pour chaque groupe, je cherche les pages concernées dans SidRubrique
            foreach($arrayGroups as $group){
                $beginTime = microtime(true);
                // RUBRIQUES
                $rubriqueRecordIds = dmDb::query('SidRubrique p')->withI18n($lang)->select()->where('pTranslation.title IN (\''.implode('\',\'',$group['values']).'\') and p.is_active = true')->execute();
                foreach ($rubriqueRecordIds as $rubriqueRecordId) {
                    // j'update dans DmPageTranslation le champ group_page
                    $groupPages = dmDb::query('DmPage d')->select()->where("d.module = 'rubrique' and d.action = 'show' and d.record_id = " . $rubriqueRecordId->id)->execute();
                    foreach ($groupPages as $groupPage) {
                        $groupPage->Translation[$lang]->group_page = $group['name-group'];
                        $groupPages->save();
                        $nbPagesModified++;
                    }
                    // SECTIONS
                    $sectionRecordIds = dmDb::query('SidSection p')->withI18n($lang)->select()->where('p.rubrique_id = ' . $rubriqueRecordId->id . ' and p.is_active = true')->execute();
                    // j'update les sections de la rubrique
                    foreach ($sectionRecordIds as $sectionRecordId) {
                        // j'update dans DmPageTranslation le champ group_page
                        $groupPages = dmDb::query('DmPage d')->select()->where("d.module = 'section' and d.action = 'show' and d.record_id = " . $sectionRecordId->id)->execute();
                        foreach ($groupPages as $groupPage) {
                            $groupPage->Translation[$lang]->group_page = $group['name-group'];
                            $groupPages->save();
                            $nbPagesModified++;
                        }
                        // ARTICLES
                        $articleRecordIds = dmDb::query('SidArticle p')->withI18n($lang)->select()->where('p.section_id = ' . $sectionRecordId->id . ' and p.is_active = true')->execute();
                        // j'update les sections de la rubrique
                        foreach ($articleRecordIds as $articleRecordId) {
                            // j'update dans DmPageTranslation le champ group_page
                            $groupPages = dmDb::query('DmPage d')->select()->where("d.module = 'article' and d.action = 'show' and d.record_id = " . $articleRecordId->id)->execute();
                            foreach ($groupPages as $groupPage) {
                                $groupPage->Translation[$lang]->group_page = $group['name-group'];
                                $groupPages->save();
                                $nbPagesModified++;
                            }
                        }
                    }



                    $return[]['La rubrique ' . $rubriqueRecordId->getTitle() . ' et tous ses enfants ('.$nbPagesModified.' pages)'] = " ont été ajouté au groupe " . $group['name-group'] . " [" . (microtime(true) - $beginTime) . " s]";
                }
            }
        }
        
        
        return $return;
    }
    
    /*
     * Nettoyage du repertoire local
     * - Suppression des dossiers vides
     * - Suppression des fichiers autres que .xml
     * - Suppression des fichiers .xml dans les dossiers rubriques
    */
    public static function nettoyageRepLocal() {
        // suppression des dossiers vides
        exec('find ' . sfConfig::get('app_rep-local') . '* -type d -empty -delete -print');
        // supprimer les fichiers dans les dossiers rubriques, maxdepth à 1 permet de ne chercher que dans le dossier spécifié et les sous dossiers directs
        exec('find ' . sfConfig::get('app_rep-local') . '* -maxdepth 1 -type f -delete -print');
        // Parcourir les dossiers des sections pour supprimer les fichiers non .xml et les dossiers eventuels
        $dir = sfConfig::get('app_rep-local');
        $rubriques = scandir($dir);
        
        foreach ($rubriques as $j => $rubrique) { // les dossiers des rubriques
            if (($rubrique != '.') && ($rubrique != '..')) {
                if (is_dir($dir . $rubrique)) {
                    $sections = scandir($dir . $rubrique);
                    
                    foreach ($sections as $j => $section) { // les dossiers des sections
                        if (($section != '.') && ($section != '..')) {
                            if (is_file($dir . $rubrique . '/' . $section)) {
                                unlink($dir . $rubrique . '/' . $section);
                            } else {
                                if (is_dir($dir . $rubrique . '/' . $section)) {
                                    // si le dossier est un dossier "images" on déplace les images dans le dossiers "rep-local-images"
                                    if ($section == 'images') {
                                        // plus de copie, on ramene les images dans une autre fonction, directement copié par le wget dans le dossier des images
                                        //echo 'copie dossier : ' . $rubrique . '/' . $section . '/* vers ' . sfConfig::get('app_rep-local-images') . "\n";
                                        //exec('cp ' . $dir . $rubrique . '/' . $section . '/* ' . sfConfig::get('app_rep-local-images')); // on déplace les iamges
                                        echo 'suppression dossier : ' . $rubrique . '/' . $section . "\n";
                                        exec('rm -R "' . $dir . $rubrique . '/' . $section . '"'); // on supprime un eventuel dossier dans un dossier de section
                                        
                                    } else {
                                        $articles = scandir($dir . $rubrique . '/' . $section);
                                        
                                        foreach ($articles as $j => $article) { // les fichiers des sections
                                            if (($article != '.') && ($article != '..')) {
                                                if (is_file($dir . $rubrique . '/' . $section . '/' . $article)) {
                                                    if (substr($article, -4) != '.xml' && substr($article, -4) != '.jpg') {
                                                        unlink($dir . $rubrique . '/' . $section . '/' . $article);
                                                    }
                                                } else {
                                                    if (is_dir($dir . $rubrique . '/' . $section . '/' . $article)) {
                                                        // si le dossier est un dossier "images" on déplace les images dans le dossiers "rep-local-images"
                                                        if ($article == 'images') {
                                                            echo 'copie dossier : ' . $rubrique . '/' . $section . '/' . $article . '/* vers ' . sfConfig::get('app_rep-local-images') . "\n";
                                                            exec('cp ' . $dir . $rubrique . '/' . $section . '/' . $article . '/* ' . sfConfig::get('app_rep-local-images')); // on deplace les iamges
                                                            
                                                        }
                                                        echo 'suppression dossier : ' . $rubrique . '/' . $section . '/' . $article . "\n";
                                                        exec('rm -R "' . $dir . $rubrique . '/' . $section . '/' . $article . '"'); // on supprime un eventuel dossier dans un dossier de section
                                                        
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        // suppression des dossiers vides à nouveau, au cas où des dossiers se seraient vidés.
        exec('find ' . sfConfig::get('app_rep-local') . '* -type d -empty -delete -print');
        // suppression des dossiers sous ec-echeancier antérieur au mois en cours
        $currentMonth = date('Ym'); // mois courant en AAAAMM
        $dirEcEcheancier = sfConfig::get('app_rep-local').'/ec_echeancier';
        if (is_dir($dirEcEcheancier)) {
            $sectionEcheanciers = scandir($dirEcEcheancier);
            foreach ($sectionEcheanciers as $j => $sectionEcheancier) { // les dossiers des sections
                if (($sectionEcheancier != '.') && ($sectionEcheancier != '..')) {
                    if (intval($sectionEcheancier) < intval($currentMonth) && $sectionEcheancier != 'vacances_scolaires'){
                        exec('rm -Rf '.$dirEcEcheancier.'/'.$sectionEcheancier);
                        echo '  Echeancier: Suppression mois echu => '. $sectionEcheancier.'
';
                    }
                }
            }
        }
    }
    /*
     * récupération des rubriques, seulement lorsque l'on veut ajouter des rubriques/sections de LEA ou à l'init
     *
    */
    public static function recupRubriqueSection() {
        $tabRubrique = array(); // stockage du nom des rubriques
        $return = array(); // array de logs
        // les languages
        $arrayLangs = sfConfig::get('dm_i18n_cultures');
        if (sfConfig::get('app_rep-local') == '') {
            $return[0]['ERROR'] = 'Merci de spécifier la variable app_rep-local dans le app.yml.';
        } else {
            //$return[0]['OK'] = '................';
            // POUR INTERROGER SERVEUR FTP : rubriques
            $rubriques = transfertTools::scandirServeur(sfConfig::get('app_rep-local'));
            
            $i = 1;
            
            foreach ($rubriques as $j => $rubrique) {
                // Vérification présence du dossier
                $rubriqueDir = sfConfig::get('app_rep-local') . $rubrique;
                // VERIFICATION SI LE NOM DE LA RUBRIQUE EXISTE EN BASE
                $bdRubrique = Doctrine_Core::getTable('SidRubrique')->findOneByTitleAndIsActive($rubrique, true);
                if ($bdRubrique->isNew()) { // création de la rubrique en base
                    
                    $bdRubrique->Translation[$arrayLangs[0]]->title = $rubrique; // On insère dans la langue par défaut
                    $bdRubrique->save();
                    
                    $return[$i]['Rubrique+'] = $rubrique;
                } else {
                    $return[$i]['Rubrique existe deja en base'] = $rubrique;
                }
                $i++;
                // POUR INTERROGER SERVEUR FTP : section de la rubrique en cours
                $sections = transfertTools::scandirServeur(sfConfig::get('app_rep-local') . $rubrique);
                $nbSections = 0;
                
                foreach ($sections as $k => $section) {
                    $nbSections++;
                    // VERIFICATION SI LE NOM DE LA Section EXISTE EN BASE
                    // Warning : La section peut exister et etre inactive
                    $bdSection = Doctrine_Core::getTable('SidSection')->findOneByTitleAndRubriqueId($section, $bdRubrique->id);
                    if ($bdSection->isNew()) { // création de la section en base
                        $bdSection->Translation[$arrayLangs[0]]->title = $section; // On insère dans la langue par défaut
                        $bdSection->rubrique_id = $bdRubrique->id;
                        $bdSection->save();
                        $return[$i]['SECTION+'] = $rubrique . '/' . $section;
                    } else {
                        $return[$i]['Section existe deja en base'] = $rubrique . '/' . $section;
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
        $nbDirToCut = substr_count(sfConfig::get('app_ftp-rep') , '/');
        //$command = "wget -A.xml -c -N -r -nH -nv --cut-dirs=1 ftp://" . self::convertStringForWget(sfConfig::get('app_ftp-login')) . ":" . self::convertStringForWget(sfConfig::get('app_ftp-password')) . "@" . sfConfig::get('app_ftp-host') . "/" . sfConfig::get('app_ftp-rep') . " -P " . sfConfig::get('app_rep-local');
        //$command = "wget -c -N -r -nH -nv --cut-dirs=1 ftp://" . self::convertStringForWget(sfConfig::get('app_ftp-login')) . ":" . self::convertStringForWget(sfConfig::get('app_ftp-password')) . "@" . sfConfig::get('app_ftp-host') . "/" . sfConfig::get('app_ftp-rep') . " -P " . sfConfig::get('app_rep-local');
        $command = "wget -A.xml -m -nH --cut-dirs=1 ftp://" . self::convertStringForWget(sfConfig::get('app_ftp-login')) . ":" . self::convertStringForWget(sfConfig::get('app_ftp-password')) . "@" . sfConfig::get('app_ftp-host') . "/" . sfConfig::get('app_ftp-rep') . " -P " . sfConfig::get('app_rep-local');
       
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
        $nbDirToCut = substr_count(sfConfig::get('app_ftp-image-rep') , '/');
        $command = "wget -A.jpg -c -N -r -nH -nv -nd --cut-dirs=" . $nbDirToCut . " ftp://" . self::convertStringForWget(sfConfig::get('app_ftp-image-login')) . ":" . self::convertStringForWget(sfConfig::get('app_ftp-image-password')) . "@" . sfConfig::get('app_ftp-image-host') . "/" . sfConfig::get('app_ftp-image-rep') . " -P " . sfConfig::get('app_rep-local-images');
        exec($command, $output);

        // count du nombre de / pour savoir combien de niveaux de répertoire il faut zapper pour que les images soient à la racine
        $nbDirToCut = substr_count(sfConfig::get('app_ftp-rep') , '/');
        $command = "wget -A.jpg -c -N -r -nH -nv -nd --cut-dirs=" . $nbDirToCut . " ftp://" . self::convertStringForWget(sfConfig::get('app_ftp-login')) . ":" . self::convertStringForWget(sfConfig::get('app_ftp-password')) . "@" . sfConfig::get('app_ftp-host') . "/" . sfConfig::get('app_ftp-rep') . " -P " . sfConfig::get('app_rep-local-images');
        exec($command, $output);

    }
    /*
     * récupération des fichiers images de LEA
    */
    public static function recupFilesDessins() {
        // récupération des articles de la rubrique effectuée par la commande :
        // -q : quiet
        // -A.xml : que les fichiers XML
        // -c :  en continu, reprise de téléchargement précédent
        // -r : recursive
        // -nH : plus de dossier par défaut
        // --cut-dirs=1 : on supprime le premier dossier pour l'arbo de copie (ie: flux_sid)
        // -N : estampille Timestamp, verifie date
        // récupération des images des articles :
        if (!is_dir(sfConfig::get('app_rep-local-dessin-semaine'))) {
            mkdir(sfConfig::get('app_rep-local-dessin-semaine'));
        }
        $command = "rm -Rf " . sfConfig::get('app_rep-local-dessin-semaine') . '*';
        exec($command, $output);
        $command = "wget -c -N ftp://" . self::convertStringForWget(sfConfig::get('app_ftp-dessin-login')) . ":" . self::convertStringForWget(sfConfig::get('app_ftp-dessin-password')) . "@" . sfConfig::get('app_ftp-dessin-host') . "/" . sfConfig::get('app_ftp-dessin-rep') . "" . sfConfig::get('app_xml-dessin') . " -P " . sfConfig::get('app_rep-local-dessin-semaine');
        exec($command, $output);
    }
    /*
     * récupération des articles de LEA
     * Rubriques : social, fiscal...
     * Section : Actualités
    */
    public static function recupArticlesLEA() {

        if (sfConfig::get('app_ftp-password') == '' || sfConfig::get('app_ftp-image-password') == '') {
            $return[0]['ERROR'] = 'Seule la base éditoriale peut récupérer les articles de LEA. Vérifier que le apps/front/config/app.yml ait les bonnes variables.';
            
            return $return;
        }
        ini_set("memory_limit", self::memoryNeeded); // allocation de mémoire nécessaire pour init des articles (beaucoup d'insert)
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
                //$section = dmDb::table('SidSection')->findOneByTitleAndIsActiveAndRubriqueIdWithI18n($dossiersSection,true, $bdRubrique->id);
                $section = Doctrine_Query::create()->from('SidSection s')
                        ->withI18n($arrayLangs[0], null, 's')
                        ->where('sTranslation.title = ? and s.is_active = ? and s.rubrique_id = ?', array($dossiersSection,true, $bdRubrique->id))
                        ->fetchOne();



//echo '>>'.$dossiersSection.'   '.$bdRubrique->id;

                if ($section->isNew()) {
                    $return[$j]['Section en base introuvable ou inactive pour le dossier'] = $bdRubrique->Translation[$arrayLangs[0]]->title.'/'.$dossiersSection. ' [rubriqueId:'.$bdRubrique->id.']';
                    $j++;
                } else {
                    // récupération des fichiers du dossier de section
                    $fichierArticles = transfertTools::scandirServeur(sfConfig::get('app_rep-local') . $bdRubrique->Translation[$arrayLangs[0]]->title . '/' . $dossiersSection);
                    
                    foreach ($fichierArticles as $fichierArticle) {
                        $beginTime = microtime(true);
                        if (substr($fichierArticle, -4) == '.xml') { // on en traite que les fichier XML
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
                                // traitement des dossiers :
                                // on recherche le dataType du xml en cours
                                $dataType = xmlTools::getLabelXml($xmlFile, "DataType");
                                $filename = $xml->getElementsByTagName('Code')->item(0)->nodeValue; // l'id LEA est aussi le nom du fichier XML
                                $titre = $xml->getElementsByTagName('Headline')->item(0)->nodeValue; //titre
                                $chapo = $xml->getElementsByTagName('Head')->item(0)->nodeValue; // chapo
                                // cas particulier de l'agenda
                                if ($chapo == '' && $dataType == 'AGENDA') {
                                    $chapo = $xml->getElementsByTagName('Section')->item(0)->nodeValue;
                                    // on supprime le premier saut de ligne
                                    if (substr($chapo, 0, 1) == CHR(10)) {
                                        $chapo = substr($chapo, 1);
                                    }
                                }
                                $date_update = $xml->getElementsByTagName('UpdateDate')->item(0)->nodeValue;
                                // la date de publication
                                // EXCEPTIONS POUR AGENDA
                                //  - on met la date ISO identique au titre dans la date de création pour gérer l'affichage sur le site
                                switch ($dataType) {
                                    case 'AGENDA':
                                        $date_publication =  $xml->getElementsByTagName('Info1')->item(0)->nodeValue;
                                        //$return[]['debug AGENDA : '.$filename] = $date_publication;
                                        break;

                                    default:
                                        $date_publication = $xml->getElementsByTagName('PublicationDate')->item(0)->getElementsByTagName('ISO')->item(0)->nodeValue;
                                        break;
                                }
                                //$return[$j]['>>>>'] = $date_publication;
                                // récupération des <keywords><keyword> du XML dans un tableau
                                // de la forme $tagsString = 'tag1, tag2, tag3';
                                $articleKeywordsNodes = $xml->getElementsByTagName('Keyword'); // la premiere (et seule normalement) balise Keywords
                                $articleKeywordsNodeLength = $articleKeywordsNodes->length; // this value will also change
                                $tagsString = '';
                                
                                for ($i = 0; $i < $articleKeywordsNodeLength; $i++) {
                                    $tagsString.= $articleKeywordsNodes->item($i)->nodeValue . ',';
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
                                    $article->Translation[$arrayLangs[0]]->created_at = $date_publication;
                                    // traitement des dossiers :
                                    // - on met article.is_dossier à true
                                    if ($dataType == 'DOSSIER') {
                                        $article->isDossier = true;
                                    } else {
                                        $article->isDossier = false;
                                    }
                                    $article->save();
                                    // on lance une seconde fois la sauvegarde pour mettre à jour le updatedAt, car lors de l'insert d'un objet on ne peut écraser le updatedAt
                                    $article->Translation[$arrayLangs[0]]->updated_at = $date_update;
                                    $article->save();
                                    //$return[$j]['Article ' . $filename] = 'Insertion dans la base ->' . (microtime(true) - $beginTime) . ' s';
                                    $nbInsert++;
                                } else { // l'article existe
                                    // if ($date_update_bdd->filename == "113632"){
                                    // $return[$j]['>>>'] = $date_update_xml.' - '.$date_update_bdd->updated_at;
                                    // }
                                    // Si le xml est plus récent que celui de la bdd, alors j'update le titre, le chapeau et la rubrique
                                    if ($date_update > $article->Translation[$arrayLangs[0]]->updated_at) {
                                        $filename = $xml->getElementsByTagName('Code')->item(0)->nodeValue;
                                        $titre = $xml->getElementsByTagName('Headline')->item(0)->nodeValue;
                                        $chapo = $xml->getElementsByTagName('Head')->item(0)->nodeValue;
                                        $article->Translation[$arrayLangs[0]]->title = $titre;
                                        $article->Translation[$arrayLangs[0]]->chapeau = $chapo;
                                        $article->setSectionId($section->id);
                                        $article->Translation[$arrayLangs[0]]->updated_at = $date_update;
                                        // traitement des dossiers :
                                        // - on met article.is_dossier à true
                                        if ($dataType == 'DOSSIER') {
                                            $article->isDossier = true;
                                        } else {
                                            $article->isDossier = false;
                                        }
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
                                exec('rm ' . $xmlFile);
                                $return[$j]['ERREUR XML invalide supprimé : '] = $xmlFile;
                                $j++;
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
        // VERIFICATION SI ARTICLE DANS LA BDD SONT PRESENTS en XML, et désactivation si absent
        
        foreach ($bdRubriques as $bdRubrique) {
            $sidSections = Doctrine_Core::getTable('SidSection')->findByRubriqueId($bdRubrique->id);
            
            foreach ($sidSections as $sidSection) {
                $sidArticles = Doctrine_Core::getTable('SidArticle')->findBySectionId($sidSection->id);
                // on scanne les fichiers du serveurs, les absents sont inactifs
                $repArticle = transfertTools::scandirServeur(sfConfig::get('app_rep-local') . $bdRubrique->Translation[$arrayLangs[0]]->title . '/' . $sidSection->Translation[$arrayLangs[0]]->title);
                $k = 1;
                
                foreach ($sidArticles as $sidArticle) {
                    if (!in_array($sidArticle->filename . '.xml', $repArticle)) {
                        if ($sidArticle->isActive) {
                            $sidArticle->delete();
                            $return[$j][$bdRubrique->Translation[$arrayLangs[0]]->title . '/' . $sidSection->Translation[$arrayLangs[0]]->title . ' - ' . $k . ' - Article supprimé'] = $sidArticle->filename;
                            $j++;
                        }
                    } elseif (!$sidArticle->getIsActive()) {
                        $sidArticle->setIsActive(true);
                        $sidArticle->save();
                        $return[$j][$bdRubrique->Translation[$arrayLangs[0]]->title . '/' . $sidSection->Translation[$arrayLangs[0]]->title . ' - ' . $k . ' - Article réactivé'] = $sidArticle->filename;
                        $j++;
                    } else {
                        //$return[$j][$rubrique . ' - ' . $k . ' - Article déjà actif'] = $sidArticle->filename;
                        
                    }
                    $k++;
                }
            }
        }
        // Constat final
        $articles = Doctrine_Core::getTable('SidArticle')->findByIsDossier(false);
        $return[]['Nombre articles'] = $articles->count();
        $dossiers = Doctrine_Core::getTable('SidArticle')->findByIsDossier(true);
        $return[]['Nombre dossiers'] = $dossiers->count();
        // temps d'execution
        $return[]['Tous les articles'] = 'Mise à jour / insertion dans la base ->' . (microtime(true) - $beginTimeRubriques) . ' s';
        
        return $return;
    }
    /**
     * Désactivation des rubriques et sections si elles n'ont pas d'enfants
     */
    public static function RubriquesSectionsDeactivation() {
        ini_set("memory_limit", self::memoryNeeded); // allocation de mémoire nécessaire pour init des articles (beaucoup d'insert)
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
            $articles = Doctrine::getTable('SidArticle')->findBySectionIdAndIsActive($section->id, true);
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
        //        # %23
        //        space %20
        //        @ %40
        $text = str_replace('#', '%23', $text);
        $text = str_replace(' ', '%20', $text);
        $text = str_replace('@', '%40', $text);
        
        return $text;
    }
    /*
     * Rapport dossier et articles fils
     *
     * @return : string
    */
    public static function rapportDossier() {
        // on cherche les dossiers
        $dossiers = Doctrine_Core::getTable('SidArticle')->findByIsDossier(true);
        $return = array();
        
        foreach ($dossiers as $dossier) {
            $xml = sfConfig::get('app_rep-local') . $dossier->getSection()->getRubrique() . '/' . $dossier->getSection() . '/' . $dossier->filename . '.xml';
            $doc_xml = new DOMDocument();
            if ($doc_xml->load($xml)) {
                $sections = $doc_xml->getElementsByTagName("Section");
                $linkedArticles = array();
                
                foreach ($sections as $section) {
                    $AssociatedWiths = $section->getElementsByTagName("AssociatedWith");
                    
                    foreach ($AssociatedWiths as $AssociatedWith) {
                        $linkedArticles[] = (isset($AssociatedWith->getElementsByTagName("Reference")->item(0)->nodeValue)) ? $AssociatedWith->getElementsByTagName("Reference")->item(0)->nodeValue : "";
                    }
                }
                //  affichage brut des articles
                $returnArticle = '';
                
                foreach ($linkedArticles as $linkedArticle) {
                    $returnArticle.= $linkedArticle . '  ';
                }
                $return[]['Dossier ' . $dossier->getSection()->getRubrique() . '/' . $dossier->getSection() . '/' . $dossier->filename . ''] = '>> articles fils: ' . $returnArticle;
            } else {
                $return[]['ERREUR : XML invalide  ' . $xml] = '';
            }
        }
        
        return $return;
    }
    /*
     * Rapport be total
     *
     * @return : string
    */
    public static function rapportTotal() {
        $rub = Doctrine_Core::getTable('SidRubrique')->findByIsActive(true);
        $return[]['Nombre rubriques'] = $rub->count();
        $sect = Doctrine_Core::getTable('SidSection')->findByIsActive(true);
        $return[]['Nombre sections'] = $sect->count();
        $articles = Doctrine_Core::getTable('SidArticle')->findByIsDossier(false);
        $return[]['Nombre articles'] = $articles->count();
        $dossiers = Doctrine_Core::getTable('SidArticle')->findByIsDossier(true);
        $return[]['Nombre dossiers'] = $dossiers->count();
        
        return $return;
    }
}
