<?php

class dmEraseSiteTask extends dmContextTask {

    /**
     * @see sfTask
     */
    protected function configure() {
	$this->addOptions(array(
//      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'front'),
//      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod')
	));

	$this->namespace = 'dm';
	$this->name = 'erase-site';
	$this->briefDescription = 'Erase site (irreversible)';

	$this->detailedDescription = $this->briefDescription;
    }

    /**
     * @see sfTask
     */
    protected function execute($arguments = array(), $options = array()) {

	// chargement des images de LEA ?
	if ($this->askConfirmation(array('Effacer le site completement ? ATTENTION IRREVERSIBLE (y/n)'), 'QUESTION_LARGE', true)) {
	    if ($this->askConfirmation(array('Vraiment sur ? (y/n)'), 'QUESTION_LARGE', true)) {
		
		$this->logSection('Erase', '...');

		$command = "rm -rf *";
		exec($command, $output);
		
	    } else {
		$this->logSection('Annulation', '...');
	    }
	} else {
	    $this->logSection('Annulation','...');
	}
    }

}