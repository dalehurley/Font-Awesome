<?php
/*
 * _descriptionWrapper.php
 * v0.1
 * Permet d'afficher un teaser (chapeau)
 * 
 * Variables disponibles :
 * $teaser
 * 
 */
echo _tag('span.description', array('itemprop' => 'description'), strip_tags($teaser, '<sup><sub>'));