<?php

class pagesDuCabinetpageCabinetListView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'length',	
            'titreBloc',
            'widthImage',
            'heightImage',
            'withImage'
        ));
    }

    
    /**
     * On choisit la page du cabinet à afficher
     * On choisit la longueur de texte
     * On choisit le titre du bloc (Facultatif, si il n'y a rien, on affiche l'intitulé de la page)
     *  
     */
    protected function doRender() {
        $vars = $this->getViewVars();
        $arrayArticle = array();
        $redirect = false;
        $header = '';
        $dmPage = sfContext::getInstance()->getPage();

        switch ($dmPage->module . '/' . $dmPage->action) {
            // si on est dans la page d'un article su cabinet, on enlève de la liste des articles en bas de page l'article qui est affiché ($dmPage->record_id)
            case 'pageCabinet/show':
                
                $pageCabinets = Doctrine_Query::create()
                        ->from('SidCabinetPageCabinet a')
                        ->where('a.is_active = ? and a.id <> ?', array(true,$dmPage->record_id))
                        ->execute();
                break;
            // pour affichage du listing des articles du cabinet quand on est sur la page "Actualités du cabinet"
            default:

                $pageCabinets = dmDb::table('SidCabinetPageCabinet')->findByIsActive(true);
//              ($vars['withImage'] == true) ? (($pageCabinet->getImage()->checkFileExists() == true) ? $image = $pageCabinet->getImage() : $image = ''): $image = '';
		}

        ($vars['titreBloc'] == NULL || $vars['titreBloc'] == " ") ? $vars['titreBloc'] = $dmPage->getName() :'';
        ($vars['lien'] != NULL || $vars['lien'] != " ") ? $lien = $vars['lien'] : $lien = '';
        
        if (count($pageCabinets) == 1 && ($dmPage->module . '/' . $dmPage->action == 'pageCabinet/list')) {
            foreach ($pageCabinets as $page) {
                $page = dmDb::table('DmPage')->findOneByModuleAndActionAndRecordId('pageCabinet', 'show', $page->id);
                // add current's controler for header() redirection
                $controlers = json_decode(dmConfig::get('base_urls'), true); // all controlers Url
                $contextEnv = sfConfig::get('dm_context_type') . '-' . sfConfig::get('sf_environment'); // i.e. "front-dev"
                $controlerUrl = (array_key_exists($contextEnv, $controlers)) ? $controlers[$contextEnv] : '';
                $header = $controlerUrl . '/' . $page->getSlug();
                $redirect = true;
            }
                return $this->getHelper()->renderPartial('pagesDuCabinet', 'pageCabinetList', array(
                    'pageCabinets' => $pageCabinets,
                    'length' => '',
                    'lien' => '',
                    'titreBloc' => '',
                    'width' => '',
                    'height' => '',
                    'withImage' => '',
                    'header' => $header,
                    'redirect' => $redirect
                ));

        }
        else {
    
        return $this->getHelper()->renderPartial('pagesDuCabinet', 'pageCabinetList', array(
                    'pageCabinets' => $pageCabinets,
                    'length' => $vars['length'],
                    'lien' => $lien,
                    'titreBloc' => $vars['titreBloc'],
                    'width' => $vars['widthImage'],
                    'height' => $vars['heightImage'],
                    'withImage' => $vars['withImage'],
                    'redirect' => $redirect
                ));
        }
    }

}
