<?php

class affichageArticleView extends dmWidgetPluginView {

    public function configure() {   
        
        parent::configure();
    }

    protected function doRender() {

        $vars = $this->getViewVars();

        if ($vars['recordId'] == '') { // donc article contextuel
            $record = sfcontext::getInstance()->getPage() ? sfcontext::getInstance()->getPage()->getRecord() : false;
            $recordId = $record->id;
        } else {
            $recordId = $vars['recordId'];
        }

        $article = dmDb::table('SidArticle') //->findOneBySectionId($section->id);
                ->createQuery('a')
                ->where('id = ? ', array($recordId))
                ->orderBy('updated_at DESC')
                ->limit(1)
                ->execute();

        return $this->getHelper()->renderPartial('affichage', 'article', array(
                    'article' => $article[0]
                ));
    }
    
    /*
     * retourne la chaîne prise en compte par lucene search pour son indexation
     * Si cette fonction n'est pas définie alors l'index de lucene est effectué sur this->doRender(), avec prise en compte du partial... etc... ATTENTION? peut être très long à traiter par Lucene
     * 
     */
    protected function doRenderForIndex() {
        
        $vars = $this->getViewVars();

        if ($vars['recordId'] == '') { // donc article contextuel
            $record = sfcontext::getInstance()->getPage() ? sfcontext::getInstance()->getPage()->getRecord() : false;
            $recordId = $record->id;
        } else {
            $recordId = $vars['recordId'];
        }

        $article = dmDb::table('SidArticle') //->findOneBySectionId($section->id);
                ->createQuery('a')
                ->where('id = ? ', array($recordId))
                ->orderBy('updated_at DESC')
                ->limit(1)
                ->execute();        

        $indexRender = '';
        if ($article[0]){
            $indexRender = stringTools::str_truncate($article[0]->chapeau, 200); // on indexe seulement le chapeau de l'article, on peut laisse le render() complet mais cela s'avère très lent...
        }
        
        return $indexRender;
    }
    
    

}