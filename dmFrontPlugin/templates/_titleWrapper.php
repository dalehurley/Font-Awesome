<?php
/*
 * _titleWrapper.php
 * v0.1
 * Permet d'afficher un titre
 * 
 * Variables disponibles :
 * $title
 * 
 */
echo _tag('span.title', array('itemprop' => 'name'), strip_tags($title, '<sup><sub>'));