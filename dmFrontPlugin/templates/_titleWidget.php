<?php
/*
 * _titleWidget.php
 * v0.1
 * Permet d'afficher un titre
 * 
 * Variables disponibles :
 * $title
 * $isContainer
 * 
 */
//Définitions des valeurs par défaut
//permet de ne pas être obligé de définir cette variable lorsque égale à false
if(!isset($isContainer)) $isContainer = false;

//définition du tag
$titleTag = $isContainer ? 'h2.title' : 'h4.title';

//affichage html de sortie
echo _tag($titleTag, $title);