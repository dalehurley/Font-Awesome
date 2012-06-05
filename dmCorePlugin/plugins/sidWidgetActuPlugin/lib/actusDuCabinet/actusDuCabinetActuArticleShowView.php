<?php

class actusDuCabinetActuArticleShowView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'type',
            'withImage',
            'widthImage',
            'heightImage'
        ));
    }

    
    protected function doRender() {
        
        $vars = $this->getViewVars();
        $dmPage = sfContext::getInstance()->getPage();
        $recordDmPage = sfContext::getInstance()->getPage()->record_id;
        
        // $nameParent pour afficher le nom de la page parent au cas ou il n'y a rien dans le titreBloc
        $ancestors = $dmPage->getNode()->getAncestors();
        $nameParent = $ancestors[count($ancestors) - 1]->getName();
       
        $actuArticles = Doctrine_Query::create()->from('SidActuArticle a')
                        ->leftJoin('a.SidActuTypeArticle sata')
                        ->Where('a.is_active = ? and a.id = ? and sata.sid_actu_type_id = ?', array(true,$dmPage->record_id,$vars['type']))
                        ->execute();
        
        $vars['titreBloc'] = ($vars['titreBloc'] == NULL || $vars['titreBloc'] == ' ') ? $nameParent : $vars['titreBloc'];

        $varWithPublishedDate = (isset($vars['withPublishedDate'])) ?  $vars['withPublishedDate'] : true;

        return $this->getHelper()->renderPartial('actusDuCabinet', 'actuArticleShow', array(
                    'articles' => $actuArticles,
                    'titreBloc' => $vars['titreBloc'],
                    'withPublishedDate' => $varWithPublishedDate,
                    'withImage' => $vars['withImage'],
                    'width' => $vars['widthImage'],
                    'height' => $vars['heightImage']
            
                ));        

}
}
