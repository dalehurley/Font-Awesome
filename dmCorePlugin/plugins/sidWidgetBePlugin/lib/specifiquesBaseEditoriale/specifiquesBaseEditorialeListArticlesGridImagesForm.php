<?php

class specifiquesBaseEditorialeListArticleGridImagesForm extends dmWidgetPluginForm {

    public function configure() {

        $options = '
            {
                   "rows"            : 3,
                   "columns"         : 7,
                   "w1024"           : {
                        "rows"    : 3,
                        "columns" : 8
                    },
                    "w768"            : {
                        "rows"    : 3,
                        "columns" : 6
                    },
                    "w480"            : {
                        "rows"    : 3,
                        "columns" : 5
                    },
                    "w320"            : {
                       "rows"    : 3,
                        "columns" : 4
                    },
                    "w240"            : {
                        "rows"    : 3,
                        "columns" : 3
                    },
                    "step"            : "random",
                    "maxStep"         : 10,
                    "preventClick"    : false,
                    "animType"        : "random",
                    "animSpeed"       : 500,
                    "animEasingOut"   : "linear",
                    "animEasingIn"    : "linear",
                    "interval"        : 2000
                }';

        parent::configure();
        
        $this->widgetSchema['options'] = new sfWidgetFormTextarea(array('default' => $options));
        $this->validatorSchema['options'] = new sfValidatorJsonString(array(
                    'required' => true
                ));

        $this->widgetSchema['titreBloc'] = new sfWidgetFormInputText(array('default' =>'Contact'));
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => true
                ));        
       
        $this->widgetSchema->setHelps(array(
            'options' => "Les options du composant.</br>
                    rows            : 4  // number of rows</br>
                    columns         : 10 // number of columns</br>
                    </br>
                    w1024           : { // responsive parameter</br>
                    &nbsp;&nbsp;&nbsp;rows    : 3, //number of rows for container width > 1024</br>
                    &nbsp;&nbsp;&nbsp;columns : 8  //number of columns for container width > 1024</br>
                    },</br>
                    </br>
                    w768, w480, w320, w240  // other responsive widths used</br> 
                    </br> 
                    // step: number of items that are replaced at the same time</br>
                    // random || [some number]</br>
                    // note: for performance issues, the number cant be > options.maxStep</br>
                    step            : 'random',</br>
                    maxStep         : 3,</br>
                    </br>
                    // click disabled (prevent user to click the items)</br>
                    preventClick    : false,</br>
                    </br>
                    // animation type</br>
                    // showHide || fadeInOut || slideLeft || </br>
                    // slideRight || slideTop || slideBottom || </br>
                    // rotateLeft || rotateRight || rotateTop || </br>
                    // rotateBottom || scale || rotate3d || </br>
                    // rotateLeftScale || rotateRightScale || </br>
                    // rotateTopScale || rotateBottomScale || random</br>
                    animType        : 'random',</br>
                    </br>
                    // animation speed</br>
                    animSpeed       : 500,</br>
                    </br>
                    // animation easings</br>
                    animEasingOut   : 'linear',</br>
                    animEasingIn    : 'linear',</br>
                    </br>
                    // the item(s) will be replaced every 3 seconds</br>
                    // note: for performance issues, the time cant be < 300 ms</br>
                    interval        : 3000</br>
            " 
        ));
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'listArticlesGridImagesForm', array(
            'form' => $this,
            'id' => 'sid_widget_list_articles_grid_images_' . $this->dmWidget->get('id')
        ));
    }


}