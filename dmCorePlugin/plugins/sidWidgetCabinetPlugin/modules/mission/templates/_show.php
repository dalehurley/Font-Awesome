<?php // Vars: $mission
echo _tag('h2.title', $mission);
echo _open ('div');
    echo _tag('div.texte', $mission->getText());
echo _close('div');