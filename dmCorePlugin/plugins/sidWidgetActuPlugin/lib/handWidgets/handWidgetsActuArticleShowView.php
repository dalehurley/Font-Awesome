<?php

class handWidgetsActuArticleShowView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'type'
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
        $idDmPage = sfContext::getInstance()->getPage()->id;
        $dmPage = dmDb::table('DmPage')->findOneById($idDmPage);
       
        $actuArticles = Doctrine_Query::create()->from('SidActuArticle a')
                        ->leftJoin('a.SidActuTypeArticle sata')
                        ->Where('a.is_active = ? and a.id = ?', array(true,$dmPage->record_id))
                        ->andWhere('sata.sid_actu_type_id = ?', array($vars['type']))
                        ->execute();
        foreach ($actuArticles as $actuArticle){
            $actu = $actuArticle;
        }
        return $this->getHelper()->renderPartial('handWidgets', 'actuArticleShow', array(
                    'articles' => $actu,
                    'titreBloc' => $vars['titreBloc'],
            
                ));        

}
}
