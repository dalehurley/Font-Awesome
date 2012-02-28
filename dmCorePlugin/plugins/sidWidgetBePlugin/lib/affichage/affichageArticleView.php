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
                ->leftJoin('a.Translation b')
                ->where('a.id = ? ', array(
            $recordId
        ))->orderBy('b.updated_at DESC')->limit(1)->execute();
        
        return $this->getHelper()->renderPartial('affichage', 'article', array(
            'article' => $article[0], 
            'widthImage' => $vars['widthImage'],
            'withImage' => $vars['withImage']
        ));
    }
    /**
     * retourne la chaîne prise en compte par lucene search pour son indexation
     * Si cette fonction n'est pas définie alors l'index de lucene estffectué suris->doRender(), avec prise en compte du partial... etc... ATTENTION? peut être très l traiter par Lucene
     * @return [type]
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
        ->createQuery('a')->leftJoin('a.Translation b')->where('a.id = ? ', array(
            $recordId
        ))->orderBy('b.updated_at DESC')->limit(1)->execute();
        $indexRender = '';
        if ($article[0]) {
            $indexRender = stringTools::str_truncate($article[0]->chapeau, 200); // on indexe seulement le chapeau de l'article, on peut laisse le render() complet mais cela s'avère très lent...
            
        }
        
        return $indRender;
    }
}
