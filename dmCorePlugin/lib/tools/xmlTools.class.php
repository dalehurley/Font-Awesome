<?php

/**
 * class xmlTools 
 * 
 */
class xmlTools {
    /*
     * Retourne la valeur de l'étiquette dataType du xml
     * 
     */

    public static function getDataTypeXml($xml, $etiquette) {
        $return = '';
        if (!is_file($xml)) {

            if (sfConfig::get('sf_environment') == 'dev') {
                $return = __('Error : missed file') . ' : ' . $xml;
            }
        }
// Je charge en mémoire mon document XML
        $doc_xml = new DOMDocument();

// recherche typeXML dossier / article
        if ($doc_xml->load($xml)) {
            $return = (isset($doc_xml->getElementsByTagName($etiquette)->item(0)->nodeValue)) ? $doc_xml->getElementsByTagName($etiquette)->item(0)->nodeValue : "";
        } else {
            $return = __('Error : invalid XML file') . ' : ' . $xml;
        }
        return $return;
    }

}
