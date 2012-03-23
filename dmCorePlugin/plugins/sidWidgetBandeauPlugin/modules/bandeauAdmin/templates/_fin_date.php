<?php
/* partial affiche fin date */

use_helper('Date');

echo format_date($sid_bandeau->getFinDate(), 'D');
?>