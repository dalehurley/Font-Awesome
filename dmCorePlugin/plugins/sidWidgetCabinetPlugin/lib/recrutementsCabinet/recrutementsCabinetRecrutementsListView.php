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
        $dmPage = sfContext::getInstance()->getPage();
        
        $nbArticles = ($vars['nbArticles'] == 0) ? '' : $vars["nbArticles"];

        $recrutements = Doctrine_Query::create()
                ->from('SidCabinetRecrutement a')
                ->leftJoin('a.Translation b')
                ->where('a.is_active = ? and a.id <> ?', array(true,$dmPage->record_id))
                ->orderBy('b.updated_at DESC')
                ->limit($nbArticles)
                ->execute();

        if($vars['titreBloc'] == NULL || $vars['titreBloc'] == " "){
        $namePage = dmDb::table('DmPage')->findOneByModuleAndAction('recrutement','list');
        $vars['titreBloc'] = $namePage->getName();
        }
        ($vars['lien'] != NULL || $vars['lien'] != " ") ? $lien = $vars['lien'] : $lien = '';

        return $this->getHelper()->renderPartial('recrutementsCabinet', 'recrutementsList', array(
                    'recrutements' => $recrutements,
                    'titreBloc' => $vars['titreBloc'],
                    'length' => $vars['length'],
                    'withImage' => $vars['withImage'],
                    'width' => $vars['widthImage'],
                    'withImage' => $vars['withImage'],
                    'lien' => $lien
            
                ));
    }

}
