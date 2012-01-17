<?php
/*
 * _teaserWrapper.php
 * v0.2
 * Permet d'afficher un teaser (chapeau)
 * 
 * Variables disponibles :
 * $teaser
 * $length
 * 
 */
//on change la longueur du texte si présent
if(isset($length)) $teaser = stringTools::str_truncate($teaser, $length, '(...)', true);

echo _tag('span.teaser', array('itemprop' => 'description'), strip_tags($teaser, '<sup><sub>'));