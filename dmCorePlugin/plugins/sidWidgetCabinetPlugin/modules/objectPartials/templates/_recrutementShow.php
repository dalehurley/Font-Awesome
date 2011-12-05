<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    //on ajoute le chapeau dans tous les cas
    echo _tag('h4.title itemprop="description"', $recrutement->getTitle());
    echo _tag('section.contentBody itemprop="descriarticleBodyption"', $recrutement->getText());
