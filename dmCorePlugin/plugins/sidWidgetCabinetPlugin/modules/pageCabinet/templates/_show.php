<?php
// Vars: $pageCabinet
    //echo _open('div.'.$pageCabinet);
    echo _tag('h2.title', $pageCabinet->getTitleEntetePage());
    echo _tag('div.text', $pageCabinetText);
    echo _link('main/contact')->text(__('Contact'));
    //echo _close('div');
