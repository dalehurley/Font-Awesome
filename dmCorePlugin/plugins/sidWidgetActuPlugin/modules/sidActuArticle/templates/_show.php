<?php // Vars: $sidActuArticle
echo _tag('h2.title', $sidActuArticle);
echo _open('div.center');
echo _media($sidActuArticle->getImage())->height(240)->method('scale');
echo _close('div');
echo _tag('h3', $sidActuArticle->getResume());
echo _tag('div.text',$sidActuArticle->getText());
//echo _tag('p', $sidActuArticle->getAuthor());
if($sidActuArticle->file != NULL)
{
//    baseDownloadsTools::DownloadFile('SidActuArticle', $sidActuArticle->file);
    echo _link($sidActuArticle->getFiles())->text($sidActuArticle->getTitleFile());
}
