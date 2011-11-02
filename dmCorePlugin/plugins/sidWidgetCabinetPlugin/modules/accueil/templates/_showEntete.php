<?php // Vars: $accueil
// Vars: $nom
echo _open('div.entete');
echo _tag('h1', $nom->getTitle());
echo _tag('h2', 'Expertise comptable et commissariat aux comptes');
echo _tag('p.region', "Experts-Comptables inscrits au tableau de l'Ordre des Experts-Comptables Région ".$nom->getRegionEc());
echo _tag('p.region', "Commissaires aux Comptes inscrits auprès de la Compagnie Régionale de ".$nom->getRegionCc());
echo _close('div'); // fin div.entete