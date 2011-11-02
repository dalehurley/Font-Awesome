<?php

/**
 * Alias of {@link krumo::dump()}
 *
 * @param mixed $data,...
 *
 * @see krumo::dump()
 */
function krumo() {
    //return krumo::dump(func_get_args());
    $args = func_get_args();
    if (func_num_args() > 1) {
        foreach ($args as $d) {
            krumo::dump($d);
        }
        return;
    } else {
        return krumo::dump($args[0]);
    }
}

function krumoText() {
    //return krumo::dump(func_get_args());
    $args = func_get_args();
    if (func_num_args() > 1) {
        foreach ($args as $d) {
            krumo::dumpText($d);
        }
        return;
    } else {
        return krumo::dumpText($args[0]);
    }
}