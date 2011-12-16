<?php

/**
 * class xmlTools 
 * 
 */
class xmlTools {

    /**
     * Retourne la valeur de l'étiquette dataType du xml
     * @param string $xml route to xml file
     * @param string $label
     * @return string  
     */
    public static function getLabelXml($xml, $label) {
        $return = '';
        // Je charge en mémoire mon document XML
        $doc_xml = new DOMDocument();
        // recherche typeXML dossier / article
        if ($doc_xml->load($xml)) {
            $return .= (isset($doc_xml->getElementsByTagName($label)->item(0)->nodeValue)) ? $doc_xml->getElementsByTagName($label)->item(0)->nodeValue : "";
        } else {
            $return .= __('Error : invalid XML file') . ' : ' . $xml;
        }

        return $return;
    }

}
