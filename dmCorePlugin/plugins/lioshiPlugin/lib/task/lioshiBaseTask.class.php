<?php
/**
 * Abstract class extends dmBaseTask >>> sfTask
 *
 *
 *
 */

class lioshiBaseTask extends sfBaseTask {
    /**
     * @see sfTask
     */
    public function initialize(sfEventDispatcher $dispatcher, sfFormatter $formatter) {
        $this->dispatcher = $dispatcher;
        /**
         * Redifinition des couleurs en ligne de commande
         *
         * Style's definitions (protected in from sfAnsiFormatterClass of symfony):
         *   options : $options    = array('bold' => 1, 'underscore' => 4, 'blink' => 5, 'reverse' => 7, 'conceal' => 8),
         *   fg      : $foreground = array('black' => 30, 'red' => 31, 'green' => 32, 'yellow' => 33, 'blue' => 34, 'magenta' => 35, 'cyan' => 36, 'white' => 37),
         *   bg      : $background = array('black' => 40, 'red' => 41, 'green' => 42, 'yellow' => 43, 'blue' => 44, 'magenta' => 45, 'cyan' => 46, 'white' => 47);
         */
        $f = new sfAnsiColorFormatter();
        $f->setStyle('INFO', array(
            'bg' => 'green',
            'fg' => 'white',
            'bold' => true
        ));
        $f->setStyle('COMMENT', array(
            'fg' => 'blue',
            'bold' => true
        ));
        $f->setStyle('QUESTION', array(
            'bg' => 'yellow',
            'fg' => 'white',
            'bold' => true
        ));
        $f->setStyle('HEADER', array(
            'bg' => 'magenta',
            'fg' => 'white',
            'bold' => false
        ));    
        $f->setStyle('LINE', array(
            'fg' => 'black',
            'bold' => true
        ));
        $this->formatter = $f;
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {
        //
    }
}
