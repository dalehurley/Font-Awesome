<?php

if (sfConfig::get('sf_environment') == 'dev') {
    echo _tag('div.dev' ,
            '<b>XML:</b>' . $xml
            . '<br/><b>XSL:</b>' . $xsl
    );
}


echo $html;



// /krumo($html);







