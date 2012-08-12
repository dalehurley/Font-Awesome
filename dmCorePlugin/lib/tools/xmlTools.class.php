<?php
/**
 * Description of xmlTools
 *
 * @author lioshi
 */

class xmlTools {
    /**
     * Retourne la valeur de l'étiquette dataType du xml
     * @param string $xmlFile route to xml file
     * @param string $label
     * @return string
     */
    public static function getLabelXml($xmlFile, $label) {
        $return = '';
        // Je charge en mémoire mon document XML
        $doc_xml = new DOMDocument();

        if (!is_file($xmlFile)) {
            echo debugTools::infoDebug(array('[xmlTools::getLabelXml] '.__('Error : missed file') => $xmlFile),'error');
        } else {
            // recherche typeXML dossier / article
            if ($doc_xml->load($xmlFile) === false) {
                $return.= debugTools::infoDebug(array('[xmlTools::getLabelXml] '.__('Error : invalid XML file') => $xmlFile),'error');
            } else {
                $return.= (isset($doc_xml->getElementsByTagName($label)->item(0)->nodeValue)) ? $doc_xml->getElementsByTagName($label)->item(0)->nodeValue : "";
            }
        }
        return $return;
    }
    public static function dom2array($root) {
        $result = array();
        if ($root->hasAttributes()) {
            $attrs = $root->attributes;
            
            foreach ($attrs as $i => $attr) $result[$attr->name] = $attr->value;
        }
        $children = $root->childNodes;
        if (isset($children->length)) {
            if ($children->length == 1) {
                $child = $children->item(0);
                if ($child->nodeType == XML_TEXT_NODE) {
                    $result['_value'] = $child->nodeValue;
                    if (count($result) == 1) {
                        
                        return $result['_value'];
                    } else {
                        
                        return $result;
                    }
                }
            }
        }
        $group = array();
        if (isset($children)) {
            $num = 0;
            
            foreach ($children as $child) {
                $num++;
                if (!isset($result[$child->nodeName])) {
                    $result[$child->nodeName] = self::dom2array($child);
                } else {
                    if (!isset($group[$child->nodeName])) {
                        $tmp = $result[$child->nodeName];
                        $result[$child->nodeName] = array(
                            $tmp
                        );
                        //$group[$child->nodeName] = 1;
                        
                    }
                    $result[$child->nodeName . $num][] = self::dom2array($child);
                }
            }
        }
        
        return $result;
    }
    public static function xmlArray2Html($array, $preserve_keys = 0, &$out = array()) {
        // Flatten a multidimensional array to one dimension, optionally preserving keys.
        //
        // $array - the array to flatten
        // $preserve_keys - 0 (default) to not preserve keys, 1 to preserve string keys only, 2 to preserve all keys
        // $out - internal use argument for recursion
        
        foreach ($array as $key => $child) {
            if (is_array($child)) {
                $out = self::xmlArray2Html($child, $preserve_keys, $out);
            } elseif ($preserve_keys + is_string($key) > 1) {
                $out[$key] = $child;
            } else {
                //if ($key == 'Headline') {
                $out[] = $child;
                //}
                
            }
        }
        
        return $out;
    }
    /**
     * [xmlToArray description]
     * @param  [type]  $xml  [description]
     * @param  boolean $root [description]
     * @return [type]
     */
    public static function xmlToArray($xml, $root = true) {
        if (!$xml->children()) {
            
            return (string)$xml;
        }
        $array = array();
        
        foreach ($xml->children() as $element => $node) {
            $totalElement = count($xml->{$element});
            if (!isset($array[$element])) {
                $array[$element] = "";
            }
            // Has attributes
            if ($attributes = $node->attributes()) {
                $data = array(
                    'attributes' => array() ,
                );
                if (!count($node->children())) {
                    $data['value'] = (string)$node;
                } else {
                    $data = array_merge($data, xmlToArray($node, false));
                }
                
                foreach ($attributes as $attr => $value) {
                    $data['attributes'][$attr] = (string)$value;
                }
                if ($totalElement > 1) {
                    $array[$element][] = $data;
                } else {
                    $array[$element] = $data;
                }
                // Just a value
                
            } else {
                if ($totalElement > 1) {
                    $array[$element][] = xmlToArray($node, false);
                } else {
                    $array[$element] = xmlToArray($node, false);
                }
            }
        }
        if ($root) {
            
            return array(
                $xml->getName() => $array
            );
        } else {
            
            return $array;
        }
    }
}
