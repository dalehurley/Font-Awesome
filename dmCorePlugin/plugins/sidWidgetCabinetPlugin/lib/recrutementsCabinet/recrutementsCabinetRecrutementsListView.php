<?php

class recrutementsCabinetRecrutementsListView extends dmWidgetPluginView {

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
        $arrayRecrutements = array();
        $redirect = false;
        $header ='';
        $constanteRecrutement = '';
        $dmPage = sfContext::getInstance()->getPage();
        
        $nbArticles = ($vars['nbArticles'] == 0) ? '' : $vars["nbArticles"];
        if($dmPage->module.'/'.$dmPage->action == 'recrutement/show'){
        $recrutements = Doctrine_Query::create()
                ->from('SidCabinetRecrutement a')
                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                ->where('a.is_active = ? and a.id <> ?', array(true,$dmPage->record_id))
                ->orderBy('aTranslation.updated_at DESC')
                ->limit($nbArticles)
                ->execute();
            $constanteRecrutement = '';
        }
        else{
            $recrutements = Doctrine_Query::create()
                ->from('SidCabinetRecrutement a')
                ->withI18n(sfContext::getInstance()->getUser()->getCulture(), null, 'a')
                ->where('a.is_active = ?', array(true))
                ->orderBy('aTranslation.updated_at DESC')
                ->limit($nbArticles)
                ->execute();
            $constanteRecrutement = '{{recrutement}}';
        }

        if($vars['titreBloc'] == NULL || $vars['titreBloc'] == " "){
        $namePage = dmDb::table('DmPage')->findOneByModuleAndAction('recrutement','list');
        $vars['titreBloc'] = $namePage->getName();
        }
        ($vars['lien'] != NULL || $vars['lien'] != " ") ? $lien = $vars['lien'] : $lien = '';

        if(count($recrutements) == 1 && ($dmPage->module . '/' . $dmPage->action == 'recrutement/list')){
            foreach($recrutements as $page){
                $page = dmDb::table('DmPage')->findOneByModuleAndActionAndRecordId('recrutement','show', $page->id );
                $header = '/'.$page->getSlug();
                $redirect = true;
            }
                return $this->getHelper()->renderPartial('recrutementsCabinet', 'recrutementsList', array(
                    'recrutements' => '',
                    'titreBloc' => '',
                    'length' => '',
                    'width' => '',
                    'withImage' => '',
                    'header' => $header,
                    'redirect' => $redirect,
                    'constanteRecrutement' => $constanteRecrutement
                ));

        }
        else {
        
        return $this->getHelper()->renderPartial('recrutementsCabinet', 'recrutementsList', array(
                    'recrutements' => $recrutements,
                    'titreBloc' => $vars['titreBloc'],
                    'length' => $vars['length'],
                    'withImage' => $vars['withImage'],
                    'width' => $vars['widthImage'],
                    'withImage' => $vars['withImage'],
                    'lien' => $lien,
                    'redirect' => $redirect,
                    'constanteRecrutement' => $constanteRecrutement
            
                ));
        }
    }

}
