<?php

class affichageArticleView extends dmWidgetPluginView {
    public function configure() {
        parent::configure();

        if(dmConfig::get('site_theme_version') == 'v2'){
            $this->addJavascript('/theme/less/bootstrap/js/bootstrap-collapse.js');
        }

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
                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                ->where('a.id = ? ', $recordId)
                ->orderBy('aTranslation.updated_at DESC')->limit(1)->execute();

        // $missions = Doctrine_Query::create()
        //             ->from('SidCabinetMission a')
        //             ->leftJoin('a.Translation b')
        //             ->where('a.is_active = ? and a.id <> ?', array(true,$dmPage->record_id))
        //             ->orderBy('b.updated_at DESC')
        //             ->limit($nbArticles)
        //             ->execute();
        
        //         foreach ($missions as $mission) { // on stock les NB actu article 
        //             $arrayMissions[$mission->id] = $mission;
        //         }

        $xml = sfConfig::get('app_rep-local') .
        $article[0]->getSection()->getRubrique() .
        '/' .
        $article[0]->getSection() .
        '/' .
        $article[0]->filename .
        '.xml';
        $xsl = dm::getDir() . '/dmCorePlugin/plugins/sidWidgetBePlugin/lib/xsl/' . sfConfig::get('app_xsl-article');        

        $dataType = xmlTools::getLabelXml($xml, "DataType");

        // gestion de l'agenda : affichage des autres articles de la meme section (le meme mois)
        $articleList = array();
        if ($dataType == 'AGENDA'){
            $articleList =  Doctrine_Query::create()
                    ->from('SidArticle a')
                    ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                    ->where('a.is_active = ? and a.id <> ? and a.section_id = ?', array(true, $article[0]->id, $article[0]->section_id))  // $dmPage->record_id))
                    ->orderBy('aTranslation.updated_at DESC')
                    ->execute();
        }
        
        return $this->getHelper()->renderPartial('affichage', 'article', array(
            'article' => $article[0], 
            'widthImage' => $vars['widthImage'],
            'withImage' => $vars['withImage'],
            'xml' => $xml,
            'xsl' => $xsl,            
            'dataType' => $dataType,
            'articleList' => $articleList            
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
                    ->createQuery('a')
                    ->leftJoin('a.Translation b')
                    ->where('a.id = ? ', array($recordId))
                    ->orderBy('b.updated_at DESC')
                    ->limit(1)
                    ->execute();
        
        $indexRender = '';
        if ($article[0]) {
            $indexRender = stringTools::str_truncate($article[0]->chapeau, 200); // on indexe seulement le chapeau de l'article, on peut laisse le render() complet mais cela s'avère très lent...
            
        }
        
        return $indexRender;
    }
}
