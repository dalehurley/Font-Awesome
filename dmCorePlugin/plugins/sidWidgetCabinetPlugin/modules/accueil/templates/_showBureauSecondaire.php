<?php // Vars: $accueil
// pour afficher d'autres bureaux secondaires
if(is_object($nomSecondaires))
{
    echo _tag('h4.title', 'Bureau secondaire');
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
        echo _tag('p.adresse', 'Tél : '.$nomSecondaire->getTel() . ' - Fax : ' . $nomSecondaire->getFax());
        echo _tag('p.adresse.mailto', 'Email : '._link('mailto:' . $nomSecondaire->getEmail())->text($nomSecondaire->getEmail()));
    echo _close('div'); // fin div.bureau
    }
}