<?php

class selectionPlacementsSelectionsListView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'nbArticles',
            'length',
            'lien',
            'withImage',
            'widthImage',
            'heightImage',
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
        $arraySelections = array();
        $redirect = false;
        $header ='';
        $constanteSelection = '';
        $dmPage = sfContext::getInstance()->getPage();
        
        $nbArticles = ($vars['nbArticles'] == 0) ? '' : $vars["nbArticles"];
        if($dmPage->module.'/'.$dmPage->action == 'selectionPlacement/show'){
        $selections = Doctrine_Query::create()
                ->from('SidCabinetSelectionPlacement a')
                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                ->where('a.is_active = ? and a.id <> ?', array(true,$dmPage->record_id))
                ->orderBy('aTranslation.updated_at DESC')
                ->limit($nbArticles)
                ->execute();
            $constanteSelection = '';
        }
        else{
            $selections = Doctrine_Query::create()
                ->from('SidCabinetSelectionPlacement a')
                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                ->where('a.is_active = ?', array(true))
                ->orderBy('aTranslation.updated_at DESC')
                ->limit($nbArticles)
                ->execute();
            $constanteSelection = '{{selection_placement}}';
        }

        if($vars['titreBloc'] == NULL || $vars['titreBloc'] == " "){
        $namePage = dmDb::table('DmPage')->findOneByModuleAndAction('selectionPlacement','list');
        $vars['titreBloc'] = $namePage->getName();
        }
        ($vars['lien'] != NULL || $vars['lien'] != " ") ? $lien = $vars['lien'] : $lien = '';

        if (count($selections) == 1 && ($dmPage->module . '/' . $dmPage->action == 'selectionPlacement/list')) {
            foreach ($selections as $page) {
                $page = dmDb::table('DmPage')->findOneByModuleAndActionAndRecordId('selectionPlacement', 'show', $page->id);
                // add current's controler for header() redirection
                $controlers = json_decode(dmConfig::get('base_urls'), true); // all controlers Url
                $contextEnv = sfConfig::get('dm_context_type') . '-' . sfConfig::get('sf_environment'); // i.e. "front-dev"
                $controlerUrl = (array_key_exists($contextEnv, $controlers)) ? $controlers[$contextEnv] : '';
                $header = $controlerUrl . '/' . $page->getSlug();
                $redirect = true;
            }
                return $this->getHelper()->renderPartial('selectionPlacements', 'selectionsList', array(
                    'selections' => '',
                    'titreBloc' => '',
                    'length' => '',
                    'width' => '',
                    'withImage' => '',
                    'header' => $header,
                    'redirect' => $redirect,
                    'constanteSelection' => $constanteSelection
                ));

        }
        else {
        
        return $this->getHelper()->renderPartial('selectionPlacements', 'selectionsList', array(
                    'selections' => $selections,
                    'titreBloc' => $vars['titreBloc'],
                    'length' => $vars['length'],
                    'withImage' => $vars['withImage'],
                    'width' => $vars['widthImage'],
                    'withImage' => $vars['withImage'],
                    'lien' => $lien,
                    'namePage' => $namePage,
                    'redirect' => $redirect,
                    'constanteSelection' => $constanteSelection
            
                ));
        }
    }

}
