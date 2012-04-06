<?php

class selectionPlacementsSelectionShowView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
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
        $dmPage = sfContext::getInstance()->getPage();
        $ancestors = $dmPage->getNode()->getAncestors();
        $nameParent = $ancestors[count($ancestors) - 1]->getName();
       
        if($vars['titreBloc'] == NULL || $vars['titreBloc'] == " "){
        $vars['titreBloc'] = $nameParent;
        };
        $selections = dmDb::table('SidCabinetSelectionPlacement')->findOneByIdAndIsActive($dmPage->record_id,true);
        return $this->getHelper()->renderPartial('selectionPlacements', 'selectionShow', array(
                    'selections' => $selections,
                    'titreBloc' => $vars['titreBloc'],
                    'width' => $vars['widthImage'],
                    'withImage' => $vars['withImage'],
            
                ));
    }

}
