<?php

class dmWidgetXmlReadByKeyView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();
    }

    protected function filterViewVars(array $vars = array()) {
        $vars = parent::filterViewVars($vars);

        $html = $this->rendu();
        $vars['html'] = $html;

        return $vars;
    }

    protected function rendu() {

        $idArticle = sfContext::getInstance()->getRequest()->getParameter('id');
        $category = sfContext::getInstance()->getRequest()->getParameter('category');

        // vérification de l'existance de l'id dans le fichier category.xml
        $xmlFileRubrique = 'ftp://'
                . sfConfig::get('app_xml_login') . ':'
                . sfConfig::get('app_xml_password') . '@'
                . sfConfig::get('app_xml_host')
                . sfConfig::get('app_xml_rep-rubrique')   //$category
                . $category . '.xml';
        /*
         *
         * A continuer
         * Il faut s'assurer que l'id passé dans l'url :
         * - fait partie de la rubrique  /:rubrique/:id...
         * - fait partie des articles auxquels est abonné le client
         *
         */


        $xmlFile = 'ftp://'
                . sfConfig::get('app_xml_login') . ':'
                . sfConfig::get('app_xml_password') . '@'
                . sfConfig::get('app_xml_host')
                . sfConfig::get('app_xml_rep-articles')   //$category
                . $idArticle . '.xml';
        $repImagesFtp = 'ftp://'
                . sfConfig::get('app_xml_login') . ':'
                . sfConfig::get('app_xml_password') . '@'
                . sfConfig::get('app_xml_host')
                . sfConfig::get('app_xml_rep-images');

        //$xmlFile = 'uploads/xml/'.$idArticle.'.xml';
        //$xslFile = 'uploads/xml/article.xsl';
        $xslFile = sfConfig::get('app_xml_xsl');

        // xml > xsl > html
        $xslt = new XSLTProcessor();
        $xml = new domDocument();
        $xml->load($xmlFile);
        $xsl = new domDocument();
        $xsl->load($xslFile);
        $xslt->importStylesheet($xsl);
        $html = $xslt->transformToXml($xml);

        $html = str_replace('#routeImagesDir#', $repImagesFtp, $html);

        return $html.'<br/>'.$xmlFile.'<br/>'.$xslFile;
    }

}