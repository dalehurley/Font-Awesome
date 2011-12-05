<?php

// var partial : $mission, $textLength, $textEnd
if (isset($mission)) {
    if ($nb != 1) {
        $titreBlocMission = _tag('span.title itemprop="name"', $mission->getTitle());
    } else {

        if ($titreBloc == null) {
            $titreBlocMission = "";
        }
        else
            $titreBlocMission = _tag('span.title itemprop="name"', $mission->getTitle());
    }
// on affiche soit le résumé de la page (0), soit le texte de la page (1)
    if ($chapo == 0)
        $text = $mission->getResume();
    elseif ($chapo == 1)
        $text = $mission->getText();
    echo _open('span.wrapper');
    echo _link($mission)->text(
                    $titreBlocMission .
                    _tag('span.teaser itemprop="description"', stringTools::str_truncate($text, $length, '(...)', true))
            )
            ->set('.link_box')
            ->title($mission->getTitle());

    echo _close('span');
} else {
    echo __('This bias needs to be a news item');
}
?>
