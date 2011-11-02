<?php

class dmWidgetTextareaActions extends myFrontModuleActions {
    /*
     * Fonction appelé en ajax par _dmWidgetPersoContentTextarea
     */

    public function executeAjax(dmWebRequest $request) {

        if ($request->isXmlHttpRequest()) {

            // update en base avec le contenu
            $content = $request->getParameter('content');
            $widgetId = $request->getParameter('widgetId');
            $culture = $request->getParameter('culture');

            $this->widget = Doctrine_Query::create()
                            ->from('dmWidget dwt')
                            ->where('dwt.id = ?', $widgetId)
                            ->withI18n($culture, null, 'dwt')
                            ->fetchOne();

            if (is_object($this->widget)) {

                $this->widget->setValues(array(
                    'libelle' => $content,
                ));

                $this->widget->save();

                return $this->renderText('ok');
            } else {
                return $this->renderText('ko');
            }
        }
    }

}