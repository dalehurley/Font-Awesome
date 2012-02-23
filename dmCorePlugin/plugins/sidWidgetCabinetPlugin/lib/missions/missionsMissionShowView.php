<?php

class missionsMissionShowView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'withImage',
            'widthImage',
            'heightImage'
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
        $dmPage = sfContext::getInstance()->getPage();
        $ancestors = $dmPage->getNode()->getAncestors();
        $namePage = $ancestors[count($ancestors) - 1]->getName();
        $missions = dmDb::table('SidCabinetMission')->findOneByIdAndIsActive($dmPage->record_id,true);
        
        if($vars['titreBloc'] == NULL || $vars['titreBloc'] == " "){
        $vars['titreBloc'] = ucfirst(strtolower($namePage));
        }
        
        return $this->getHelper()->renderPartial('missions', 'missionShow', array(
                    'missions' => $missions,
                    'titreBloc' => $vars['titreBloc'],
                    'withImage' => $vars['withImage'],
                    'width' => $vars['widthImage']
            
                ));
    }

}
