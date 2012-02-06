<?php

class dmSearchUpdateTask extends dmContextTask {
    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'front') ,
            new sfCommandOption('init', null, sfCommandOption::PARAMETER_OPTIONAL, 'Init the lucene directory', false) ,
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod')
        ));
        $this->namespace = 'dm';
        $this->name = 'search-update';
        $this->briefDescription = 'Update search engine index';
        $this->detailedDescription = $this->briefDescription;
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {
        if ($options['init']) {
          // on fait seulement l'initialisation, pour créer l'arborescence de dossiers
          // afin d'éviter d'avoir une initialisation lancée par apache lors du premier appel à la fonction search du front
            $this->get('search_engine');
        } else {
            $this->withDatabase();
            $this->log('Search engine index update');
            $this->get('service_container')->setService('logger', new sfConsoleLogger($this->dispatcher));
            $this->get('search_engine')->populate();
            $this->get('search_engine')->optimize();
        }
    }
}
