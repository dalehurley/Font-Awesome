<?php
//echo "blabl";
$debug = true;
require_once('/simu-auto/nusoap-0.7.2/lib/nusoap.php');
require_once('library.php');
$s = new soap_server();
$s->register('simuautoService');

$s->service($HTTP_RAW_POST_DATA);
?>