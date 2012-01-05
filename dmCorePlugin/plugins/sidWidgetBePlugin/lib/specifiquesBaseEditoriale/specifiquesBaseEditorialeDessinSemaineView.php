<?php

class specifiquesBaseEditorialeDessinSemaineView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'title',
            'effect',
            'filDActu',
            'titreLien'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();

        // traitement du fichier XML
        $xml = new DOMDocument();
        $xmlFile = sfConfig::get('app_rep-local-dessin-semaine') . sfConfig::get('app_xml-dessin');

        $listDessin = '';

        if ($xml->load($xmlFile)) {

            $articles = $xml->getElementsByTagName("article");
            $dessins = array();
            
            foreach ($articles as $article) {
                $idArticle = $article->getAttribute("idArticle");
                
                // on ne stocke pour le template que les dessins avec une dateArticle passée
                $dateArticleInt = stringTools::dateFrenchToInt($article->getAttribute("dateArticle"));  // tranformation de la date du XML en entire AAAAMMJJ      
                
                if ( $dateArticleInt <= date('Ymd')) { // comparaison avec lka date du jour
                    $dessins[$idArticle]['rubrique'] = $article->getAttribute("rubrique");
                    $dessins[$idArticle]['auteurArticle'] = $article->getAttribute("auteurArticle");
                    $dessins[$idArticle]['dateArticle'] = $article->getAttribute("dateArticle");
                    $dessins[$idArticle]['site'] = $article->getAttribute("site");
                    // on considère le premier objet des tags suivants, contenu dans l'article, donc ->item(0)
                    $dessins[$idArticle]['copyright'] = (isset($article->getElementsByTagName("copyright")->item(0)->nodeValue)) ? $article->getElementsByTagName("copyright")->item(0)->nodeValue : "";
                    $dessins[$idArticle]['titre'] = (isset($article->getElementsByTagName("titre")->item(0)->nodeValue)) ? $article->getElementsByTagName("titre")->item(0)->nodeValue : "";
                    $dessins[$idArticle]['chapeau'] = (isset($article->getElementsByTagName("chapeau")->item(0)->nodeValue)) ? $article->getElementsByTagName("chapeau")->item(0)->nodeValue : "";
                    $dessins[$idArticle]['contenu'] = (isset($article->getElementsByTagName("contenu")->item(0)->nodeValue)) ? $article->getElementsByTagName("contenu")->item(0)->nodeValue : "";

                    //lien vers l'image du dessin
                    $dessins[$idArticle]['imgLinkBig'] = '/_images/' . $idArticle . '-b.jpg';
                    $dessins[$idArticle]['imgLinkSmall'] = '/_images/' . $idArticle . '-a.jpg';
                }
            }
        } else {
            $return[$j]['ERREUR : XML invalide ' . $xmlFile] = $xmlFile . '.xml Invalide';
        }

        if($vars['filDActu'] == false){
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'dessinSemaine', array(
                    'rubriqueTitle' => $vars['title'], // pour avoir le TITRE de la page 
                    'effect' => $vars['effect'], // pour avoir le TITRE de la page 
                    'dessins' => $dessins
                ));
        }
        else {
            reset($dessins);
            return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'blocDessinSemaine', array(
                    'rubriqueTitle' => $vars['title'], // pour avoir le TITRE de la page 
                    'dessins' => current($dessins),
                    'titreLien' => $vars['titreLien']
                ));
        }
    }

}
