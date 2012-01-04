<?php
// vars : $recrutements, $titreBloc
if (count($recrutements)) { // si nous avons des actu articles
    echo _tag('h2.title', $titreBloc);
        include_partial("objectPartials/recrutementShow", array("recrutement" => $recrutements));
}
else echo _tag('p','{{recrutement}}');
