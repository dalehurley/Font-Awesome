<?php // Vars: $sidBlogArticlePager
use_stylesheet('../../sidWidgetBlogPlugin/css/styleWidgetBlog');
echo $sidBlogArticlePager->renderNavigationTop();

echo _open('ul.elements');

foreach ($sidBlogArticlePager as $sidBlogArticle)
{
  echo _open('li.element');

    echo _link($sidBlogArticle)->text(_tag('h3',$sidBlogArticle->getTitle()).$sidBlogArticle->getResume());

  echo _close('li');
}

echo _close('ul');

echo $sidBlogArticlePager->renderNavigationBottom();