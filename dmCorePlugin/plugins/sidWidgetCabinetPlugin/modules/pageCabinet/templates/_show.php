<?php
// Vars: $pageCabinet
echo _tag('h2.title', $pageCabinet->getTitle());

    include_partial("objectPartials/pageCabinet", array("pageCabinet" => $pageCabinet));
   
echo _link('main/contact')->text(__('Contact'));
