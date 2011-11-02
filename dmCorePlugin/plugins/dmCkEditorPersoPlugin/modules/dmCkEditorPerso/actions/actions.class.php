<?php

class dmCkEditorPersoActions extends dmBaseActions {

    public function executeMedia(dmWebRequest $request) {
        $this->forward404Unless(
                ($mediaId = $request->getParameter('id')) &&
                ($media = dmDb::table('DmMedia')->findOneByIdWithFolder($mediaId))
        );

        return $this->renderText($this->getHelper()->media($media)->set('#ck-media-' . $mediaId));
    }

    public function executePage(dmWebRequest $request) {
        $this->forward404Unless(
                ($pageId = $request->getParameter('id')) &&
                ($page = dmDb::table('DmPage')->findOneByIdWithI18n($pageId))
        );

        return $this->renderText($this->getHelper()->link($page)->set('#dmPage-' . $pageId));
    }

    /*
     * Action ajax pour enregistrer le contenu en BD
     *
     */
    public function executeAjax(dmWebRequest $request) {

       if ($request->isXmlHttpRequest()) {

            // update en base avec le contenu
            $content = $request->getParameter('content');
            $data = $widgetId = $request->getParameter('data');
            $widgetId = $data['widgetId'];
            $culture = $data['culture'];

            $this->widget = Doctrine_Query::create()
                            ->from('dmWidget dwt')
                            ->where('dwt.id = ?', $widgetId)
                            ->withI18n($culture, null, 'dwt')
                            ->fetchOne();

            var_dump($this->widget);

            if (is_object($this->widget)) {

                $widgetValues = json_decode($this->widget->getValue(),true); // converti la chaine json en bd en un tableau associatif

                $this->widget->setValues(array(
                    'html' => $content ,
                    'perso' => $widgetValues['perso'],
                    'bandeau' => $widgetValues['bandeau'],
                    'optionbandeauloop' => $widgetValues['optionbandeauloop'],
                    'optionbandeaubehavior' => $widgetValues['optionbandeaubehavior'],
                    'optionbandeaudirection' => $widgetValues['optionbandeaudirection'],
                    'optionbandeauscrollamount' => $widgetValues['optionbandeauscrollamount'],
                    'optionbandeauheight' => $widgetValues['optionbandeauheight'],
                    'optionbandeauwidth' => $widgetValues['optionbandeauwidth']
                ));

                $this->widget->save();

                return $this->renderText('ok');
 
            } else {

                return $this->renderText('ko'.print_r($_POST['data']).$content);

            }
        }
    }

}
