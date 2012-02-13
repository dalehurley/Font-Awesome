<?php
/**
 * class stringTools
 */

class stringTools {
    /**
     *
     * @param type $string : La chaîne d'entrée
     * @param type $max_length : Longueur maximale de la chaine retournée, si 0 alors la chaîne est retournée complète
     * @param type $replacement : Texte de remplacement
     * @param type $trunc_at_space : Si ce paramètre vaut TRUE, str_truncate() tentera de ne pas tronquer la chaine au milieu d'un mot
     * @param type $html : Si ce paramètre vaut TRUE, on enléve les balises HTML et on retourne la chaine de texte tronquée (pour des cas particulier)
     * @return type
     */
    public static function str_truncate($string, $max_length = 0, $replacement = '', $trunc_at_space = true, $html = false) {
        if ($html == false) {
            if (strip_tags($string) != $string) { // si la chaîne est formaté en HTML alors on l'a renvoi non tronquée
                
                return $string;
            }
            if ($max_length != 0) {
                $max_length-= strlen(strip_tags($replacement));
                $string_length = strlen($string);
                if ($string_length <= $max_length)
                return $string;
                if ($trunc_at_space && ($space_position = strrpos($string, ' ', $max_length - $string_length))) $max_length = $space_position;
                
                return substr_replace($string, $replacement, $max_length);
            } else {
                
                return $string;
            }
        } else {
            $stringSimple = strip_tags($string);
            if ($max_length != 0) {
                $max_length-= strlen(strip_tags($replacement));
                $string_length = strlen($stringSimple);
                if ($string_length <= $max_length)
                return $string;
                if ($trunc_at_space && ($space_position = strrpos($stringSimple, ' ', $max_length - $string_length))) $max_length = $space_position;
                
                return substr_replace($stringSimple, $replacement, $max_length);
            } else {
                
                return $string;
            }
        }
    }
    public static function strVerticalize($s) {
        $return = '';
        
        for ($i = 0; $i < strlen($s); $i++) {
            $return.= substr($s, $i, 1) . '<br />';
        }
        
        return $return;
    }
    public static function removeAccents($txt) {
        $txt = str_replace('œ', 'oe', $txt);
        $txt = str_replace('Œ', 'Oe', $txt);
        $txt = str_replace('æ', 'ae', $txt);
        $txt = str_replace('Æ', 'Ae', $txt);
        mb_regex_encoding('UTF-8');
        $txt = mb_ereg_replace('[ÀÁÂÃÄÅĀĂǍẠẢẤẦẨẪẬẮẰẲẴẶǺĄ]', 'A', $txt);
        $txt = mb_ereg_replace('[àáâãäåāăǎạảấầẩẫậắằẳẵặǻą]', 'a', $txt);
        $txt = mb_ereg_replace('[ÇĆĈĊČ]', 'C', $txt);
        $txt = mb_ereg_replace('[çćĉċč]', 'c', $txt);
        $txt = mb_ereg_replace('[ÐĎĐ]', 'D', $txt);
        $txt = mb_ereg_replace('[ďđ]', 'd', $txt);
        $txt = mb_ereg_replace('[ÈÉÊËĒĔĖĘĚẸẺẼẾỀỂỄỆ]', 'E', $txt);
        $txt = mb_ereg_replace('[èéêëēĕėęěẹẻẽếềểễệ]', 'e', $txt);
        $txt = mb_ereg_replace('[ĜĞĠĢ]', 'G', $txt);
        $txt = mb_ereg_replace('[ĝğġģ]', 'g', $txt);
        $txt = mb_ereg_replace('[ĤĦ]', 'H', $txt);
        $txt = mb_ereg_replace('[ĥħ]', 'h', $txt);
        $txt = mb_ereg_replace('[ÌÍÎÏĨĪĬĮİǏỈỊ]', 'I', $txt);
        $txt = mb_ereg_replace('[ìíîïĩīĭįıǐỉị]', 'i', $txt);
        $txt = str_replace('Ĵ', 'J', $txt);
        $txt = str_replace('ĵ', 'j', $txt);
        $txt = str_replace('Ķ', 'K', $txt);
        $txt = str_replace('ķ', 'k', $txt);
        $txt = mb_ereg_replace('[ĹĻĽĿŁ]', 'L', $txt);
        $txt = mb_ereg_replace('[ĺļľŀł]', 'l', $txt);
        $txt = mb_ereg_replace('[ÑŃŅŇ]', 'N', $txt);
        $txt = mb_ereg_replace('[ñńņňŉ]', 'n', $txt);
        $txt = mb_ereg_replace('[ÒÓÔÕÖØŌŎŐƠǑǾỌỎỐỒỔỖỘỚỜỞỠỢ]', 'O', $txt);
        $txt = mb_ereg_replace('[òóôõöøōŏőơǒǿọỏốồổỗộớờởỡợð]', 'o', $txt);
        $txt = mb_ereg_replace('[ŔŖŘ]', 'R', $txt);
        $txt = mb_ereg_replace('[ŕŗř]', 'r', $txt);
        $txt = mb_ereg_replace('[ŚŜŞŠ]', 'S', $txt);
        $txt = mb_ereg_replace('[śŝşš]', 's', $txt);
        $txt = mb_ereg_replace('[ŢŤŦ]', 'T', $txt);
        $txt = mb_ereg_replace('[ţťŧ]', 't', $txt);
        $txt = mb_ereg_replace('[ÙÚÛÜŨŪŬŮŰŲƯǓǕǗǙǛỤỦỨỪỬỮỰ]', 'U', $txt);
        $txt = mb_ereg_replace('[ùúûüũūŭůűųưǔǖǘǚǜụủứừửữự]', 'u', $txt);
        $txt = mb_ereg_replace('[ŴẀẂẄ]', 'W', $txt);
        $txt = mb_ereg_replace('[ŵẁẃẅ]', 'w', $txt);
        $txt = mb_ereg_replace('[ÝŶŸỲỸỶỴ]', 'Y', $txt);
        $txt = mb_ereg_replace('[ýÿŷỹỵỷỳ]', 'y', $txt);
        $txt = mb_ereg_replace('[ŹŻŽ]', 'Z', $txt);
        $txt = mb_ereg_replace('[źżž]', 'z', $txt);
        
        return $txt;
    }
    public static function replaceAccents($txt, $replace) {
        $txt = str_replace('œ', $replace, $txt);
        $txt = str_replace('Œ', $replace, $txt);
        $txt = str_replace('æ', $replace, $txt);
        $txt = str_replace('Æ', $replace, $txt);
        mb_regex_encoding('UTF-8');
        $txt = mb_ereg_replace('[ÀÁÂÃÄÅĀĂǍẠẢẤẦẨẪẬẮẰẲẴẶǺĄ]', $replace, $txt);
        $txt = mb_ereg_replace('[àáâãäåāăǎạảấầẩẫậắằẳẵặǻą]', $replace, $txt);
        $txt = mb_ereg_replace('[ÇĆĈĊČ]', $replace, $txt);
        $txt = mb_ereg_replace('[çćĉċč]', $replace, $txt);
        $txt = mb_ereg_replace('[ÐĎĐ]', $replace, $txt);
        $txt = mb_ereg_replace('[ďđ]', $replace, $txt);
        $txt = mb_ereg_replace('[ÈÉÊËĒĔĖĘĚẸẺẼẾỀỂỄỆ]', $replace, $txt);
        $txt = mb_ereg_replace('[èéêëēĕėęěẹẻẽếềểễệ]', $replace, $txt);
        $txt = mb_ereg_replace('[ĜĞĠĢ]', $replace, $txt);
        $txt = mb_ereg_replace('[ĝğġģ]', $replace, $txt);
        $txt = mb_ereg_replace('[ĤĦ]', $replace, $txt);
        $txt = mb_ereg_replace('[ĥħ]', $replace, $txt);
        $txt = mb_ereg_replace('[ÌÍÎÏĨĪĬĮİǏỈỊ]', $replace, $txt);
        $txt = mb_ereg_replace('[ìíîïĩīĭįıǐỉị]', $replace, $txt);
        $txt = str_replace('Ĵ', $replace, $txt);
        $txt = str_replace('ĵ', $replace, $txt);
        $txt = str_replace('Ķ', $replace, $txt);
        $txt = str_replace('ķ', $replace, $txt);
        $txt = mb_ereg_replace('[ĹĻĽĿŁ]', $replace, $txt);
        $txt = mb_ereg_replace('[ĺļľŀł]', $replace, $txt);
        $txt = mb_ereg_replace('[ÑŃŅŇ]', $replace, $txt);
        $txt = mb_ereg_replace('[ñńņňŉ]', $replace, $txt);
        $txt = mb_ereg_replace('[ÒÓÔÕÖØŌŎŐƠǑǾỌỎỐỒỔỖỘỚỜỞỠỢ]', $replace, $txt);
        $txt = mb_ereg_replace('[òóôõöøōŏőơǒǿọỏốồổỗộớờởỡợð]', $replace, $txt);
        $txt = mb_ereg_replace('[ŔŖŘ]', $replace, $txt);
        $txt = mb_ereg_replace('[ŕŗř]', $replace, $txt);
        $txt = mb_ereg_replace('[ŚŜŞŠ]', $replace, $txt);
        $txt = mb_ereg_replace('[śŝşš]', $replace, $txt);
        $txt = mb_ereg_replace('[ŢŤŦ]', $replace, $txt);
        $txt = mb_ereg_replace('[ţťŧ]', $replace, $txt);
        $txt = mb_ereg_replace('[ÙÚÛÜŨŪŬŮŰŲƯǓǕǗǙǛỤỦỨỪỬỮỰ]', $replace, $txt);
        $txt = mb_ereg_replace('[ùúûüũūŭůűųưǔǖǘǚǜụủứừửữự]', $replace, $txt);
        $txt = mb_ereg_replace('[ŴẀẂẄ]', $replace, $txt);
        $txt = mb_ereg_replace('[ŵẁẃẅ]', $replace, $txt);
        $txt = mb_ereg_replace('[ÝŶŸỲỸỶỴ]', $replace, $txt);
        $txt = mb_ereg_replace('[ýÿŷỹỵỷỳ]', $replace, $txt);
        $txt = mb_ereg_replace('[ŹŻŽ]', $replace, $txt);
        $txt = mb_ereg_replace('[źżž]', $replace, $txt);
        
        return $txt;
    }
    public static function strHighlightWord($str, $word) {
        $str = str_replace($word, '<font class="highlight">' . $word . '</font>', $str);
        
        return $str;
    }
    public static function removeHtml($str) {
        $str = ereg_replace("<[^>]*>", "", trim(strip_tags($str)));
        
        return $str;
    }
    /*
     * Transforme date 201112 to Décembre 2011
    */
    public static function dateNumericToString($date) {
        $year = substr($date, 0, 4);
        $month = substr($date, 4, 2);
        $search = array(
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '09',
            '10',
            '11',
            '12'
        );
        $replace = array(
            'Janvier',
            'Février',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
            'Juillet',
            'Août',
            'Septembre',
            'Octobre',
            'Novembre',
            'Décembre'
        );
        
        return str_replace($search, $replace, $month) . ' ' . $year;
    }
    /*
     * Transforme date 13/06/2015 to 20150613
    */
    public static function dateFrenchToInt($date) {
        $year = substr($date, 6, 4);
        $month = substr($date, 3, 2);
        $day = substr($date, 0, 2);
        
        return $year . $month . $day;
    }
}
