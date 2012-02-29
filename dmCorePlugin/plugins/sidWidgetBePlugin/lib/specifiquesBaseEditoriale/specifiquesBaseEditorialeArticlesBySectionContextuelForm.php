<?php

class specifiquesBaseEditorialeArticlesBySectionContextuelForm extends dmWidgetPluginForm {

    public function configure() {
        
        
        parent::configure();

        $this->widgetSchema['titreBloc']->setDefault('Dossiers');
        $this->validatorSchema['titreBloc'] = new sfValidatorString(array(
                    'required' => true
                ));
        
        $this->widgetSchema['section'] = new sfWidgetFormDoctrineChoice(array(
            'model' => 'SidSection',
            'method' => 'show_section_rubrique',
            'multiple' => true,
            'expanded' => true,
            'table_method' => 'order_by_title'));
        $this->validatorSchema['section'] = new sfValidatorDoctrineChoice(array('model' => 'SidSection', 'multiple' => true, 'required' => true));


        $this->widgetSchema['isDossier'] = new sfWidgetFormInputCheckbox(array('default'=> false, 'label' => 'Afficher uniquement des dossiers'));
        $this->validatorSchema['isDossier']  = new sfValidatorBoolean();
        
        $this->widgetSchema['visibleInDossier'] = new sfWidgetFormInputCheckbox(array('default'=> false, 'label' => 'Visible dans page dossier'));
        $this->validatorSchema['visibleInDossier']  = new sfValidatorBoolean();

        
        
        $this->widgetSchema->setHelps(array(
            'titreBloc' => 'Le titre OBLIGATOIRE du bloc', 
            'isDossier' => 'Pour afficher UNIQUEMENT des dossiers dans le bloc contextuel et ne le faire apparaitre que dans les pages article/show quand le DATATYPE de l\'article est ARTICLE UNIQUEMENT',
            'visibleInDossier' => 'Pour afficher ce bloc dans la page article/show quand le DATATYPE de l\'article est DOSSIER UNIQUEMENT'
        ));

    }

    public function getStylesheets() {
        return array(
//            'lib.ui-tabs'
        );
    }

    public function getJavascripts() {
        return array(
//            'lib.ui-tabs',
//            'core.tabForm',
//            'sidWidgetBePlugin.widgetShowForm'
        );
    }

    protected function renderContent($attributes) {
        return $this->getHelper()->renderPartial('specifiquesBaseEditoriale', 'articlesBySectionContextuelForm', array(
            'form' => $this,
            'id' => 'sid_widget_articles_contextuel_' . $this->dmWidget->get('id')
        ));
    }

    public function getWidgetValues() {
        $values = parent::getWidgetValues();

        return $values;
    }
// rajout pour ajouter des classes suppémentaires aux widget pour stylage plus fin    
    public function updateWidget()
  {
    $this->dmWidget->setValues($this->getWidgetValues());
    
    //je récupère le tableau des id des sections
    $arraySections = $this->getValues('section');
    
    //pour chaque section, je recherche son title que je slugify pour le futur nom de la classe
    // format du tableau section [][section][]
    foreach($arraySections['section'] as $i=>$section){
        
        $nameSections = dmDb::table('SidSection')->findOneById($section);
        // je récupère le nom du title et j'enlève les caractères jusqu'à _
        $nameSection = substr($nameSections->getTitle(), strpos($nameSections->getTitle(), '_')+1);
        // mise au format du title
        $name = dmString::slugify($nameSection);
        // je mets en tableau les différentes sections
        $arrayCssNames[$name]= $name;
    }
    // je mets le tableau en string séparé par un espace
    $cssClass = implode(" ", $arrayCssNames);
    // ajout des classes css
    $this->dmWidget->set('css_class', $cssClass);
    
    return $this->dmWidget;
  }

}