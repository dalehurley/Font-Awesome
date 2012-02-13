<?php

class handWidgetsListSousHomeByRubriqueView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'photo',
            'titreBloc'
        ));
    }

    protected function doRender() {
        $arrayArticles = array();
        $arrayGlobal = array();
        $articles = array();
        $sectionNames = array();
        $vars = $this->getViewVars();
        
        $namePage = $this->context->getPage()->getName();
        $recordPage = $this->context->getPage()->getRecordId();
        $nomRubrique = dmDb::table('SidRubrique')->findOneById($recordPage);
        if($nomRubrique != 'ec_echeancier'){
        $sections = dmDb::table('SidSection')
                ->createQuery('p')
                ->where('p.is_active = ? and p.rubrique_id=? ', array(true,$recordPage))
                ->execute();
        
        foreach ($sections as $section){
        $articles = dmDb::table('SidArticle') //->findOneBySectionId($section->id);
                ->createQuery('a')
                ->leftJoin('a.Translation b')
                ->where('a.section_id = ? and a.is_active = ? ', array($section->id, true))
                ->orderBy('b.updated_at DESC')
                ->limit(1)
                ->execute();
                
        $sectionTitres = dmDb::table('DmPage') //->findAllBySectionId($vars['section']);
                ->createQuery('p')
                ->where('p.module = ? and p.action= ? and p.record_id= ?', array('section','show',$section->id))
                ->limit(1) 
                ->execute();
        $sectionNames[$articles[0]->id] = $sectionTitres[0]->getName();
        $sections[$articles[0]->id] = $sectionTitres[0];
        $arrayArticles[] = $articles;
        }
        
        $rubrique = $namePage;
        // Je personnalise le titre du widget
//        if($vars['title'] == ''){
            $title = $namePage;
            
            
//        }
//        else $title = $vars['title'];
        
        return $this->getHelper()->renderPartial('handWidgets', 'listSousHomeByRubrique', array(
            'articles' => $arrayArticles,
            //'articles' => $articles,
            //'nbArticles' => $vars['nbArticles'],
            'photoArticle' => $vars['photo'],
            'titreBloc' => $vars['titreBloc'],
            'rubrique' => $rubrique,
            'sectionName' =>$sectionNames,
            'section' => $sections
            
            //'link' => $sectionPage,
            //'sectionName' => $title,    // on envoi la première page trouvée         
        ));
    }
    }

}
