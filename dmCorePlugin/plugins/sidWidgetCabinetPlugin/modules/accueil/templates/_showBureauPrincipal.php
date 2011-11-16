<?php // Vars: $accueil
// pour afficher le bureau principal (siège social)
echo _tag('h4.title', __('Main office'));
echo _open('div.bureau');
    echo _open('p.adresse');
        echo $nom->getAdresse();
        // vérif si il existe un complément d'adresse pour gérer l'affichage
        if ($nom->getAdresse2() != NULL)
        {
        echo ' - ' . $nom->getAdresse2();
        echo ' - ' . $nom->getCodePostal() . ' ' . $nom->getVille();
        }
        else
        {
        echo ' - ' . $nom->getCodePostal() . ' ' . $nom->getVille();
        }
    echo _close('p');
    echo _tag('p.adresse',__('phone'). ' : '.$nom->getTel() . ' - '.__('fax').' :' . $nom->getFax());
    echo _tag('p.adresse.mailto', __('Email').' : '._link('mailto:' . $nom->getEmail())->text($nom->getEmail()));
echo _close('div'); // fin div.bureau