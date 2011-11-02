<?php

class xmlTools {

    public static function dom2array($root) {
        $result = array();

        if ($root->hasAttributes()) {
            $attrs = $root->attributes;

            foreach ($attrs as $i => $attr)
                $result[$attr->name] = $attr->value;
        }

        $children = $root->childNodes;

        if (isset($children->length)) {
            if ($children->length == 1) {
                $child = $children->item(0);

                if ($child->nodeType == XML_TEXT_NODE) {
                    $result['_value'] = $child->nodeValue;

                    if (count($result) == 1){
                        return $result['_value'];
                    }
                    else {
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

                if (!isset($result[$child->nodeName])){
                    $result[$child->nodeName] = self::dom2array($child);
                }
                else {
                    if (!isset($group[$child->nodeName])) {
                        $tmp = $result[$child->nodeName];
                        $result[$child->nodeName] = array($tmp);
                        //$group[$child->nodeName] = 1;
                    }

                    $result[$child->nodeName.$num][] = self::dom2array($child);
                }
            }
        }

        return $result;
    }



    public static function xmlArray2Html($array, $preserve_keys = 0, &$out = array()) {
        # Flatten a multidimensional array to one dimension, optionally preserving keys.
        #
        # $array - the array to flatten
        # $preserve_keys - 0 (default) to not preserve keys, 1 to preserve string keys only, 2 to preserve all keys
        # $out - internal use argument for recursion


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



 




}

