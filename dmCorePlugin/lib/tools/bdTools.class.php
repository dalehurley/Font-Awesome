<?php

/**
 * class bdTools
 *
 */
class bdTools {

    /**
     * Fonction envoyant une requete SQL dans un fichier
     *
     * @param connect $connexion  La connexion à la base
     * @param string $query    La requête
     * @param string $destFile     Le fichier de destination
     *
     * @return string Le message de log
     */
    public static function createFileFromQuery($connexion, $arrayQuery) {

        $return = array();
        $nbErreur = 0;

        foreach ($arrayQuery as $qKey => $qValue) {
            $destFile = $qKey;
            $query = $qValue;

            try {
                $resultats = $connexion->query($query)->fetchAll();
                //$return[] = 'OK - Requête exécutée [' . $query . ']';

                // on écrit dans le fichier
                $f = fopen($destFile, "w");
                foreach ($resultats as $tuple) {
                    //$texte = implode('|', $tuple); // On ne peut pas faire ceci le tableau de résultats comprend 6 entrées pour
                    $texte = '';
                    for ($i = 0; $i < (count($tuple) / 2); $i++) { // on prend donc les champs identifié par leur numéro et il y'en a la moitié du count du tableau
                        //if ($i == 1) $return[] = krumo ($tuple);
                        $texte .= str_replace("'", "''", $tuple[$i]) . '|'; // on double les ' pour ne pas avoir de soucis lors de l'import
                    }
                    fputs($f, $texte);
                    fputs($f, "\n");
                }
                fclose($f);

                $return[] = 'OK - ' . $destFile . ' créé avec succès.';
            } catch (Exception $e) {
                $nbErreur++;
                $return[] = 'ERREUR - Une erreur est survenue:' . $e->getMessage();
            }
        }

        $return['ERREURS : '] = $nbErreur;
        return $return;
        // return $resultats;
    }

    /**
     * Fonction créant des tables temporaires à partir de fichiers
     *
     * @param connect $connexion  La connexion à la base
     * @param string $table    La table dans laquelle insérer les données
     * @param array $arrayFields  Le tableau qui contient les tableaux présentant: le fichier, le nom de la table temporaire et les champs. exemple : array('files/bdc/groupements.txt','tmp_groupement','nom|code_groupement|actif')
     *
     * @return string Le message de log
     */
    public static function createTableFromFile($connexion, $arrayInfos) {

        $return = array(); // le return pour tous les ficheirs
        $nbErreur = 0;

        foreach ($arrayInfos as $info) {
            
            $returnFile = array();  // le retour pour un fichier donné dans la boucle foreach

            //Extraction des champs de $arrayInfos
            $file = sfConfig::get('sf_web_dir') . $info['file'];
            $file = str_replace('\\', '/', $file); // remplacer les \ par des / pour windows
            $table = $info['table'];
            $tableTmp = 'tmp_' . $info['table'];
            $fields = explode('|', $info['fields']);

            // creation de la table
            $listFields = '';
            foreach ($fields as $field) {
                $listFields .= $field . ' TEXT NOT NULL,';
            }
            $listFields = substr($listFields, 0, strlen($listFields) - 1); // on enlève la dernière ',' de la chaine
            $queryCreateTable = 'DROP TABLE IF EXISTS ' . $tableTmp . '; CREATE TABLE ' . $tableTmp . ' (
                            ' . $listFields . '
                            );';
            // Intégration du fichier
            $queryLoadTable = 'LOAD DATA INFILE \'' . $file . '\' INTO TABLE ' . $tableTmp . '
            FIELDS TERMINATED BY \'|\'
            LINES TERMINATED BY \'\n\';';

            // on exécute les requêtes
            try {
                $retourCreate = $connexion->query($queryCreateTable);
                $retourCreate->closeCursor();
                $returnFile[] = 'OK - Création de table [' . $queryCreateTable . ']';

                $retourLoad = $connexion->query($queryLoadTable);
                $returnFile[] = 'OK - Load fichier table tmp [' . $queryLoadTable . ']';
            } catch (Exception $e) {
                $nbErreur ++;
                $returnFile[] = 'ERREUR - Une erreur est survenue:' . $e->getMessage();
            }
            $return['Table : '.$table] = $returnFile;
        }

        $return['ERREURS : '] = $nbErreur;
        return $return;
    }

    /**
     * Fonction mettant à jour les tables de production en fonction des tables temporaires tmp.
     * - Update est possible sur les tables temporaires contenant des clefs étrangères
     * - Insert des lignes des tables temporaires non présentes dans la table finale
     * - Delete des lignes de la table temporaire qui viennent d'être insérées
     * - Update des lignes de la table finale en fonction des lignes restantes de la table temporaire
     *
     * @param connect $connexion  La connexion à la base
     * @param array $arrayInfos  Le tableau qui contient les tableaux présentant : la table temporaire, la table de production, les champs et la clef. exemple : array('tmp_groupement','groupement','nom|code_groupement|actif','code_groupement')
     *
     * @return string Le message de log
     */
    public static function synchroTable($connexion, $arrayInfos, $test=true) {
        $return = array();
        $nbErreur = 0;

        foreach ($arrayInfos as $info) {
            //Extraction des champs de $arrayInfos
            $tableTmp = 'tmp_' . $info['table']; // remplacer les \ par des / pour windows
            $tableProd = $info['table'];
            $fields = explode('|', $info['fields']);
            $key = $info['key'];
            $fieldsId = explode('|', $info['fieldsId']);
            $tablesId = explode('|',$info['tablesId']);
            $keysId = explode('|',$info['keysId']);
            // les tableaux de log
            $returnFile = array(); // le return d'un fichier particulier

            //$returnFile[] = $fieldsId;



            // les updates des clefs etrangères si besoin
            if ($fieldsId[0] != '') { // que les lignes de arrayInfos qui on au moins un champ id externe
                try {
                    $nbUkeyId = 0;
                    for ($j = 0; $j < count($fieldsId); $j++) {
                        $listFieldsUpdate = 'u.' . $fieldsId[$j] . ' = o.' . $fieldsId[$j]; // pour avoir de la forme u.nom = o.nom, ...etc...
                        $updatequeryKeyTmp = 'SELECT distinct ' . $fieldsId[$j] . ' from ' . $tableTmp . ';';
                        //$returnFile[] = 'OK - Requêtes selectId - ' . $updatequeryKeyTmp;
                        $arrayUpdatequeryKeyTmp = $connexion->query($updatequeryKeyTmp)->fetchAll();
                        //$returnFile[] = $arrayUpdatequeryKeyTmp;

                        foreach ($arrayUpdatequeryKeyTmp as $tuple) {
                            for ($i = 0; $i < count($tuple) / 2; $i++) { // on prend donc les champs identifié par leur numéro et il y'en a la moitié du count du tableau
                                // requete d'update des table temp possédant des clefs étrangères
                                $updateQuery = 'UPDATE ' . $tableTmp . ' u, ' . $tablesId[$j] . ' o
                                SET ' . $listFieldsUpdate . '
                                WHERE u.' . $fieldsId[$j] . ' = o.' . $keysId[$j] . ' and u.' . $fieldsId[$j] . ' = \'' . $tuple[$i] . '\' ;';
                                if (!$test) {
                                    $retourUpdate = $connexion->query($updateQuery);
                                    $retourUpdate->closeCursor(); 
                                }
                                $nbUkeyId ++;
                                //$returnFile[] = 'OK - Requêtes UpdateId - ' . $updateQuery;
                            }
                        }
                    }
                    $returnFile[] = 'OK - ' . $nbUkeyId . ' lignes mise à jour dans prod (clefs étrangères)';
                } catch (Exception $e) {
                    $nbErreur ++;
                    $returnFile[] = 'ERREUR - Une erreur est survenue:' . $e->getMessage();
                }
            } else {
                $returnFile[] = 'OK - Pas de champs Id externe';
            }
            
            // Les insert / delete / update / drop
            if ($key != '') {
                /**
                 *  création des requêtes
                 */
                // liste des champs sans alias
                $listFieldsSelect = '';
                foreach ($fields as $field) {
                    $listFieldsSelect .= $field . ',';
                }
                $listFieldsSelect = substr($listFieldsSelect, 0, strlen($listFieldsSelect) - 1); // on enlève la dernière ',' de la chaine
                // liste des champs avec alias tmp.
                $listFieldsSelectTmp = '';
                foreach ($fields as $field) {
                    $listFieldsSelectTmp .= 'tmp.'.$field . ',';
                }
                $listFieldsSelectTmp = substr($listFieldsSelectTmp, 0, strlen($listFieldsSelectTmp) - 1); // on enlève la dernière ',' de la chaine
                // liste des champs pour update
                $listFieldsUpdate = '';
                foreach ($fields as $field) {
                    $listFieldsUpdate .= 'prod.' . $field . ' = tmp.' . $field . ','; // pour avoir de la forme prod.nom = tmp.nom, ...etc...
                }
                $listFieldsUpdate = substr($listFieldsUpdate, 0, strlen($listFieldsUpdate) - 1); // on enlève la dernière ',' de la chaine

                

                try {
                    /*
                     *  requete d'insertion dans prod si la clef n'est pas présente, puis suppression de la ligne dans temp
                     */
                    $insertqueryKeyProd = 'SELECT distinct ' . $key . ' from ' . $tableProd . ';';
                    $insertqueryKeyTmp = 'SELECT distinct ' . $key . ' from ' . $tableTmp . ';';

//$returnFile[] = $insertqueryKeyProd;

                    $arrayInsertqueryKeyTmp = $connexion->query($insertqueryKeyTmp)->fetchAll();
                    $arrayInsertqueryKeyProd = $connexion->query($insertqueryKeyProd)->fetchAll();

                    $arrayTmp = array();
                    foreach ($arrayInsertqueryKeyTmp as $tuple) {
                        for ($i = 0; $i < count($tuple) / 2; $i++) { // on prend donc les champs identifié par leur numéro et il y'en a la moitié du count du tableau
                            $arrayTmp[] = $tuple[$i];
                        }
                    }
                    $arrayProd = array();
                    foreach ($arrayInsertqueryKeyProd as $tuple) {
                        for ($i = 0; $i < count($tuple) / 2; $i++) { // on prend donc les champs identifiés par leur numéro et il y'en a la moitié du count du tableau
                            $arrayProd[] = $tuple[$i];
                        }
                    }
                    $arrayInsertqueryKey = array_diff($arrayTmp, $arrayProd); // les keys de temp non présentes en prod (pour insertion)
                    $arrayUpdatequeryKey = array_intersect($arrayTmp, $arrayProd); // les keys de temp présentes en prod (pour faire des updates des valeurs seulement)

                    //$returnFile[] = $arrayInsertqueryKey;
                    // $returnFile[] = $arrayUpdatequeryKey;
                    /*
                     * insertions
                     */
                    $nbID = 0;
                    foreach ($arrayInsertqueryKey as $idKey) {
                        $insertQuery = 'INSERT INTO ' . $tableProd . ' (' . $listFieldsSelect . ')
                                SELECT ' . $listFieldsSelect . ' FROM ' . $tableTmp . '
                                WHERE ' . $key . ' = \'' . $idKey . '\';';
                        if(!$test) $retourInsert = $connexion->query($insertQuery); // on insert la ligne dans prod
                        //$nb = (isset($retourInsert))? $retourInsert->rowCount():'XXX';
                        //$returnFile[] = $insertQuery;

                        $deleteTmpQuery = 'DELETE FROM ' . $tableTmp . '
                                WHERE ' . $key . ' = \'' . $idKey . '\';';
                        if(!$test) $retourDelete = $connexion->query($deleteTmpQuery); // on supprime la ligne dans le temp
                        //$nb = (isset($retourDelete))? $retourDelete->rowCount():'XXX';
                        //$returnFile[] = '['.$nb.' lignes]  '.$deleteTmpQuery;
                        $nbID ++;
                    }
                    $returnFile[] = 'OK - '.$nbID.' lignes insérées dans prod puis supprimées dans tmp';
                    /*
                     * updates
                     */
                    $nbU = 0;
                    // on réarrange $key pour ajouter les alias
                    $pos = strpos($key, 'concat');
                    if ($pos === false) {
                        $prodKey = 'prod.'.$key;
                        $tmpKey = 'tmp.'.$key;
                    } else {
                        $prodKey = str_replace('(', '(prod.', $key);
                        $prodKey = str_replace('\',', '\',prod.', $prodKey);
                        $tmpKey = str_replace('(', '(tmp.', $key);
                        $tmpKey = str_replace('\',', '\',tmp.', $tmpKey);
                    }
                    //$returnFile[] = '>>key>>'.$key;
                    //$returnFile[] = '>>prodKey>>>'.$prodKey;
                    //$returnFile[] = '>>tmpKey>>>'.$tmpKey;
                    
                    //$returnFile[]=$arrayUpdatequeryKey;
                    foreach ($arrayUpdatequeryKey as $idKey) { // les updates
                        // requete d'update de la table de prod en fonction de la table temp, sur les lignes de la tmp qui n'ont pas été insérées
                        $updateQuery = 'UPDATE ' . $tableProd . ' prod, ' . $tableTmp . ' tmp
                            SET ' . $listFieldsUpdate . '
                            WHERE ' . $prodKey . ' = ' . $tmpKey . ' and '. $prodKey .' = \''.$idKey.'\';';
                        //$returnFile[] = '>>>>>>' . $updateQuery;
                        if(!$test) {
                            $retourUpdate = $connexion->query($updateQuery); // on supprime la ligne dans le temp
                            $retourUpdate->closeCursor();
                        }
                        //$nb = (isset($retourUpdate)) ? $retourUpdate->rowCount() : 'XXX';
                        //$returnFile[] = '[' . $nb . ' lignes]  ' . $updateQuery;
                         $nbU ++;
                    }
                    $returnFile[] = 'OK - '.$nbU.' lignes mise à jour dans prod';
                    
                   
                    /*
                     * Drop tmp
                     */
                    $dropTableQuery = 'DROP table ' . $tableTmp . ';';
                    if(!$test)$retourDrop = $connexion->query($dropTableQuery);
                    $returnFile[] = $dropTableQuery;

                } catch (Exception $e) {
                    $nbErreur ++;
                    $returnFile[] = 'ERREUR - Message: Fichier ' . $e->getFile().' ligne '. $e->getLine().' | '. $e->getMessage();
                }

            } else {
                $returnFile[] = 'OK - Pas de champ key';
            }
            $return['Table : '.$tableProd] = $returnFile;
        }

        $return['ERREURS : '] = $nbErreur;
        return $return;
    }

    /**
     * Fonction inserant des champs en base
     *
     * @param connect $connexion  La connexion à la base
     * @param string $table    La table dans laquelle insérer les données
     * @param array $arrayFields  Le tableau qui contient les champs et les valeurs
     *
     * @return string Le message de log
     *
     * @copyright lf
     */
    public static function insertFromArray($connexion, $table, $arrayFields) {
        $sqlFields = '';
        $sqlValues = '';

        foreach ($arrayFields as $key => $value) {
            $sqlFields .= $key . ',';
            $sqlValues .= $value . ',';
        }
        $sqlFields = substr($sqlFields, 0, strlen($sqlFields) - 1); // on enlève la dernière ',' de la chaine
        $sqlValues = substr($sqlValues, 0, strlen($sqlValues) - 1); // on enlève la dernière ',' de la chaine
        // la requete d'insert
        $query = 'insert into ' . $table . ' (' . $sqlFields . ') values (' . $sqlValues . ')';
        // on exécute les requêtes
        try {
            $connexion->query($query);
            $return[] = 'OK - Ligne inséerée';
        } catch (Exception $e) {
            $return[] = 'ERREUR - Une erreur est survenue:' . $e->getMessage();
        }

        return $return;
    }

}