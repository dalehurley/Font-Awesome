<?php

/*
 * An $item is an array containing:
 * - title:       title of the feed item
 * - link:        url of the feed item
 * - content:     HTML content
 * - pub_date:    item publication date (timestamp)
 * - author_name: author name
 * - author_link: author link
 * - author_email: author email
 * - logo_les_echos : show logo les echos
 */
if($logo_les_echos == true){
    $title .= '<img src="/dmWidgetFeedReaderPlugin/_images/logo-lesechos-fr2.png" alt="Les Echos" height=25px" style="position:absolute; right:5px; top:5px"/>';
}
echo _tag('h2.title', $title);

echo _open('ul.elements');

foreach($items[0] as $item)
{
  
    echo _tag('li.element',

    // link to the feed page
    _link($item['link'])->text($item['title'])->set('.feed_item_link').

    // render truncated feed content
    _tag('div.feed_item_content', dmString::truncate(strip_tags($item['content']), $length))

  );
}

echo _close('ul');