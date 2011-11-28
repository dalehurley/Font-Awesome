<?php
$debug = true;
require_once('nusoap-0.7.2/lib/nusoap.php');
require_once('library.php');
$s = new soap_server();
$s->register('vacpicddService');
$s->register('vasvcService');
$s->register('trcService');
$s->register('trsvcService');
$s->register('cceService');
$s->register('cdService');
$s->register('cvpService');
$s->register('ctService');
$s->register('clService');
$s->register('ctrService');
$s->register('cldService');
$s->register('srtbService');
$s->register('fkService');
$s->register('vtService');
$s->register('hsService');
$s->register('acsService');
$s->register('cjoService');
$s->register('vaService');
$s->register('jalService');

$s->service($HTTP_RAW_POST_DATA);
?>