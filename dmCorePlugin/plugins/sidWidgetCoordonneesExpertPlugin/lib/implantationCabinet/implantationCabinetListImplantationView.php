<?php

class implantationCabinetListImplantationView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'resume_town'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
        $dmPage = sfContext::getInstance()->getPage();
        if($dmPage->module.'/'.$dmPage->action == 'renseignements/show'){
            $adresses = dmDb::table('SidCoordName')->createQuery('a')->where('a.is_active = ? and a.id <> ?',array(true,$dmPage->record_id))->orderBy('a.siege_social DESC')->execute();
        }
        else{
        $adresses = dmDb::table('SidCoordName')->createQuery('a')->where('a.is_active = ?',true)->orderBy('a.siege_social DESC')->execute();
        }
        
        ($vars['titreBloc'] == NULL || $vars['titreBloc'] == " ") ? $vars['titreBloc'] = $dmPage->getName() :'';
        return $this->getHelper()->renderPartial('implantationCabinet', 'listImplantation', array(
                    'adresses' => $adresses,
                    'titreBloc' => $vars['titreBloc'],
                    'visible_resume_town' => $vars['resume_town']
                ));
        
    }

}
