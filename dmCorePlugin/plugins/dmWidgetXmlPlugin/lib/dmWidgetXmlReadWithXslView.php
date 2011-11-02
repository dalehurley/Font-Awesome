<?php

class dmWidgetXmlReadWithXslView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array('xml'));
        $this->addRequiredVar(array('xsl'));
    }

    protected function filterViewVars(array $vars = array()) {
        $vars = parent::filterViewVars($vars);

        $html = $this->rendu($vars);

        $vars['xml'] = $vars['xml'];
        $vars['xsl'] = $vars['xsl'];
        $vars['html'] = $html;

        return $vars;
    }

    protected function rendu($vars) {

        $xmlFile = $vars['xml'];
        $xslFile = $vars['xsl'];

        // xml > xsl > html
        /*        $xslt = new XSLTProcessor();
          $xml = new domDocument();
          $xml->load($xmlFile);
          $xsl = new domDocument();
          $xsl->load($xslFile);
          $xslt->importStylesheet($xsl);
          $html = $xslt->transformToXml($xml);
         */

        // Adresse de mon document DocBook XML
        $domxml = $xmlFile;

        // Adresse du docbook XSL
        $domxsl = $xslFile;

        if (!is_file($domxml) || !is_file($domxsl) ) {
        
            return __('Error : missed file');
        }


            // Je charge en mémoire mon document DocBook XML
            $doc_xml = new DOMDocument();
            $doc_xml->load($domxml);

            // Je charge en mémoire mon document DocBook XSL
            $doc_xsl = new DOMDocument();
            $doc_xsl->load($domxsl);

            // Configuration du transformateur
            $moteurXslt = new xsltProcessor();
            $moteurXslt->importstylesheet($doc_xsl);

            // Transformation du document XML en XHTML et sauvegarde du résultat
            $obj = $moteurXslt->transformtodoc($doc_xml);
            $output = $obj->savexml();

            // Vue
            $html = $output;

            return $html;
            

    }

}