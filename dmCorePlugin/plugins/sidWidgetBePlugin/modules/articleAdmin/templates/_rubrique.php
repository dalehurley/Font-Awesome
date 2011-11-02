<?php
/** dans le generator.yml on a définit le champ display Rubrique avec _ devant:
 * 
 *      list:
 *        display: 
 *          - _rubrique
 * 
 * Il suffit donc de définir le partial _rubrique.php afin d'afficher la rubrique
 * Le generator.yml renvoie au partial l'instance de l'objet article, accessible en appelant son Model_name en minuscule
 * Plus d'infos : http://www.symfony-project.org/reference/1_4/fr/06-Admin-Generator#chapter_06_sub_peer_method
 * 
 */

echo $sid_article->Section->Rubrique;
?>
