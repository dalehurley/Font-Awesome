<?php
if(dmConfig::get('site_theme_version') == 'v1'){
    echo _tag('h4.title','Calcul de la durée (selon votre capacité de remboursement)');

    echo $form;

    if ($sf_user->hasFlash('results')) {

        $results = $sf_user->getFlash('results');

        //var_dump($results); // pour afficher les valeurs postée et le résultat soap

        echo $results['soap'];

    } else { ?>

            <h6 class="title">Exemple</h6>
            <em>
                    vous devez emprunter 38 000 € à un taux de 10.1 %, mais vous ne pouvez rembourser que 1 800 € par mois, alors quelle sera la durée de votre emprunt ?
            </em>

            <table>
                    <tbody><tr>
                            <td align="right">Capital emprunté</td>

                            <td align="left">38 000</td>
                    </tr>
                    <tr>
                            <td align="right">Montant des remboursements</td>
                            <td align="left">1 800</td>
                    </tr>
                    <tr>

                            <td align="right">Périodicité </td>
                            <td align="left">Mois</td>
                    </tr>
                    <tr>
                            <td align="right">Fin de période ou Début de période </td>
                            <td align="left">Fin de période</td>
                    </tr>

                    <tr>
                            <td align="right">Taux proportionnel annuel</td>
                            <td align="left">10,1</td>
                    </tr>
            </tbody></table>

            <h6 >Résultat </h6>
            <em>

                            un capital emprunté de 38 000 euros remboursé au taux proportionnel annuel (frais inclus) de 10,1 %, avec des versements constants de 1 800 euros, s'effectuant en fin de période aura une durée de <b>23 mois</b>.
            </em>

    <?php }
}
elseif(dmConfig::get('site_theme_version') == 'v2'){
        echo _tag('h3','Calcul de la durée (selon votre capacité de remboursement)');

    echo $form->render(array('class' => 'form-horizontal'));

    if ($sf_user->hasFlash('results')) {

        $results = $sf_user->getFlash('results');

        //var_dump($results); // pour afficher les valeurs postée et le résultat soap

        echo $results['soap'];

    } else { ?>

            <h6 class="title">Exemple</h6>
            <em>
                    vous devez emprunter 38 000 € à un taux de 10.1 %, mais vous ne pouvez rembourser que 1 800 € par mois, alors quelle sera la durée de votre emprunt ?
            </em>

            <table>
                    <tbody><tr>
                            <td align="right">Capital emprunté</td>

                            <td align="left">38 000</td>
                    </tr>
                    <tr>
                            <td align="right">Montant des remboursements</td>
                            <td align="left">1 800</td>
                    </tr>
                    <tr>

                            <td align="right">Périodicité </td>
                            <td align="left">Mois</td>
                    </tr>
                    <tr>
                            <td align="right">Fin de période ou Début de période </td>
                            <td align="left">Fin de période</td>
                    </tr>

                    <tr>
                            <td align="right">Taux proportionnel annuel</td>
                            <td align="left">10,1</td>
                    </tr>
            </tbody></table>

            <h6 >Résultat </h6>
            <em>

                            un capital emprunté de 38 000 euros remboursé au taux proportionnel annuel (frais inclus) de 10,1 %, avec des versements constants de 1 800 euros, s'effectuant en fin de période aura une durée de <b>23 mois</b>.
            </em>

    <?php }
}