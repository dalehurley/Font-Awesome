<?php

class dmWidgetContentLegalNoticesForm extends dmWidgetPluginForm {

    public function configure() {

        parent::configure();
        
        $this->widgetSchema['defaultInfos'] = new sfWidgetFormInputCheckbox(array('default'=> true, 'label' => 'Afficher les infos par défauts'));
        $this->validatorSchema['defaultInfos']  = new sfValidatorBoolean();
        
        $this->widgetSchema['text'] = new sfWidgetFormTextarea(array('default' => '### Édition
**SAS SID Presse**  
**Siège social**  
16, rue du 4 Septembre - 75002 Paris  
Capital social : 1 728 750 €  
RCS 381 123 868 Paris  
**Production et administratif**  
15, rue de la Demi-Lune - BP 1119 - 86061 Poitiers Cedex 9  
Tél. 05 49 60 20 60  -  Fax 05 49 01 87 08

**Directeur de la Publication :** Nicolas Beytout  
**Directeur de la Rédaction :** Laurent David

### Hébergement
**Novius**  
**Siège social**  
55, avenue Galline - 69100 Villeurbanne  
RCS 443 207 154 Villeurbanne  '));
        $this->validatorSchema['text'] = new sfValidatorString(array('required' => false));
        
    }

    public function getStylesheets() {
        return array(
//      'lib.ui-tabs',
      'lib.markitup',
      'lib.markitupSet',
//      'lib.ui-resizable'
        );
    }

    public function getJavascripts() {
        return array(
//      'lib.ui-tabs',
      'lib.markitup',
      'lib.markitupSet',
//      'lib.ui-resizable',
//      'lib.fieldSelection',
//      'core.tabForm',
      'core.markdown'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('dmWidgetContent', 'legalNoticesForm', array(
                    'form' => $this,
                    'id' => 'sid_widget_coordonnees_expert' . $this->dmWidget->get('id')
                ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }

}
