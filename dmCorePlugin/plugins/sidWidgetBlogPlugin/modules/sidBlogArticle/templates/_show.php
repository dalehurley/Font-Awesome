<?php // Vars: $sidBlogArticle
use_stylesheet('../../sidWidgetBlogPlugin/css/styleWidgetBlog');
echo _tag('h2', $sidBlogArticle);
echo _open('div.center');
echo _media($sidBlogArticle->getImage())->height(240)->method('scale');
echo _close('div');
echo _tag('h3', $sidBlogArticle->getResume());
echo _tag('div.text',$sidBlogArticle->getText());
echo _tag('p', $sidBlogArticle->getAuthor());
if($sidBlogArticle->file != NULL)
{
//    baseDownloadsTools::DownloadFile('SidBlogArticle', $sidBlogArticle->file);
    echo _link($sidBlogArticle->getFiles())->text($sidBlogArticle->getTitleFile());
}
