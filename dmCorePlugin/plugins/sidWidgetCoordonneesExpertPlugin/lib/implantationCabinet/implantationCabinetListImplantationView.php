<?php

class implantationCabinetListImplantationView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc',
            'resume_town',
            'length',	
            'widthImage',
            'heightImage',
            'withImage'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
        $redirect = false;
        $dmPage = sfContext::getInstance()->getPage();
        if($dmPage->module.'/'.$dmPage->action == 'renseignements/show'){
            $adresses = dmDb::table('SidCoordName')->createQuery('a')->where('a.is_active = ? and a.id <> ?',array(true,$dmPage->record_id))->orderBy('a.siege_social DESC')->execute();
        }
        else{
        $adresses = dmDb::table('SidCoordName')->createQuery('a')->where('a.is_active = ?',true)->orderBy('a.siege_social DESC')->execute();
        }
        
        ($vars['titreBloc'] == NULL || $vars['titreBloc'] == " ") ? $vars['titreBloc'] = $dmPage->getName() :'';
        ($vars['lien'] != NULL || $vars['lien'] != " ") ? $lien = $vars['lien'] : $lien = '';
        
        if (count($adresses) == 1 && ($dmPage->module . '/' . $dmPage->action == 'renseignements/list')) {
            foreach ($adresses as $page) {
                $page = dmDb::table('DmPage')->findOneByModuleAndActionAndRecordId('renseignements', 'show', $page->id);
                // add current's controler for header() redirection
                $controlers = json_decode(dmConfig::get('base_urls'), true); // all controlers Url
                $contextEnv = sfConfig::get('dm_context_type') . '-' . sfConfig::get('sf_environment'); // i.e. "front-dev"
                $controlerUrl = (array_key_exists($contextEnv, $controlers)) ? $controlers[$contextEnv] : '';
                $header = $controlerUrl . '/' . $page->getSlug();
                $redirect = true;
            }
        
                return $this->getHelper()->renderPartial('implantationCabinet', 'listImplantation', array(
                    'adresses' => $adresses,
                    'length' => '',
                    'lien' => '',
                    'titreBloc' => '',
                    'width' => '',
                    'height' => '',
                    'withImage' => '',
                    'visible_resume_town' => '',
                    'header' => $header,
                    'redirect' => $redirect
                ));

        }
        else {
            
        return $this->getHelper()->renderPartial('implantationCabinet', 'listImplantation', array(
                    'adresses' => $adresses,
                    'titreBloc' => $vars['titreBloc'],
                    'visible_resume_town' => $vars['resume_town'],
                    'redirect' => $redirect,
                    'length' => $vars['length'],
                    'width' => $vars['widthImage'],
                    'height' => $vars['heightImage'],
                    'withImage' => $vars['withImage'],
                ));
        
    }

}
}
