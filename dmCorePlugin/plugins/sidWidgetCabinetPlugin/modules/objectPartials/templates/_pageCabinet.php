<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$imgLink='';
 $imgLink = '/uploads/' . $pageCabinet->getImage();
    $html = '';
//on vérifie que l'image existe
    $imgExist = is_file(sfConfig::get('sf_web_dir') . $imgLink);
    if ($imgExist) {
        $html.= _open('div.imageFullWrapper');
        $html.= _media($imgLink)
                ->set('.image itemprop="image"')
                ->alt($pageCabinet->getTitle())
                ->width(spLessCss::gridGetContentWidth());
        $html.= _close('div');
    }
    $html.= _open('span.wrapper');
    //on ajoute le chapeau dans tous les cas
    $html.= _tag('span itemprop="description"', '<b>'.$pageCabinet->getTitleEntetePage().'</b>');
    $html.= _tag('span.teaser itemprop="description"', $pageCabinet->getText());
    $html.= _close('span.wrapper');
    
    echo $html;
?>
