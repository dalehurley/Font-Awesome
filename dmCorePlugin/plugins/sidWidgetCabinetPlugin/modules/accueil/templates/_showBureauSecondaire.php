<?php // Vars: $accueil
// pour afficher d'autres bureaux secondaires
if(is_object($nomSecondaires))
{
    echo _tag('h4.title', __('Sub-office'));
    foreach ($nomSecondaires as $nomSecondaire)
    {
    echo _open('div.bureau');
        echo _open('p.adresse');
            echo $nomSecondaire->getAdresse();
            if ($nomSecondaire->getAdresse2() != NULL)
            {
            echo ' - ' . $nomSecondaire->getAdresse2();
            echo ' - ' . $nomSecondaire->getCodePostal() . ' ' . $nomSecondaire->getVille();
            }
            else
            {
            echo ' - ' . $nomSecondaire->getCodePostal() . ' ' . $nomSecondaire->getVille();
            }
        echo _close('p');
        echo _tag('p.adresse',__('phone'). ' : '.$nomSecondaire->getTel() . ' - '.__('fax').' : ' . $nomSecondaire->getFax());
        echo _tag('p.adresse.mailto', __('Email').' : '._link('mailto:' . $nomSecondaire->getEmail())->text($nomSecondaire->getEmail()));
    echo _close('div'); // fin div.bureau
    }
}