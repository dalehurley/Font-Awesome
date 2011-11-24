<?php
// le page success du core themesFmk, relatif au template utilisé
$includePageSuccessTemplate = sfConfig::get('dm_core_dir') . '/../themesFmk/_templates/' . spLessCss::getLessParam('mainTemplate') . '/Externals/php/pageSuccessTemplate.php';

if (is_file($includePageSuccessTemplate)) {
    include $includePageSuccessTemplate;
} else {
    echo $includePageSuccessTemplate.' est introuvable';
}