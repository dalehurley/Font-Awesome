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
$i = 1;
$i_max = count($items[0]);
$class = '';
foreach($items[0] as $item)
{
  if ($i == 1) {
        $class = 'first';
        if ($i == $i_max)
            $class = 'first last';
	}
    elseif ($i == $i_max)
        $class = 'last';
    else
        $class = '';
    $text = _open('span', array('class' => 'subWrapper')).
    		_tag('span', array('class' => 'title', 'itemprop' => 'name'),$item['title']).
    		_close('span').
    		_tag('span.teaser', dmString::truncate(strip_tags($item['content']), $length));
    		
    echo _tag('li.element.'.$class.' ',

    // link to the feed page
    
    _link($item['link'])->text($text)->set('.feed_item_link.link_box')
    // render truncated feed content
    

  );
  $i++;
}

echo _close('ul');