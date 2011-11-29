<?php

class leeWebservicesView extends dmWidgetPluginView {

    public function configure() {
        parent::configure();

        $this->addRequiredVar(array(
            'titreBloc'
        ));
    }

    protected function doRender() {
        $vars = $this->getViewVars();
       
        return $this->getHelper()->renderPartial('lee', 'webservices', array(
                    'titreBloc' => $vars['titreBloc']
                ));
    }

}
