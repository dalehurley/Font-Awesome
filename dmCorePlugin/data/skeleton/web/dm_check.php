<?php

/*
 * Shows server configuration and verify its compatibility with Diem 5.
 * This file must be protected in production server for obvious security reasons.
 * Just uncomment the following line to make it unreachable.
 */

// header('HTTP/1.0 404 Page Not Found'); die;

if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '192.168.81.111')))
{
  die(utf8_encode('Vous ['.$_SERVER["REMOTE_ADDR"].'] n\'êtes pas autorisé à accéder à ce fichier.<br/>Vérifiez le fichier '.basename(__FILE__).' pour plus d\'information.'));
}

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

dm::checkServer();