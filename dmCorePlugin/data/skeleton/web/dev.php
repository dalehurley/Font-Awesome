<?php

//if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '192.168.81.130')))
if (substr($_SERVER['REMOTE_ADDR'], 0, 10) != '192.168.81') { // adresse locale dev
    if (!in_array(@$_SERVER['REMOTE_ADDR'], array('217.108.240.193', '217.108.240.209', '217.108.240.217', '217.108.240.225'))) {  // adresse externe
        die(utf8_encode('Vous [' . $_SERVER["REMOTE_ADDR"] . '] n\'etes pas autorise a acceder a ce fichier.<br/>Vérifiez le fichier ' . basename(__FILE__) . ' pour plus d\'information.'));
    }
}

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('front', 'dev', true);

dm::createContext($configuration)->dispatch();