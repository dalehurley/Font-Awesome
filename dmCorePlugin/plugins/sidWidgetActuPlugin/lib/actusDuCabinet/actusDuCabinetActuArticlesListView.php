<?php

class actusDuCabinetActuArticlesListView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
//            'maxPerPage',
//            'navTop',
//            'navBottom',
            'nbArticles',
            'length',
            'withImage',
            'chapo',
            'heightImage',
            'widthImage'
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
        $redirect = false;
        $header ='';
        $constanteActuCabinet = '';
        $dmPage = sfContext::getInstance()->getPage();
        
        $nbArticles = ($vars['nbArticles'] == 0) ? '' : $vars["nbArticles"];
        
        switch ($dmPage->module . '/' . $dmPage->action) {
            // si on est dans la page d'un article su cabinet, on enlève de la liste des articles en bas de page l'article qui est affiché ($dmPage->record_id)
            case 'sidActuArticle/show':
                
                $actuArticles = Doctrine_Query::create()
                        ->from('SidActuArticle a')
                        ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                        ->leftJoin('a.SidActuTypeArticle sata')
                        ->where('a.is_active = ? and sata.sid_actu_type_id = ? and a.id <> ?', array(true, $vars['type'], $dmPage->record_id))
                        ->orderBy('aTranslation.updated_at DESC')
                        ->limit($nbArticles)
                        ->execute();

                foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
                    $arrayArticle[$actuArticle->id] = $actuArticle;
                };
                $constanteActuCabinet = '';
                break;
            // pour affichage du listing des articles du cabinet quand on est sur la page "Actualités du cabinet"
            default:
                
                $actuArticles = Doctrine_Query::create()
                        ->from('SidActuArticle a')
                        ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                        ->leftJoin('a.SidActuTypeArticle sata')
                        ->where('a.is_active = ? and sata.sid_actu_type_id = ?', array(true, $vars['type']))
                        ->orderBy('a.position ASC')
                        ->limit($nbArticles)
                        ->execute();

                foreach ($actuArticles as $actuArticle) { // on stock les NB actu article 
                    $arrayArticle[$actuArticle->id] = $actuArticle;
                }
                $constanteActuCabinet = '{{actualites_du_cabinet}}';
        }
        $vars['titreBloc'] = ($vars['titreBloc'] == NULL || $vars['titreBloc'] == ' ') ? $dmPage->getName() : $vars['titreBloc'];
        
        if (count($actuArticles) == 1 && ($dmPage->module . '/' . $dmPage->action == 'sidActuArticle/list')) {
            foreach ($actuArticles as $page) {
                $page = dmDb::table('DmPage')->findOneByModuleAndActionAndRecordId('sidActuArticle', 'show', $page->id);
                // add current's controler for header() redirection
                $controlers = json_decode(dmConfig::get('base_urls'), true); // all controlers Url
                $contextEnv = sfConfig::get('dm_context_type') . '-' . sfConfig::get('sf_environment'); // i.e. "front-dev"
                $controlerUrl = (array_key_exists($contextEnv, $controlers)) ? $controlers[$contextEnv] : '';
                $header = $controlerUrl . '/' . $page->getSlug();
                $redirect = true;
            }
                return $this->getHelper()->renderPartial('actusDuCabinet', 'actuArticlesList', array(
                    'articles' => '',
                    'nbArticles' => '',
                    'titreBloc' => '',
                    'length' => '',
                    'width' => '',
                    'height' => '',
                    'withImage' => '',
                    'chapo' => '',
                    'header' => $header,
                    'redirect' => $redirect,
                    'constanteActuCabinet' => $constanteActuCabinet
                ));

        }
        else {
        
        return $this->getHelper()->renderPartial('actusDuCabinet', 'actuArticlesList', array(
                    'articles' => $arrayArticle,
                    'nbArticles' => $vars['nbArticles'],
                    'titreBloc' => $vars['titreBloc'],
                    'length' => $vars['length'],
                    'width' => $vars['widthImage'],
                    'height' => $vars['heightImage'],
                    'withImage' => $vars['withImage'],
                    'chapo' => $vars['chapo'],
                    'redirect' => $redirect,
                    'constanteActuCabinet' => $constanteActuCabinet
                ));
        }
    }

}
