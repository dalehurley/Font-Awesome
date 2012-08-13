<?php
if(dmConfig::get('site_theme_version') == 'v1'){
    echo _tag('h4.title','Correspondance jours ouvrés/jours ouvrables');

    echo '<table>
                <tr>
                    <td colspan="2">
                            Calcul en jours ouvrables ou en jours ouvrés ?<br/>

                                    En général, le nombre de jours de congés payés se comptabilise en jours ouvrables, soit tous les jours de la semaine à l\'exception des dimanches et des jours fériés chômés.<br/>
                                    Attention<br/>
                                    votre convention collective peut prévoir un calcul en jours ouvrés.<br/>

                                    Vous pouvez obtenir, ci dessous, l\'équivalence de jours ouvrables en jours ouvrés et inversement. <br/>Cette table de concordance est calculée sur la base de 5 jours ouvrés pour 6 jours ouvrables.
                                    Concordance<br/>
                    </td>
                </tr>
        </table>';

    echo $form;

    if ($sf_user->hasFlash('results')) {

        $results = $sf_user->getFlash('results');

        //var_dump($results); // pour afficher les valeurs postée et le résultat soap

        echo $results['soap'];

    }
}
elseif(dmConfig::get('site_theme_version') == 'v2'){
    echo _tag('h3','Correspondance jours ouvrés/jours ouvrables');

    echo '<table>
                <tr>
                    <td colspan="2">
                            Calcul en jours ouvrables ou en jours ouvrés ?<br/>

                                    En général, le nombre de jours de congés payés se comptabilise en jours ouvrables, soit tous les jours de la semaine à l\'exception des dimanches et des jours fériés chômés.<br/>
                                    Attention<br/>
                                    votre convention collective peut prévoir un calcul en jours ouvrés.<br/>

                                    Vous pouvez obtenir, ci dessous, l\'équivalence de jours ouvrables en jours ouvrés et inversement. <br/>Cette table de concordance est calculée sur la base de 5 jours ouvrés pour 6 jours ouvrables.
                                    Concordance<br/>
                    </td>
                </tr>
        </table>';

    echo $form->render(array('class' => 'form-horizontal'));

    if ($sf_user->hasFlash('results')) {

        $results = $sf_user->getFlash('results');

        //var_dump($results); // pour afficher les valeurs postée et le résultat soap

        echo $results['soap'];

    }
}