<?php
if (array_key_exists($sidActuRubrique->id, $actuRubriques) == TRUE) {
    echo _open('li.element');
    $html = '';
    $html .= _tag('span.title', ucfirst($sidActuRubrique->getTitle()));
    if (($actuRubriques[$sidActuRubrique->id]->image) != NULL) {
        $html.= _open('span.imageWrapper');
        $html.= _media($actuRubriques[$sidActuRubrique->id]->getImage())
                ->set('.image itemprop="image"')
                ->alt($actuRubriques[$sidActuRubrique->id]->getTitle())
                ->width(spLessCss::gridGetWidth(2, 0))
                ->height(spLessCss::gridGetHeight(4, 0));
        $html.= _close('span.imageWrapper');
    }
    $html .= _open('span.wrapper');
    $html .= _tag('span.title itemprop="name"', ucfirst($actuRubriques[$sidActuRubrique->id]->getTitle()));
    $html .= _tag('span.teaser itemprop="description"', $actuRubriques[$sidActuRubrique->id]->getResume());
    $html .= _close('span.wrapper');

    echo _link($sidActuRubrique)
            ->text($html)
            ->set('.link_box');
    echo _close('li');
};
?>
