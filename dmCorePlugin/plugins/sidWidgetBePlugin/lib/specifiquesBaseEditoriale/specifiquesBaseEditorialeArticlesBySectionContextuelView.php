<?php

class specifiquesBaseEditorialeArticlesBySectionContextuelView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'titreLien',
            'section',
            'longueurTexte',
            'nbArticle'
        ));
    }

    /**
     * On affiche NB articles Actu selon 3 types:
     * 1) il est sur une dmPage automatique de type "Rubrique" : on affiche les articles qui sont dans les sections de cette rubrique
     * 2) il est sur une dmPage automatique de type "Section" : on affiche les articles qui sont dans cette section
     * 3) il est sur une page ni Rubrique ni Section, automatique ou pas : on affiche les articles de la rubrique choisie par défaut
     *  
     */
    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayArticle = array();

        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
        
        switch ($dmPage->module . '/' . $dmPage->action) {
        
            case 'rubrique/show':

                $rubrique = dmDb::table('SidRubrique')->findOneById($dmPage->record_id);

                foreach ($vars['section'] as $section) {
                    $sectionPages = Doctrine_Query::create()->from('SidSection sa')
                                ->Where('sa.is_active = ? and sa.id = ?', array(true, $section))
                                ->orderBy('sa.updated_at DESC')
                                ->limit(1)
                                ->execute();
        
                    if ($sectionPages[0]->rubrique_id == $rubrique->id) {

                        
                        $articles = Doctrine_Query::create()->from('SidArticle sa')
                                ->Where('sa.is_active = ? and sa.section_id = ?', array(true, $sectionPages[0]->id))
                                ->orderBy('sa.updated_at DESC')
                                
                                ->limit($vars['nbArticle'])
                                ->execute();
                        
                        
//                        echo'totto '. $rubriqueName->name.'<br />';
//                        echo'totto '.$sectionName->name;
                    }
                }
                $rubriqueName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId($dmPage->module,$dmPage->action,$dmPage->record_id);
                $sectionName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId('section',$dmPage->action,$sectionPages[0]->id);
                
                break;
                
                
            case 'section/show':

                $sectionId = dmDb::table('SidSection')->findOneById($dmPage->record_id);
                $rubrique = dmDb::table('SidRubrique')->findOneById($sectionId->rubrique_id);

                foreach ($vars['section'] as $section) {
                    
                    $sectionPages = Doctrine_Query::create()->from('SidSection sa')
                                ->Where('sa.is_active = ? and sa.id = ?', array(true, $section))
                                ->orderBy('sa.updated_at DESC')
                                ->limit(1)
                                ->execute();
                    
                    if ($sectionPages[0]->rubrique_id == $rubrique->id) {
                        $articles = Doctrine_Query::create()->from('SidArticle sa')
                                ->Where('sa.is_active = ? and sa.section_id = ?', array(true, $section))
                                ->orderBy('sa.updated_at DESC')
                                ->limit($vars['nbArticle'])
                                ->execute();
                        
                        $rubriqueName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId('rubrique','show',$rubrique->id);
                        $sectionName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId($dmPage->module,$dmPage->action,$sectionPages[0]->id);
//                        echo $rubriqueName->name.' - '.$sectionName->name;
                    }
                }
                $rubriqueName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId('rubrique','show',$rubrique->id);
                $sectionName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId($dmPage->module,$dmPage->action,$sectionPages[0]->id);
                break;
                
                case 'article/show':

                $articleId = dmDb::table('SidArticle')->findOneById($dmPage->record_id);
                $rubrique = dmDb::table('SidRubrique')->findOneById($articleId->Section->rubrique_id);

                foreach ($vars['section'] as $section) {
                    
                    $sectionPages = Doctrine_Query::create()->from('SidSection sa')
                                ->Where('sa.is_active = ? and sa.id = ?', array(true, $section))
                                ->orderBy('sa.updated_at DESC')
                                ->limit(1)
                                ->execute();
                    
                    if ($sectionPages[0]->rubrique_id == $rubrique->id) {
                        $articles = Doctrine_Query::create()->from('SidArticle sa')
                                ->Where('sa.is_active = ? and sa.section_id = ?', array(true, $section))
                                ->orderBy('sa.updated_at DESC')
                                
                                ->limit($vars['nbArticle'])
                                ->execute();
                        
                        
//                        echo $rubriqueName->name.' - '.$sectionName->name;
                    }
                }
                $rubriqueName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId('rubrique','show',$rubrique->id);
                $sectionName = dmDb::table('dmPage')->findOneByModuleAndActionAndRecordId('section',$dmPage->action,$articleId->section_id);
                break;
                
                default : 
                    $articles = array();
                    $rubriqueName ="";
                    $sectionName="";
                    break;
                
        }
        
//                foreach ($arrayFilActus as $key => $value) {
//                    $updated[$key] = $value['updated_at'];
//                }
//                array_multisort($updated, SORT_DESC, $arrayFilActus);
//                $arrayFilActus = array_slice($arrayFilActus, 0, $vars['nbArticle']);

                

        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'articlesBySectionContextuel', array(
                    'articles' => $articles,
//                    'lien' => $arrayLienIds,
                    'titreBloc' => $vars['titreBloc'],
                    'titreLien' => $vars['titreLien'],
                    'longueurTexte' => $vars['longueurTexte'],
                    'rubrique' => $rubriqueName,
                    'section' => $sectionName
                ));
    }

}
