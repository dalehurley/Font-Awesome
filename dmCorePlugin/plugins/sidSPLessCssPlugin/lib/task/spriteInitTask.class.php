<?php

class spriteInitTask extends sfBaseTask {
    /**
     * @see sfTask
     */
    protected function configure() {
        $this->addArguments(array());
        $this->addOptions(array(
            // application positionnée sur front afin d'avoir accès aux app.yml du front
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'front') ,
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod') ,
        ));
        $this->namespace = 'less';
        $this->name = 'sprite-init';
        $this->briefDescription = 'Create sprites';
        $this->detailedDescription = <<<EOF
The [less:sprite-init|INFO] task creates sprites.
Call it with:

  [php symfony less:sprite-init|INFO]
EOF;
        
    }
    /**
     * @see sfTask
     */
    protected function execute($arguments = array() , $options = array()) {
        $timerTotal = new sfTimer;
        $return['hashMd5'] = null;
        $return['spriteFormat'] = null;

        $return = spLessCss::spriteInit();
        
        while ($return) {
            $timer = new sfTimer;
            $return = spLessCss::spriteInit($return['hashMd5'], $return['spriteFormat']);
            $this->logSection('Sprite init ('.round($timer->getElapsedTime(),3).' s)', $return['hashMd5'] . ' ' . $return['spriteFormat'] . ' ' . $return['prct']);
        }
        $this->logSection('Sprite init total time', round($timerTotal->getElapsedTime(),3).' s');
    }
}
