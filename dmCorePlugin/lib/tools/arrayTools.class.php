<?php

/**
 * class stringTools 
 * 
 */
class arrayTools {
    /*
     * test si le tableau est multidimensionnel
     */

    public static function array_is2D($array) {
        return is_array($array) ? count($array) === count($array, COUNT_RECURSIVE) : -1;
    }

    /*
     * counts elements of an multidimensional array
     *
     * @param array $array Input Array
     * @param int $limit dimensions that shall be considered (-1 means no limit )
     * @return int counted elements
     */

    public static function multicount($array, $limit = -1) {
        $cnt = 0;
        $limit = $limit > 0 ? (int) $limit : -1;
        $arrs[] = $array;
        for ($i = 0; isset($arrs[$i]) && is_array($arrs[$i]); ++$i) {
            foreach ($arrs[$i] as $value) {
                if (!is_array($value))
                    ++$cnt;
                elseif ($limit == -1 || $limit > 1) {
                    if ($limit > 1)
                        --$limit;
                    $arrs[] = $value;
                }
            }
        }
        return $cnt;
    }

    /**
     * Fonction permettant d'effacer certaines lignes dans un fichier
     *
     * @param string $fileInput     le fichier origine à effacer
     * @param string $fileOutput    le fichier resultat
     * @param array $arrayLigne     le tableau qui contient les lignes (exemple: array(1,2,8,23) )
     *
     * @return bool true, if the component exists, otherwise false
     */
    function effacer($fileInput, $fileOutput, $arrayLigne) {
        $tab = array();
        $return = '';

        //ouverture du fichier 
        $fichier = fopen($fileInput, 'r');
        if ($fichier) {
            while (!feof($fichier)) {
                array_push($tab, fgets($fichier));
            }
            fclose($fichier);
        } else {
            $return .= 'Le fichier en entrée est introuvable. ';
        }
        //toutes les lignes de notre fichier sont stockées dans le tableau tab...
        $fic = fopen($fileOutput, 'w');
        if ($fic) {
            // on recopie toutes les données sans les lignes du tableau arrayligne
            $nb = count($tab);
            for ($i = 0; $i < $nb; $i++) {
                if (!(in_array($i, $arrayLigne))) {
                    fputs($fic, $tab[$i - 1]);
                }
            }
            fclose($fic);
        } else {
            $return .= 'Le fichier en sortie a un soucis. ';
        }

        return $return;
    }

    /**
     * Converts string to array
     * Credits : symfony 1.4
     *
     * Modification pour gérer les attribut html5 avec un -, exemple data-role (arnaudgaudin)
     * 
     * @param  string $string  the value to convert to array
     *
     * @return array
     */
    public static function stringToArray($string) {
        preg_match_all('/
      \s*(\w*\-*\w+)           # key                               \\1
      \s*=\s*               # =
      (\'|")?               # values may be included in \' or " \\2
      (.*?)                 # value                             \\3
      (?(2) \\2)            # matching \' or " if needed        \\4
      \s*(?:
        (?=\w+\s*=) | \s*$  # followed by another key= or the end of the string
      )
    /x', $string, $matches, PREG_SET_ORDER);

        $attributes = array();
        foreach ($matches as $val) {
            $attributes[$val[1]] = sfToolkit::literalize($val[3]);
        }

        return $attributes;
    }

    /**
     * natsort on key and not on value
     * @param  array &$karr array to sort
     * @return array        array in param
     */
    public static function knatsort(&$karr){
        $kkeyarr = array_keys($karr);
        natsort($kkeyarr);
        $ksortedarr = array();
        foreach($kkeyarr as $kcurrkey){
            $ksortedarr[$kcurrkey] = $karr[$kcurrkey];
        }
        $karr = $ksortedarr;
        return true;
    }


}
