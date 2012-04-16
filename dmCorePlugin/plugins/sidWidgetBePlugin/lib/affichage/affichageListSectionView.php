<?php

class affichageListSectionView extends dmWidgetPluginView {
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
        $vars['nbSections'] =  ($vars['nbSections']== 0) ? '' : $vars['nbSections'];
        $sections = dmDb::table('SidSection') //->findOneBySectionId($section->id);
                ->createQuery('s')
                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 's')
                ->where('s.rubrique_id = ? ', $recordId)
//                ->orderBy('RAND()')
                ->orderBy('s.position')
                ->limit($vars['nbSections'])->execute();

        $sectionArticles = array(); 
        $articleTitles = array();
        $sectionName= array();
        foreach ($sections as $section) {
            $listTitles = '\''.implode('\',\'', $articleTitles).'\'';

            // on regarde s'il y a des dossiers dans cette sections
            $nbDossiers = dmDb::table('SidArticle') 
                ->createQuery('a')
                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                ->where('a.section_id = ? ', $section->id)
                ->addWhere('a.is_dossier = 1')   
                ->count();  

            if ($nbDossiers){ // s'il y a au moins un dossier alors on n'affiche que les dossiers de la sections
                $articles = dmDb::table('SidArticle') 
                    ->createQuery('a')
                    ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                    ->where('a.section_id = ? ', $section->id)
                    ->addWhere('aTranslation.title NOT IN ('.$listTitles.')')   // on s'assure que le title ne soit pas déjà récupéré dans une précédente section
                    ->addWhere('a.is_dossier = 1') 
                    ->orderBy('aTranslation.updated_at DESC, aTranslation.title')
                    ->groupBy('aTranslation.title')
                    ->limit($vars['nbArticles'])->execute();                  
            } else {
                $articles = dmDb::table('SidArticle') 
                    ->createQuery('a')
                    ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                    ->where('a.section_id = ? ', $section->id)
                    ->addWhere('aTranslation.title NOT IN ('.$listTitles.')')   // on s'assure que le title ne soit pas déjà récupéré dans une précédente section
                    ->orderBy('aTranslation.updated_at DESC, aTranslation.title')
                    ->groupBy('aTranslation.title')
                    ->limit($vars['nbArticles'])->execute();                  
            }


            $titleSection = $section->getRubriquePageTitle().' - '.$section->getPageTitle();    
            $sectionArticles[$titleSection] = $articles;
            // récupération des titles des articles
            foreach ($articles as $article) {
                $articleTitles[] = str_replace('\'', ' ', $article->title);
            }
            $nameSectionPage = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId('section', 'show', $section->id);
            $sectionName[$section->id] = $nameSectionPage->getTitle();
        }

        return $this->getHelper()->renderPartial('affichage', 'listSection', array(
            'sectionArticles' => $sectionArticles, 
            'sectionName' => $sectionName,
            'widthImage' => $vars['widthImage'],
            'withImage' => $vars['withImage'],
            'nbArticles' => $vars['nbArticles'],
            'nbSections' => $vars['nbSections']
        ));
    }

}


