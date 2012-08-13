<?php
if(dmConfig::get('site_theme_version') == 'v1'){
    echo _tag('h4.title','Seuil de rentabilité (estimation rapide)');

    echo '  	<p>
            Le seuil de rentabilité est le chiffre d\'affaires (CA) pour lequel le résultat est égal à 0. C\'est à dire le CA minimum qu\'il vous faut réaliser pour ne pas perdre d\'argent ou le CA à partir duquel votre projet devient bénéficiaire.
                    <br/>
                    <br/>Indice de sécurité : c\'est le rapport entre votre chiffre d\'affaires et le seuil de rentabilité.
                    <br/>Exemple : 26 000/15 250 : l\'indice de sécurité = 1,70.
                    <br/>
                    <br/>La marge de sécurité s\'élève à 10 750 €, votre chiffre d\'affaire peut baisser de 10 750 € avant que vous ne soyez en perte.
                    <br/>
                    <br/>Pour effectuer ce calcul, vous devez connaître le taux de marge brute dégagé par votre exploitation, le pourcentage de charges variables généré par l\'exploitation, le volume de charges de structure (celles qui sont fixes, quel que soit le niveau du chiffre d\'affaires) et le chiffre d\'affaires annuel réalisé.
                    <br/>
                    <br/>Si vous êtes en entreprise individuelle, vous devez évaluer votre niveau de rémunération.
            </p>
    ';

    echo $form;

    if ($sf_user->hasFlash('results')) {

        $results = $sf_user->getFlash('results');

        //var_dump($results); // pour afficher les valeurs postée et le résultat soap

        echo $results['soap'];

    }
}
elseif(dmConfig::get('site_theme_version') == 'v2'){
    echo _tag('h3','Seuil de rentabilité (estimation rapide)');

    echo '  	<p>
            Le seuil de rentabilité est le chiffre d\'affaires (CA) pour lequel le résultat est égal à 0. C\'est à dire le CA minimum qu\'il vous faut réaliser pour ne pas perdre d\'argent ou le CA à partir duquel votre projet devient bénéficiaire.
                    <br/>
                    <br/>Indice de sécurité : c\'est le rapport entre votre chiffre d\'affaires et le seuil de rentabilité.
                    <br/>Exemple : 26 000/15 250 : l\'indice de sécurité = 1,70.
                    <br/>
                    <br/>La marge de sécurité s\'élève à 10 750 €, votre chiffre d\'affaire peut baisser de 10 750 € avant que vous ne soyez en perte.
                    <br/>
                    <br/>Pour effectuer ce calcul, vous devez connaître le taux de marge brute dégagé par votre exploitation, le pourcentage de charges variables généré par l\'exploitation, le volume de charges de structure (celles qui sont fixes, quel que soit le niveau du chiffre d\'affaires) et le chiffre d\'affaires annuel réalisé.
                    <br/>
                    <br/>Si vous êtes en entreprise individuelle, vous devez évaluer votre niveau de rémunération.
            </p>
    ';

    echo $form->render(array('class' => 'form-horizontal'));

    if ($sf_user->hasFlash('results')) {

        $results = $sf_user->getFlash('results');

        //var_dump($results); // pour afficher les valeurs postée et le résultat soap

        echo $results['soap'];

    }
}