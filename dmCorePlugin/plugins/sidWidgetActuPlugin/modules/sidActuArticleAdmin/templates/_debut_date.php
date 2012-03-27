<?php
/* partial affiche debut date */

use_helper('Date');

echo format_date($sid_actu_article->getDebutDate(), 'D');
?>