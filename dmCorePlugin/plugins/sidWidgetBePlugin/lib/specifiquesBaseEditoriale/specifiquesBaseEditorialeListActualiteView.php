<?php

class specifiquesBaseEditorialeListActualiteView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'nbArticles',
            'length',
            'withImage',
            'chapo',
            'heightImage',
            'widthImage'     
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayArticle = array();
        $dmPage = sfContext::getInstance()->getPage();
        
        //$ancestors = $dmPage->getNode()->getAncestors();
        //$recordId = $ancestors[count($ancestors) - 1]->getRecordId();

        $nbArticles = ($vars['nbArticles'] == 0) ? '' : $vars["nbArticles"];
        
        switch ($dmPage->module . '/' . $dmPage->action) {
            // si on est dans la page d'un article su cabinet, on enlève de la liste des articles en bas de page l'article qui est affiché ($dmPage->record_id)
            case 'article/show':
                $orderBy ='';
                $andWhere ='';

                $ancestors = $dmPage->getNode()->getAncestors();
                $recordId = $ancestors[count($ancestors) - 1]->getRecordId();
                if($this->context->getPage()->getRecord()->Section->Rubrique->getTitle() == 'ec_echeancier'){
                    $orderBy = 'aTranslation.created_at ASC';
                    $andWhere = 'and aTranslation.created_at > CURRENT_DATE';
                }
                else $orderBy = 'aTranslation.updated_at DESC';
                
                $actuArticles = Doctrine_Query::create()
                        ->from('SidArticle a')
                        ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                        ->where('a.is_active = ? and  a.id <> ? and a.section_id = ? '.$andWhere, array(true, $dmPage->record_id,$recordId))
                        ->orderBy($orderBy)
                        ->limit($nbArticles)
                        ->execute();

                foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
                    $arrayArticle[$actuArticle->id] = $actuArticle;
                }
                break;
            // pour affichage du listing des articles du cabinet quand on est sur la page "Actualités du cabinet"
            default:
                break;
        }
        $vars['titreBloc'] = ($vars['titreBloc'] == NULL || $vars['titreBloc'] == ' ') ? sfContext::getInstance()->getI18N()->__('The other news in').' '.strtolower($ancestors[count($ancestors) - 1]->getName()) : $vars['titreBloc'];
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'listActualite', array(
                    'articles' => $arrayArticle,
                    'nbArticles' => $vars['nbArticles'],
                    'titreBloc' => $vars['titreBloc'],
                    'length' => $vars['length'],
                    'width' => $vars['widthImage'],
                    'height' => $vars['heightImage'],
                    'withImage' => $vars['withImage'],
                    'chapo' => $vars['chapo'],
                    'justTitle' => isset($vars['justTitle'])?$vars['justTitle']:false
                ));


    }

}
